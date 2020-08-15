<?php declare(strict_types=1);

namespace Omatamix\SessionLock;

use SessionHandlerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * The cache session handler.
 */
class CacheSessionHandler implements SessionHandlerInterface
{
    /** @var \Symfony\Component\Cache\Adapter\AdapterInterface $cache The cache adapter. */
    private $cache;

    /** @var \Omatamix\SessionLock\EncryptionWrapper $encryptionWrapper The encryption wrapper. */
    private $encryptionWrapper;

    /**
     * Construct the cache based session handler.
     *
     * @param \Symfony\Component\Cache\Adapter\AdapterInterface $cacheAdapter      The cache adapter.
     * @param \Omatamix\SessionLock\EncryptionWrapper           $encryptionWrapper The encryption wrapper.
     *
     * @return void Returns nothing.
     */
    public function __construct(AdapterInterface $cacheAdapter, EncryptionWrapper $encryptionWrapper = \null)
    {
        $this->cache = $cacheAdapter;
        $this->encrpytionWrapper = $encryptionWrapper;
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
        return !\is_null($this->encryptionWrapper) ? $this->encryptionWrapper->decrypt($sessionImport->get()) : $sessionImport->get();
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        $sessionImport = $this->cache->getItem($sessionId);
        if (!\is_null($this->encryptionWrapper)) {
            $sessionImport->set($this->encryptionWrapper->encrypt($data));
        } else {
            $sessionImport->set($data);
        }
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

