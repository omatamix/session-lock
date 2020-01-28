<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class SessionManagerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testSessions()
    {
        $options = [
            'session_security_code' => 'testCode',
            'session_encrypt'       => \true,
        ];
        unlink(__DIR__ . '/../../Session/build/logs/clover.xml');
        rmdir(__DIR__ . '/../../Session');
        $sessionManager = new \Session\SessionManager($options, \false);
        $result = $sessionManager->start();
        $this->assertTrue($result);
        $sessionManager->put('key1', 'Kooser6');
        $sessionManager->put('key2', 'Kooser6Session');
        $data1 = $sessionManager->get('key1', \null);
        $data2 = $sessionManager->get('key2', \null);
        $this->assertTrue(($data1 != \null));
        $this->assertTrue(($data1 == 'Kooser6'));
        $this->assertTrue(($data2 != \null));
        $this->assertTrue(($data2 == 'Kooser6Session'));
        $result = $sessionManager->stop();
        $this->assertTrue($result);
        $sessionManager->setSaveHandler(new \Session\NullSessionHandler());
        $result = $sessionManager->start();
        $this->assertTrue($result);
        $sessionManager->regenerate();
        $sessionManager->put('key1', 'Kooser6');
        $sessionManager->put('key2', 'Kooser6Session');
        $data1 = $sessionManager->get('key1', \null);
        $data2 = $sessionManager->get('key2', \null);
        $this->assertTrue(($data1 != \null));
        $this->assertTrue(($data1 == 'Kooser6'));
        $this->assertTrue(($data2 != \null));
        $this->assertTrue(($data2 == 'Kooser6Session'));
        $data3 = $sessionManager->get('key3', \null);
        $data4 = $sessionManager->get('key4', \null);
        $this->assertTrue(($data3 === \null));
        $this->assertTrue(($data4 === \null));
        $sessionManager->delete('key1');
        $sessionManager->delete('key2');
        $sessionManager->put('key1', 'Kooser6');
        $sessionManager->put('key2', 'Kooser6Session');
        $result1 = $sessionManager->flash('key1', \null);
        $result2 = $sessionManager->flash('key2', \null);
        $this->assertTrue(($result1 != \null));
        $this->assertTrue(($result1 == 'Kooser6'));
        $this->assertTrue(($result2 != \null));
        $this->assertTrue(($result2 == 'Kooser6Session'));
        $result3 = $sessionManager->has('key1');
        $result4 = $sessionManager->has('key2');
        $this->assertTrue(!$result3);
        $this->assertTrue(!$result4);
        $result5 = $sessionManager->flash('key3', \null);
        $result6 = $sessionManager->flash('key4', \null);
        $this->assertTrue(($result5 === \null));
        $this->assertTrue(($result6 === \null));
        $result = $sessionManager->stop();
        $this->assertTrue($result);
    }
}
