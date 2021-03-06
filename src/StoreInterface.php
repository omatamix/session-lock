<?php declare(strict_types=1);

namespace Omatamix\SessionLock;

use Defuse\Crypto\Key;

/**
 * Encrypter session store.
 */
interface StoreInterface
{
    /**
     * Construct the encrypter store.
     *
     * @param mixed $encryptionKey The encryption key.
     *
     * @return void Returns nothing.
     */
    public function __construct(Key $encryptionKey);

    /**
     * Decrypt the data.
     *
     * @param mixed $data The data to decrypt.
     *
     * @return mixed Returns the decrypted data.
     */
    public function decrypt($data);

    /**
     * Encrypt the data.
     *
     * @param mixed $data The data to store.
     *
     * @return mixed Returns the encrypted data.
     */
    public function encrypt($data);
}
