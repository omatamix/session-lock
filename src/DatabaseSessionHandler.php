<?php declare(strict_types=1);
/**
 * Session - Securely manage and preserve session data.
 *
 * @license MIT License. (https://github.com/Commander-Ant-Screwbin-Games/session/blob/master/license)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * https://github.com/Commander-Ant-Screwbin-Games/firecms/tree/master/src/Core
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package Commander-Ant-Screwbin-Games/session.
 */

namespace Session;

use SessionHandlerInterface;
use Directory\ConnectionManager;
use Directory\ConnectionManagerInterface;
use Directory\SQLDatabaseHandler;
use Directory\SQLDatabaseHandlerInterface;

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

    /** @var \Directory\ConnectionManagerInterface $connectionManager The connection manager. */
    private $connectionManager;

    /** @var \Directory\SQLDatabaseHandler $SQLManager The sql manager. */
    private $SQLManager;

    /** @var string $table The table to access. */
    private $table;

    /**
     * Construct the database based session handler.
     *
     * @param string                                       $table             The table to access.
     * @param \Directory\ConnectionManagerInterface $connectionManager The connection manager.
     *
     * @return void Returns nothing.
     */
    public function __construct(string $table = 'sessions', ConnectionManagerInterface $connectionManager)
    {
        $this->table = $table;
        $this->setConnectionManager($connectionManager);
    }

    /**
     * Set the connection manager.
     *
     * @param \Directory\ConnectionManagerInterface $connectionManager The connection manager.
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
     * Create the table.
     *
     * @return bool Returns true after execution.
     */
    public function install(): bool
    {
        $link = $this->connectionManager->getConnectionString();
        $link->query('CREATE TABLE IF NOT EXISTS ' . $this->table . ' (
            session_id varchar(32) NOT NULL,
            session_time int(10) unsigned DEFAULT NULL,
            session_data text,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        $link = \null;
        return \true;
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
