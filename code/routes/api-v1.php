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
         * Resource Context
         */
        Route::resource('resources', 'ResourceController', [
            'only' => [
                'index',
            ],
        ]);

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
            Route::resource('contacts', 'User\ContactController', [
                'only' => [
                    'index', 'store', 'update',
                ],
            ]);

            Route::resource('payment-methods', 'User\PaymentMethodController', [
                'only' => [
                    'store', 'destroy',
                ],
            ]);
            Route::resource('subscriptions', 'User\SubscriptionController', [
                'only' => [
                    'store', 'update',
                ],
            ]);

            Route::resource('threads', 'User\ThreadController', [
                'only' => [
                    'index', 'store',
                ],
            ]);

            Route::group(['prefix' => 'threads/{thread}', 'as' => 'thread.'], function () {
                Route::resource('messages', 'User\Thread\MessageController', [
                    'only' => [
                        'index', 'store', 'update',
                    ],
                ]);
            });
        });

        /**
         * Membership Plan Context
         */
        Route::resource('membership-plans', 'MembershipPlanController', [
            'except' => [
                'create', 'edit'
            ]
        ]);

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
