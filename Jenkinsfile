pipeline {
  agent {
    docker { image 'php:8.2' }
  }
  stages {
    stage('Setup env') {
      steps {
        sh 'ci/docker_install.sh'
      }
    }

    stage ('Test') {
      steps {
        sh 'php -v'
      }
    }
  }
}