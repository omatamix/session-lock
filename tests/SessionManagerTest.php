<?php
use PHPUnit\Framework\TestCase;

class SessionManagerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testSessions()
    {
        $sessionConfig = ['use_cookies' => \false, 'runningTests' => \true];
        $result = Kooser\Session\SessionManager::start($sessionConfig);
        $this->assertTrue($result);
    }
}
