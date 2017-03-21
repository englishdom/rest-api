# rest-api
REST API with modern libraries and technologies. Zend Expressive, JWT, JSON API

## Install

Copy docker-compose file

    cp docker-compose.yml.dist docker-compose.yml

Execute

    docker-compose up
    
Add string to /etc/hosts

    172.17.0.1 rest-api
    
Get last `composer.phar`

    wget https://raw.githubusercontent.com/composer/getcomposer.org/1b137f8bf6db3e79a38a5bc45324414a6b1f9df2/web/installer -O - -q | php -- --quiet
 
Install composer's dependencies

    docker exec -it rest-api-php php composer.phar install
    
## Working with docker container

* Connect to docker container `docker exec -it rest-api-php bash`
* Get statistics about docker container `docker inspect <container_id>`
* Default IP for container with mysql `172.20.0.2` 