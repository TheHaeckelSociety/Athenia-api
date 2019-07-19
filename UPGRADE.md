# Athenia App Upgrade Guide

To upgrade from previous version of Athenia please check each version number listed below step by step.

## 0.18.0

New Subscription Function! This build adds a function the subscription repository that allows developers to find all subscriptions of a specific subscriber type that expire after a certain date. To update to this build copy over the following files.

* code/app/Contracts/Repositories/Subscription/SubscriptionRepositoryContract.php
* code/app/Repositories/Subscription/SubscriptionRepository.php
* code/tests/Integration/Repositories/Subscription/SubscriptionRepositoryTest.php

## 0.17.0

Partial Refund! This is a simple build that adds a new helper function to the payment service. There are also some typos that have been fixed in this build too. To run this update copy over the following files.

* code/app/Contracts/Services/StripePaymentServiceContract.php
* code/app/Listeners/User/UserMerge/UserBallotCompletionsMergeListener.php
* code/app/Listeners/User/UserMerge/UserCreatedArticlesMergeListener.php
* code/app/Listeners/User/UserMerge/UserCreatedIterationsMergeListener.php
* code/app/Listeners/User/UserMerge/UserMessagesMergeListener.php
* code/app/Services/StripePaymentService.php
* code/tests/Unit/Services/StripePaymentServiceTest.php

## 0.16.0

User merge! This build adds a brand new event that you can trigger to merge two users into one based on a set of options. This also includes a lot of listeners to this event that will merge various pieces of user data based on the passed in options. To complete this upgrade start by copying over the following locations.

* code/app/Events/User/UserMergeEvent.php 
* code/app/Listeners/User/UserMerge/
* code/database/migrations/2019_06_11_212824_add_merged_to_id_to_users.php
* code/tests/Unit/Events/User/UserMergeEventTest.php
* code/tests/Unit/Listeners/User/UserMerge/

After that is complete make sure to update your event provider with the new listeners. Then there is a bit of optional code cleanup. The following files have had changes made to their imports and headers, so copying those can be optional.

* code/app/Models/Payment/PaymentMethod.php
* code/app/Models/User/Message.php
* code/app/Models/User/User.php
* code/app/Models/Vote/BallotCompletion.php
* code/app/Models/Wiki/Article.php

## 0.15.1

A bug fix! This version fixes a bug that would cause the articles index to load all iterations of an article. To apply this fix simply copy the `getContentAttribute` function from the `Article` model.

## 0.15.0

Decoupling! This version modifies the subscription and payments module in order to decouple them from the user. This means that no modifications will be necessary to these core modules anymore in order to link them to any piece of data that may be required. Along with that a new helper function has been added to the message repository that will make it easier to send emails in the future.

### Message Repository

The new helper function will automatically put together an email template along with sending it. To add this update copy over the following files.

* code/app/Contracts/Repositories/User/MessageRepositoryContract.php
* code/app/Repositories/User/MessageRepository.php
* code/tests/Integration/Repositories/User/MessageRepositoryTest.php

### Decoupling

This is a fairly complicated change, so make sure to follow this guide very closely. The first thing to do would be to make sure the app name is set properly within the app config. This config variable is now being used to send subscription emails, so it is very important to make sure it is set.

#### Simple Files to copy

These files can just be copied over without any issues.

* code/app/Console/Commands/ChargeRenewal.php
* code/app/Console/Commands/SendRenewalReminders.php
* code/app/Contracts/Models/CanBeMorphedTo.php
* code/app/Contracts/Models/HasPaymentMethodsContract.php
* code/app/Contracts/Services/StripeCustomerServiceContract.php
* code/app/Http/V1/Controllers/User/SubscriptionController.php
* code/app/Models/Payment/PaymentMethod.php
* code/app/Models/Subscription/Subscription.php
* code/app/Models/Traits/HasPaymentMethods.php
* code/app/Models/Traits/HasSubscriptions.php
* code/app/Policies/Payment/PaymentMethodPolicy.php
* code/app/Policies/Subscription/SubscriptionPolicy.php
* code/app/Services/StripeCustomerService.php
* code/app/Validators/Subscription/PaymentMethodIsOwnedByUserValidator.php
* code/database/factories/PaymentFactory.php
* code/database/factories/SubscriptionFactory.php
* code/database/migrations/2019_05_17_201626_decouple_user_related_data.php
* code/tests/Feature/Http/User/PaymentMethod/UserPaymentMethodDeleteTest.php
* code/tests/Feature/Http/User/Subscription/UserSubscriptionCreateTest.php
* code/tests/Feature/Http/User/Subscription/UserSubscriptionUpdateTest.php
* code/tests/Integration/Console/Commands/ChargeRenewalTest.php
* code/tests/Integration/Console/Commands/SendRenewalRemindersTest.php
* code/tests/Integration/Policies/Payment/PaymentMethodPolicyTest.php
* code/tests/Integration/Policies/Subscription/SubscriptionPolicyTest.php
* code/tests/Integration/Repositories/Payment/PaymentMethodRepositoryTest.php
* code/tests/Integration/Repositories/Subscription/SubscriptionRepositoryTest.php
* code/tests/Unit/Models/Payment/PaymentMethodTest.php
* code/tests/Unit/Models/Subscription/SubscriptionTest.php
* code/tests/Unit/Services/StripeCustomerServiceTest.php
* code/tests/Unit/Validators/Subscription/PaymentMethodIsOwnedByUserValidatorTest.php

#### User Model

This model needs to have some extensive changes. First you should remove the current subscriptions, and paymentMethods. Then you should make your user model implement the `HasPaymentMethodsContract` interface. Then you are also going to want to use the traits `HasPaymentMethods` and `HasSubscriptions`. At this point you will have to implement the method `morphRelationName`, and make it return the string 'user'.

#### App Repository Provider

This will need to have the morph map imported that will cast the user model to the string user.

## 0.14.1

Quick little patch. The migration for creating the votes module needs to be copied over in order for it to work in MySQL.

## 0.14.0

This update adds a brand new voting data module. This new data module does not have any public available routes, but all piping is now in place for future flexible voting systems. This build also fixes a bug generated from the last build, and adds an important update to the base repository class.

### Voting Module

This module is meant to be incredibly flexible, and it comes with a vote model, ballot model, ballot completion model, and a ballot subject model. The ballot subject model uses a morph relationship that allows for any possible piece of data to be voted upon via this module. To update this module start by copying over the `Vote` directories from the following locations.

* code/app/Contracts/Repositories/
* code/app/Models/
* code/app/Repositories/
* code/tests/Integration/Repositories/
* code/tests/Unit/Models/

Then copy over the following files.

* code/app/Events/Vote/VoteCreatedEvent.php
* code/app/Listeners/Vote/VoteCreatedListener.php
* code/database/factories/VoteFactory.php
* code/database/migrations/2019_05_16_203134_create_votes_model.php
* code/tests/Unit/Events/Vote/VoteCreatedEventTest.php
* code/tests/Unit/Listeners/Vote/VoteCreatedListenerTest.php

Then update the user model, and associated test to have the new `ballotCompletions` relationship, and finally updated the `AppRepositoryProvider` to have all new repositories registered, and the `EventServiceProvider` to have the vote created event registered.

### BaseRepositoryAbstract Update

The `BaseRepositoryAbstract` class should also be copied over. This now has a sync children that will make linking children models very easy. Any similar functionality to this can be updated to use this new function, and an example of how to do this can be found in the ballot repository.

### Stripe Reporting Bug Fix

The stripe description reporting had a bug in it, which can be fixed by updating the following files again.

* code/app/Console/Commands/ChargeRenewal.php
* code/app/Http/V1/Controllers/User/SubscriptionController.php
* code/app/Models/Subscription/Subscription.php
* code/tests/Integration/Console/Commands/ChargeRenewalTest.php

## 0.13.0

This update adds a description paramater to the stripe payment services that will then give more information on the Stripe dashboard when viewing payments made via the app. The Athenia core usages of the services have been updated to automatically report information based on purchased membership plan information, but additional usages of the service will need to be updated or the app will not process payments properly. Please update the following files in your app.

* code/app/Console/Commands/ChargeRenewal.php
* code/app/Contracts/Services/StripePaymentServiceContract.php
* code/app/Http/V1/Controllers/User/SubscriptionController.php
* code/app/Services/StripePaymentService.php
* code/tests/Integration/Console/Commands/ChargeRenewalTest.php
* code/tests/Unit/Services/StripePaymentServiceTest.php

## 0.12.2

This is another minor update. This version has a bit of code clean up, and it also adds some more detailed tests. There are only two files that need to be updated, and they are as follows.

* code/app/Console/Commands/ChargeRenewal.php
* code/tests/Feature/Http/Authentication/RefreshTest.php

## 0.12.1

This is a minor code clean up update with a number of classes that need to be updated. The test `code/tests/Unit/Providers/RouteServiceProviderTest.php` should also be removed. The test `code/tests/Feature/Http/User/GetMeTest.php` should also be replaced with `code/tests/Feature/Http/User/UserMeTest.php`.

### Updated Files

* code/app/Console/Commands/ChargeRenewal.php
* code/app/Http/Middleware/LogMiddleware.php
* code/app/Http/V1/Controllers/AuthenticationController.php
* code/app/Http/V1/Controllers/BaseControllerAbstract.php
* code/app/Http/V1/Controllers/StatusController.php
* code/app/Http/V1/Middleware/JWTGetUserFromTokenProtectedRouteMiddleware.php
* code/app/Http/V1/Middleware/JWTGetUserFromTokenUnprotectedRouteMiddleware.php
* code/app/Http/V1/Requests/Traits/HasNoRules.php
* code/app/Models/Traits/HasValidationRules.php
* code/app/Policies/BasePolicyAbstract.php
* code/app/Providers/RouteServiceProvider.php
* code/tests/Feature/Http/Authentication/LoginTest.php
* code/tests/Feature/Http/Authentication/LogoutTest.php
* code/tests/Feature/Http/Authentication/RefreshTest.php
* code/tests/Feature/Http/Authentication/SignUpTest.php
* code/tests/Integration/Repositories/Subscription/MembershipPlanRateRepositoryTest.php
* code/tests/Unit/Models/Wiki/ArticleTest.php
* code/tests/Unit/Providers/AppRepositoryProviderTest.php
* code/tests/Unit/Providers/EventServiceProviderTest.php

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