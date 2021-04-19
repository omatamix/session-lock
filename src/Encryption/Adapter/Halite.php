<?php declare(strict_types=1);
/**
 * MIT License
 * 
 * Copyright (c) 2021 Nicholas English
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
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Omatamix\SessionLock\Encryption\Adapter;

use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\ConstantTime\Binary;
use ParagonIE\ConstantTime\Hex;
use ParagonIE\Halite\Alerts\InvalidMessage;
use ParagonIE\Halite\Halite;
use ParagonIE\Halite\Symmetric\Crypto;
use ParagonIE\Halite\Symmetric\EncryptionKey;
use ParagonIE\HiddenString\HiddenString;

/**
 * Use the Halite encryption adapter.
 */
class Halite implements EncryptionAdapter
{
    /** @var \ParagonIE\Halite\Symmetric\EncryptionKey The encryption key. */
    private $key;

    /**
     * Construct a new Halite encryption adapter.
     *
     * @param \ParagonIE\Halite\Symmetric\EncryptionKey The encryption key.
     *
     * @return void Returns nothing.
     */
    public function __construct(EncryptionKey $key)
    {
        $this->key = $key;
    }

    /**
     * Make sure the key is not visible from `var_dump`.
     * 
     * @return array Returns the debug info.
     */
    public function __debugInfo()
    {
        return [
            'key' => 'private'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt(string $value): string
    {
        return Crypto::encrypt(new HiddenString(json_encode($value)), $this->key);
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt(string $encrypted): string
    {
        $length = Binary::safeStrlen($encrypted);
        if ($length < 8)
            throw new InvalidMessage('Encrypted password hash is way too short.');
        if (hash_equals(Binary::safeSubstr($encrypted, 0, 5), Halite::VERSION_PREFIX)) {
            $decoded = Base64UrlSafe::decode($encrypted);
            return SymmetricConfig::getConfig($decoded, 'encrypt');
        }
        $value = Hex::decode(Binary::safeSubstr($encrypted, 0, 8));
        $config = SymmetricConfig::getConfig($value, 'encrypt');
        $decrypted = Crypto::decrypt($encrypted, $this->key, $config->ENCODING);
        return json_decode($decrypted->getString(), true);
    }
}
