<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace Kooser\Session;

/**
 * Session fingerprint management.
 *
 * @class SessionFingerprintManager.
 */
class SessionFingerprintManager
{

    /** @var bool $ipValidate Should we validate fingerprint with the ip address. */
    private $ipValidate = \true;

    /** @var bool $uaValidate Should we validate fingerprint with the user agent. */
    private $uaValidate = \true;

    /** @var string $hashAlgo The hashing algo for fingerprint generation. */
    private $hashAlgo = 'sha512';

    /**
     * Construct a new fingerprint manager.
     *
     * @param array $validators The fingerprint validators.
     *
     * @return void Returns nothing.
     */
    public function __construct(array $validators = [], string $hashAlgo = 'sha512')
    {
        if (isset($validators['ipValidate'])) {
            $this->ipValidate = $validators['ipValidate'];
        }
        if (isset($validators['uaValidate'])) {
            $this->uaValidate = $validators['uaValidate'];
        }
        $this->hashAlgo = $hashAlgo;
    }

    /**
     * Generate a fingerprint.
     *
     * @return string The fingerprint.
     */
    public function generate(): string
    {
        $ip = "null";
        if ($this->ipValidate) {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }
        $ua = "null";
        if ($this->uaValidate) {
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $ua = $_SERVER['HTTP_USER_AGENT'];
            }
        }
        $fp = \sprintf(
            "%s|%s",
            $ip,
            $ua
        );
        return (string) \hash($this->hashAlgo, $fp);
    }

    /**
     * Clear the class.
     *
     * @return void Returns nothing.
     */
    public function clear(): void
    {
        $this->ipValidate = \true;
        $this->uaValidate = \true;
    }
}
