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
        $this->options = array(
            'id_field'     => '_id',
            'data_field'   => 'data',
            'time_field'   => 'time',
            'expiry_field' => 'expires_at',
        );
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
        $sessInfo = $this->collection->findOne(array(
            $this->options['id_field']     => $id,
            $this->options['expiry_field'] => array(
                '$gte' => new \MongoDate(),
            ),
        ));
        return (string) (\null === $sessInfo) ? '' : $sessInfo[$this->options['data_field']]->bin;
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
     */
    public function write($id, $data)
    {
        $sessionConfig = \Kooser\Session\SessionManager::getSessionConfig();
        $maxlifetime   = $sessionConfig['gc_maxlifetime'];
        $expiry = new \MongoDate(\time() + (int) $maxlifetime);
        $fields = array(
            $this->options['data_field']   => new \MongoBinData($data, \MongoBinData::BYTE_ARRAY),
            $this->options['time_field']   => new \MongoDate(),
            $this->options['expiry_field'] => $expiry,
        );
        $this->collection->update(array(
            $this->options['id_field'] => $id,
            ), array(
                '$set' => $fields,
            ), array(
                'upsert'   => \true,
                'multiple' => \false,
            )
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
        $this->collection->remove(array(
            $this->options['id_field'] => $id,
        ));
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
        $this->collection->remove(array(
            $this->options['expiry_field'] => array(
                '$lt' => new \MongoDate(),
            ),
        ));
        return (bool) \true;
    }
}
