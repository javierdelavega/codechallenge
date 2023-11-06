node {
    try {
        checkout scm
        withDockerNetwork{ n ->
          docker.image('mariadb:10.11.4').withRun("--network ${n} --name db -e MYSQL_ROOT_PASSWORD=codechallenge") { c->
            docker.image('php:8.2').inside("--network ${n} -u root --name php") {
              stage('Setup env') {
                sh 'chmod +x ci/docker_install.sh'
                sh 'ci/docker_install.sh'
              }

              stage ('Build') {
                  sh 'composer install --ignore-platform-reqs --no-scripts'
                  sh 'php bin/console --env=test doctrine:database:create --if-not-exists --no-interaction'
                  sh 'bin/console --env=test doctrine:schema:create --no-interaction'
              }

              stage ('Test') {
                  sh 'php bin/phpunit --testdox'
              }
            }
          }
        } 


        /*docker.image('php:8.2').inside('-u root --network host') {
            stage('Setup env') {
                sh 'chmod +x ci/docker_install.sh'
                sh 'ci/docker_install.sh'
            }

            stage ('Build') {
                sh 'composer install --ignore-platform-reqs --no-scripts'
                sh 'php bin/console --env=test doctrine:database:create --if-not-exists --no-interaction'
                sh 'bin/console --env=test doctrine:schema:create --no-interaction'
            }

            stage ('Test') {
                sh 'php bin/phpunit --testdox'
            }
        }*/
    }
    
    catch (e) {
        echo 'Error: ' + e.toString()
        throw e
    }

    finally {
        sh 'docker system prune -af --volumes'
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