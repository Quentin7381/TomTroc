# TomTroc

## Project Description

TomTroc is a simple book trading platform that allows users to trade books with other users. Users can create an account, list books they own, and send messages to other users to propose trades.

## Project Structure

The project is structured in a Symfony inspired way, with the following directories:

- `config`: contains the configuration files of the project
- `public`: contains the public files of the project (images, stylesheets, scripts, etc.)
- `src`: contains the source code of the project (controllers, entities, services, etc.)
- `templates`: contains the templates of the project
- `tests`: contains the tests of the project
- `vendor`: contains the dependencies of the project

## Installation

Docker files are provided to facilitate the installation of the project. To use them, follow the steps below:

1. Install and start Docker
2. Clone the repository
3. Run the following commands:

```bash
docker-compose build
docker-compose up
```

4. Access the project at `http://localhost:8000`

## Contributing

Please read the [CONTRIBUTING.MD](CONTRIBUTING.MD) file for details on our workflow and code conventions.
