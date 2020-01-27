<?php declare(strict_types=1);
/**
 * Session - Securely manage and preserve session data.
 *
 * @license MIT License. (https://github.com/Commander-Ant-Screwbin-Games/session/blob/master/license)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * https://github.com/Commander-Ant-Screwbin-Games/firecms/tree/master/src/Core
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package Commander-Ant-Screwbin-Games/session.
 */

namespace Session;

use ParagonIE\ConstantTime\{
    Base64UrlSafe,
    Binary,
    Hex
};
use ParagonIE\Halite\Halite;
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
    public function decrypt($stored)
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
    public function encrypt($store)
    {
        if ($this->shouldEncrypt) {
            return Crypto::encrypt(new HiddenString((string) \json_encode($store)), $this->encryptionKey);
        }
        return $store;
    }
}
