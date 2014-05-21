RestBundle
===========================

Symfony2 REST Client bundle


Installation
------------

Add dependencies in your `composer.json` file:
```json
"repositories": [
    ...,
    {
        "type": "vcs",
        "url": "https://github.com/Tessi-Tms/TmsRestClientBundle.git"
    }
],
"require": {
        ...,
        "tms/rest-client-bundle": "dev-master"
    },
```

Install these new dependencies of your application:
```sh
$ php composer.phar update
```

Enable the bundle in your application kernel:
```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Tms\Bundle\RestBundle\TmsRestClientBundle(),
    );
}
```

Import the bundle configuration:
```yml
# app/config/config.yml

imports:
    - { resource: @TmsRestClientBundle/Resources/config/config.yml }
```

To check if every thing seem to be ok, you can execute this command:
```sh
$ php app/console container:debug
```

Documentation
-------------

[Read the Documentation](Resources/doc/index.md)


Tests
-----

Install bundle dependencies:
```sh
$ php composer.phar update
```

To execute unit tests:
```sh
$ phpunit --coverage-text
```
