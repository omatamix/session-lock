<?php
use PHPUnit\Framework\TestCase;

class SessionManagerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testSessionStart()
    {
        $sessionConfig = ['use_cookies' => \false];
        Kooser\Session\SessionManager::start($sessionConfig);
    }
}
