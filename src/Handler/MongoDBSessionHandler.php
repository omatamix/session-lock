<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace Kooser\Session\Handler;

/**
 * MongoDB session storage.
 *
 * @class MongoDBSessionHandler.
 *
 * @see <https://www.php.net/manual/en/class.sessionhandlerinterface.php>.
 */
class MongoDBSessionHandler implements \SessionHandlerInterface
{

    /** @var mixed $collection The mongodb collection. */
    private $collection = \null;

    /**
     * Construct the mongodb session handler.
     *
     * @param mixed $client The mongodb client.
     *
     * @return void Returns nothing.
     */
    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    /**
     * Opens the session storage.
     *
     * @param string $savePath    The location where the session files will be stored.
     * @param string $sessionName The session namespace.
     *
     * @return bool Returns TRUE after function execution.
     */
    public function open($savePath, $sessionName)
    {
        return (bool) \true;
    }

    /**
     * Close the session storage.
     *
     * @return bool Returns TRUE after function execution.
     */
    public function close()
    {
        return (bool) \true;
    }

    /**
     * Read session data.
     *
     * @param string $id The session id.
     *
     * @return string Returns the data.
     */
    public function read($id)
    {
        return (string) "";
    }

    /**
     * Write session data.
     *
     * @param string $id   The session id.
     * @param string $data The session data.
     *
     * @return bool Returns TRUE on success and FALSE on faliure.
     */
    public function write($id, $data)
    {
        return (bool) \true;
    }

    /**
     * Destroy the session.
     *
     * @param string $id The session id.
     *
     * @return bool Returns TRUE once the session has been destroyed.
     */
    public function destroy($id)
    {
        return (bool) \true;
    }

    /**
     * Deletes old sessions.
     *
     * @param int $maxlifetime The max amount of time the session can be stored.
     *
     * @return bool Returns TRUE once garbage collection has finished.
     */
    public function gc($maxlifetime)
    {
        return (bool) \true;
    }
}
