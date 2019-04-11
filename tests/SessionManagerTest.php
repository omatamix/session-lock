<?php
use PHPUnit\Framework\TestCase;

class SessionManagerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testSessions()
    {
        $sessionConfig = ['use_cookies' => \false];
        $handler = new Kooser\Session\Handler\FileSessionHandler();
        \session_save_path(\realpath(__DIR__ . '/session'));
        Kooser\Session\SessionManager::setSaveHandler($handler, \true);
        $result = Kooser\Session\SessionManager::start($sessionConfig);
        $this->assertTrue($result);
        $id = Kooser\Session\SessionManager::id();
        $this->assertTrue(\is_string($id));
        $id = \session_create_id();
        Kooser\Session\SessionManager::id($id);
        $idS = Kooser\Session\SessionManager::id();
        $this->assertTrue(($id == $idS));
    }
}
