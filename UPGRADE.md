# Athenia App Upgrade Guide

To upgrade from previous version of Athenia please check each version number listed below step by step.

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