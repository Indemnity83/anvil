## Anvil
Anvil is an opinionated docker image for running your Laravel projects in. Its tailored specificlaly for running small applications locally. This project is fully functional, but there are many more features to be implemented. The goal is to have a Laravel Forge like experience to managing and deploying your applications without the overhead of spinning up a VPS or cobbling together. 

## Image Variants

there is an `anvil:<version>` tag that will follow semver for image releases. If you're using a tool that can watch for updates, the `anvil:latest` image will always match the most recent released version that has been compiled. 

## Supported Architectures

The container is based on Alpine Linux and will likely compile for any of Alpines supported architectures, however, the only image that is compiled and tagged is the `linux/amd64`. Feel free to make a PR to expand the build process in Makefile. 

## Usage

Anvil is built to run your Laravel application in a single, low resource image utilizing SQLite, file based cache and session drivers and syncronouse queues and all the .env settings are pre-populated that way. However, if you want to link up to an external database, queue or 

Here are some example snippets to help you get started creating a container. 

### docker

```
docker create \
  --name=anvil \
  -v /path/to/data:/storage \
  -p 8080:8080
  -e DEPLOY_REPO=https://github.com/laravel/laravel
  -e DEPLOY_BRANCH=master
  --restart unless-stopped \
  indemnity83/anvil
```

### docker-compose

You can use docker-compose or docker stack deploy if you want to bundle MySQL and other services with the webserver. Laravel is wonderefully capable of scaling up and down using stacks, but the Nginx and PHP configurations built into Anvil have been tuned for low volume traffic.  

```
---
version: '2.1'

services:

  web:
    image: indemnity83/anvil
    ports:
      - 8080:8080
    environment:
      - DEPLOY_REPO=
      - DEPLOY_BRANCH=
    volumes:
      - /path/to/data:/storage
  
  db:
    image: mysql
    command: --default-authentication-plugin=mysql_native_password
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: example
```

## Environment Variables

`UPLOAD_LIMIT`

Sets the maximum allowable upload size. Defaults to 20M.

## Application Setup

Your application will be available at `HTTP://<your-ip>:8080` (port may be different if you've modified the above examples). The rest is up to you. 

A reverse proxy is recomended to provide SSL protection for public access. [jc21/nginx-proxy-manager](https://github.com/jc21/nginx-proxy-manager) is an execelent tool for managing multiple reverse proxieis and includes LetsEncrypt support. 

All persistent configuration data and media will be stored in the attached volume.  

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[GNU GPLv3](https://choosealicense.com/licenses/gpl-3.0/)
