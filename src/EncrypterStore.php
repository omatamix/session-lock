  
<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace Kooser\Session;

/**
 * Encrypter session store.
 *
 * @class EncrypterStore.
 */
final class EncrypterStore implements StoreInterface
{

    /** @var mixed $encryptionKey The encryption key. */
    private $encryptionKey;

    /** @var bool $shouldEncrypt Should we encrypt/decrypt data. */
    private $shouldEncrypt;

    /**
     * {@inheritdoc}
     */
    public function __construct($encryptionKey, bool $shouldEncrypt = \true)
    {
        $this->setEncryptionKey($encryptionKey);
        $this->setShouldEncrypt($shouldEncrypt);
    }

    /**
     * {@inheritdoc}
     */
    public function setEncryptionKey($encryptionKey): StoreInterface
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
        $length = Binary::safeStrlen($stored);
        if ($length < 8) {
            throw new InvalidMessage('Encrypted hash string is way too short.');
        }
        if (\hash_equals(Binary::safeSubstr($stored, 0, 5), Halite::VERSION_PREFIX)) {
            $decoded = Base64UrlSafe::decode($stored);
            return SymmetricConfig::getConfig(
                $decoded,
                'encrypt'
            );
        }
        $v = Hex::decode(Binary::safeSubstr($stored, 0, 8));
        $config SymmetricConfig::getConfig($v, 'encrypt');
        $decrypted = Crypto::decrypt(
            $stored,
            $this->encryptionKey,
            $config->ENCODING
        );
        return \json_decode($decrypted->getString(), \true);
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt($store): bool
    {
        return Crypto::encrypt(new HiddenString((string) \json_encode($store)), $this->encryptionKey);
    }
}
