![Homepage view](./public/logo_api.jpg)

A simple API REST for SCM application.

# Stack

- PHP8
- Symfony 5
- Api Platform

# Init the API for dev environment (linux)

/!\ You will need docker and docker-compose on your machine

## Clone the project and go to the directory
```bash
//in /var/www/ 
git clone https://github.com/qamar-h/scm-backend.git
cd scm-backend
```

## Start
```bash
sudo make build 
```

## Access
```bash
http://localhost:3001
```

## For Dev
 Refer to the Makefile to know the commands you would need throughout your development.

 ``` 
make help
``` 
to see the list of makefile commands in terminal 



