<?php
/**
 * Validate the user can signup
 */
declare(strict_types=1);

namespace App\Http\V1\Requests\Authentication;

use App\Http\V1\Requests\BaseUnauthenticatedRequest;
use App\Models\User\User;

/**
 * Class SignUpRequest
 * @package App\Http\V1\Requests\Authentication
 */
class SignUpRequest extends BaseUnauthenticatedRequest
{
    /**
     * Gets the rules for the verification
     *
     * @param User $user
     * @return array
     */
    public function rules(User $user)
    {
        return [
            'email' => 'required|string|max:120|email|unique:users,email',
            'name' => 'required|string|max:120',
            'password' => 'required|string|min:6|max:256',
        ];
    }
}