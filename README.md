## Running Composer inside Docker

This project uses [Symfony Docker](https://github.com/dunglas/symfony-docker) as the base setup.
Composer is executed inside the PHP container so you don’t need PHP or Composer installed on your host machine.

To install or update dependencies, run:

```bash
docker compose run --rm php composer install
```

### Git safe.directory warning

When running Composer inside Docker, you may see this warning:

```bash
fatal: detected dubious ownership in repository at '/app'
```

This happens because the code is mounted into the container at `/app` with a different user ID.

To fix it, mark `/app` as a safe directory inside the container:

```bash
docker compose run --rm php git config --global --add safe.directory /app
```

Alternatively, you can set the environment variables UID and GID before building the containers to match your host user:

```bash
export UID
export GID
docker compose build --no-cache
```
