<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace Kooser\Session\Handler;

use Symfony\Component\Filesystem\Filesystem;

/**
 * File session storage.
 *
 * @class FileSessionHandler.
 *
 * @see <https://www.php.net/manual/en/class.sessionhandlerinterface.php>.
 */
class FileSessionHandler implements \SessionHandlerInterface
{

    /** @var string $savePath The location where the session files will be stored. */
    private $savePath = "";

    /** @var mixed The filesystem handler. */
    private $filesystem = \null;

    /**
     * Construct the file session handler.
     *
     * @return void Returns nothing.
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * Ensure the save path exists.
     *
     * @param string $savePath    The location where the session files will be stored.
     * @param string $sessionName The session namespace.
     *
     * @return bool Returns TRUE after function execution.
     */
    public function open(string $savePath, string $sessionName): bool
    {
        $this->savePath = $savePath;
        if (!is_dir($this->savePath)) {
            $this->filesystem->mkdir($this->savePath, 0777);
        }
        return (bool) \true;
    }

    /**
     * Do nothing on close operation.
     *
     * @return bool Always return true.
     */
    public function close(): bool
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
    public function read(string $id): string
    {
        $id = \basename(\realpath($id));
        return (string) @\file_get_contents("$this->savePath/sess_$id");
    }

    /**
     * Write session data to a file.
     *
     * @param string $id   The session id.
     * @param string $data The session data.
     *
     * @return bool Returns TRUE on success and FALSE on faliure.
     */
    public function write(string $id, $data): string
    {
        $id = \basename(\realpath($id));
        return (bool) (\file_put_contents("$this->savePath/sess_$id", $data) === \false) ? \false : \true;
    }

    /**
     * Destroy the session.
     *
     * @param string $id The session id.
     *
     * @return bool Returns TRUE once the session has been destroyed.
     */
    public function destroy($id): bool
    {
        $id = \basename(\realpath($id));
        $file = "$this->savePath/sess_$id";
        if ($this->filesystem->exists($file)) {
            $this->filesystem->remove($file);
        }
        return true;
    }

    /**
     * Deletes old sessions.
     *
     * @param int $maxlifetime The max amount of time the session can be stored.
     *
     * @return bool Returns TRUE once garbage collection has finished.
     */
    public function gc(int $maxlifetime): bool
    {
        foreach (\glob("$this->savePath/sess_*") as $file) {
            if (\filemtime($file) + $maxlifetime < \time() && $this->filesystem->exists($file)) {
                $this->filesystem->remove($file);
            }
        }
        return \true;
    }
}
