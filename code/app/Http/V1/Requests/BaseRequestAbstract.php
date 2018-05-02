<?php
/**
 * The Base request class for all requests in the system
 */
declare(strict_types=1);

namespace App\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class BaseRequestAbstract
 * @package App\Http\V1\Requests
 */
abstract class BaseRequestAbstract extends FormRequest
{
    /**
     * Whether or not the current user is authenticated to run this request
     *
     * @return bool
     */
    abstract public function authorize() : bool;
}