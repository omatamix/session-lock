<?php declare(strict_types=1);

namespace Omatamix\SessionLock;

use SessionHandlerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A null session handler for testing.
 */
class NullSessionHandler implements SessionHandlerInterface
{
    /**
     * Construct a new session handler.
     *
     * @param array $options The session handler options.
     *
     * @return void Returns nothing.
     */
    public function __construct(array $options = [])
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        return \true;
    }
}
