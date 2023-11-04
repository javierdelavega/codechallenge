pipeline {
  agent {
    docker { image 'php:8.2' }
  }
  stages {
    stage('Setup env') {
      steps {
        sh 'echo hola'
      }
    }

    stage ('Test') {
      steps {
        sh 'php -v'
      }
    }
  }
}