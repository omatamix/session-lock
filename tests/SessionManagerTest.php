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
        $handler = new \Kooser\Session\Handler\FileSessionHandler();
        \Kooser\Session\SessionManager::setSavePath(__DIR__ . '/sessions');
        \Kooser\Session\SessionManager::setSaveHandler($handler, \true);
        $result = \Kooser\Session\SessionManager::start($sessionConfig);
        $this->assertTrue($result);
        $id = \Kooser\Session\SessionManager::id();
        $this->assertTrue(\is_string($id));
        \Kooser\Session\SessionManager::gc();
        \Kooser\Session\SessionManager::regenerate();
        $newId = Kooser\Session\SessionManager::id();
        $this->assertTrue(($id != $newId));
        Kooser\Session\SessionManager::abort();
        Kooser\Session\SessionManager::reset();
        Kooser\Session\SessionManager::set('key1', 'Kooser6');
        Kooser\Session\SessionManager::set('key2', 'Kooser6Session');
        $data1 = Kooser\Session\SessionManager::get('key1', \null);
        $data2 = Kooser\Session\SessionManager::get('key2', \null);
        $this->assertTrue(($data1 != \null));
        $this->assertTrue(($data1 == 'Kooser6'));
        $this->assertTrue(($data2 != \null));
        $this->assertTrue(($data2 == 'Kooser6Session'));
        $data3 = \Kooser\Session\SessionManager::get('key3', \null);
        $data4 = \Kooser\Session\SessionManager::get('key4', \null);
        $this->assertTrue(($data3 === \null));
        $this->assertTrue(($data4 === \null));
        \Kooser\Session\SessionManager::delete('key1');
        \Kooser\Session\SessionManager::delete('key2');
        \Kooser\Session\SessionManager::set('key1', 'Kooser6');
        \Kooser\Session\SessionManager::set('key2', 'Kooser6Session');
        $result1 = \Kooser\Session\SessionManager::flash('key1', \null);
        $result2 = \Kooser\Session\SessionManager::flash('key2', \null);
        $this->assertTrue(($result1 != \null));
        $this->assertTrue(($result1 == 'Kooser6'));
        $this->assertTrue(($result2 != \null));
        $this->assertTrue(($result2 == 'Kooser6Session'));
        $result3 = \Kooser\Session\SessionManager::has('key1');
        $result4 = \Kooser\Session\SessionManager::has('key2');
        $this->assertTrue(!$result3);
        $this->assertTrue(!$result4);
        $result5 = \Kooser\Session\SessionManager::flash('key3', \null);
        $result6 = \Kooser\Session\SessionManager::flash('key4', \null);
        $this->assertTrue(($result5 === \null));
        $this->assertTrue(($result6 === \null));
        \Kooser\Session\SessionManager::commit();
        $result = \Kooser\Session\SessionManager::start($sessionConfig);
        $this->assertTrue($result);
        $result = \Kooser\Session\SessionManager::destroy(\false);
        $this->assertTrue($result);
    }
}
