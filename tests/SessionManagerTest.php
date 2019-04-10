<?php
use PHPUnit\Framework\TestCase;

class SessionManagerTest extends TestCase
{
    public function testPushAndPop()
    {
        $sessionConfig = ['use_cookies' => \false];
        Kooser\Session\SessionManager::start($sessionConfig);
    }
}
