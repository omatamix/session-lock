<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

// enable error reporting
\error_reporting(\E_ALL);
\ini_set('display_errors', 'stdout');

\header('Content-type: text/plain; charset=utf-8');

require __DIR__.'/../vendor/autoload.php';

// start output buffering
\ob_start();

(isset($_SESSION) === \false) or \fail(__LINE__);
(\Kooser\Session::id() === '') or \fail(__LINE__);
\Kooser\Session::start();
(isset($_SESSION) === \true) or \fail(__LINE__);
(\Kooser\Session::id() !== '') or \fail(__LINE__);
$oldSessionId = \Delight\Cookie\Session::id();
\Kooser\Session::regenerate();
(\Kooser\Session::id() !== $oldSessionId) or \fail(__LINE__);
(\Kooser\Session::id() !== \null) or \fail(__LINE__);
\session_unset();
(isset($_SESSION['key1']) === \false) or \fail(__LINE__);
(\Kooser\Session::has('key1') === \false) or \fail(__LINE__);
(\Kooser\Session::get('key1') === \null) or \fail(__LINE__);
(\Kooser\Session::get('key1', 5) === 5) or \fail(__LINE__);
(\Kooser\Session::get('key1', 'monkey') === 'monkey') or \fail(__LINE__);
\Kooser\Session::set('key1', 'value1');
(isset($_SESSION['key1']) === true) or \fail(__LINE__);
(\Kooser\Session::has('key1') === true) or \fail(__LINE__);
(\Kooser\Session::get('key1') === 'value1') or \fail(__LINE__);
(\Kooser\Session::get('key1', 5) === 'value1') or \fail(__LINE__);
(\Kooser\Session::get('key1', 'monkey') === 'value1') or \fail(__LINE__);
(\Kooser\Session::flash('key1') === 'value1') or \fail(__LINE__);
(\Kooser\Session::flash('key1') === null) or \fail(__LINE__);
(\Kooser\Session::flash('key1', 'value2') === 'value2') or \fail(__LINE__);
(isset($_SESSION['key1']) === false) or \fail(__LINE__);
(\Kooser\Session::has('key1') === false) or \fail(__LINE__);
\Kooser\Session::set('key2', 'value3');
(isset($_SESSION['key2']) === true) or \fail(__LINE__);
(\Kooser\Session::has('key2') === true) or \fail(__LINE__);
(\Kooser\Session::get('key2', 'value4') === 'value3') or \fail(__LINE__);
\Kooser\Session::delete('key2');
(\Kooser\Session::get('key2', 'value4') === 'value4') or \fail(__LINE__);
(\Kooser\Session::get('key2') === null) or \fail(__LINE__);
(\Kooser\Session::has('key2') === false) or \fail(__LINE__);
\Kooser\Session::destroy();

echo 'ALL TESTS PASSED' . "\n";

function fail($lineNumber) {
  exit('Error in line ' . $lineNumber);
}

