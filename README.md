[![Travis (.org) branch](https://img.shields.io/travis/Kooser6/Session/master.svg)](https://travis-ci.org/Kooser6/Session)
[![Coveralls github branch](https://img.shields.io/coveralls/github/Kooser6/Session/master.svg)](https://coveralls.io/github/Kooser6/Session?branch=master)

# Session

Securely manage and preserve session data.

## Installation

via Composer:

The best way to install this php component is through composer. If you do not have composer installed you can install it directly from the [composer website](https://getcomposer.org/). After composer is successfully installed run the command line code below.

```sh
composer require kooser/session
```

## Usage

### Basic usage

Using the session manager is extremely simple.

```php
<?php

use Kooser\Session\SessionManager;

// Require the composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Generate a secret security code that will be used in binded
// the user ip and user agent to the session.
$secuirtyCode = '%someData%';

// Set the session options.
$options = [
    'session_security_code' => $securityCode,
];

// Create a session manager.
$sessionManager = new SessionManager($options);

// Start the session.
$sessionManager->start();

// See if a session exists.
$exists = $sessionManager->exists();
var_dump($exists);

// Set a session variable.
$sessionManager->put('kooser', 'session');

// Get a session variable.
$value = $sessionManager->get('kooser');
var_dump($value);

// Flash a session variable (deletes the variable after retrievable).
$value = $sessionManager->flash('kooser');
var_dump($value);

// See if this session manager exists.
$doWeHave = $sessionManager->has('kooser');
var_dump($doWeHave);

// Set a session variable.
$sessionManager->put('kooser', 'session');

// See if this session manager exists.
$doWeHave = $sessionManager->has('kooser');
var_dump($doWeHave);

// Delete the session variable.
$sessionManager->delete('kooser');
$value = $sessionManager->get('kooser');
var_dump($value);

// Regenerate the session.
$sessionManager->regenerate();

// Delete the session.
$sessionManager->stop();

// See if a session exists.
$exists = $sessionManager->exists();
var_dump($exists);

```

### Session Fingerprinting

This session manager includes automatic session fingerprinting.

```php

$options = [
    'session_fingerprint' => \true,
    'session_fingerprint_hash' => 'sha512',
    'session_lock_to_ip_address' => \true,
    'session_lock_to_user_agent' => \true,
];

```

### Session Encryption

This session manager includes a built-in encryption system from the `paragonie/halite` package.

```php

$options = [
    'session_encrypt' => \true,
    'session_fingerprint_hash' => YourHaliteEncryptionKey,
];

https://github.com/paragonie/halite

```

### Session Handlers

This session manager allows different session handlers to be implemented to alter how the data is stored.

## Contributing

All contributions are welcome! If you wish to contribute.

## License

This project is licensed under the terms of the [MIT License](https://opensource.org/licenses/MIT).
