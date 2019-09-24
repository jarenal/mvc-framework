# Welcome!

This is a test project using a basic MVC Framework, a basic Template Engine and a basic Dependencies Injection Container, all of them created from scratch.

## Setup

#### Using Docker (Recommended)

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

>NOTICE: The database is automatically imported when you are using Docker, but if you need to import the database manually you will find the dump at ./tools/schema/dump.sql

#### Using Apache (Optional: Only if you don't use Docker)

1- Create a vhost for Apache (optional)

Go to 

```bash
./tools/apache/vhost.conf
```

For to copy the template for to create your own vhost.

Remember to modify the paths properly for to fit with your file system.

>NOTICE: Remember reload or restart Apache.

2- Import database dump.

You need import into your MySQL database the dump located at 

```bash
./tools/schema/dump.sql
``` 

3- Setup config

Modify the config.yaml file located at

```bash
./config/config.yaml
```

You will need to modify the next parameters with yours:

```yaml
database:
  host: "localhost"
  username: "test"
  password: "1234"
  name: "web-test"
  port: "3306"
```

4- Enjoy!

Finally open in your favourite browser the URL to the vhost defined in your Apache and enjoy!


## Open previous quotes

When you create a quote you can opening it again passing their id or reference:

For example you can pass its primary key 'id':

http://localhost:8080/step3?id=1

or using its unique rererence:

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