<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace KooserSession\Exception;

use Exception;

/**
 * If the session fingerprint is invalid.
 */
class InvalidFingerprintException extends Exception implements ExceptionInterface
{
}
