<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace Kooser\Session;

use SessionHandler;

/**
 * The native session handler.
 *
 * @class NativeSessionHandler.
 *
 * @codeCoverageIgnore
 */
class NativeSessionHandler extends SessionHandler
{

    /** @var StoreInterface|null $storeType The default store type. */
    private $storeType = \null;

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
    public function read($id)
    {
        $data = parent::read($id);
        if (!$data) {
            return "";
        } else {
            /** @psalm-suppress PossiblyNullReference **/
            return $this->storeType->decrypt($data);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function write($id, $data)
    {
        /** @psalm-suppress PossiblyNullReference **/
        $data = $this->storeType->encrypt($data);
        return parent::write($id, $data);
    }
}
