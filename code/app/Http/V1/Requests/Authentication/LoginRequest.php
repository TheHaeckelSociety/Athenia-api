<?php
declare(strict_types=1);

namespace App\Http\V1\Requests\Authentication;

use App\Http\V1\Requests\BaseUnauthenticatedRequest;

/**
 * Class LoginRequest
 * @package App\Http\V1\Requests\Authentication
 */
class LoginRequest extends BaseUnauthenticatedRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'email' => 'required|max:256|email',
            'password' => 'required|max:256',
        ];
    }
}
