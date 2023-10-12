# K-Dex

An open-source character-driven roleplay forum, inspired by [**F-List**](https://f-list.net).

## Setup

0. [Docker](https://www.docker.com/) should already be installed on your system
1. Launch the docker containers.
   - `UID=$(id -u) GID=$(id -g) USER=$(whoami) docker-compose -f .docker/docker-compose.yml up -d` from the shell
1. Enter the 'app' container's shell
   - `docker exec -it KDex-php8 sh` from the shell

All further console commands get run from this Docker container shell

3. Install dependencies though the Docker container
   - `composer install`

And now you can access through http://localhost:8000/. Ignore the SSL warning; for development purposes, the SSH key is manually generated & signed inside of the Nginx container.

## Details

### Structure of this project:

- [.docker](/.docker): The Docker files to create a running enviroment
  - Container logs are saved in their own appropriate folders
- [.mysql](/.mysql): The MySQL local database files (because the container is unable to store them itself)
- [Source](/src): Business logic
- [Public](/public): The only directory that's meant to be accessed directly from the internet.
- [Tests](/tests): Directory containing XDebug tests
- [Vendor](/vendor): Composer controlled PHP dependencies (not logged, for obvious reasons)
