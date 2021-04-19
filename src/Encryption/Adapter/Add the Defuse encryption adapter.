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

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

/**
 * Use the Defuse encryption adapter.
 */
class Defuse implements EncryptionAdapter
{
    /** @var \Defuse\Crypto\Key The encryption key. */
    private $key;

    /**
     * Construct a new Defuse encryption adapter.
     *
     * @param \Defuse\Crypto\Key The encryption key.
     *
     * @return void Returns nothing.
     */
    public function __construct(Key $key)
    {
        $this->key = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt(string $value): string
    {
        return Crypto::encrypt($value, $this->key);
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt(string $cipher): string
    {
        return Crypto::decrypt($cipher, $this->key);
    }
}
