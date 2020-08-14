<?php declare(strict_types=1);

namespace Omatamix\SessionLock;

use SessionHandlerInterface;

/**
 * A null session handler for testing.
 */
class NullSessionHandler implements SessionHandlerInterface
{
    /** @var array $options The session handler options. */
    private $options;

    /**
     * Construct a new session handler.
     *
     * @param array $options The session handler options.
     *
     * @return void Returns nothing.
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Set the session handler options.
     *
     * @param array $options The session handler options.
     *
     * @return \SessionHandlerInterface Returns the session handler.
     */
    public function setOptions(array $options = []): SessionHandlerInterface
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        return $this;
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
