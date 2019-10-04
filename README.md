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

require_once __DIR__ . '/vendor/autoload.php';

$secuirtyCode = 'Your Security Code';

$options = [
    'session_security_code' => $securityCode,
];

$sessionManager = new SessionManager($options);

$sessionManager->start();

// Do stuff

// ...

// I am ready to destroy session.
$sessionManager->stop();

// ...

```

### Session Status

Here is how you see if the session is active or not.

```php
<?php

// ...

// See if this session manager exists.
$exists = $sessionManager->exists();

// ...
```

### Session Variables

You might need to set session variables between requests.

```php
<?php

// ...

// Set a session variable.
$sessionManager->put('kooser', 'session');

// Get a session variable.
$value = $sessionManager->get('kooser');

// Flash a session variable (deletes the variable after retrievable).
$value = $sessionManager->flash('kooser');

// See if this session variable exists.
$doWeHave = $sessionManager->has('kooser');

// Set a session variable.
$sessionManager->put('kooser', 'session');

// See if this session variable exists.
$doWeHave = $sessionManager->has('kooser');

// Delete the session variable.
$sessionManager->delete('kooser');

// ...

```

### Session Fingerprinting

This session manager includes automatic session fingerprinting.

```php
<?php

// ...

// Session fingerprint options.
$options = [
    'session_fingerprint' => \true,
    'session_fingerprint_hash' => 'sha512',
    'session_lock_to_ip_address' => \true,
    'session_lock_to_user_agent' => \true,
];

// ...

```

### Session Encryption

This session manager includes a built-in encryption system from the `paragonie/halite` package.

```php

// ...

// Session encryption options.
$options = [
    'session_encrypt' => \true,
    'session_encrypt_key' => KeyFactory::generateEncryptionKey(), // Don't generate a new encryption key on every request.
];

// ...

```

Confused on how to generate an encryption key? [Click here](https://github.com/paragonie/halite)

### Session Handlers

This session manager allows different session handlers to be implemented to alter how the data is stored.

```php

// ...

use Kooser\Session\NativeSessionHandler();

// ...

$sessionManager->setSaveHandler(new NativeSessionHandler());

// ...

```

The native session handler is invoked automatically.

## Contributing

All contributions are welcome! If you wish to contribute.

## License

This project is licensed under the terms of the [MIT License](https://opensource.org/licenses/MIT).
