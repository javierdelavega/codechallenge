pipeline {
  agent {
    docker { image 'php:8.2' 
    args '-u root'}
  }
  stages {
    stage('Setup env') {
      steps {
        sh 'whoami'
        sh 'echo hola'
      }
    }

    stage ('Test') {
      steps {
        sh 'whoami'
        sh 'php -v'
      }
    }
  }
}