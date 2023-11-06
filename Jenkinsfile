node {
    try {
        checkout scm
        docker.image('php:8.2').inside {
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
    
    catch (e) {
        echo 'Error'
    }

    finally {
        sh 'docker system prune -af --volumes'
    }

}