<?php declare(strict_types=1);

namespace Omatamix\SessionLock;

use Cake\Database\Connection;
use SessionHandlerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The database session handler.
 */
class DatabaseSessionHandler implements SessionHandlerInterface
{
    /** @var \Cake\Database\Connection $connection The database connection. */
    private $connection;

    /** @var \Omatamix\SessionLock\EncryptionWrapper $encryptionWrapper The encryption wrapper. */
    private $encryptionWrapper;

    /**
     * Construct the cache based session handler.
     *
     * @param \Cake\Database\Connection               $connection        The database connection.
     * @param \Omatamix\SessionLock\EncryptionWrapper $encryptionWrapper The encryption wrapper.
     *
     * @return void Returns nothing.
     */
    public function __construct(Connection $connection, EncryptionWrapper $encryptionWrapper = \null)
    {
        $this->connection = $connection;
        $this->encrpytionWrapper = $encryptionWrapper;
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
        $statement = $this->connection->execute('SELECT * FROM sessions WHERE session_id = :id', ['id' => $sessionId]);
        $results = $statement->fetch('assoc');
        if (\count($results) == 1) {
            return !\is_null($this->encryptionWrapper) ? $this->encryptionWrapper->decrypt($results[0]) : $results[0];
        }
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        $statement = $this->connection->execute('SELECT * FROM sessions WHERE session_id = :id', ['id' => $sessionId]);
        $results = $statement->fetch('assoc');
        if (!\is_null($this->encryptionWrapper)) {
            $data = $this->encryptionWrapper->encrypt($data);
        }
        if (\count($result) == 1) {
            $this->connection->update('sessions', ['payload' => $data], ['id' => $sessionId]);
        } else {
            $this->connection->insert('sessions', ['session_id' => $sessionId, 'payload' => $data]);
        }
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        $this->connection->delete('sessions', ['session_id' => $sessionId]);
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        $this->connection->delete('articles', ['expires >=' => $lifetime], ['expires' => 'integer']);
        return \true;
    }
}
