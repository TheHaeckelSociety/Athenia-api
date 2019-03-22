<?php
/**
 * V1 Routes!!
 *
 * loaded by RouteServiceProvider within a group, which
 * is assigned the "api-v1" middleware group
 */
declare(strict_types=1);

/******************************************************
 * API v1 routes
 ******************************************************/
Route::group(['prefix' => 'v1', 'as' => 'v1.'], function() {

    /**
     * Routes that are available to the public
     */
    Route::group(['middleware' => 'jwt.auth.unprotected'], function() {

        Route::get('status', 'StatusController')
            ->name('status');

        // Add open routes below
    });

    /**
     * Forgot password routes
     */
    Route::post('forgot-password', 'ForgotPasswordController@forgotPassword')
        ->name('forgot-password');

    Route::post('reset-password', 'ForgotPasswordController@resetPassword')
        ->name('reset-password');

    /**
     * Authentication routes
     */
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function() {

        Route::post('refresh', 'AuthenticationController@refresh')
            ->name('refresh');

        Route::post('login', 'AuthenticationController@login')
            ->name('login');

        Route::post('logout', 'AuthenticationController@logout')
            ->name('logout');

        Route::post('sign-up', 'AuthenticationController@signUp')
            ->name('sign-up');
    });

    /**
     * Routes that a user needs to be authenticated for in order to access
     */
    Route::group(['middleware' => 'jwt.auth.protected'], function() {

        /**
         * User Context
         */
        Route::get('users/me', 'UserController@me')
            ->name('view-self');

        Route::resource('users', 'UserController', [
            'only' => [
                'show', 'update',
            ],
        ]);
        Route::group(['prefix' => 'users/{user}', 'as' => 'user.'], function () {
            Route::resource('payment-methods', 'User\PaymentMethodController', [
                'only' => [
                    'index', 'store',
                ],
            ]);
        });

        /**
         * Article Context
         */
        Route::resource('articles', 'ArticleController', [
            'except' => [
                'create', 'edit', 'destroy'
            ]
        ]);
        Route::group(['prefix' => 'articles/{article}', 'as' => 'article.'], function () {
            Route::resource('iterations', 'Article\IterationController', [
                'only' => [
                    'index',
                ],
            ]);
        });

        /**
         * Roles Context
         */
        Route::resource('roles', 'RoleController', [
            'only' => [
                'index'
            ]
        ]);

        // add auth protected routes below
    });
});
