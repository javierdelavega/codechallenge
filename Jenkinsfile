node {
    try {
        checkout scm
        withDockerNetwork{ n ->
          docker.image('mariadb:10.11.4').withRun("--network ${n} --name db -e MYSQL_ROOT_PASSWORD=codechallenge") { c->
            docker.image('php:8.2').inside(
                "--network ${n} 
                -u root 
                --name php 
                -e DB_NAME_TEST=codechallenge 
                -e DB_USER_TEST=codechallenge 
                -e DB_PASSWORD_TEST=codechallenge 
                -e MARIADB_VERSION=10.11.4"
                ) {
                stage('Setup env') {
                    echo 'Branch...' + env.BRANCH_NAME
                    sh 'chmod +x ci/docker_install.sh'
                    sh 'ci/docker_install.sh'
                }

                stage ('Build') {
                    sh 'composer install --ignore-platform-reqs --no-scripts'
                    sh 'php bin/console --env=test doctrine:database:create --if-not-exists --no-interaction'
                    sh 'bin/console --env=test doctrine:schema:create --no-interaction'
                }

                stage ('Test') {
                    sh 'XDEBUG_MODE=coverage php bin/phpunit'
                    recordCoverage(tools: [[parser: 'COBERTURA', pattern: 'build/logs/cobertura.xml']])
                    junit 'build/logs/junit.xml'
                }

                if (env.BRANCH_NAME == 'staging' || env.BRANCH_NAME == 'main') {
                    stage ('Deploy') {
                        withEnv(['ANSIBLE_PROJECT_ID=1', 'ANSIBLE_DEPLOY_STAGING_TEMPLATE_ID=6', 'ANSIBLE_DEPLOY_PROD_TEMPLATE_ID=7']) {
                            echo "DEBUG: got branch ${env.BRANCH_NAME}"
                            withCredentials([[$class: 'StringBinding', credentialsId: 'semaphore-token', variable: 'bearer']]) {
                                
                                if (env.BRANCH_NAME == 'staging') {
                                    echo "DEBUG: got project_id ${ANSIBLE_PROJECT_ID} and template_id ${ANSIBLE_DEPLOY_STAGING_TEMPLATE_ID}"
                                    def ANSIBLE_DEPLOY_TEMPLATE_ID = env.ANSIBLE_DEPLOY_STAGING_TEMPLATE_ID
                                    httpRequest acceptType: 'APPLICATION_JSON', consoleLogResponseBody: true, contentType: 'APPLICATION_JSON', customHeaders: [[name: 'Authorization', value: "Bearer ${env.bearer}"]], httpMode: 'POST', requestBody: """{
                                    \"template_id\": ${ANSIBLE_DEPLOY_TEMPLATE_ID},
                                    \"debug\": false,
                                    \"dry_run\": false,
                                    \"playbook\": \"\",
                                    \"environment\": \"\"
                                    }""", url: "https://ansible.semaphore.smartidea.es/api/project/${ANSIBLE_PROJECT_ID}/tasks"
                                }

                                if (env.BRANCH_NAME == 'main') {
                                    echo "DEBUG: got project_id ${ANSIBLE_PROJECT_ID} and template_id ${ANSIBLE_DEPLOY_STAGING_TEMPLATE_ID}"
                                    def ANSIBLE_DEPLOY_TEMPLATE_ID = env.ANSIBLE_DEPLOY_PRODUCTION_TEMPLATE_ID
                                    input(message: "Deploy to PRODUCTION?", ok: "Yes")
                                    httpRequest acceptType: 'APPLICATION_JSON', consoleLogResponseBody: true, contentType: 'APPLICATION_JSON', customHeaders: [[name: 'Authorization', value: "Bearer ${env.bearer}"]], httpMode: 'POST', requestBody: """{
                                    \"template_id\": ${ANSIBLE_DEPLOY_TEMPLATE_ID},
                                    \"debug\": false,
                                    \"dry_run\": false,
                                    \"playbook\": \"\",
                                    \"environment\": \"\"
                                    }""", url: "https://ansible.semaphore.smartidea.es/api/project/${ANSIBLE_PROJECT_ID}/tasks"
                                }
                            }
                        }
                    }
                }
            }
          }
        }
        currentBuild.result = 'SUCCESS'
    }
    
    catch (e) {
        echo 'Error: ' + e.toString()
        currentBuild.result = 'FAILURE'
        throw e
    }

    finally {
        sh 'docker system prune -af --volumes'
        emailext(
            subject: "Build ${env.JOB_NAME} - ${currentBuild.displayName} ${currentBuild.result}",
            body: """Build ${currentBuild.result}
                ${env.RUN_DISPLAY_URL}
                """,
            recipientProviders: [[$class: 'DevelopersRecipientProvider']]
        )
    }
}

def withDockerNetwork(Closure inner) {
    try {
        networkId = UUID.randomUUID().toString()
        sh "docker network create ${networkId}"
        inner.call(networkId)
    } finally {
        sh "docker network rm ${networkId}"
    }
}