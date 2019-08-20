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

    /** @var \MongoCollection|null $collection The mongodb collection. */
    private $collection = \null;

    /** @var array $options The collection options. */
    private $options = [];

    /**
     * Construct the mongodb session handler.
     *
     * @param \MongoCollection $collection The mongodb client.
     *
     * @return void Returns nothing.
     *
     * @psalm-suppress PossiblyNullReference
     */
    public function __construct(\MongoCollection $collection)
    {
        $this->collection = $collection;
        $this->options = [
            'id_field' => '_id',
            'data_field' => 'data',
            'time_field' => 'time',
            'expiry_field' => 'expires_at',
        ];
    }

    /**
     * Opens the session storage.
     *
     * @param string $savePath    The location where the session files will be stored. <VOID>
     * @param string $sessionName The session namespace.                               <VOID>
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
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress DocblockTypeContradiction
     * @psalm-suppress PossiblyNullArrayAccess
     */
    public function read($id)
    {
        $dbData = $this->collection->findOne([
            $this->options['id_field'] => $sessionId,
            $this->options['expiry_field'] => ['$gte' => new \MongoDB\BSON\UTCDateTime()],
        ]);
        if (null === $dbData) {
            return "";
        }
        return $dbData[$this->options['data_field']]->getData();
    }

    /**
     * Write session data.
     *
     * @param string $id   The session id.
     * @param string $data The session data.
     *
     * @return bool Returns TRUE on success and FALSE on faliure.
     *
     * @psalm-suppress PossiblyNullReference
     *
     * @codeCoverageIgnore
     */
    public function write($id, $data)
    {
        $sessionConfig = \Kooser\Session\SessionManager::getSessionConfig();
        $expiry = new \MongoDB\BSON\UTCDateTime((time() + (int) $sessionConfig['gc_maxlifetime']) * 1000);
        $fields = [
            $this->options['time_field'] => new \MongoDB\BSON\UTCDateTime(),
            $this->options['expiry_field'] => $expiry,
            $this->options['data_field'] => new \MongoDB\BSON\Binary($data, \MongoDB\BSON\Binary::TYPE_OLD_BINARY),
        ];
        $this->collection->updateOne(
            [$this->options['id_field'] => $id],
            ['$set' => $fields],
            ['upsert' => \true]
        );
        return (bool) \true;
    }

    /**
     * Destroy the session.
     *
     * @param string $id The session id.
     *
     * @return bool Returns TRUE once the session has been destroyed.
     *
     * @psalm-suppress PossiblyNullReference
     */
    public function destroy($id)
    {
        $this->collection->deleteOne([
            $this->options['id_field'] => $id,
        ]);
        return (bool) \true;
    }

    /**
     * Deletes old sessions.
     *
     * @param int $maxlifetime The max amount of time the session can be stored.
     *
     * @return bool Returns TRUE once garbage collection has finished.
     *
     * @psalm-suppress PossiblyNullReference
     */
    public function gc($maxlifetime)
    {
        $this->collection->deleteMany([
            $this->options['expiry_field'] => ['$lt' => new \MongoDB\BSON\UTCDateTime()],
        ]);
        return (bool) \true;
    }
}
