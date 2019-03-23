# Sporty-api

## About
Sporty-api is a REST and GraphQL API for football

## How to install it

    git clone https://github.com/LMDWEB/Sporty-api.git
    cd Sporty-api
    composer install
    cp .env.dist .env
    --> update .env
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

## Run migrations
    php bin/console doctrine:migrations:migrate
  
## Get a JWT Token
    curl -X POST -H "Content-Type: application/json" http://localhost:8000/login_check -d '{"username":"admin","password":"password"}'
  
## Example of accessing secured routes
    curl -H "Authorization: Bearer [TOKEN]" http://localhost:8000/api/clubs
    curl -H "Authorization: Bearer [TOKEN]" http://localhost:8000/api/players
    
## PS: You can Register a new user
    curl -X POST http://localhost:8000/register -d _username=johndoe -d _password=test
  
    