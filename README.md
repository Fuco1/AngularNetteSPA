# Entity Repository SPA + REST Server [![Build Status](https://semaphoreci.com/api/v1/fuco/angularnettespa/branches/master/badge.svg)](https://semaphoreci.com/fuco/angularnettespa)

This project is a showcase of a simple Entity Repository (CRUD) [SPA](https://en.wikipedia.org/wiki/Single-page_application) using [Angular](https://angularjs.org/) and [ng-admin](https://github.com/marmelab/ng-admin) backed by REST service written in [Nette Framework](https://nette.org/en/) using [Doctrine](http://www.doctrine-project.org/) for storage.

# Requirements

The backend application runs on [PHP](http://php.net/) version `7.1` or newer.  The frontend requires [node.js](https://nodejs.org/en/) version `6.11.1`.  You can use [nvm](https://github.com/creationix/nvm) to install the required version.

The easiest way to try the application out is to use the provided [Docker](https://www.docker.com/) stack.  You will need `docker` in version at least `1.12` and [docker-compose](https://docs.docker.com/compose/) in version at least `1.14`.

# Installation & Usage

After cloning the repo, run

``` shell
make deps
```

to grab all the dependencies.

The first time docker stack is brought up a database volume will be created with a clean database.  You will need to execute the migration scripts to make it usable:

``` shell
./cmd migrations:migrate
```

Then run

``` shell
make run
```

to start the backend and frontend services.  Then visit http://localhost:8912.  That's it!

You can reach the backend at http://localhost:8080/api.

## Docker helper

The `./cmd` helper can be used to execute application commands inside the docker container.  Use `--test` as the first argument to run the command in the test environment (separate database).

# Build & Tests

To define the build we use [phing](https://www.phing.info/), which is a build system written in PHP and therefore readily available.

The entire build consists of five stages:

- [linter](https://github.com/JakubOnderka/PHP-Parallel-Lint)
- [CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [unit tests](https://tester.nette.org/en/)
- [integration tests](https://tester.nette.org/en/)
- [phpstan](https://github.com/phpstan/phpstan) analysis

To run the fast build (no integration tests), use

``` shell
make test
```

To run the complete build with integration tests, use

``` shell
make test-slow
```

To fix auto-fixable style errors we can run:

``` shell
make cs-fix
```

# Backend application architecture

The backend uses the standard "MC" (no view in api server) architecture.  Entities are managed by an ORM service which is encapsulated inside facades.  The controllers (presenters in Nette terminology) use the facades to carry out the tasks which they are supposed to do.

The REST endpoints follow somewhat standard convention of mapping operations to HTTP methods:

```
list all      => GET    /entities
create new    => POST   /entities
show detail   => GET    /entities/:id
edit entity   => PUT    /entities/:id
delete entity => DELETE /entities/:id
```

## Data validation and normalization

Special care is taken to make sure data inside the system stay consistent.  We ensure, using the PHPs type system, that invalid data from the user never reach the inner logic of the system.

This is achieved by first screening the input arrays through validators and then wrapping them in a `Validated` class instance.  Later stages of the system, which require validated data, accept only this type instead of plain arrays.  This way we ensure on the type level that unvalidated data cannot reach the model layer.

Similarly, since the REST api uses JSON objects as the transfer format, we use normalizers to translate the plain strings into PHP objects (such as the string `2000-10-20` into a `DateTimeImmutable` instance).  Normalizers only accept `Validated` objects so that they are guaranteed to never fail because they always receive good data.  Normalizers emit instances of `Normalized` class so that we can further ensure that only normalized data are fed into entity creation methods.

Both the `Validated` and `Normalized` classes implement `ArrayAccess` for ease of use.  They are made immutable to further ensure that the data is never changed inside them once created.

This approach requires a little bit of manual boxing and unboxing of data but is very resilient to programmer errors and ensures better correctness when dealing with sanitation of external inputs.

## Immutable entities

Entities are kept immutable as far as possible.  This ensures that once created they stay valid for the entire duration of the script execution and never get corrupted.  This guarantee makes it easier for the programmer to argue about the state of the system as there is never any doubt whether the entity is in a correct state or not.

Due to the fact that Doctrine can not use immutable entities (the entity manager uses inherently mutable methods to update entities), special mutable wrappers are used to mutate the entities.

The entity `Cosmonaut` is immutable and has no setters other than the constructor.  Once created it can not be changed.

However, we might want to update the same entity and persist the changes to the same row.  This would not be possible if we created a new entity because it would become unmanaged and it would get assigned a new ID.  We can wrap `Cosmonaut` in an instance of `MutableCosmonaut`:

``` php
$immutableCosmonaut = new Cosmonaut('name', 'surname', ...);
$mutableCosmonaut = new MutableCosmonaut($immutableCosmonaut);
```

Mutable entities allow changes to the data of the underlying entity (via class inheritance) through curated sets of methods (so not any updates are possible, only those which are necessary for the application).

The functions which wish to mutate entities must then explicitly make this apparent through the type of the input argument.  Of course, a mutable entity can be passed anywhere an immutable one can be passed because we are sure nothing inside the method would change it (it has no access to the mutators... though in PHP this is not enforced by the runtime itself).

## Further type safety

One can ensure even more type safety by wrapping all the primitive types in domain classes, so that a `$name` property would not have type `string` but a class of type `Name` which would wrap the string.  This would prevent us from passing a `string` representing `$surname` to the constructor field for `$name` and so on.

This is considered extreme by some and can make the code a bit more annoying to write and read but it can again prevent a host of errors such as having constructors with 5 string arguments and passing the data into it in incorrect order.  This can be detected with tests but having the type system do it is much more robust in the long run.

The application presented here does not use this approach because it is small and a reasonable test coverage is possible.  However in bigger systems it can be very advantageous for the long-term maintenance.

See [c2](http://wiki.c2.com/?PrimitiveObsession) wiki and [object calisthenics](https://medium.com/web-engineering-vox/improving-code-quality-with-object-calisthenics-aa4ad67a61f1) for further reading.
