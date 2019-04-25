# Athenia App Upgrade Guide

To upgrade from previous version of Athenia please check each version number listed below step by step.

## 0.12.0

This is a very important security release that will require lots of modifications to existing requests. This release closes a large security hole that would previously allow a user to expand unlimited amount of server data.

### Requests 
 
Most of the changes are related to requests, and the easiest thing to do would be to copy all of the contents in the requests directory into your project. There is a new trait named `HasNoExpands` in this directory along with some fundamental changes made to the base requests that now authorize requests based on the expand fields in the user request, and every other sub request has been updated to use a new function that returns a list of all relations that are allowed to be expanded on a request.

### Middlewares

The `ExpandParsingMiddleware` has also had a minor update that should be copied over.

### Exceptions

The handler should also be updated so that it spits out a details string when an AuthorizationException is hit.

### Tests

The `GetMeTest` has also been updated to have an additional test added that verifies that the expands are not possible beyond a certain point.

## 0.11.1

This is a patch version that simply added some code cleanup, and additional services.

### Composer

Add the generators dependency in the requires-dev section.

```json
        "laracasts/generators": "^1.1",
```

Then update `zircote/swagger-php` to `^3.0`, and run `composer update`.

### Providers

Then add the following line to the `AppServiceProvider` in the local environment registration section.

```php
            $this->app->register(GeneratorsServiceProvider::class);
```

Then update the related `AppServiceProviderTest` to the related environment specific test.

```php
        $appMock->shouldReceive('register')->with(GeneratorsServiceProvider::class);
```

### Code Cleanup

Then the following files have had minor code cleanup updates that can be optionally updated.

* code/app/Contracts/Policies/BasePolicyContract.php
* code/app/Http/V1/Controllers/UserController.php
* code/app/Models/User/Message.php
* code/app/Models/User/User.php
* code/app/Services/UserAuthenticationService.php

After that you will be up to date with version 0.11.1