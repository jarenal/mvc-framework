# Welcome!

This is a test project using a basic MVC Framework, a basic Template Engine and a basic Dependencies Injection Container, all of them created from scratch.

## Setup

Clone or download the project in a folder and then install dependencies using composer:

```bash
    $ cd PROJECT_FOLDER
    $ composer install
```

Start Docker containers using

```bash
$ docker-compose up
```

Then open in your browser the next url:

http://localhost:8080

NOTICE: The database is automatically imported when you are using Docker, but if you need to import the database manually you will find the dump at ./tools/schema/dump.sql

## Open previous quotes

You can see previous quotes passing their id or reference:

by id:

http://localhost:8080/step3?id=1

or by rererence:

http://localhost:8080/step3?reference=Q-5d8a1cbfc7585

## Tests

For to run Unit Tests execute the next command:

```bash
$ ./vendor/phpunit/phpunit/phpunit tests
```
## Authors

* **Jose Antonio** - *Initial work*

## Donations

If you found this useful. Please, consider support with a small donation:

* **BTC** - 1PPn4qvCQ1gRGFsFnpkufQAZHhJRoGo2g5
* **BCH** - qr66rzdwlcpefqemkywmfze9pf80kwue0v2gsfxr9m
* **ETH** - 0x5022cf2945604CDE2887068EE46608ed6B57cED8

## License

This project is licensed under the ISC License - see the [LICENSE](LICENSE) file for details