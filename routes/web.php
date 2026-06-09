<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Vendor\AuthController as VendorAuthController;
use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicBusinessController;
use App\Http\Controllers\DirectoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\BulkImportExportController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\User\YcIgniteController;
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Cache;
use App\Models\Business;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


Route::get('/test-mail', function () {

    try {

        Mail::raw('Test email from Laravel', function ($message) {
            $message->to('darshankondekar01@gmail.com')
                    ->subject('Test Mail');
        });

        return 'Mail Sent Successfully';

    } catch (\Exception $e) {

        Log::error('Mail Error: ' . $e->getMessage());

        Log::error($e);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    
    dd('clear');
});

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/about-yc-spark', [PublicController::class, 'about_yc'])->name('about_yc');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::get('/privacy-policy', [PublicController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms-of-use', [PublicController::class, 'termsOfUse'])->name('terms.of.use');
Route::get('/refund', [PublicController::class, 'refundPolicy'])->name('refund.policy');
Route::get('/infringement', [PublicController::class, 'infringementPolicy'])->name('infringement.policy');
Route::get('/categories', [PublicController::class, 'allCategories'])->name('categories.all');
// Route::get('/about-yc-spark', [PublicController::class, 'aboutYCSpark'])->name('about.yc.spark');

// General Authenticated Routes (Common for User, Vendor, Admin)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    // Add other common authenticated routes here if needed
    
    // Logout route often goes here too, but let's assume the existing one works
    // Route::post('/logout', [YourAuthController::class, 'logout'])->name('logout'); 
});

// === Role Specific Routes ===

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
    
    // Protected routes
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/dashboard', function () {
            $totalUsers = \App\Models\User::where('role', 'user')->count();
            $totalVendors = \App\Models\User::where('role', 'vendor')->count();
            $pendingVendors = \App\Models\User::where('role', 'vendor')->where('status', 'pending')->count();
            $totalProducts = \App\Models\Business::count();
            
            return view('admin.dashboard', compact('totalUsers', 'totalVendors', 'pendingVendors', 'totalProducts'));
        })->name('dashboard');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        // Admin routes for creating users and vendors
        Route::get('/users/create', [AdminAuthController::class, 'showCreateUserForm'])->name('users.create');
        Route::post('/users/create', [AdminAuthController::class, 'createUser'])->name('users.store');
        
        Route::get('/vendors/create', [AdminAuthController::class, 'showCreateVendorForm'])->name('vendors.create');
        Route::post('/vendors/create', [AdminAuthController::class, 'createVendor'])->name('vendors.store');
        
        // Vendor approval routes
        Route::get('/vendors/pending', [AdminAuthController::class, 'pendingVendors'])->name('vendors.pending');
        Route::post('/vendors/{id}/approve', [AdminAuthController::class, 'approveVendor'])->name('vendors.approve');
        Route::post('/vendors/{id}/reject', [AdminAuthController::class, 'rejectVendor'])->name('vendors.reject');
        Route::get('/vendors/{id}', [AdminAuthController::class, 'showVendor'])->name('vendors.show');
        
        // Category routes
        Route::get('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
        
        // Subcategory routes
        Route::get('/subcategories', [App\Http\Controllers\Admin\SubcategoryController::class, 'index'])->name('subcategories.index');
        Route::get('/subcategories/create', [App\Http\Controllers\Admin\SubcategoryController::class, 'create'])->name('subcategories.create');
        Route::post('/subcategories', [App\Http\Controllers\Admin\SubcategoryController::class, 'store'])->name('subcategories.store');
        Route::get('/subcategories/{subcategory}/edit', [App\Http\Controllers\Admin\SubcategoryController::class, 'edit'])->name('subcategories.edit');
        Route::put('/subcategories/{subcategory}', [App\Http\Controllers\Admin\SubcategoryController::class, 'update'])->name('subcategories.update');
        Route::delete('/subcategories/{subcategory}', [App\Http\Controllers\Admin\SubcategoryController::class, 'destroy'])->name('subcategories.destroy');
        
        // Location Management routes
        // States
        Route::get('/states', [App\Http\Controllers\Admin\StateController::class, 'index'])->name('states.index');
        Route::get('/states/create', [App\Http\Controllers\Admin\StateController::class, 'create'])->name('states.create');
        Route::post('/states', [App\Http\Controllers\Admin\StateController::class, 'store'])->name('states.store');
        Route::get('/states/{state}/edit', [App\Http\Controllers\Admin\StateController::class, 'edit'])->name('states.edit');
        Route::put('/states/{state}', [App\Http\Controllers\Admin\StateController::class, 'update'])->name('states.update');
        Route::delete('/states/{state}', [App\Http\Controllers\Admin\StateController::class, 'destroy'])->name('states.destroy');
        
        // Cities
        Route::get('/cities', [App\Http\Controllers\Admin\CityController::class, 'index'])->name('cities.index');
        Route::get('/cities/create', [App\Http\Controllers\Admin\CityController::class, 'create'])->name('cities.create');
        Route::post('/cities', [App\Http\Controllers\Admin\CityController::class, 'store'])->name('cities.store');
        Route::get('/cities/{city}/edit', [App\Http\Controllers\Admin\CityController::class, 'edit'])->name('cities.edit');
        Route::put('/cities/{city}', [App\Http\Controllers\Admin\CityController::class, 'update'])->name('cities.update');
        Route::delete('/cities/{city}', [App\Http\Controllers\Admin\CityController::class, 'destroy'])->name('cities.destroy');
        
        // Areas
        Route::get('/areas', [App\Http\Controllers\Admin\AreaController::class, 'index'])->name('areas.index');
        Route::get('/areas/create', [App\Http\Controllers\Admin\AreaController::class, 'create'])->name('areas.create');
        Route::post('/areas', [App\Http\Controllers\Admin\AreaController::class, 'store'])->name('areas.store');
        Route::get('/areas/{area}/edit', [App\Http\Controllers\Admin\AreaController::class, 'edit'])->name('areas.edit');
        Route::put('/areas/{area}', [App\Http\Controllers\Admin\AreaController::class, 'update'])->name('areas.update');
        Route::delete('/areas/{area}', [App\Http\Controllers\Admin\AreaController::class, 'destroy'])->name('areas.destroy');
        
        // Business management
        Route::get('businesses/pending', [App\Http\Controllers\Admin\BusinessController::class, 'pending'])->name('businesses.pending');
        Route::patch('businesses/{business}/approve', [App\Http\Controllers\Admin\BusinessController::class, 'approve'])->name('businesses.approve');
        Route::patch('businesses/{business}/reject', [App\Http\Controllers\Admin\BusinessController::class, 'reject'])->name('businesses.reject');
        Route::get('businesses/subcategories', [App\Http\Controllers\Admin\BusinessController::class, 'getSubcategories'])->name('businesses.subcategories');
        Route::get('businesses/cities', [App\Http\Controllers\Admin\BusinessController::class, 'getCities'])->name('businesses.cities');
        Route::get('businesses/areas', [App\Http\Controllers\Admin\BusinessController::class, 'getAreas'])->name('businesses.areas');
        Route::put('businesses/{business}/unclaim', [App\Http\Controllers\Admin\BusinessController::class, 'unclaim'])->name('businesses.unclaim');
        Route::resource('businesses', App\Http\Controllers\Admin\BusinessController::class);
        
        // Business claim management
        Route::get('/claims', [App\Http\Controllers\Admin\ClaimController::class, 'index'])->name('claims.index');
        Route::get('/claims/history', [App\Http\Controllers\Admin\ClaimController::class, 'history'])->name('claims.history');
        Route::get('/claims/{claim}', [App\Http\Controllers\Admin\ClaimController::class, 'show'])->name('claims.show');
        Route::post('/claims/{claim}/approve', [App\Http\Controllers\Admin\ClaimController::class, 'approve'])->name('claims.approve');
        Route::post('/claims/{claim}/reject', [App\Http\Controllers\Admin\ClaimController::class, 'reject'])->name('claims.reject');
        
        // Plans Routes
        Route::get('/plans', [App\Http\Controllers\Admin\PlanController::class, 'index'])->name('plans.index');
        Route::get('/plans/create', [App\Http\Controllers\Admin\PlanController::class, 'create'])->name('plans.create');
        Route::post('/plans', [App\Http\Controllers\Admin\PlanController::class, 'store'])->name('plans.store');
        Route::get('/plans/{plan}/edit', [App\Http\Controllers\Admin\PlanController::class, 'edit'])->name('plans.edit');
        Route::put('/plans/{plan}', [App\Http\Controllers\Admin\PlanController::class, 'update'])->name('plans.update');
        Route::delete('/plans/{plan}', [App\Http\Controllers\Admin\PlanController::class, 'destroy'])->name('plans.destroy');
        Route::patch('/plans/{plan}/toggle-status', [App\Http\Controllers\Admin\PlanController::class, 'toggleStatus'])->name('plans.toggle-status');
        Route::get('/plans/{plan}/subscriptions', [App\Http\Controllers\Admin\PlanController::class, 'subscriptions'])->name('plans.subscriptions');
        
        // Subscriptions Routes
        Route::get('/subscriptions', [App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::get('/subscriptions/create', [App\Http\Controllers\Admin\SubscriptionController::class, 'create'])->name('subscriptions.create');
        Route::post('/subscriptions', [App\Http\Controllers\Admin\SubscriptionController::class, 'store'])->name('subscriptions.store');
        Route::get('/subscriptions/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'show'])->name('subscriptions.show');
        Route::get('/subscriptions/{subscription}/edit', [App\Http\Controllers\Admin\SubscriptionController::class, 'edit'])->name('subscriptions.edit');
        Route::put('/subscriptions/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'update'])->name('subscriptions.update');
        Route::delete('/subscriptions/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
        Route::patch('/subscriptions/{subscription}/cancel', [App\Http\Controllers\Admin\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
        Route::post('/subscriptions/{subscription}/extend', [App\Http\Controllers\Admin\SubscriptionController::class, 'extend'])->name('subscriptions.extend');

        // User Management Routes
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Review moderation
        Route::get('/reviews', [App\Http\Controllers\Admin\ReviewModerationController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/dashboard', [App\Http\Controllers\Admin\ReviewModerationController::class, 'dashboard'])->name('reviews.dashboard');
        Route::get('/reviews/{id}', [App\Http\Controllers\Admin\ReviewModerationController::class, 'show'])->name('reviews.show');
        Route::patch('/reviews/{id}/approve', [App\Http\Controllers\Admin\ReviewModerationController::class, 'approve'])->name('reviews.approve');
        Route::get('/reviews/{id}/reject', [App\Http\Controllers\Admin\ReviewModerationController::class, 'rejectForm'])->name('reviews.reject-form');
        Route::patch('/reviews/{id}/reject', [App\Http\Controllers\Admin\ReviewModerationController::class, 'reject'])->name('reviews.reject');
        Route::delete('/reviews/{id}', [App\Http\Controllers\Admin\ReviewModerationController::class, 'destroy'])->name('reviews.destroy');

        // Bulk Import/Export Routes
        Route::get('bulk-import-export', [BulkImportExportController::class, 'index'])->name('bulk-import-export');
        Route::post('bulk-import', [BulkImportExportController::class, 'import'])->name('bulk-import');
        Route::get('bulk-export', [BulkImportExportController::class, 'export'])->name('bulk-export');
        Route::get('bulk-sample-download', [BulkImportExportController::class, 'downloadSample'])->name('bulk-sample-download');

        // Event routes
        Route::get('events', [App\Http\Controllers\Admin\EventController::class, 'index'])->name('events.index');
        Route::get('events/create', [App\Http\Controllers\Admin\EventController::class, 'create'])->name('events.create');
        Route::post('events', [App\Http\Controllers\Admin\EventController::class, 'store'])->name('events.store');
        Route::get('events/{event}/edit', [App\Http\Controllers\Admin\EventController::class, 'edit'])->name('events.edit');
        Route::put('events/{event}', [App\Http\Controllers\Admin\EventController::class, 'update'])->name('events.update');
        Route::delete('events/{event}', [App\Http\Controllers\Admin\EventController::class, 'destroy'])->name('events.destroy');
        Route::get('events/pending', [App\Http\Controllers\Admin\EventController::class, 'pending'])->name('events.pending');
        Route::post('events/{event}/approve', [App\Http\Controllers\Admin\EventController::class, 'approve'])->name('events.approve');
        Route::post('events/{event}/reject', [App\Http\Controllers\Admin\EventController::class, 'reject'])->name('events.reject');

        // Admin event participant routes
        Route::get('events/{event}/participants', [App\Http\Controllers\Admin\EventController::class, 'participants'])
            ->name('events.participants');

        // Static Content Management Routes (single events listing banner)
        Route::prefix('static-content')->name('static-content.')->group(function () {
            Route::get('manage-event', [App\Http\Controllers\Admin\StaticContentController::class, 'manageEvent'])->name('manage-event');
            Route::get('manage-event/edit', [App\Http\Controllers\Admin\StaticContentController::class, 'editFeaturedBanner'])->name('edit-featured-banner');
            Route::put('manage-event', [App\Http\Controllers\Admin\StaticContentController::class, 'updateFeaturedBanner'])->name('update-featured-banner');
            Route::delete('manage-event/banner', [App\Http\Controllers\Admin\StaticContentController::class, 'deleteFeaturedBanner'])->name('delete-featured-banner');

            // Homepage slider items (JD Slider)
            Route::get('home-slider', [App\Http\Controllers\Admin\HomeSliderController::class, 'index'])->name('home-slider.index');
            Route::get('home-slider/create', [App\Http\Controllers\Admin\HomeSliderController::class, 'create'])->name('home-slider.create');
            Route::post('home-slider', [App\Http\Controllers\Admin\HomeSliderController::class, 'store'])->name('home-slider.store');
            Route::get('home-slider/{item}/edit', [App\Http\Controllers\Admin\HomeSliderController::class, 'edit'])->name('home-slider.edit');
            Route::put('home-slider/{item}', [App\Http\Controllers\Admin\HomeSliderController::class, 'update'])->name('home-slider.update');
            Route::patch('home-slider/{item}/toggle', [App\Http\Controllers\Admin\HomeSliderController::class, 'toggleStatus'])->name('home-slider.toggle');
            Route::delete('home-slider/{item}', [App\Http\Controllers\Admin\HomeSliderController::class, 'destroy'])->name('home-slider.destroy');
        });

        // Admin event analytics route
        Route::get('events/{event}/analytics', [App\Http\Controllers\Admin\EventAnalyticsController::class, 'index'])
            ->name('events.analytics');
            
        // Admin event receipt download route
        Route::get('events/registration/{registration}/receipt', [App\Http\Controllers\Admin\EventController::class, 'downloadReceipt'])
            ->name('events.receipt.download');
            
        // Admin event registrations routes
        Route::get('event-registrations', [App\Http\Controllers\Admin\EventRegistrationController::class, 'index'])
            ->name('event-registrations.index');
        Route::get('event-registrations/filter', [App\Http\Controllers\Admin\EventRegistrationController::class, 'filter'])
            ->name('event-registrations.filter');
        Route::get('event-registrations/{registration}', [App\Http\Controllers\Admin\EventRegistrationController::class, 'show'])
            ->name('event-registrations.show');
        Route::get('event-registrations/{registration}/receipt', [App\Http\Controllers\Admin\EventRegistrationController::class, 'downloadReceipt'])
            ->name('event-registrations.receipt.download');
            
        Route::get('yc-ignites', [\App\Http\Controllers\Admin\YcIgniteController::class, 'index'])
            ->name('yc-ignites.index');
        
        Route::get('/yc-ignites/{id}', [App\Http\Controllers\Admin\YcIgniteController::class, 'show'])->name('yc-ignites.show');
            
        // Study Material Routes
        Route::prefix('study-materials')->name('study-materials.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\StudyMaterialController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\StudyMaterialController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\StudyMaterialController::class, 'store'])->name('store');
            Route::get('fetch-students', [App\Http\Controllers\Admin\StudyMaterialController::class, 'getStudents'])->name('get-students');
            Route::get('/{studyMaterial}/edit', [App\Http\Controllers\Admin\StudyMaterialController::class, 'edit'])->name('edit');
            Route::put('/{studyMaterial}', [App\Http\Controllers\Admin\StudyMaterialController::class, 'update'])->name('update');
            Route::get('/{studyMaterial}', [App\Http\Controllers\Admin\StudyMaterialController::class, 'show'])->name('show');
            Route::delete('/{studyMaterial}', [App\Http\Controllers\Admin\StudyMaterialController::class, 'destroy'])->name('destroy');
            Route::patch('/{studyMaterial}/toggle-status', [App\Http\Controllers\Admin\StudyMaterialController::class, 'toggleStatus'])->name('toggle-status');
        });
        // Model Question Papers (admin)
        Route::prefix('model-question-papers')->name('model-question-papers.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ModelQuestionPaperController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\ModelQuestionPaperController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\ModelQuestionPaperController::class, 'store'])->name('store');
            Route::get('fetch-students', [App\Http\Controllers\Admin\ModelQuestionPaperController::class, 'getStudents'])->name('get-students');
            Route::get('/{modelQuestionPaper}/edit', [App\Http\Controllers\Admin\ModelQuestionPaperController::class, 'edit'])->name('edit');
            Route::put('/{modelQuestionPaper}', [App\Http\Controllers\Admin\ModelQuestionPaperController::class, 'update'])->name('update');
            Route::get('/{modelQuestionPaper}', [App\Http\Controllers\Admin\ModelQuestionPaperController::class, 'show'])->name('show');
            Route::delete('/{modelQuestionPaper}', [App\Http\Controllers\Admin\ModelQuestionPaperController::class, 'destroy'])->name('destroy');
        });

        // Progress Tracking (admin)
        Route::prefix('progress-tracking')->name('progress-tracking.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ProgressTrackingController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\ProgressTrackingController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\ProgressTrackingController::class, 'store'])->name('store');
            Route::get('/{progressActivity}/edit', [App\Http\Controllers\Admin\ProgressTrackingController::class, 'edit'])->name('edit');
            Route::put('/{progressActivity}', [App\Http\Controllers\Admin\ProgressTrackingController::class, 'update'])->name('update');
            Route::delete('/{progressActivity}', [App\Http\Controllers\Admin\ProgressTrackingController::class, 'destroy'])->name('destroy');
        });
            
    });
});


Route::get('/yc-ignites/{id}/receipt', [\App\Http\Controllers\Admin\YcIgniteController::class, 'downloadReceipt'])
    ->middleware(['auth']) // only logged-in users can access
    ->name('yc-ignites.receipt');

// Vendor Routes
Route::name('vendor.')->group(function () {
    // Guest routes
    Route::middleware('guest')->group(function () {
        Route::get('/vendors/signin', [VendorAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/vendors/signin', [VendorAuthController::class, 'login']);
        
        Route::get('/vendors/signup', [VendorAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/vendors/signup', [VendorAuthController::class, 'register']);
    });
    
    // Protected routes
    Route::middleware(['auth', 'role:vendor'])->group(function () {
        // Route for the subscription required page - accessible without subscription
        Route::get('/subscription-required', [App\Http\Controllers\Vendor\SubscriptionController::class, 'showRequiredPage'])->name('subscription.required');
        
        // Subscription management routes - accessible without active subscription
        Route::prefix('vendor/subscriptions')->name('subscriptions.')->group(function () {
            Route::get('/plans', [App\Http\Controllers\Vendor\SubscriptionController::class, 'plans'])->name('plans');
            Route::get('/history', [App\Http\Controllers\Vendor\SubscriptionController::class, 'history'])->name('history');
            // Current subscription might need check, keep outside for now
            Route::get('/current', [App\Http\Controllers\Vendor\SubscriptionController::class, 'current'])->name('current'); 
            Route::get('/plans/{plan}/checkout', [App\Http\Controllers\Vendor\SubscriptionController::class, 'checkout'])->name('checkout');
            Route::post('/plans/{plan}/process', [App\Http\Controllers\Vendor\SubscriptionController::class, 'process'])->name('process');
            Route::get('/{subscription}/success', [App\Http\Controllers\Vendor\SubscriptionController::class, 'success'])->name('success');
            // Cancel might need check, keep outside for now
            Route::post('/cancel', [App\Http\Controllers\Vendor\SubscriptionController::class, 'cancel'])->name('cancel'); 
        });

        // Routes requiring subscription will go inside the next group
        Route::prefix('vendor')->middleware('vendor.subscribed')->group(function () {
            Route::get('/dashboard', function () {
                $businesses = \App\Models\Business::where('claimed_by', auth()->id())
                    ->with(['subcategory.category'])
                    ->latest()
                    ->take(5)
                    ->get();
                return view('vendor.dashboard', compact('businesses'));
            })->name('dashboard');
            
            Route::post('/logout', [VendorAuthController::class, 'logout'])->name('logout');
            
            // Business management
            Route::get('businesses/subcategories', [App\Http\Controllers\Vendor\BusinessController::class, 'getSubcategories'])->name('businesses.subcategories');
            Route::get('businesses/cities', [App\Http\Controllers\Vendor\BusinessController::class, 'getCities'])->name('businesses.cities');
            Route::get('businesses/areas', [App\Http\Controllers\Vendor\BusinessController::class, 'getAreas'])->name('businesses.areas');
            Route::get('businesses/areas-search', [App\Http\Controllers\Vendor\BusinessController::class, 'searchAreas'])->name('businesses.areas-search');
            Route::resource('businesses', App\Http\Controllers\Vendor\BusinessController::class);
            
            // Business images management
            Route::post('businesses/{id}/images', [App\Http\Controllers\Vendor\BusinessController::class, 'uploadImage'])->name('businesses.images.upload');
            Route::patch('businesses/images/{id}/primary', [App\Http\Controllers\Vendor\BusinessController::class, 'setPrimaryImage'])->name('businesses.images.primary');
            Route::delete('businesses/images/{id}', [App\Http\Controllers\Vendor\BusinessController::class, 'deleteImage'])->name('businesses.images.delete');
            
            // Business claim routes
            Route::get('/available-claims', [App\Http\Controllers\Vendor\ClaimController::class, 'index'])->name('claims.index');
            Route::get('/my-claims', [App\Http\Controllers\Vendor\ClaimController::class, 'myClaims'])->name('claims.myClaims');
            Route::get('/claim-business/{business}', [App\Http\Controllers\Vendor\ClaimController::class, 'create'])->name('claims.create');
            Route::post('/claim-business/{business}', [App\Http\Controllers\Vendor\ClaimController::class, 'store'])->name('claims.store');
            Route::get('/claims/{claim}', [App\Http\Controllers\Vendor\ClaimController::class, 'show'])->name('claims.show');
            
            // Reviews
            Route::get('/reviews', [App\Http\Controllers\Vendor\ReviewController::class, 'index'])->name('reviews.index');
            Route::get('/reviews/dashboard', [App\Http\Controllers\Vendor\ReviewController::class, 'dashboard'])->name('reviews.dashboard');
            Route::get('/reviews/{id}', [App\Http\Controllers\Vendor\ReviewController::class, 'show'])->name('reviews.show');
            Route::get('/reviews/{id}/respond', [App\Http\Controllers\Vendor\ReviewController::class, 'respondForm'])->name('reviews.respond-form');
            Route::post('/reviews/{id}/respond', [App\Http\Controllers\Vendor\ReviewController::class, 'respond'])->name('reviews.respond');
            Route::patch('/reviews/{id}/respond', [App\Http\Controllers\Vendor\ReviewController::class, 'updateResponse'])->name('reviews.update-response');
            Route::delete('/reviews/{id}/respond', [App\Http\Controllers\Vendor\ReviewController::class, 'deleteResponse'])->name('reviews.delete-response');

            // Event routes
            Route::get('events', [App\Http\Controllers\Vendor\EventController::class, 'index'])->name('events.index');
            Route::get('events/create', [App\Http\Controllers\Vendor\EventController::class, 'create'])->name('events.create');
            Route::post('events', [App\Http\Controllers\Vendor\EventController::class, 'store'])->name('events.store');
            Route::get('events/{event}/edit', [App\Http\Controllers\Vendor\EventController::class, 'edit'])->name('events.edit');
            Route::put('events/{event}', [App\Http\Controllers\Vendor\EventController::class, 'update'])->name('events.update');
            Route::delete('events/{event}', [App\Http\Controllers\Vendor\EventController::class, 'destroy'])->name('events.destroy');

            // Vendor event participant routes
            Route::get('events/{event}/registrations', [App\Http\Controllers\Vendor\EventController::class, 'registrations'])
                ->name('events.registrations');
            Route::get('events/{event}/registrations/{registration}', [App\Http\Controllers\Vendor\EventController::class, 'showRegistration'])
                ->name('events.registrations.show');

            // Vendor event analytics route
            Route::get('vendor/events/{event}/analytics', [App\Http\Controllers\Vendor\EventAnalyticsController::class, 'index'])
                ->name('vendor.events.analytics');

            // Vendor event registrations routes
            Route::get('event-registrations', [App\Http\Controllers\Vendor\EventController::class, 'get_registrations'])
                ->name('event-registrations.get_registrations');
            Route::get('event-registrations/filter', [App\Http\Controllers\Vendor\EventController::class, 'filter'])
                ->name('event-registrations.filter');
            Route::get('event-registrations/{registration}', [App\Http\Controllers\Vendor\EventController::class, 'show'])
                ->name('event-registrations.show');
            Route::get('event-registrations/{registration}/receipt', [App\Http\Controllers\Vendor\EventController::class, 'downloadReceipt'])
                ->name('event-registrations.receipt.download');
            });
    });
});

// User Routes
Route::name('user.')->group(function () {
    // Guest routes
    Route::middleware('guest')->group(function () {
        Route::get('/signin', [UserAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/signin', [UserAuthController::class, 'login']);
        
        Route::get('/signup', [UserAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/signup', [UserAuthController::class, 'register']);
    });
    
    // Protected routes
    Route::middleware(['auth', 'role:user'])->group(function () {
        Route::get('/dashboard', function () {
            $registrations = \App\Models\EventRegistration::where('user_id', Auth::id())
                ->with('event')
                ->latest()
                ->take(5)
                ->get();
            $createdEvents = \App\Models\Event::where('created_by', Auth::id())
                ->latest()
                ->take(5)
                ->get();
            
            $activities = collect();
            foreach ($registrations as $reg) {
                $activities->push((object)[
                    'type' => 'registration',
                    'title' => 'Registered for: ' . ($reg->event ? $reg->event->title : 'Deleted Event'),
                    'date' => $reg->created_at,
                    'status' => $reg->payment_status ?? 'N/A'
                ]);
            }
            
            foreach ($createdEvents as $evt) {
                $activities->push((object)[
                    'type' => 'creation',
                    'title' => 'Created Event: ' . $evt->title,
                    'date' => $evt->created_at,
                    'status' => $evt->status ?? 'pending'
                ]);
            }
            
            $activities = $activities->sortByDesc('date')->take(5);

            return view('user.dashboard', compact('activities'));
        })->name('dashboard');
        
        Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

        // Reviews
        Route::get('/reviews', [App\Http\Controllers\User\ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/create/{businessId}', [App\Http\Controllers\User\ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/reviews', [App\Http\Controllers\User\ReviewController::class, 'store'])->name('reviews.store');
        Route::get('/reviews/{id}', [App\Http\Controllers\User\ReviewController::class, 'show'])->name('reviews.show');
        Route::get('/reviews/{id}/edit', [App\Http\Controllers\User\ReviewController::class, 'edit'])->name('reviews.edit');
        Route::put('/reviews/{id}', [App\Http\Controllers\User\ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{id}', [App\Http\Controllers\User\ReviewController::class, 'destroy'])->name('reviews.destroy');

        // User Event Management (General Events Only)
        Route::resource('my-events', App\Http\Controllers\User\EventController::class)->parameters(['my-events' => 'event'])->names('my-events');
        Route::get('/my-events/{event}/registrations', [App\Http\Controllers\User\EventController::class, 'registrations'])
            ->name('my-events.registrations');
        Route::get('/my-events/{event}/registrations/{registration}', [App\Http\Controllers\User\EventController::class, 'showRegistration'])
            ->name('my-events.registrations.show');

        // Authenticated User Event Registration routes (Success, Failure, My Registrations, Receipt Download)
        Route::prefix('events')->name('events.')->group(function () {
            // Note: payment.show and register.process were moved outside auth middleware
            Route::post('/payment/handle', [App\Http\Controllers\User\EventRegistrationController::class, 'handlePayment'])->name('payment.handle');
            Route::get('/registration/{registration}/success', [App\Http\Controllers\User\EventRegistrationController::class, 'success'])->name('success');
            Route::get('/registration/{registration}/failure', [App\Http\Controllers\User\EventRegistrationController::class, 'failure'])->name('failure');
            Route::get('/registration/{registration}/receipt', [App\Http\Controllers\User\EventRegistrationController::class, 'downloadReceipt'])->name('receipt.download');
            Route::get('/my-registrations', [App\Http\Controllers\User\EventRegistrationController::class, 'userRegistrations'])
                // ->middleware(['auth', 'role:user']) // Middleware already applied by parent group
                ->name('my-registrations');
        });
        
        Route::get('/my-yc-ignite', [\App\Http\Controllers\User\YcIgniteController::class, 'myRegistrations'])
        ->name('yc-ignite.index');
    });
    
    // Add the route that matches what the template is using
    Route::post('/events/payment/handle', [App\Http\Controllers\User\EventRegistrationController::class, 'handlePayment'])->name('events.payment.handle');
});

// Student Routes
Route::prefix('student')->name('student.')->group(function () {
    // Guest routes
    Route::middleware('guest:student')->group(function () {
        // Route::get('/signin', [\App\Http\Controllers\Student\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/send-otp', [\App\Http\Controllers\Student\AuthController::class, 'sendOtp'])->name('send-otp');
        Route::get('/otp', [\App\Http\Controllers\Student\AuthController::class, 'showOtpForm'])->name('otp');
        Route::post('/verify-otp', [\App\Http\Controllers\Student\AuthController::class, 'verifyOtp'])->name('verify-otp');
    });
    
    // Protected routes
    Route::middleware(['auth:student'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
        // Notifications
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\Student\NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/{id}/unread', [\App\Http\Controllers\Student\NotificationController::class, 'markUnread'])->name('notifications.unread');
        
        Route::get('/study-materials', [\App\Http\Controllers\Student\StudyMaterialController::class, 'index'])->name('study-materials.index');
        Route::get('/study-materials/{studyMaterial}/view', [\App\Http\Controllers\Student\StudyMaterialController::class, 'view'])->name('study-materials.view');
        Route::get('/study-materials/{studyMaterial}/download', [\App\Http\Controllers\Student\StudyMaterialController::class, 'download'])->name('study-materials.download');
        
        // Student model question papers
        Route::get('/model-question-papers', [\App\Http\Controllers\Student\ModelQuestionPaperController::class, 'index'])->name('model-question-papers.index');
        Route::get('/model-question-papers/{modelQuestionPaper}/view', [\App\Http\Controllers\Student\ModelQuestionPaperController::class, 'view'])->name('model-question-papers.view');
        Route::get('/model-question-papers/{modelQuestionPaper}/download', [\App\Http\Controllers\Student\ModelQuestionPaperController::class, 'download'])->name('model-question-papers.download');
        Route::post('/model-question-papers/{modelQuestionPaper}/complete', [\App\Http\Controllers\Student\ModelQuestionPaperController::class, 'markCompleted'])->name('model-question-papers.complete');
        
        // Student progress tracker
        Route::get('/progress-tracker', [\App\Http\Controllers\Student\ProgressTrackerController::class, 'index'])->name('progress-tracker.index');
        
        Route::post('/logout', [\App\Http\Controllers\Student\AuthController::class, 'logout'])->name('logout');
    });
});

// OTP Authentication Routes
Route::prefix('auth')->name('otp.')->group(function () {
    Route::get('/otp-login', [OtpController::class, 'showOtpLoginForm'])->name('login');
    Route::post('/send-otp', [OtpController::class, 'sendOtp'])->name('send');
    Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('verify');
    Route::post('/resend-otp', [OtpController::class, 'resendOtp'])->name('resend');
});

// Default redirects
Route::get('/login', function () {
    return redirect()->route('user.login');
})->name('login');

Route::get('/register', function () {
    return redirect()->route('user.register');
})->name('register');

// Fallback logout route
Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');

// Business directory routes (public)
Route::get('/directory', function() {
    return redirect()->route('categories.all');
})->name('directory.index');
Route::get('/directory/business/{business}', [DirectoryController::class, 'show'])->name('directory.show');
Route::get('/directory/category/{category}', [DirectoryController::class, 'byCategory'])->name('directory.category');
Route::get('/directory/subcategory/{subcategory}', function(App\Models\Subcategory $subcategory) {
    return redirect()->route('listings', $subcategory);
})->name('directory.subcategory');
Route::get('/directory/area/{area}', [DirectoryController::class, 'byArea'])->name('directory.area');

// Public category page
Route::get('/category/{category}', [PublicController::class, 'category'])->name('public.category');
Route::get('/listings/{subcategory}', [PublicController::class, 'listings'])->name('listings');
Route::get('/listings/{subcategory}/load-more', [PublicController::class, 'loadMoreListings'])->name('listings.load-more');
Route::get('/business/{business}', [PublicBusinessController::class, 'show'])->name('public.business.show');

// Search route
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Contact form submission
Route::post('/contact', [PublicController::class, 'contactSubmit'])->name('contact.submit');

// Password Reset Routes
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.update');

// Public event routes
Route::get('/events', [App\Http\Controllers\PublicEventController::class, 'index'])->name('events.index');
Route::get('/events/registration/{registration}/payment', [App\Http\Controllers\User\EventRegistrationController::class, 'showPaymentPage'])->name('events.payment.show');
Route::get('/events/{event}', [App\Http\Controllers\PublicEventController::class, 'show'])->name('events.show');
Route::get('/events/{event}/result', [App\Http\Controllers\PublicEventController::class, 'result'])->name('events.result');
Route::get('/events/terms/parent-guardian-consent', [App\Http\Controllers\PublicEventController::class, 'parentGuardianConsent'])->name('events.parent-guardian-consent');

Route::get('/general_events/terms/general-parent-guardian-consent', [App\Http\Controllers\PublicEventController::class, 'GeneralparentGuardianConsent'])->name('events.general-parent-guardian-consent');

Route::get('/events/list/faq', [App\Http\Controllers\PublicEventController::class, 'faqs'])->name('events.faqs');
Route::get('/events/yc_ignite/{event}', [App\Http\Controllers\PublicEventController::class, 'yc_ignite_show'])->name('events.yc_ignite');



// Razorpay Payment Routes
Route::prefix('razorpay')->name('razorpay.')->group(function () {
    Route::get('/checkout/{planId}', [RazorpayController::class, 'checkout'])
        ->middleware(['auth'])
        ->name('checkout');
    
    Route::post('/callback', [RazorpayController::class, 'paymentCallback'])
        ->name('callback');
    
    Route::get('/success/{subscription}', [RazorpayController::class, 'success'])
        ->middleware(['auth'])
        ->name('success');
    
    Route::get('/failed', [RazorpayController::class, 'failed'])
        ->name('failed');
    
    Route::post('/webhook', [RazorpayController::class, 'webhook'])
        ->name('webhook');
});

// Map these routes to simpler names for the controller to use
Route::get('/subscription/success/{subscription}', function($subscription) {
    return redirect()->route('razorpay.success', $subscription);
})->name('subscription.success');

Route::get('/subscription/failed', function() {
    return redirect()->route('razorpay.failed');
})->name('subscription.failed');

// --- Public Event Registration Routes ---
Route::get('/events/{event}/register', [App\Http\Controllers\User\EventRegistrationController::class, 'registerForm'])
    ->name('events.register'); // Form display

Route::post('/events/{event}/register', [App\Http\Controllers\User\EventRegistrationController::class, 'processRegistration'])
    ->name('events.register.process'); // Form submission

Route::post('/events/{event}/book', [App\Http\Controllers\User\EventRegistrationController::class, 'processBook'])
    ->name('events.book.process');
    
Route::get('/events/{event}/verify-otp', [App\Http\Controllers\User\EventRegistrationController::class, 'showOtpForm'])->name('events.register.verifyOtp');
Route::post('/events/{event}/verify-otp', [App\Http\Controllers\User\EventRegistrationController::class, 'verifyOtp'])->name('events.register.verifyOtp.process');

Route::post('/yc-ignite/register', [YcIgniteController::class, 'store'])->name('yc-ignite.store');

Route::get('/yc-ignite/verify-otp', function() {
    return view('public.events.yc_ignite.verify-otp'); 
    })->name('yc_ignite.verifyOtp');

// Step 3: Submit OTP → completes registration
Route::post('/yc-ignite/verify-otp', [YcIgniteController::class, 'verifyOtpSubmit'])
    ->name('yc_ignite.verifyOtp.submit');

// --- Removed duplicate/fallback routes ---

/*
 * Location API routes to make dropdowns work
 */
Route::get('/api/states/{state}/cities', [App\Http\Controllers\LocationController::class, 'getCities']);
Route::get('/api/cities/{city}/areas', [App\Http\Controllers\LocationController::class, 'getAreas']);

/*
 * Fallback routes for location dropdowns (in case API routes are not working)
 */
Route::get('/states/{state}/cities', [App\Http\Controllers\LocationController::class, 'getCities']);
Route::get('/cities/{city}/areas', [App\Http\Controllers\LocationController::class, 'getAreas']);

// Debug routes (only available in non-production environments)
if (app()->environment(['local', 'development', 'testing'])) {
    Route::get('/debug/registration/{id}', [App\Http\Controllers\User\EventRegistrationController::class, 'debugRegistration']);
}

// Location routes
Route::prefix('location')->name('location.')->group(function () {
    Route::get('/states', [LocationController::class, 'getStates'])->name('states');
    Route::get('/states/{state}/cities', [LocationController::class, 'getCities'])->name('cities');
    Route::get('/cities/{city}/areas', [LocationController::class, 'getAreas'])->name('areas');
    Route::post('/auto-detect', [LocationController::class, 'autoDetect'])->name('auto-detect');
    Route::post('/set', [LocationController::class, 'setLocation'])->name('set');
    Route::post('/clear', [LocationController::class, 'clearLocation'])->name('clear');
    Route::get('/current', [LocationController::class, 'getCurrentLocation'])->name('current');
});

// Location
Route::post('/update-location', [PublicController::class, 'updateLocation'])->name('location.update');



// ===================== Dynamic Sitemaps =====================
// Sitemap Index
Route::get('/sitemaps.xml', function() {
    $ttl = 60 * 60 * 24; // 24 hours

    $xml = Cache::remember('sitemap:index', $ttl, function () {
        $businessCount = Business::where('status', 'active')->count();
        $perPage = 5000; // URLs per sitemap file (well below 50k limit)
        $pages = (int) ceil(max(1, $businessCount) / $perPage);

        $now = now()->toAtomString();

        $parts = [];
        $parts[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $parts[] = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Core sitemaps
        $coreSitemaps = [
            url('/sitemaps-static.xml'),
            url('/sitemaps-categories.xml'),
            url('/sitemaps-subcategories.xml'),
            url('/sitemaps-listings.xml'),
        ];
        foreach ($coreSitemaps as $loc) {
            $parts[] = '<sitemap><loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc><lastmod>' . $now . '</lastmod></sitemap>';
        }

        // Businesses sitemaps (paginated)
        for ($i = 1; $i <= $pages; $i++) {
            $loc = url('/sitemaps-businesses-' . $i . '.xml');
            $parts[] = '<sitemap><loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc><lastmod>' . $now . '</lastmod></sitemap>';
        }

        $parts[] = '</sitemapindex>';

        return implode("\n", $parts);
    });

    return response($xml, 200)->header('Content-Type', 'application/xml');
});

// Static pages sitemap
Route::get('/sitemaps-static.xml', function() {
    $ttl = 60 * 60 * 24;

    $xml = Cache::remember('sitemap:static', $ttl, function () {
        $now = now()->toAtomString();
        $urls = [
            route('home'),
            route('about'),
            route('contact'),
            route('privacy.policy'),
            route('terms.of.use'),
            route('refund.policy'),
            route('infringement.policy'),
            route('categories.all'),
            route('about.yc.spark'),
        ];

        $parts = [];
        $parts[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $parts[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as $loc) {
            $parts[] = '<url><loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc><lastmod>' . $now . '</lastmod><changefreq>daily</changefreq><priority>0.8</priority></url>';
        }
        $parts[] = '</urlset>';

        return implode("\n", $parts);
    });

    return response($xml, 200)->header('Content-Type', 'application/xml');
});

// Categories sitemap
Route::get('/sitemaps-categories.xml', function() {
    $ttl = 60 * 60 * 24;

    $xml = Cache::remember('sitemap:categories', $ttl, function () {
        $categories = Category::query()->select(['id', 'updated_at'])->orderBy('id')->get();
        $parts = [];
        $parts[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $parts[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($categories as $category) {
            $loc = route('public.category', ['category' => $category->id]);
            $lastmod = optional($category->updated_at)->toAtomString() ?? now()->toAtomString();
            $parts[] = '<url><loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc><lastmod>' . $lastmod . '</lastmod><changefreq>daily</changefreq><priority>0.7</priority></url>';
        }
        $parts[] = '</urlset>';

        return implode("\n", $parts);
    });

    return response($xml, 200)->header('Content-Type', 'application/xml');
});

// Subcategories sitemap
Route::get('/sitemaps-subcategories.xml', function() {
    $ttl = 60 * 60 * 24;

    $xml = Cache::remember('sitemap:subcategories', $ttl, function () {
        $subcategories = Subcategory::query()->select(['id', 'updated_at'])->orderBy('id')->get();
        $parts = [];
        $parts[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $parts[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($subcategories as $subcategory) {
            $loc = url('/directory/subcategory/' . $subcategory->id);
            $lastmod = optional($subcategory->updated_at)->toAtomString() ?? now()->toAtomString();
            $parts[] = '<url><loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc><lastmod>' . $lastmod . '</lastmod><changefreq>daily</changefreq><priority>0.6</priority></url>';
        }
        $parts[] = '</urlset>';

        return implode("\n", $parts);
    });

    return response($xml, 200)->header('Content-Type', 'application/xml');
});

// Listings sitemap (subcategories listings pages)
Route::get('/sitemaps-listings.xml', function() {
    $ttl = 60 * 60 * 24;

    $xml = Cache::remember('sitemap:listings', $ttl, function () {
        $subcategories = Subcategory::query()->select(['id', 'updated_at'])->orderBy('id')->get();
        $parts = [];
        $parts[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $parts[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($subcategories as $subcategory) {
            $loc = route('listings', ['subcategory' => $subcategory->id]);
            $lastmod = optional($subcategory->updated_at)->toAtomString() ?? now()->toAtomString();
            $parts[] = '<url><loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc><lastmod>' . $lastmod . '</lastmod><changefreq>daily</changefreq><priority>0.6</priority></url>';
        }
        $parts[] = '</urlset>';

        return implode("\n", $parts);
    });

    return response($xml, 200)->header('Content-Type', 'application/xml');
});

// Businesses sitemap (paginated)
Route::get('/sitemaps-businesses-{page}.xml', function(int $page) {
    $ttl = 60 * 60 * 24;

    $cacheKey = 'sitemap:businesses:' . $page;
    $xml = Cache::remember($cacheKey, $ttl, function () use ($page) {
        $perPage = 5000;
        $offset = ($page - 1) * $perPage;

        if ($offset < 0) {
            abort(404);
        }

        $businesses = Business::query()
            ->where('status', 'active')
            ->orderBy('id')
            ->skip($offset)
            ->take($perPage)
            ->get(['id', 'updated_at']);

        if ($businesses->isEmpty() && $page !== 1) {
            abort(404);
        }

        $parts = [];
        $parts[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $parts[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($businesses as $business) {
            $loc = route('public.business.show', ['business' => $business->id]);
            $lastmod = optional($business->updated_at)->toAtomString() ?? now()->toAtomString();
            $parts[] = '<url><loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc><lastmod>' . $lastmod . '</lastmod><changefreq>daily</changefreq><priority>0.5</priority></url>';
        }
        $parts[] = '</urlset>';

        return implode("\n", $parts);
    });

    return response($xml, 200)->header('Content-Type', 'application/xml');
});
// =================== End Dynamic Sitemaps ===================
