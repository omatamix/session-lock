<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace Kooser\Session;

use SessionHandlerInterface;
use Kooser\Directory\ConnectionManager;
use Kooser\Directory\ConnectionManagerInterface;
use Kooser\Directory\SQLDatabaseHandler;
use Kooser\Directory\SQLDatabaseHandlerInterface;

/**
 * The database session handler.
 *
 * @class DatabaseSessionHandler.
 *
 * @codeCoverageIgnore
 */
class DatabaseSessionHandler implements SessionHandlerInterface
{

    /** @var StoreInterface|null $storeType The default store type. */
    private $storeType = \null;

    /** @var \Kooser\Directory\ConnectionManager $connectionManager The connection manager. */
    private $connectionManager;

    /** @var \Kooser\Directory\SQLDatabaseHandler $SQLManager The sql manager. */
    private $SQLManager;

    /** @var string $table The table to access. */
    private $table;

    /**
     * Construct the database based session handler.
     *
     * @param string                                            $table             The table to access.
     * @param \Kooser\Directory\ConnectionManagerInterface|null $connectionManager The connection manager.
     *
     * @return void Returns nothing.
     */
    public function __construct(string $table = 'sessions', ConnectionManagerInterface $connectionManager = \null)
    {
        $this->table = $table;
        if (!\is_null($connectionManager)) {
            $this->setConnectionManager($connectionManager);
        }
    }

    /**
     * Set the connection manager.
     *
     * @param \Kooser\Directory\ConnectionManagerInterface $connectionManager The connection manager.
     *
     * @return \SessionHandlerInterface Return this class.
     */
    public function setConnectionManager(ConnectionManagerInterface $connectionManager): SessionHandlerInterface
    {
        $this->connectionManager = $connectionManager;
        $this->SQLManager = new SQLDatabaseHandler($this->connectionManager);
        return $this;
    }

    /**
     * Set the store object.
     *
     * @param StoreInterface $storeType The default store type.
     *
     * @return void Returns nothing.
     */
    public function setStore(StoreInterface $storeType): void
    {
        $this->storeType = $storeType;
    }

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        $table = $this->table;
        $data = $this->SQLManager->select("SELECT * FROM $table WHERE session_id = :id", ['id' => $sessionId]);
        if (count($data) == 1) {
            /** @psalm-suppress PossiblyNullReference **/
            return $this->storeType->decrypt($data[0]['session_data']);
        }
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        $table = $this->table;
        $result = $this->SQLManager->select("SELECT * FROM $table WHERE session_id = :id", ['id' => $sessionId]);
        if (count($result) == 1) {
            /** @psalm-suppress PossiblyNullReference **/
            $this->SQLManager->update($table, ['session_data' => $this->storeType->encrypt($data)], "session_id = :id", ["id" => $sessionId]);
        } else {
            /** @psalm-suppress PossiblyNullReference **/
            $this->SQLManager->insert($table, ['session_id' => $sessionId, 'session_data' => $this->storeType->encrypt($data), 'session_time' => \time()]);
        }
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        $table = $this->table;
        $this->SQLManager->delete($table, "session_id = :id", ["id" => $sessionId]);
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        $table = $this->table;
        $this->SQLManager->delete($table, "session_time >= :time", ['time' => $lifetime]);
        return \true;
    }
}
