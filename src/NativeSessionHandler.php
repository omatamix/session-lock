<?php declare(strict_types=1);

namespace Omatamix\SessionLock;

use SessionHandler;

class NativeSessionHandler extends SessionHandler
{
    /** @var \Omatamix\SessionLock\EncryptionWrapper $encryptionWrapper The encryption wrapper. */
    private $encryptionWrapper;

    /**
     * Construct the native session handler.
     *
     * @param \Omatamix\SessionLock\EncryptionWrapper $encryptionWrapper The encryption wrapper.
     *
     * @return void Returns nothing.
     */
    public function __construct(EncryptionWrapper $encryptionWrapper = \null)
    {
        $this->encrpytionWrapper = $encryptionWrapper;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        $data = parent::read($sessionId);
        if (!$data) {
            return '';
        } else {
            if (!\is_null($this->encryptionWrapper)) {
                $data = $this->encryptionWrapper->encrypt($data);
            }
            return $data;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        if (!\is_null($this->encryptionWrapper)) {
            $data = $this->encryptionWrapper->encrypt($data);
        }
        return parent::write($sessionId, $data);
    }
}
