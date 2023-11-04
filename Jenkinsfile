pipeline {
  agent none
  stages {
    stage('Setup env') {
      agent {
        docker { image 'php:8.2' 
        args '-u root'}
      }
      steps {
        sh 'chmod +x ci/docker_install.sh'
        sh 'ci/docker_install.sh'
      }
    }

    stage ('Build') {
      agent {
        docker { image 'php:8.2' 
        args '-u root'}
      }
      steps {
        sh 'composer install --ignore-platform-reqs --no-scripts'
        sh 'php bin/console --env=test doctrine:database:create --if-not-exists --no-interaction'
        sh 'bin/console --env=test doctrine:schema:create --no-interaction'
      }
    }

    stage ('Test') {
      agent {
        docker { image 'php:8.2' 
        args '-u root'}
      }
      steps {
        sh 'php bin/phpunit --testdox'
      }
    }
  }
  post { 
        always { 
            sh 'whoami'
            sh 'docker system prune -af --volumes'
        }
  }
}