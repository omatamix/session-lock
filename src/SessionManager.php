<?php declare(strict_types=1);

namespace Omatamix\SessionLock;

use Omatamix\RequestLock\RequestHandler;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SessionHandlerInterface;

/**
 * Securely manage and preserve session data.
 */
final class SessionManager implements SessionManagerInterface
{
    /** @var array $options The session manager options. */
    private $options = [];

    /** @var bool $exceptions Should we utilize exceptions. */
    private $exceptions;

    /**
     * {@inheritdoc}
     */
    public function __construct(RequestHandler $requestHandler, array $options = [], bool $exceptions = \true)
    {
        $this->requestLock = $requestHandler;
        $this->setExceptions($exceptions);
        $this->setOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options = []): SessionManagerInterface
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setExceptions(bool $exceptions = \true): SessionManagerInterface
    {
        $this->exceptions = $exceptions;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSaveHandler(SessionHandlerInterface $sessionHandler): void
    {
        \session_set_save_handler($sessionHandler, \true);
    }

    /**
     * {@inheritdoc}
     */
    public function start(): bool
    {
        $session = \session_start($this->options['session_config']);
        if ($this->options['session_fingerprint']) {
            if ($this->has('session-lock.fingerprint')) {
                $this->stop();
                if ($this->exceptions) {
                    throw new Exception\InvalidFingerprintException('The fingerprint supplied is invalid.');
                }
                \trigger_error('The fingerprint supplied is invalid.', \E_USER_ERROR);
            } else {
                $this->put('session-lock.fingerprint');
            }
        }
        return $session;
    }

    /**
     * {@inheritdoc}
     */
    public function stop(): bool
    {
        $_SESSION = [];
        if ($this->options['session_config']['use_cookies']) {
            $params = \session_get_cookie_params();
            \setcookie(
                \session_name(),
                '',
                \time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        return \session_destroy();
    }

    /**
     * {@inheritdoc}
     */
    public static function exists(): bool
    {
        if (\php_sapi_name() !== 'cli') {
            return \session_status() === \PHP_SESSION_ACTIVE ? \true : \false;
        }
        return \false;
    }

    /**
     * {@inheritdoc}
     */
    public static function regenerate(bool $deleteOldSession = \true): bool
    {
        return (bool) \session_regenerate_id($deleteOldSession);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key, $defaultValue = \null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $defaultValue;
    }

    /**
     * {@inheritdoc}
     */
    public function flash(string $key, $defaultValue = \null)
    {
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $value;
        }
        return $defaultValue;
    }

    /**
     * {@inheritdoc}
     */
    public function put(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Get the fingerprint from the current accessing user.
     *
     * @return string Returns the session fingerprint.
     *
     * @see <https://github.com/OWASP/CheatSheetSeries/blob/master/cheatsheets/Session_Management_Cheat_Sheet.md#binding-the-session-id-to-other-user-properties>
     */
    private function getFingerprint(): string
    {
        $ua = $this->options['session_lock_to_ip_address'] ? $this->requestLock()->get('ipAddress') : 'null';
        $ua = $this->options['session_lock_to_user_agent'] ? $this->requestLock()->get('userAgent') : 'null';
        $rawFingerprint = \sprintf('%s|%s', $ip, $ua);
        return \hash_hmac($this->options['session_fingerprint_hash'], $rawFingerprint, $this->options['session_security_code']);
    }

    /**
     * Configure the hasher options.
     *
     * @param OptionsResolver The symfony options resolver.
     *
     * @return void Returns nothing.
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'session_fingerprint'        => \true,
            'session_fingerprint_hash'   => 'sha512',
            'session_lock_to_ip_address' => \true,
            'session_lock_to_user_agent' => \true,
            'session_config' => [
                'use_cookies'      => \true,
                'use_only_cookies' => \true,
                'cookie_httponly'  => \true,
                'cookie_samesite'  => 'Lax',
                'use_strict_mode'  => \true,
            ],
        ]);
        $resolver->setRequired('session_security_code');
        $resolver->setAllowedTypes('session_fingerprint', 'bool');
        $resolver->setAllowedTypes('session_fingerprint_hash', 'string');
        $resolver->setAllowedTypes('session_lock_to_ip_address', 'bool');
        $resolver->setAllowedTypes('session_lock_to_user_agent', 'bool');
        $resolver->setAllowedTypes('session_config', 'array');
        $resolver->setAllowedTypes('session_security_code', 'string');
    }
}
