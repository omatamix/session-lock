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
     * @return \Session\StoreInterface Returns the store method.
     */
    public function setEncryptionKey(EncryptionKey $encryptionKey): StoreInterface;

    /**
     * Set the should encrypt parameter.
     *
     * @param bool $shouldEncrypt Should we encrypt/decrypt data.
     *
     * @return \Session\StoreInterface Returns the store method.
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
