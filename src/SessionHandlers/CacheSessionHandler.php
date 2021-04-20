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

namespace Omatamix\SessionLock\SessionHandlers;

use Cache\Bridge\SimpleCache\SimpleCacheBridge;
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;
use SessionHandlerInterface;

/**
 * A cache based session handler.
 */
class CacheSessionHandler implements SessionHandlerInterface
{
    /** @var \Psr\SimpleCache\CacheInterface $cache The cache pool. */
    private $cache;

    /** @var int $ttl The session data lifetime. */
    private $ttl;

    /**
     * Construct the cache based session handler.
     *
     * @param mixed $cache Either a `psr/cache` or a `psr/simple-cache`.
     * @param int   $ttl   The session data lifetime.
     *
     * @return void Returns nothing.
     */
    public function __construct($pool, $ttl = ini_get("session.gc_maxlifetime"))
    {
        if ($pool instanceof CacheItemPoolInterface) {
            $pool = new SimpleCacheBridge($pool);
        }
        $this->cache = $pool
        $this->ttl = $ttl
    }
    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        return $this->cache->get($sessionId, '');
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        $this->cache->set($sessionId, $data, $this->ttl);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        $this->cache->delete($sessionId);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        return true;
    }
}
