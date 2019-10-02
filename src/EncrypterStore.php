<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace Kooser\Session;

use ParagonIE\ConstantTime\{
    Base64UrlSafe,
    Binary,
    Hex
};
use ParagonIE\Halite\Alerts\InvalidMessage;
use ParagonIE\Halite\Symmetric\{
    Config as SymmetricConfig,
    Crypto,
    EncryptionKey
};
use ParagonIE\HiddenString\HiddenString;

/**
 * Encrypter session store.
 *
 * @class EncrypterStore.
 */
final class EncrypterStore implements StoreInterface
{

    /** @var \ParagonIE\Halite\Symmetric\EncryptionKey $encryptionKey The encryption key. */
    private $encryptionKey;

    /** @var bool $shouldEncrypt Should we encrypt/decrypt data. */
    private $shouldEncrypt;

    /**
     * {@inheritdoc}
     */
    public function __construct(EncryptionKey $encryptionKey, bool $shouldEncrypt = \true)
    {
        $this->setEncryptionKey($encryptionKey);
        $this->setShouldEncrypt($shouldEncrypt);
    }

    /**
     * {@inheritdoc}
     */
    public function setEncryptionKey(EncryptionKey $encryptionKey): StoreInterface
    {
        $this->encryptionKey = $encryptionKey;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setShouldEncrypt(bool $shouldEncrypt = \true): StoreInterface
    {
        $this->shouldEncrypt = $shouldEncrypt;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($stored): bool
    {
        if ($this->shouldEncrypt) {
            $length = Binary::safeStrlen($stored);
            if ($length < 8) {
                throw new InvalidMessage('Encrypted hash string is way too short.');
            }
            if (\hash_equals(Binary::safeSubstr($stored, 0, 5), Halite::VERSION_PREFIX)) {
                $decoded = Base64UrlSafe::decode($stored);
                $config = SymmetricConfig::getConfig(
                    $decoded,
                    'encrypt'
                );
            } else {
                $v = Hex::decode(Binary::safeSubstr($stored, 0, 8));
                $config = SymmetricConfig::getConfig($v, 'encrypt');
            }
            $decrypted = Crypto::decrypt(
                $stored,
                $this->encryptionKey,
                $config->ENCODING
            );
            return \json_decode($decrypted->getString(), \true);
        }
        return $stored;
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt($store): bool
    {
        if ($this->shouldEncrypt) {
            return Crypto::encrypt(new HiddenString((string) \json_encode($store)), $this->encryptionKey);
        }
        return $store;
    }
}
