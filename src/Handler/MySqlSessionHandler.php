<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace Kooser\Session\Handler;

/**
 * Mysql session storage.
 *
 * @class MySqlSessionHandler.
 *
 * @see <https://www.php.net/manual/en/class.sessionhandlerinterface.php>.
 */
class MySqlSessionHandler implements \SessionHandlerInterface
{

    /** @var mixed $connection The database connection link. */
    private $connection = \null;

    /** @var string|null $databaseDns The database dns. */
    private $databaseDns = \null;

    /** @var string|null $username The database username. */
    private $username = \null;

    /** @var string|null $password The database password. */
    private $password = \null;

    /** @var string $tableName The sessions table name. */
    private $tableName = "sessions";

    /**
     * Construct the mysql session handler.
     *
     * @param string $databaseDns The database dns.
     * @param string $username    The database username.
     * @param string $password    The database password.
     *
     * @return void Returns nothing.
     */
    public function __construct(string $databaseDns, string $username = "", string $password = "", string $tableName = "sessions")
    {
        $this->databaseDns = $databaseDns;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Opens the mysql database connection.
     *
     * @param string $savePath    The location where the session files will be stored. <VOID>
     * @param string $sessionName The session namespace.                               <VOID>
     *
     * @return bool Returns TRUE after function execution.
     */
    public function open($savePath, $sessionName)
    {
        $this->connection = \ParagonIE\EasyDB\Factory::fromArray([
            $this->databaseDns,
            $this->username,
            $this->password,
        ]);
        $pdo = $this->connection->getPdo();
        $query = "CREATE TABLE IF NOT EXISTS `$this->tableName` (";
        $query .= "`id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,";
        $query .= "`sess_time` CHAR(10) NOT NULL,";
        $query .= "`sess_data` varchar(255) NOT NULL,";
        $query .= "`sess_id` varchar(255) NOT NULL";
        $query .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        $pdo->query($query);
        $pdo = \null;
        return (bool) \true;
    }

    /**
     * Close the database connection.
     *
     * @return bool Returns TRUE after function execution.
     */
    public function close()
    {
        $this->connection = \null;
        $this->databaseDns = \null;
        $this->username = \null;
        $this->password = \null;
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
        $sessInfo = $this->connection->row("SELECT * FROM $this->tableName WHERE sess_id = ?", $id);
        if (!empty($sessInfo)) {
            return $sessInfo['sess_data'];
        }
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
        $sessInfo = $this->connection->row("SELECT * FROM $this->tableName WHERE sess_id = ?", $id);
        if (!empty($sessInfo)) {
            $this->connection->update($this->tableName, [
                'sess_data' => $data,
                'sess_time' => \time(),
            ], [
                'sess_id' => $id
            ]);
        } else {
            $this->connection->insert($this->tableName, [
                'sess_id'   => $id,
                'sess_data' => $data,
                'sess_time' => \time(),
            ]);
        }
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
        $this->connection->delete($this->tableName, [
            'sess_id' => $id
        ]);
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
        $old = \time() - $maxlifetime;
        $pdo = $this->connection->getPdo();
        $pdo->prepare("DELETE FROM $this->tableName WHERE set_time < ?");
        $this->pdo->bind_param('s', $old);
        $this->pdo->execute();
        return (bool) \true;
    }
}
