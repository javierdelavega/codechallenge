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

    stage ('Test') {
      steps {
        sh 'whoami'
        sh 'php -v'
      }
    }
  }
}