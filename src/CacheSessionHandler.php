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

use SessionHandlerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * The cache session handler.
 *
 * @class CacheSessionHandler.
 *
 * @codeCoverageIgnore
 */
class CacheSessionHandler implements SessionHandlerInterface
{

    /** @var StoreInterface|null $storeType The default store type. */
    private $storeType = \null;

    /** @var \Symfony\Component\Cache\Adapter\AdapterInterface $cache The cache adapter. */
    private $cache;

    /**
     * Construct the cache based session handler.
     *
     * @param \Symfony\Component\Cache\Adapter\AdapterInterface The cache adapter.
     *
     * @return void Returns nothing.
     */
    public function __construct(AdapterInterface $cacheAdapter)
    {
        $this->cache = $cacheAdapter;
    }

    /**
     * Set the store object.
     *
     * @param StoreInterface $storeType The default store type.
     *
     * @return void Returns nothing.
     */
    public function setStore(StoreInterface $storeType): void
    {
        $this->storeType = $storeType;
    }

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        $sessionImport = $this->cache->getItem($sessionId);
        if (!$sessionImport->isHit()) {
            return '';
        }
        /** @psalm-suppress PossiblyNullReference **/
        return $this->storeType->decrypt($sessionImport->get());
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        $sessionImport = $this->cache->getItem($sessionId);
        /** @psalm-suppress PossiblyNullReference **/
        $sessionImport->set($this->storeType->encrypt($data));
        $this->cache->save($sessionImport);
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        $this->cache->deleteItem($sessionId);
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        return \true;
    }
}

