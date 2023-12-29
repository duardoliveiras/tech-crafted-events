# Tech Crafted

Tech Crafted is an open-source university event management website crafted by three friends passionate about development. Built with PHP, Laravel, JS, HTML, CSS, Bootstrap, Postgresql, and Docker, our platform aims to enhance development skills while providing an intuitive and scalable solution for event organizers.

[![Video](https://raw.githubusercontent.com/duardoliveiras/tech-crafted-events/main/uploads/aboutus.png)](https://youtu.be/ucvq9tmYUHU)

## Index
- [Installation](#installing-the-software-dependencies)
- [Starting](#starting-the-service)
- [Deploy](#image)
- [Team](#team)
- [Wiki](https://github.com/duardoliveiras/tech-crafted-events/wiki)


## Introduction

This README describes how to setup the development environment for Tech Crafted.

These instructions address the development with a local environment (with PhP installed) and Docker containers for PostgreSQL and pgAdmin.

## Installing the software dependencies

To prepare you computer for development you need to install PHP >=v8.1 and Composer >=v2.2.

We recommend using an **Ubuntu** distribution that ships with these versions (e.g Ubuntu 22.04 or newer). You may install the required software with:

```bash
sudo apt update
sudo apt install git composer php8.1 php8.1-mbstring php8.1-xml php8.1-pgsql php8.1-curl php-gd
```

On MacOS, you can install them using [Homebrew](https://brew.sh/) and:

```bash
brew install php@8.1 composer
```

If you use [Windows WSL](https://learn.microsoft.com/en-us/windows/wsl/install), please ensure you are also using Ubuntu 22.04 inside. Previous versions do not provide the requirements needed for this template, and then follow the Ubuntu instructions above.

## Setting up the development repository

You should clone our repository to your local machine.

```bash
git clone https://git.fe.up.pt/lbaw/lbaw2324/lbaw2316.git

cd lbaw2316
```

## Installing local PHP dependencies

After the steps above, you will have updated your repository with the required Laravel structure from this repository.
Afterwards, the command bellow will install all local dependencies.

```bash
composer update
```

If this fails, ensure you're using version 2 or above of Composer. If there are errors regarding missing extensions, make sure you uncomment them in your [php.ini file](https://www.php.net/manual/en/configuration.file.php).

## Working with PostgreSQL

We've created a _docker compose_ file that sets up **PostgreSQL** and **pgAdmin4** to run as local Docker containers.

From the project root issue the following command:

```bash
docker compose up -d
```

This will start your containers in detached mode. To stop them use:

```bash
docker compose down
```

Navigate on your browser to http://localhost:4321 to access pgAdmin4 and manage your database. Depending on your installation setup, you might need to use the IP address from the virtual machine providing docker instead of `localhost`. Please refer to your installation documentation.
Use the following credentials to login:

```
Email: postgres@lbaw.com
Password: pg!password
```

On the first usage you will need to add the connection to the database using the following attributes:

```
hostname: postgres
username: postgres
password: pg!password
```

Hostname is _postgres_ instead of _localhost_ since _Docker Compose_ creates an internal DNS entry to facilitate the connection between linked containers.

## Starting the service

Com todas as configurações definidas, agora podemos iniciar o servidor laravel

To start the server from the project's run:

```bash
# Used to apply migrations, which are instructions in PHP code that define the structure of the database
php artisan migrate

# Seed database from the SQL file.
# Needed on first run and every time the database script changes.
php artisan db:seed

# Start the development server
php artisan serve
```

```bash
# To update javascript libraries
npm install

# Used to start a local development environment
npm run dev
```

Access `http://localhost:8000` to access the app. Username is `admin@gmail.com`, and password `admin123`. These credentials are copied to the database on the first instruction above.

To stop the server just hit Ctrl-C.

### Configuration

Laravel configurations are acquired from environment variables. They can be available in the environment where the Laravel process is started, or acquired by reading the `.env` file in the root folder of the Laravel project. This file can set environment variables, which set or override the variables from the current context. You will likely have to update these variables, mainly the ones configuring the access to the database, starting with `DB_`.

_You must manually create a schema that matches your username._

Note that you can make your local application use the remote database by simply changing the `.env` file accordingly.

If you change the configuration, you might need to run the following command to discard a compiled version of the configuration from Laravel's cache:

```bash
php artisan route:clear
php artisan cache:clear
php artisan config:clear
```

### Image

To build the project image, you can use the `Dockerfile`.

```bash
sudo docker build --tag techcrafted:latest .
```

To create a container from the project image:

```bash
sudo docker run --name tech-crafted-service -d -p 8000:80 techcrafted:latest
```

This will start a local `nginx` service on port 8000 for Tech Crafted.

## Team

* Member 1 [Bernardo Brito](https://github.com/brito-bernardo)
* Member 2 [Eduardo Oliveira](https://github.com/duardoliveiras)
* Member 3 [Vicente Damasceno](https://github.com/Vicente-MD)
