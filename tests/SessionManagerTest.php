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
        Kooser\Session\SessionManager::setSaveHandler($handler, \true);
        \session_save_path(\realpath(\dirname(__DIR__ . '/session')));
        $id = \session_create_id();
        Kooser\Session\SessionManager::id($id);
        $idS = Kooser\Session\SessionManager::id();
        $this->assertTrue(($id == $idS));
        $result = Kooser\Session\SessionManager::start($sessionConfig);
        $this->assertTrue($result);
        $id = Kooser\Session\SessionManager::id();
        $this->assertTrue(\is_string($id));
        $result = Kooser\Session\SessionManager::start($sessionConfig);
        $this->assertTrue($result);
    }
}