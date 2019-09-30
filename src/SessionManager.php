<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace Kooser\Session;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Secure session management.
 *
 * You can start a session using the static method `SessionManager::start(...)` which
 * is compatible to PHP's built-in `session_start()` function.
 *
 * @class SessionManager.
 */
final class SessionManager implements SessionManagerInterface
{

    /** @var array $options The session manager options. */
    private $options;

    /**
     * Construct a new session manager.
     *
     * @param array $options    The session manager options.
     * @param bool  $exceptions Should we utilize exceptions.
     *
     * @return void Returns nothing.
     */
    public function __construct(array $options = [], bool $exceptions = \true)
    {
        $this->setExceptions($exceptions);
        $this->setOptions($options);
    }

    /**
     * Set the session manager options.
     *
     * @param array $options The session manager options.
     *
     * @return \Kooser\Session\SessionManagerInterface Returns the session manager.
     */
    public function setOptions(array $options = []): SessionManagerInterface
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        return $this;
    }

    /**
     * Set the exceptions param.
     *
     * @param bool $exceptions Should we utilize exceptions.
     *
     * @return \Kooser\Session\SessionManagerInterface Returns the session manager.
     */
    public function setExceptions(bool $exceptions = \true): SessionManagerInterface
    {
        $this->exceptions = $exceptions;
        return $this;
    }

    /**
     * Starts or resumes a session.
     *
     * @throws Exception\InvalidFingerprintException If the fingerprint from the user does not match the one
     *                                               binded to the session.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function start(): bool
    {
        $result = @\session_start($this->options['session_config']);
        if ($this->options['session_fingerprint']) {
            if (!$fingerprint = $this->get('kooser.session.fingerprint')) {
                if (!\hash_equals($fingerprint, $this->getFingerprint())) {
                    $this->stop();
                    if ($this->exceptions) {
                        throw new Exception\InvalidFingerprintException('The fingerprint supplied is invalid.');
                    }
                }
            } else {
                $this->put('kooser.session.fingerprint', $this->getFingerprint())
            }
        }
        return (bool) $result;
    }

    /**
     * Stop a session and destroys all data.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function stop(): bool
    {
        $_SESSION = array();
        if ($this->options['session_config']["use_cookies"]) {
            $params = \session_get_cookie_params();
            \setcookie(
                \session_name(),
                '',
                \time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        return (bool) @\session_destroy();
    }

    /**
     * Check to see if a session already exists.
     *
     * @return bool Returns true if one exists and false if not.
     */
    public static function exists(): bool
    {
        if (\php_sapi_name() !== 'cli') {
            return (bool) (\session_status() === \PHP_SESSION_ACTIVE) ? \true : \false;
        }
        return (bool) \false;
    }

    /**
     * Regenerates the session.
     *
     * @param bool $deleteOldSession Whether to delete the old session or not.
     *
     * @return bool Returns true on success or false on failure.
     */
    public static function regenerate(bool $deleteOldSession = \true): bool
    {
        return (bool) \session_regenerate_id($deleteOldSession);
    }

    /**
     * Checks whether a value for the specified key exists in the session.
     *
     * @param string $key The key to check.
     *
     * @return bool Returns true if the key was found and false if not.
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Returns the requested value from the session or, if not found, the
     * specified default value.
     *
     * @param string $key          The key to retrieve the value for.
     * @param mixed  $defaultValue The default value to return if the
     *                             requested value cannot be found.
     *
     * @return mixed Returns the requested value or the default
     *               value.
     */
    public static function get(string $key, $defaultValue = \null)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return $defaultValue;
    }

    /**
     * Returns the requested value and removes it from the session.
     *
     * This is identical to calling `get` first and then `remove` for the same
     * key.
     *
     * @param string $key         The key to retrieve and remove the value for.
     * @param mixed $defaultValue The default value to return if the requested
     *                            value cannot be found.
     *
     * @return mixed The requested value or the default value.
     */
    public static function flash(string $key, $defaultValue = \null)
    {
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $value;
        }
        return $defaultValue;
    }

    /**
     * Sets the value for the specified key to the given value.
     *
     * Any data that already exists for the specified key will be overwritten.
     *
     * @param string $key   The key to set the value for.
     * @param mixed  $value The value to set.
     *
     * @return void Returns nothing.
     */
    public static function put(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Removes the value for the specified key from the session.
     *
     * @param string $key The key to remove the value for.
     *
     * @return void Returns nothing.
     */
    public static function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Get the fingerprint from the current accessing user.
     *
     * @throws Exception\IPAddressNotFoundException If the ip address could not be retrieved.
     * @throws Exception\UserAgentNotFoundException If the user agent could not be retrieved.
     *
     * @return string Returns the unique fingerprint.
     */
    private function getFingerprint(): string
    {
        if ($this->options['session_lock_to_ip_address']) {
            $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : \null;
            if (!$ip && $this->exceptions) {
                throw new Exception\IPAddressNotFoundException('The ip address could not be retrieved.');
            }
        }
        if ($this->options['session_lock_to_user_agent']) {
            $ua = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : \null;
            if (!$ua && $this->exceptions) {
                throw new Exception\UserAgentNotFoundException('The user agent could not be retrieved.');
            }
        }
        $raw_fingerprint = \sprintf(
            '%s|%s',
            $ip,
            $ua
        );
        return (string) \hash_hmac($this->options['session_fingerprint_hash'], $raw_fingerprint, $this->options['session_security_code']);
    }

    /**
     * Configure the hasher options.
     *
     * @param OptionsResolver The symfony options resolver.
     *
     * @return void Returns nothing.
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'session_fingerprint'        => \true,
            'session_fingerprint_hash'   => 'sha512',
            'session_lock_to_ip_address' => \true,
            'session_lock_to_user_agent' => \true,
            'session_config' => [
                'use_cookies'      => \true,
                'use_only_cookies' => \true,
                'cookie_httponly'  => \true,
                'cookie_samesite'  => 'Lax',
                'use_strict_mode'  => \true,
            ],
        ]);
        $resolver->setRequired('session_security_code');
        $resolver->setAllowedTypes('session_fingerprint', 'bool');
        $resolver->setAllowedTypes('session_fingerprint_hash', 'string');
        $resolver->setAllowedTypes('session_lock_to_ip_address', 'bool');
        $resolver->setAllowedTypes('session_lock_to_user_agent', 'bool');
        $resolver->setAllowedTypes('session_config', 'array');
        $resolver->setAllowedTypes('session_security_code', 'string');
    }
}
