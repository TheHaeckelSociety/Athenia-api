<?php
declare(strict_types=1);

namespace App\Providers;

use App\Validators\ForgotPassword\TokenIsNotExpiredValidator;
use App\Validators\ForgotPassword\UserOwnsTokenValidator;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppValidatorProvider
 * @package App\Providers
 */
class AppValidatorProvider extends ServiceProvider
{
    /**
     * Registers all application validators
     */
    public function boot()
    {
        /** @var Factory $validator */
        $validator = $this->app->make(Factory::class);

        $validator->extend('token_is_not_expired', TokenIsNotExpiredValidator::class);
        $validator->extend('user_owns_token', UserOwnsTokenValidator::class);
    }
}