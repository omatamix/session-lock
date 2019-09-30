<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace Kooser\Session\Exception;

use Exception;

/**
 * If the ip address could not be retrieved.
 */
class IPAddressNotFoundException extends Exception implements ExceptionInterface
{
}
