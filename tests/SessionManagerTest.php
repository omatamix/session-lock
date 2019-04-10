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
        $this->assertTrue(Kooser\Session\SessionManager::exists());
    }
}
