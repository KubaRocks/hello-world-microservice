# Microservice template
A template for PHP-based microservices in Symfony 6.

## Structure
```bash
├── config       # Symfony configuration and service definitions for dependency injection
├── bin          # Used for binaries that we want to commit
├── build        # A build directory for code coverage, logs, caches, etc. Ignore by .gitignore
├── docker       # Dockerfiles and associated assets to build images
├── docs         # Documentation, included autogenerated and code metrics
├── features     # Feature definitions written in Gherkin notation
├── public       # Entrypoint file for when the microservice is being used as an API.
├── src
│   ├── api      # Implementations specific to the transport mechanism, e.g. HTTP Controllers and Console Commands.
│   └── app      # The application itself, agnostic to transport medium. Commands, Queries, and Handlers live here
│   ├── domain   # Business-level code which has no concept of the outside world (e.g. HTTP)
│   └── infra    # Implementation-specific application code, ie a `DBALCustomerRepository`
├── tests        # Test suites
│   ├── behat    # Behat Contexts
│   ├── fixtures # Fixtures for tests to use
│   ├── unit     # Unit tests
└── vendor       # Vendor (ignored by git)
```

## Bundled Tooling

### Testing

#### [PHPUnit][phpunit]
Standard tool for creating unit tests. Each test case should be for an isolated classs (the unit, or the Subject Under Test), with as many dependencies and collaborators mocked as possible. The mocking framework of choice in this template is [Prophecy][prophecy].
#### [Infection][infection]

#### [Behat][behat]
Framework for [Behavior-driven development (BDD)][bdd] to create test automation around business expectations. In particular, there are various _Contexts_ that should be created, each one wrapping a particular driver (ie, API or console) or pure service, allowing segregation of business logic from implementation.

### Quality & Reporting

#### phpcs

#### psalm

#### phpstan

#### phpmd

#### phpmetrics

## Make commands

In most cases, the various `make` targets run the docker container with a specific `composer` script. Here are some of the core targets:

* `setup` - Sets up the project.
* `install` - prepares the development environment, runs `composer install`
* `test` - runs the `test` script defined in `composer.json`, which will run the entire test suite (e.g. both unit and functional).

[phpunit]: https://phpunit.readthedocs.io/en/9.5/
[infection]: https://infection.github.io/guide/
[prophecy]: https://github.com/phpspec/prophecy
[bdd]: https://en.wikipedia.org/wiki/Behavior-driven_development
[behat]: https://docs.behat.org/en/latest/
