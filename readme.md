<p align="center">
  <img src="https://raw.githubusercontent.com/omatamix/session-lock/master/bin/session-lock-new.png" alt="SessionLock" />
</p>
<p align="center">
  <a href="https://github.com/omatamix/session-lock/actions/workflows/php.yml"><img src="https://github.com/omatamix/session-lock/actions/workflows/php.yml/badge.svg" alt="Build Status" /></a>
</p>

## Installation

The best way to install SessionLock is through composer. If you do not have composer installed you can install it directly from the [composer website](https://getcomposer.org/). After composer is successfully installed run the command line code below.

```sh
composer require omatamix/session-lock
```

## Usage
### Session Manager
The session manger comes with a simple api.
```php
// Construct a new session manager.
$session = new SessionManager();
```
Start or resume a session.
```php
$session->start();
```
Check to see if our session is running.
```php
if ($session->exists()) {
    echo "The session is running!";
}
```
The put method sets a session variable.
'''php
$session->put('hello', 'world');
```
This checks to see if this session variable is set.
```php
if ($session->has('hello')) {
    echo "The session variable exists.";
}
```
The delete method deletes a session variable.
```php
$session->delete('hello');
```
The flash method does the same as get but flash will delete the session variable after retrievale.
```php
echo "Hello " . $session->flash('hello') . "!";
```
Stop a session.
```php
$session->stop();
```
### Session Regeneration
It is very easy to update the current session id with a newly generated one.
```php
$session->regerate();
```
### Session Fingerprinting
This session manager comes with a built-in session fingerprinting which in a way improves session security. When you create a session handler instance, session fingerprinting is enabled by defualt, it binds your remote ip and user agent. If you do not want this enabled you can turn it off with.
```php
$session = new SessionManager([
    'fingerprinting' => false,
]);
```
You can also disable binding the remote ip or user agent like this.
```php
$session = new SessionManager([
    'bind_ip_address' => false, // If set to true we will bind the ip address else dont.
    'bind_user_agent' => false, // If set to true we will bind the user agent else dont.
]);
```
If you are using a trusted proxy you can set the remote ip with this.
```php
$session = new SessionManager([
    'use_ip' => 127.0.0.1,
]);
```
### Session Handlers
### Encryption Adapters
### Session Config

## Security Vulnerabilities

If you discover a security vulnerability within Session Lock, please send an e-mail to Nicholas via [omatamix@gmail.com](mailto:omatamix@gmail.com). All security vulnerabilities will be promptly addressed.

## Contributing

All contributions are welcome! If you wish to contribute.

## License

This project is licensed under the terms of the [MIT License](https://opensource.org/licenses/MIT).
