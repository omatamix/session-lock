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
        $result = Kooser\Session\SessionManager::start($sessionConfig);
        $this->assertTrue($result);
        $id = Kooser\Session\SessionManager::id();
        $this->assertTrue(\is_string($id));
        $id = "rmcotLLrooxkEOj";
        Kooser\Session\SessionManager::id($id);
        $id = Kooser\Session\SessionManager::id();
        $this->assertTrue(($id == "rmcotLLrooxkEOj"));
    }
}
