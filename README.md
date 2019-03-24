# Sporty-api

## About
Sporty-api is a REST and GraphQL API for football

## First clone the repository
    git clone https://github.com/LMDWEB/Sporty-api.git

## Move in directory
    cd Sporty-api
    
## Install dependencies
    composer install

## Create .env file from .env.dist
    cp .env.dist .env

## Configure database connection and write your API keys
    --> update .env
    DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
    PAYPAL_ID=
    PAYPAL_SECRET=

## Now you can make migration, launch it, and use fixtures
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:fixtures:load
    
## Generate the SSH keys
    mkdir config/jwt
    openssl genrsa -out config/jwt/private.pem -aes256 4096
    openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem 
    openssl rsa -in config/jwt/private.pem -out config/jwt/private2.pem
    mv config/jwt/private.pem config/jwt/private.pem-back
    mv config/jwt/private2.pem config/jwt/private.pem

## Run server
    php bin/console server:run
  
## Get a JWT Token
    You can go in token page in application or use command bellow :
    curl -X POST -H "Content-Type: application/json" http://localhost:8000/login_check -d '{"username":"admin","password":"password"}'
  
## Example of accessing secured routes
    curl -H "Authorization: Bearer [TOKEN]" http://localhost:8000/api/clubs
    curl -H "Authorization: Bearer [TOKEN]" http://localhost:8000/api/players
    
## PS: You can Register a new user
    curl -X POST http://localhost:8000/register -d _username=johndoe -d _password=test
  
    