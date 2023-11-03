pipeline {
    agent {
        docker { image 'php:8.2' }
    }
    stages {
        stage('Test') {
            steps {
                sh 'php -v'
            }
        }
    }
}