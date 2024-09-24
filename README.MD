# TomTroc

## Note to the corrector

I must warn you that some elements withing the evaluation grid are not validated. Here are the points and the reasons why they are not validated:
- "Les tables de la base de données possèdent des relations entres elles qui garantissent l’intégrité de la base de données (ex. : au niveau de la BDD, il est impossible de créer un post sans utilisateur valide)."
    => The database is dynamicly created to match the php entities. This feature is still semi-experimental, and going into the database configuration to add constraints would be quite some more work.
- "La partie admin n’est pas accessible aux utilisateurs lamba." => there was no role management in the project, neither a specified admin account. This validation point may be obsolete.

## Project Description

TomTroc is a simple book trading platform that allows users to trade books with other users. Users can create an account, list books they own, and send messages to other users to propose trades.

## Project Structure

The project is structured in a Symfony inspired way, with the following directories:

- `config`: loads the .env configuration of the project
- `public`: contains the public files of the project (images, stylesheets, scripts, etc.)
- `src`: contains the source code of the project (controllers, entities, services, etc.)
- `templates`: contains the templates of the project
- `vendor`: contains the dependencies of the project

## Installation

Docker files are provided to facilitate the installation of the project. To use them, follow the steps below:

1. Install and start Docker on your machine
2. Clone the repository
```bash
git clone https://github.com/Quentin7381/TomTroc.git
```
3. Open the repo, then checkout to develop branch
```bash
cd TomTroc
git checkout develop
```
3. Build and start the Docker containers
```bash
docker-compose build
docker-compose up
```

Now you're environement is set up, but there is some more steps to do to have the project fully working:

4. Create a .env file in the root of the project with the following content:
```bash
# Database (to be filled with your own database information)
DB_USER=
DB_PASSWORD=
DB_HOST=
DB_PORT=
DB_NAME=

# Paths
PATH_TEMPLATES=templates/
PATH_CSS=assets/css/
PATH_JS=assets/js/
PATH_IMG=public/img/
PATH_PUBLIC=public/
```

5. Install the dependencies of the project by running the following command (from the root of the project):
```bash
docker-compose exec web bash -c "composer install"
```

6. All set up ! Access the project at `http://localhost:8000`

## Contributing

Please read the [CONTRIBUTING.MD](CONTRIBUTING.MD) file for details on our workflow and code conventions.
