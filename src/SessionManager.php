<?php
declare(strict_types=1);
/**
 * Kooser Session - Secure manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace Kooser\Session;

/**
 * Secure session management.
 *
 * You can start a session using the static method `Session::start(...)` which
 * is compatible to PHP's built-in `session_start()` function.
 *
 * @class SessionManager.
 */
final class SessionManager
{

    /** @var array $sessionConfig The session configuration. */
    private $sessionConfig = [];

    /**
     * Starts or resumes a session in a way compatible to PHP's built-in `session_start()` function
     *
     * @param array $sessionConfig The session configuration.
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public static function start(array $sessionConfig): bool {
        $this->sessionConfig = $sessionConfig;
        $result = \session_start($sessionConfig);
        return $result;
    }

    /**
     * Sets user-level session storage functions.
     *
     * @param \SessionHandlerInterface $handler          The session handler to use.
     * @param bool                     $registerShutdown Should we use the register shutdown function.
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public static function setSaveHandler(\SessionHandlerInterface $handler, bool $registerShutdown = \true): bool
    {
        return (bool) \session_set_save_handler($handler, $registerShutdown);
    }

    /**
     * Get or set the ID of the current session.
     *
     * @param string|null The session ID to create.
     *
     * @return string Returns the session ID or an empty string.
     */
    public static function id(string $id = \null): string
    {
        return (string) \session_id($id);
    }

    /**
     * Destroys all data registered to a session.
     *
     * @param bool $deleteCookie Should we delete the cookie header.
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public static function destroy(bool $deleteCookie = \true): bool
    {
        $_SESSION = array();
        if (isset($this->sessionConfig["use_cookies"]) && $this->sessionConfig["use_cookies"] == \true) {
            $params = \session_get_cookie_params();
            \setcookie(\session_name(), '', \time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        return (bool) \session_destroy();
    }

    /**
     * Perform session data garbage collection.
     *
     * @return void Returns nothing.
     */
    public static function gc(): void
    {
        \session_gc();
    }

    /**
     * Re-generates the session ID in a way compatible to PHP's built-in
     * `session_regenerate_id()` function.
     *
     * @param bool   $deleteOldSession    Whether to delete the old session or not.
     * @param string $sameSiteRestriction Indicates that the cookie should not
     *                                    be sent along with cross-site
     *                                    requests (either `Lax`, `Strict`, or
     *                                    en empty string.)
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public static function regenerate(bool $deleteOldSession = \true, string $sameSiteRestriction = "") {
        $result = \session_regenerate_id($deleteOldSession);
        return $result;
    }

    /**
     * Checks whether a value for the specified key exists in the session.
     *
     * @param string $key The key to check.
     *
     * @return bool Returns TRUE if the key was found and FALSE if not.
     */
    public static function has(string $key): bool
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
    public static function get(string $key, $defaultValue = null)
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
    public static function flash(string $key, $defaultValue = null)
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
    public static function set(string $key, $value): void
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
}
