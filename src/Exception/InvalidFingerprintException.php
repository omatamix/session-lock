<?php declare(strict_types=1);

namespace Omatamix\SessionLock\Exception;

use Exception;

/**
 * If the session fingerprint is invalid.
 */
class InvalidFingerprintException extends Exception implements ExceptionInterface
{
}
