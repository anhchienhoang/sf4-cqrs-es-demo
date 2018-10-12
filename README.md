Symfony 4 - CQRS-ES Demonstration
=====================

A simple products management application for learning and sharing purpose which's based on Symfony 4 and implemented CQRS-ES within DDD tactical patterns approach:
 
 * [Aggregate, Entity, Domain Events, Value Object, Repository][3]
 * [Layered][4] and Hexagonal (Ports & Adapters) Architecture

Main using components:

 * [Messenger Component][1] for dispatching command and query.
 * [JMS Serializer][2] for object (de-)serializing data object

For Event Sourcing (ES), it just implemented a basic concept with a single events stream and synchronous projections. 
In advanced, it could have multiple streams and asynchronous projections.

Feel free to create your PR if you want to change something to make it better.

## Requirements
* PHP >= 7.1
* MySQL >= 5.7

## Installation

### For non-docker developers

Setup PHP dependencies
```bash
$ composer install
```

Run migrations
```bash
$ ./bin/console doctrine:migrations:migrate
```

Build Front-end
```bash
$ yarn install && yarn build
```
or
```bash
$ npm install && npm run build
```

Nginx and PHP pool config you can find here
```bash
docker/nginx/conf.d/demo.conf
docker/php/pool.d/demo.conf
```

### For docker developers
Start containers:

```bash
$ docker-compose up -d
```

Build Front-end:
```bash
$ docker run -it --rm --volume $(pwd):/app -w=/app node:9.0 /bin/bash -c "npm install && npm run build"
```

Next you have to update your hosts file (commonly located at /etc/hosts) with the following
```bash
127.0.0.1 demo.local
```

Open your browser and go to
```bash
http://demo.local
```
Add some products, try to update them and check the data in the database to see how it works :)

[1]: https://symfony.com/doc/current/components/messenger.html
[2]: https://github.com/schmittjoh/serializer
[3]: https://en.wikipedia.org/wiki/Domain-driven_design#Building_blocks
[4]: src/SfCQRSDemo
