![Packagist Lang](https://img.shields.io/badge/PHP-%3E%3D7.2-blue.svg)
[![Build Status](https://travis-ci.org/Kooser6/Session.svg?branch=master)](https://travis-ci.org/Kooser6/Session)
[![Coverage Status](https://coveralls.io/repos/github/Kooser6/Session/badge.svg?branch=master)](https://coveralls.io/github/Kooser6/Session?branch=master)

# Session

Securely manage and preserve session data.

## Requirements

> pdo php extension <br />
> php 7.3 and up

## Installation

via Composer:

The best way to install this session library is through composer. If you do not have composer installed you can install it directly from thier website (https://getcomposer.org/). After composer is successfully install run the command line code below.

```sh
composer require kooser/session
```

## Usage

### Basic usage

Our session api is easy to use static methods that replace the native session function. You ultimately control the session using the `SessionManager` class. Here is a small example of starting a secure session with same-site cookies. Also the session config is the session runtime config which can be found here (https://www.php.net/manual/en/session.configuration.php). You can use them in the session config array just remove the `session.` prefix.

```php
<?php

use Kooser\Session\SessionManager;

// Require the composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Define our session config.
$sessionConfig = [
    "use_cookies"      => \true,
    "use_only_cookies" => \true,
    "cookie_samesite"  => "Lax",
];

// Start the session.
SessionManager::start($sessionConfig);

// Check to see if we are active.
var_dump(SessionManager::exists());

```

### Session Handlers

This session library comes with its own custom session handlers which can be implemented and used with complete ease. We have a lot of session handlers to choose from here is an example of using the filesystem and mysql session handler. Also when using the mysql session handler it will create the table for you, all you have to do is set the name of the table.
