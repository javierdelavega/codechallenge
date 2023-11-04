pipeline {
  agent {
    docker { image 'php:8.2' 
    args '-u root'}
  }
  stages {
    stage('Setup env') {
      steps {
        sh 'chmod +x ci/docker_install.sh'
        sh 'ci/docker_install.sh'
      }
    }

    stage ('Build') {
      steps {
        sh 'composer install --ignore-platform-reqs --no-scripts'
        sh 'php bin/console --env=test doctrine:database:create --if-not-exists --no-interaction'
        sh 'bin/console --env=test doctrine:schema:create --no-interaction'
      }
    }

    stage ('Test') {
      steps {
        sh 'php bin/phpunit --testdox'
      }
    }
  }
}