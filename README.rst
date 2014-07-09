Unify
=============================

Unify is an extensible data manipulation framework, that can use any iterable datasource.

It's helpful for fast development of complex data manipulation processes.

Unify also provide some extension libraries for specific uses (XML, Json, Math....)

Prerequisites
----------

* `PHP`_ 5.4 or greater.
* `Expand`_ 1.0 or greater

Installation
----------

The recommended way to install Unify is through `Composer`_.

First, add Unify to the list of dependencies inside your `composer.json`:

.. code-block:: json

    {
        "require": {
            "mecum/unify": "1.0.*"
        }
    }

Then simply install it with composer:

.. code-block:: batch

    $> composer install --prefer-dist

Continuous integration
----------
This project is automatically tested on the `Travis CI`_ plateform.

See below the status of the last dev build :

.. image:: https://travis-ci.org/mecum/unify.svg?branch=master

Tests
----------

To run the test suite, you need `Composer`_:

Linux :

.. code-block:: bash

    $> php composer.phar install --dev
    $> vendor/bin/phpunit
    
    
Windows :

Launch the batch files ``dev/composer.bat`` and ``dev/phpunit.bat``
    
        
License 
----------

Mecum is licensed under the MIT license.

For the full copyright and license information, please view the `LICENSE`_.

.. _LICENSE:             https://github.com/mecum/unify/blob/master/LICENSE
.. _Expand:              https://github.com/mecum/expand
.. _PHP:                 http://www.php.net/
.. _Composer:            http://getcomposer.org
.. _Travis CI:           https://travis-ci.org
