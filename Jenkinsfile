pipeline {
  agent {
    dockerfile {
        filename 'Dockerfile.jenkins'
        dir 'ci'
        label 'docker-jenkins'
        additionalBuildArgs  '--build-arg PHP_VERSION=8.2'
    }
  }
  stages {
    stage('Setup env') {
      steps {
        sh 'starting mariadb server'
        sh 'mariadbd &'
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
  post {
    always {
        node(null) {
          steps {
            sh 'docker system prune -af --volumes'
          }
        }
    }
  }
}