<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */
namespace Kooser\Session;

use ParagonIE\Halite\Symmetric\EncryptionKey;

/**
 * Encrypter session store.
 *
 * @interface StoreInterface.
 */
interface StoreInterface
{

    /**
     * Construct the encrypter store.
     *
     * @param \ParagonIE\Halite\Symmetric\EncryptionKey $encryptionKey The encryption key.
     * @param bool                                      $shouldEncrypt Should we encrypt/decrypt data.
     *
     * @return void Returns nothing.
     */
    public function __construct(EncryptionKey $encryptionKey, bool $shouldEncrypt = \true);

    /**
     * Set the encryption key.
     *
     * @param \ParagonIE\Halite\Symmetric\EncryptionKey $encryptionKey The encryption key.
     *
     * @return \Kooser\Session\StoreInterface Returns the store method.
     */
    public function setEncryptionKey(EncryptionKey $encryptionKey): StoreInterface;

    /**
     * Set the should encrypt parameter.
     *
     * @param bool $shouldEncrypt Should we encrypt/decrypt data.
     *
     * @return \Kooser\Session\StoreInterface Returns the store method.
     */
    public function setShouldEncrypt(bool $shouldEncrypt = \true): StoreInterface;

    /**
     * Decrypt the data.
     *
     * @param mixed $stored The data to decrypt.
     *
     * @return mixed Returns the decrypted data.
     */
    public function decrypt($stored);

    /**
     * Encrypt the data.
     *
     * @param mixed $store The data to store.
     *
     * @return mixed Returns the encrypted data.
     */
    public function encrypt($store);
}
