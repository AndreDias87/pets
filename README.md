## Running Composer inside Docker

This project uses [Symfony Docker](https://github.com/dunglas/symfony-docker) as the base setup.
Composer is executed inside the PHP container so you don’t need PHP or Composer installed on your host machine.

### Getting Started

Follow the instructions to install Docker Compose if you have not already done it: https://docs.docker.com/compose/install/

Run `docker compose build --pull --no-cache` to build fresh images

Run `docker compose up --wait` to set up and start a fresh Symfony project

Run `docker compose run --rm php composer install` to install dependencies

Run `docker compose run --rm php bin/console tailwind:build` to build tailwind

Open https://localhost/pet/new to register a new pet

Run `docker compose down --remove-orphans` to stop the Docker containers.

