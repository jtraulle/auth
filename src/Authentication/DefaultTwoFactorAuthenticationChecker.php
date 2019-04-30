<?php
declare(strict_types=1);
/**
 * Copyright 2010 - 2019, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2019, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace CakeDC\Auth\Authentication;

use Cake\Core\Configure;

/**
 * Default class to check if two factor authentication is enabled and required
 *
 * @package CakeDC\Auth\Authentication
 */
class DefaultTwoFactorAuthenticationChecker implements TwoFactorAuthenticationCheckerInterface
{
    protected $enabledKey = 'OneTimePasswordAuthenticator.login';

    /**
     * DefaultTwoFactorAuthenticationChecker constructor.
     *
     * @param string $enableKey configuration key to check if enabled
     */
    public function __construct($enableKey = null)
    {
        if ($enableKey !== null) {
            $this->enabledKey = $enableKey;
        }
    }
    /**
     * Check if two factor authentication is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return Configure::read($this->enabledKey) !== false;
    }

    /**
     * Check if two factor authentication is required for a user
     *
     * @param array $user user data
     *
     * @return bool
     */
    public function isRequired(?array $user = null)
    {
        return !empty($user) && $this->isEnabled();
    }
}
