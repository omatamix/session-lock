<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace Kooser\Session;

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
        return $sessionImport->get();
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        $sessionImport = $this->cache->getItem($sessionId);
        $sessionImport->set($data);
        $this->cache->save($sessionImport);
        return \true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        $this->cache->delete($sessionId);
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

