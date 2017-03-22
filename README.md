# Flowder PHPUnit [![Build Status](https://travis-ci.org/imjoehaines/flowder-phpunit.svg?branch=master)](https://travis-ci.org/imjoehaines/flowder-phpunit) [![codecov](https://codecov.io/gh/imjoehaines/flowder-phpunit/branch/master/graph/badge.svg)](https://codecov.io/gh/imjoehaines/flowder-phpunit)

**Flowder PHPUnit** is a PHPUnit Test Listener for integrating the [Flowder](https://github.com/imjoehaines/flowder) fixture loader into PHPUnit test suites.

## Usage

1. Install Flowder PHPUnit as a development dependency through [Composer](https://getcomposer.org/)

   ```sh
   $ composer install imjoehaines/flowder-phpunit --dev
   ```

2. Enable Flowder PHPUnit as a test listener in your `phpunit.xml` file ([PHPUnit documentation](https://phpunit.de/manual/current/en/appendixes.configuration.html#appendixes.configuration.test-listeners))

   ```xml
   <listeners>
     <listener class="\Imjoehaines\Flowder\PhpUnit\FlowderListener"></listener>
   </listeners>
   ```

3. Bootstrap Flowder PHPUnit by calling `FlowderListener::bootsrap` in your PHPUnit `bootstrap.php` file, passing in an instance of `\Imjoehaines\Flowder\Flowder` (see the [Flowder documentation](https://github.com/imjoehaines/flowder) for more information).

   This is only necessary until [PHPUnit #1873](https://github.com/sebastianbergmann/phpunit/issues/1873) is fixed. After this, you can configure Flowder PHPUnit through your `phpunit.xml` file instead.

   A simple SQLite example might look like this:

   ```php
   <?php

   require __DIR__ . '/../vendor/autoload.php';

   use Imjoehaines\Flowder\PhpUnit\FlowderListener;

   use Imjoehaines\Flowder\Loader\PhpFileLoader;
   use Imjoehaines\Flowder\Truncator\SqliteTruncator;
   use Imjoehaines\Flowder\Persister\SqlitePersister;

   $db = new PDO(...);
   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   FlowderListener::bootstrap(
       __DIR__ . '/_data/example.php',
       new PhpFileLoader(),
       new SqliteTruncator($db),
       new SqlitePersister($db)
   );
   ```

4. That's it! Before any test file runs, Flowder will load your fixture data for you
