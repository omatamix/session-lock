<?php declare(strict_types=1);

namespace Omatamix\SessionLock;

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;

/**
 * Encryption wrapper for encrypting sessions.
 */
class EncryptionWrapper implements StoreInterface
{
    /** @var \Defuse\Crypto\Key $encryptionKey The encryption key. */
    private $encryptionKey;

    /**
     * {@inheritdoc}
     */
    public function __construct(Key $encryptionKey)
    {
        $this->encryptionKey = $encryptionKey;
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt($data)
    {
        return Crypto::encrypt($data, $this->encryptionKey);
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($data)
    {
        return Crypto::decrypt($data, $this->encryptionKey);
    }
}
