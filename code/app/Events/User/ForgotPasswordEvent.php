<?php
declare(strict_types=1);

namespace App\Events\User;

use App\Models\User\PasswordToken;

/**
 * Class ForgotPasswordEvent
 * @package App\Events\User
 */
class ForgotPasswordEvent
{
    /**
     * @var PasswordToken
     */
    private $passwordToken;

    /**
     * ForgotPasswordEvent constructor.
     * @param PasswordToken $passwordToken
     */
    public function __construct(PasswordToken $passwordToken)
    {
        $this->passwordToken = $passwordToken;
    }

    /**
     * @return PasswordToken
     */
    public function getPasswordToken(): PasswordToken
    {
        return $this->passwordToken;
    }
}