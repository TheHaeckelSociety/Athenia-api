<?php
/**
 * service for generating tokens
 */
declare(strict_types=1);

namespace App\Services;

use App\Contracts\Services\TokenGenerationServiceContract;

/**
 * Class TokenGenerationService
 * @package App\Services
 */
class TokenGenerationService implements TokenGenerationServiceContract
{
    /**
     * Generates a token
     *
     * @param int $length
     * @return string
     */
    public function generateToken($length = 40): string
    {
        return str_random($length);
    }
}