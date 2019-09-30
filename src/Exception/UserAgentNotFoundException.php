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
 * If the user agent could not be retrieved.
 */
class UserAgentNotFoundException extends Exception implements ExceptionInterface
{
}
