# Athenia App Upgrade Guide

To upgrade from previous version of Athenia please check each version number listed below step by step.

## 0.27.0 

Extendable endpoints! This is another long overdue update. All Athenia endpoints now have their own base controllers where all future implementations should be created. The core has also been upgraded to Laravel 7. To complete this update complete the following steps.

### Routing Upgrade

To start copy over the directory `code/app/Http/Core/`, this file `code/routes/core.php`, and then update the following paths.

* README.md - There was a useful snippet added about how to best define routes.
* code/app/Http/V1/Middleware/ - All middlewares in this directory should be moved to the root HTTP directory
* code/app/Http/Kernel.php - Update the paths of middlewares to point to root instead of V1
* code/app/Http/V1/Controllers/Article/ArticleVersionController.php
* code/app/Http/V1/Controllers/Article/IterationController.php
* code/app/Http/V1/Controllers/ArticleController.php
* code/app/Http/V1/Controllers/AuthenticationController.php
* code/app/Http/V1/Controllers/ForgotPasswordController.php
* code/app/Http/V1/Controllers/MembershipPlanController.php
* code/app/Http/V1/Controllers/Organization/OrganizationManagerController.php
* code/app/Http/V1/Controllers/OrganizationController.php
* code/app/Http/V1/Controllers/ResourceController.php
* code/app/Http/V1/Controllers/RoleController.php
* code/app/Http/V1/Controllers/StatusController.php
* code/app/Http/V1/Controllers/User/AssetController.php
* code/app/Http/V1/Controllers/User/ContactController.php
* code/app/Http/V1/Controllers/User/PaymentMethodController.php
* code/app/Http/V1/Controllers/User/ProfileImageController.php
* code/app/Http/V1/Controllers/User/SubscriptionController.php
* code/app/Http/V1/Controllers/User/Thread/MessageController.php
* code/app/Http/V1/Controllers/User/ThreadController.php
* code/app/Http/V1/Controllers/UserController.php
* code/routes/api-v1.php - Any routes that are from Athenia should be removed, and every route group should now include the core routes
* tests/Unit/Http/V1/Middleware/ - All tests in this directory should be moved into the root middleware directory 

Once that is complete you are going to want to copy over all application specific controllers, requests, and middlewares. Then modify any version specific controllers to either simply extend the parents or to have a custom implementation. Then remove the following paths.

* code/app/Http/V1/Controllers/BaseControllerAbstract.php
* code/app/Http/V1/Controllers/Traits/
* code/app/Http/V1/Requests/

### Laravel Upgrade

To start this part open up your composer.json, and make sure to update your laravel version, and then check any corresponding dependencies. Then update the following files.

* code/app/Exceptions/Handler.php
* code/config/cors.php
* code/config/mail.php
* code/config/session.php
* code/tests/Integration/Repositories/User/ProfileImageRepositoryTest.php

## 0.26.0 

This update adds a profile image feature for our users will now be able to make use of. There was also a fix made to the ansible dependencies, so start off by replacing all instances of 7.3 to 7.4 in the file `ansible/roles/php/tasks/main.yml`. Then copy over the following new files.

* code/app/Contracts/Repositories/User/ProfileImageRepositoryContract.php
* code/app/Http/V1/Controllers/User/ProfileImageController.php
* code/app/Http/V1/Requests/User/ProfileImage/
* code/app/Models/User/ProfileImage.php
* code/app/Policies/User/ProfileImagePolicy.php
* code/app/Repositories/User/ProfileImageRepository.php
* code/database/migrations/2020_03_21_194840_add_profile_image_id_to_users.php
* code/tests/Feature/Http/User/ProfileImage/UserProfileImageCreateTest.php
* code/tests/Integration/Repositories/User/ProfileImageRepositoryTest.php
* code/tests/Unit/Models/User/ProfileImageTest.php

Then there are a couple updates that need to be ran for this on existing files.

* code/app/Models/Asset.php - The relation type to user has been changed in the function type signature.
* code/app/Models/User/User.php - A new attribute was added for the profile image url, and a new relation was added to the profile image.
* code/app/Providers/AppRepositoryProvider.php - The new repository needs to be registered.
* code/routes/api-v1.php - The new route needs to be registered.
* code/tests/Unit/Listeners/User/SignUpListenerTest.php - A bug fix was made from the last update on one of the assertions.
* code/tests/Unit/Models/User/UserTest.php - The new attribute and relation have new tests.

## 0.25.0

A very long overdue update. This update brings general ordering to all possible index requests of the API. This means that all findAll function calls have been updated, and all usages of those functions will need to be updated in all related apps. The core has also been updated to php 7.4, and a previously provided function has been removed.

### PHP 7.4

To complete the php update simply copy over the following files, and then run the provision.

* ansible/roles/athenia/templates/api.projectathenia.com.conf.j2  
* ansible/roles/php/tasks/main.yml

### Ordering

There are a large amount of files that were changed for this. Most of these changes were pretty simply changes to findAll calls, but there are a number of large changes to some files that were made. These are thus broken into two corresponding sections.

#### Big Changes

* code/app/Contracts/Repositories/BaseRepositoryContract.php
* code/app/Http/V1/Controllers/Traits/HasIndexRequests.php
* code/app/Repositories/BaseRepositoryAbstract.php
* code/app/Repositories/Traits/NotImplemented/FindAll.php
* code/tests/Feature/Http/User/Thread/Message/UserThreadMessageIndexTest.php
* code/tests/Integration/Repositories/User/UserRepositoryTest.php

#### Small Changes

* code/app/Console/Commands/ReindexResources.php
* code/app/Http/V1/Controllers/Article/ArticleVersionController.php
* code/app/Http/V1/Controllers/Article/IterationController.php
* code/app/Http/V1/Controllers/ArticleController.php
* code/app/Http/V1/Controllers/MembershipPlanController.php
* code/app/Http/V1/Controllers/Organization/OrganizationManagerController.php
* code/app/Http/V1/Controllers/OrganizationController.php
* code/app/Http/V1/Controllers/ResourceController.php
* code/app/Http/V1/Controllers/RoleController.php
* code/app/Http/V1/Controllers/User/AssetController.php
* code/app/Http/V1/Controllers/User/ContactController.php
* code/app/Http/V1/Controllers/User/Thread/MessageController.php
* code/app/Http/V1/Controllers/User/ThreadController.php
* code/app/Repositories/User/ContactRepository.php
* code/tests/Integration/Repositories/RoleRepositoryTest.php
* code/tests/Integration/Repositories/User/ContactRepositoryTest.php

### Message Repository Helper Removal

This is partially an optional change, but if you choose not to do it then you must still update the findAll function in the MessageRepository. Along with the updated files below, any calls to the function `findAllOrderedByOldest` should be replaced with a standard `findAll` utilizing the new ordering functionality to order the `created_at` field ascending.

* code/app/Contracts/Repositories/User/MessageRepositoryContract.php
* code/app/Repositories/User/MessageRepository.php

## 0.24.0

Big ole update! This update adds a new organization module along with a bunch of other new updates. To complete this update run the following steps for each group of updates.

### Organization Module

Start by copying over the following paths.

* code/app/Contracts/Repositories/Organization/
* code/app/Events/Organization/
* code/app/Http/V1/Controllers/Organization/OrganizationManagerController.php
* code/app/Http/V1/Controllers/OrganizationController.php
* code/app/Http/V1/Requests/Organization/
* code/app/Listeners/Organization/
* code/app/Models/Organization/
* code/app/Policies/Organization/
* code/app/Repositories/Organization/
* code/database/factories/OrganizationFactory.php 
* code/database/migrations/2020_02_10_211858_create_organizations.php
* code/resources/views/mailers/organization-manager-created.blade.php
* code/tests/Feature/Http/Organization/
* code/tests/Integration/Policies/Organization/
* code/tests/Integration/Repositories/Organization/
* code/tests/Unit/Events/Organization/OrganizationManagerCreatedEventTest.php
* code/tests/Unit/Listeners/Organization/OrganizationManagerCreatedListenerTest.php
* code/tests/Unit/Models/Organization/

Then make sure to complete the following more involved steps. 

* code/app/Models/Role.php - Copy over the new organization roles at the top of the class.
* code/app/Models/User/User.php - Add the `organizationManagers` relation and the function `canManageOrganization`.
* code/tests/Integration/Models/User/UserTest.php - Add the function `testCanManageOrganization`.
* code/tests/Unit/Models/User/UserTest.php - Add the function `testOrganizationManagers`.

Then you need to register the new modules in the following files.

* code/app/Providers/AppRepositoryProvider.php - Register the new organization repositories in the Athenia repo section.
* code/app/Providers/EventServiceProvider.php - Register the new OrganizationManagerCreatedEvent and Listener.
* code/app/Providers/RouteServiceProvider.php - Register the new Organization models.
* code/routes/api-v1.php - Register the new organization controllers.

### Minor Improvements

#### Signup Listener

This listener was updated to use a newer storage function. To run this update copy over the following files.

* code/app/Listeners/User/SignUpListener.php
* code/tests/Unit/Listeners/User/SignUpListenerTest.php

#### CanGetAndUnset Move

This trait was moved out of the Repositories trait into the root. To start off remove the old trait and add the new trait located at `code/app/Traits/CanGetAndUnset.php`. Then update the import in the following classes.

* code/app/Repositories/AssetRepository.php
* code/app/Repositories/Subscription/MembershipPlanRepository.php
* code/app/Repositories/User/ThreadRepository.php
* code/app/Repositories/Vote/BallotRepository.php

#### Laravel helpers removal

The old laravel helpers were removed, so these files should be updated.

* code/app/Services/TokenGenerationService.php
* code/config/session.php

#### Payment Method Deletion 

A bug was fixed where the stripe customer service was not deleting the payment method.

* code/app/Services/StripeCustomerService.php
* code/tests/Unit/Services/StripeCustomerServiceTest.php

#### Miscellaneous

* code/database/migrations/2020_03_03_153033_make_users_fields_nullable.php - Some field have been made nullable
* code/resources/views/base-mailer.blade.php - The greeting field is now checked for a value before it is displayed
* code/tests/Feature/Http/MembershipPlan/MembershipPlanDeleteTest.php - Code cleanup
* code/tests/Integration/Models/Subscription/MembershipPlanTest.php - Added database setup trait
* code/tests/Integration/Policies/Subscription/MembershipPlanPolicyTest.php - Added database setup trait
* code/tests/Integration/Repositories/Subscription/MembershipPlanRateRepositoryTest.php - Removal of membership plan rate pre test deletion

## 0.23.1

Bug Fix! Simply copy over this file `code/app/Http/Sockets/ArticleIterations.php`.

## 0.23.0

New Function for the HasSubscriptions trait! To run this update simply copy over the file `code/app/Models/Traits/HasSubscriptions.php`, and then add the test `testCurrentSubscription` from `code/tests/Unit/Models/User/UserTest.php`.

## 0.22.0

Very simple update! This simply adds the `last_iteration_content` as an appending field to the article model.

## 0.21.0

Big bug fix! This adds a new service for dealing with multi byte strings, which is then used to fix a bug in the article iterations socket. There are also some minor code cleanup changes that have been made, and `phploc/phploc` has been added to the require dev block of the composer file.

### Article Iteration Changes

Start by copying over the following files.

* code/app/Contracts/Services/StringHelperServiceContract.php
* code/app/Services/StringHelperService.php
* code/tests/Feature/Socket/ArticleIterationTest.php
* code/tests/Integration/Http/Sockets/ArticleIterationTest.php
* code/tests/Unit/Http/Sockets/ArticleIterationsTest.php
* code/tests/Unit/Services/StringHelperServiceTest.php
* code/tests/Unit/Validators/ArticleVersion/SelectedIterationBelongsToArticleValidatorTest.php

Then make sure to register the new service.

### Minor code cleanup 

* code/app/Policies/Wiki/IterationPolicy.php
* code/tests/Integration/Policies/Wiki/IterationPolicyTest.php
* code/tests/Unit/Providers/AuthServiceProviderTest.php

## 0.20.1

Very simply bug fix! Simply copy over the file `code/app/Http/V1/Controllers/MembershipPlanController.php`.

## 0.20.0

This version adds a few new fields to the user model that are needed for sending notifications, and for allowing a user to control their profile more. Start off by copying over the new migration `code/database/migrations/2019_11_27_163845_add_profile_fields_to_user.php`. This will add all needed fields for this upgrade. Then run the ide helpers in order to add these fields to the header of your user model. After that you will also want to copy over the new validation rules `push_notification_key`, `about_me`, `allow_users_to_add_me`, and `receive_push_notifications` within the user model. Then update the `code/tests/Feature/Http/User/UserUpdateTest.php` test in order to take into account the changes made for the new fields. 

## 0.19.0 - Cusco Spec

This version starts off with an upgrade from laravel 5.8 to laravel 6. Follow the following instructions in order to complete this part of the update.

1) Update the composer json core dependencies to the ones found in this project
2) Add the new ignore found in .gitignore
3) Copy over code/tests/Unit/Exceptions/HandlerTest.php in order to fix some phpunit deprecation warnings
4) Copy over code/tests/Unit/Http/Sockets/ArticleIterationsTest.php in order to fix some phpunit deprecation warnings
5) Copy over code/tests/Unit/Models/Wiki/ArticleTest.php in order to fix some phpunit deprecation warnings
6) The Vagrantfile has also been updated, but you may not want to copy that

### New Files

Then we have a whole pile of changes made for the Cusco spec. To start this process copy over the following new files.

### Console

* code/app/Console/Commands/ReindexResources.php

### Contracts

* code/app/Contracts/Models/CanBeIndexedContract.php
* code/app/Contracts/Repositories/AssetRepositoryContract.php
* code/app/Contracts/Repositories/ResourceRepositoryContract.php
* code/app/Contracts/Repositories/User/ContactRepositoryContract.php
* code/app/Contracts/Repositories/User/ThreadRepositoryContract.php
* code/app/Contracts/Repositories/Wiki/ArticleVersionRepositoryContract.php
* code/app/Contracts/Services/ArticleVersionCalculationServiceContract.php
* code/app/Contracts/ThreadSecurity/ThreadSubjectGateContract.php 
* code/app/Contracts/ThreadSecurity/ThreadSubjectGateProviderContract.php

### Events

* code/app/Events/Article/ArticleVersionCreatedEvent.php
* code/app/Events/User/Contact/ContactCreatedEvent.php

### HTTP

* code/app/Http/V1/Controllers/Article/ArticleVersionController.php
* code/app/Http/V1/Controllers/ResourceController.php
* code/app/Http/V1/Controllers/User/AssetController.php
* code/app/Http/V1/Controllers/User/ContactController.php
* code/app/Http/V1/Controllers/User/Thread/MessageController.php
* code/app/Http/V1/Controllers/User/ThreadController.php
* code/app/Http/V1/Requests/Article/ArticleVersion/
* code/app/Http/V1/Requests/BaseAssetUploadRequestAbstract.php
* code/app/Http/V1/Requests/Resource/
* code/app/Http/V1/Requests/User/Asset/
* code/app/Http/V1/Requests/User/Contact/
* code/app/Http/V1/Requests/User/Thread/

### Listeners

* code/app/Listeners/Article/ArticleVersionCreatedListener.php 
* code/app/Listeners/User/Contact/ContactCreatedListener.php

### Models

* code/app/Models/Asset.php
* code/app/Models/Resource.php 
* code/app/Models/Traits/CanBeIndexed.php
* code/app/Models/User/Contact.php
* code/app/Models/User/Thread.php
* code/app/Models/Wiki/ArticleVersion.php

### Observers

* code/app/Observers/IndexableModelObserver.php

### Policies

* code/app/Policies/AssetPolicy.php
* code/app/Policies/ResourcePolicy.php
* code/app/Policies/User/ContactPolicy.php
* code/app/Policies/User/MessagePolicy.php
* code/app/Policies/User/ThreadPolicy.php
* code/app/Policies/Wiki/ArticleVersionPolicy.php

### Repositories

* code/app/Repositories/AssetRepository.php
* code/app/Repositories/ResourceRepository.php
* code/app/Repositories/User/ContactRepository.php
* code/app/Repositories/User/ThreadRepository.php
* code/app/Repositories/Wiki/ArticleVersionRepository.php

### Services

* code/app/Services/ArticleVersionCalculationService.php

### Security

* code/app/ThreadSecurity/
* code/app/Validators/ArticleVersion/SelectedIterationBelongsToArticleValidator.php

### database

* code/database/factories/AssetFactory.php 
* code/database/factories/ResourceFactory.php
* code/database/migrations/2019_10_29_154335_cusco.php

### Feature Tests

* code/tests/Feature/Http/Article/ArticleVersion/
* code/tests/Feature/Http/User/Asset/
* code/tests/Feature/Http/User/Contact/
* code/tests/Feature/Http/User/Thread/

### Integration Tests

* code/tests/Integration/Console/Commands/ReindexResourcesTest.php
* code/tests/Integration/Models/ResourceTest.php
* code/tests/Integration/Models/User/ThreadTest.php
* code/tests/Integration/Policies/AssetPolicyTest.php
* code/tests/Integration/Policies/ResourcePolicyTest.php 
* code/tests/Integration/Policies/User/ContactPolicyTest.php
* code/tests/Integration/Policies/User/MessagePolicyTest.php
* code/tests/Integration/Policies/User/ThreadPolicyTest.php
* code/tests/Integration/Policies/Wiki/ArticleVersionPolicyTest.php
* code/tests/Integration/Repositories/AssetRepositoryTest.php
* code/tests/Integration/Repositories/ResourceRepositoryTest.php
* code/tests/Integration/Repositories/User/ContactRepositoryTest.php
* code/tests/Integration/Repositories/User/ThreadRepositoryTest.php
* code/tests/Integration/Repositories/Wiki/ArticleVersionRepositoryTest.php

### Unit Tests

* code/tests/Unit/Events/Article/ArticleVersionCreatedEventTest.php
* code/tests/Unit/Events/User/Contact/ContactCreatedEventTest.php
* code/tests/Unit/Listeners/Article/ArticleVersionCreatedListenerTest.php
* code/tests/Unit/Listeners/User/Contact/ContactCreatedListenerTest.php
* code/tests/Unit/Models/AssetTest.php
* code/tests/Unit/Models/User/ContactTest.php
* code/tests/Unit/Models/User/ThreadTest.php
* code/tests/Unit/Models/Wiki/ArticleVersionTest.php
* code/tests/Unit/Observers/IndexableModelObserverTest.php
* code/tests/Unit/Services/ArticleVersionCalculationServiceTest.php 
* code/tests/Unit/ThreadSecurity/
* code/tests/Unit/Validators/ArticleVersion/SelectedIterationBelongsToArticleValidatorTest.php

### Modified Files

Then we have to update a bunch of files that were modified in the core.

### configuration

In .env.example a new ASSET_URL variable has now been added. This is also in conjunction with a new configuration variable added in code/config/app.php.

### Repositories

#### findAll - Breaking Change

The findAll function within the data repositories has been updated quite a bit to allow for the injection of the page number, and to allow for querying for every record in a model with the limit variable. In order to complete this update then copy over the following files.

* code/app/Contracts/Repositories/BaseRepositoryContract.php
* code/app/Http/V1/Controllers/Article/IterationController.php
* code/app/Http/V1/Controllers/ArticleController.php
* code/app/Http/V1/Controllers/MembershipPlanController.php
* code/app/Http/V1/Controllers/RoleController.php
* code/app/Repositories/BaseRepositoryAbstract.php
* code/app/Repositories/Traits/NotImplemented/FindAll.php

Then once these files are copied make sure to copy over this snippet `, (int)$request->input('page', 1)` after the belongsTo variable into all findAll function calls throughout your app. This is specifically related to the index functions within controllers.

#### MessageRepository Changes - Non-Breaking Change

This adds a new function to the message repository as well as a new parameter to an existing function. This will not break any existing implementations, so copy over these files without worry.

* code/app/Contracts/Repositories/User/MessageRepositoryContract.php
* code/app/Repositories/User/MessageRepository.php
* code/tests/Integration/Repositories/User/MessageRepositoryTest.php

#### Implementation of findAll - Non-Breaking Change

A couple more findAll queries were implemented. Simply copy over the following files for this change.

* code/app/Repositories/User/UserRepository.php
* code/app/Repositories/Wiki/IterationRepository.php
* code/tests/Integration/Repositories/User/UserRepositoryTest.php
* code/tests/Integration/Repositories/Wiki/IterationRepositoryTest.php 

### Models

#### Article Content Field - Breaking Change

The field on the article named content is now being pulled from the most recent article version. The old functionality of using the most recently created article iteration has been moved to a private field named `last_iteration_content`. To update for this change copy over the following files.

* code/app/Http/Sockets/ArticleIterations.php
* code/app/Models/Wiki/Article.php
* code/tests/Feature/Http/Article/ArticleViewTest.php 
* code/tests/Integration/Http/Sockets/ArticleIterationTest.php
* code/tests/Integration/Models/Wiki/ArticleTest.php 

After that make sure that versions are created for any articles that you are using. The migration should take care of this, but a new version will need to be made anytime an article is ready to be published from here on out.

#### Message Expansion - Breaking Change

The message model has been expanded in numerous ways. The old user_id has been changed to a to_id along with the addition of a from_id. It now has validation rules, and a number of new fields that control what the message does.

* code/app/Listeners/Message/MessageCreatedListener.php
* code/app/Mail/MessageMailer.php
* code/app/Models/User/Message.php
* code/tests/Unit/Listeners/Message/MessageCreatedListenerTest.php
* code/tests/Unit/Mail/MessageMailerTest.php
* code/tests/Unit/Models/User/MessageTest.php

#### User Expansion - Non-Breaking Change

The user has had a number of relations added to it, and it has also had an indexable relation added to it. It is probably best to run a diff to see everyting on this, but the changed files are as follows.

* code/app/Models/User/User.php 
* code/tests/Unit/Models/User/UserTest.php

#### Iteration Version Relation - Non-Breaking Change

A new relation has been added to the iteration model with the following files changed.

* code/app/Models/Wiki/Iteration.php
* code/tests/Unit/Models/Wiki/IterationTest.php

#### Cleanup - Non-Breaking Change

Mostly just header clean ups.

* code/app/Models/Payment/PaymentMethod.php
* code/app/Models/Role.php
* code/app/Models/Subscription/MembershipPlan.php
* code/app/Models/Subscription/MembershipPlanRate.php
* code/app/Models/Subscription/Subscription.php
* code/app/Models/Vote/Ballot.php
* code/app/Models/Vote/BallotCompletion.php
* code/app/Models/Vote/BallotSubject.php 

### Miscellaneous 

#### Iteration Policy Bug

* code/app/Policies/Wiki/IterationPolicy.php
* code/tests/Integration/Policies/Wiki/IterationPolicyTest.php

#### UserViewTest Update 

This test has been updated due to an issue with the new resource model.

* code/tests/Feature/Http/User/UserViewTest.php 

#### AuthServiceProviderTest change

This test was modified very slightly.

* code/tests/Unit/Providers/AuthServiceProviderTest.php

#### Database Factories

These factories have been modified. 

* code/database/factories/UserFactory.php
* code/database/factories/WikiFactory.php

#### Registrations

Make sure to copy over the Athenia portion of the AppRepositoryProvider. The ArticleVersionCalculationService also needs to be registered in the AppServiceProvider. The new validator then needs to be registered in both the AppValidatorProvider and the validation language. Then the ThreadSubjectGateProvider should be registered in the AuthServiceProvider. Then the new events need to be added in the EventServiceProvider, and the User model needs to have the indexable model observer added to it. Finally make sure to copy over the Athenia specific routes from the v1 router.

Hooray! That's it for the Cusco update.

## 0.18.0

New Subscription Function! This build adds a function to the subscription repository that allows developers to find all subscriptions of a specific subscriber type that expire after a certain date. To update to this build copy over the following files.

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

```
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