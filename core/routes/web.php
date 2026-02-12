<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Emergency Cache Clear Route
Route::get('/fix-config', function () {
    try {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return '<h1>✅ Önbellekler Temizlendi (Config, Cache, View, Route).</h1><p>Şimdi sayfayı yenileyip tekrar deneyin.</p>';
    } catch (\Exception $e) {
        return 'Hata: '.$e->getMessage();
    }
});

// SEO Routes (Must be at top for search engines)
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [\App\Http\Controllers\SitemapController::class, 'robots'])->name('robots');
Route::get('/site-haritasi', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Blog Routes
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

// Admin Blog Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/blog', [\App\Http\Controllers\Admin\BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [\App\Http\Controllers\Admin\BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [\App\Http\Controllers\Admin\BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{post}/edit', [\App\Http\Controllers\Admin\BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{post}', [\App\Http\Controllers\Admin\BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{post}', [\App\Http\Controllers\Admin\BlogController::class, 'destroy'])->name('blog.destroy');

    Route::get('/blog/categories', [\App\Http\Controllers\Admin\BlogController::class, 'categories'])->name('blog.categories');
    Route::post('/blog/categories', [\App\Http\Controllers\Admin\BlogController::class, 'storeCategory'])->name('blog.categories.store');
});

Route::get('/', function () {
    $businesses = \Illuminate\Support\Facades\Cache::remember('home_featured_businesses', 3600, function () {
        return \App\Models\Business::where('is_active', true)->with(['category', 'approvedReviews'])->take(6)->get();
    });

    return view('home', compact('businesses'));
})->name('home');

Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search.index');

Route::get('/api/autocomplete', [\App\Http\Controllers\AutocompleteController::class, 'search'])->name('autocomplete');

// Static Pages
Route::redirect('/explore', '/search');
Route::get('/business-partner', [\App\Http\Controllers\PageController::class, 'business'])->name('pages.business');
Route::get('/rezervista-pos', [\App\Http\Controllers\PageController::class, 'rezervistaPos'])->name('pages.pos');
Route::get('/rezervista-pos/versions', function () {
    return view('pages.pos-versions');
})->name('pages.pos.versions');
Route::get('/help', [\App\Http\Controllers\PageController::class, 'help'])->name('pages.help');
Route::get('/campaigns', [\App\Http\Controllers\PageController::class, 'campaigns'])->name('pages.campaigns');
Route::get('/privacy-policy', [\App\Http\Controllers\PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/contact', [\App\Http\Controllers\PageController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [\App\Http\Controllers\PageController::class, 'submitContact'])->name('pages.contact.submit')->middleware('throttle:5,1');

Route::get('/live-support', [\App\Http\Controllers\PageController::class, 'liveSupport'])->name('pages.live-support');
Route::post('/live-support', [\App\Http\Controllers\PageController::class, 'submitLiveSupport'])->name('pages.live-support.submit');
Route::redirect('/support', '/live-support');

// Business Application Routes
Route::get('/business/apply', [\App\Http\Controllers\BusinessController::class, 'showApplyForm'])->name('business.apply');
Route::post('/business/apply', [\App\Http\Controllers\BusinessController::class, 'apply'])->name('business.apply.submit');
Route::get('/business/application/status', [\App\Http\Controllers\BusinessController::class, 'applicationStatus'])->middleware('auth')->name('business.application.status');
Route::get('/business/application/edit', [\App\Http\Controllers\BusinessController::class, 'editApplication'])->middleware('auth')->name('business.application.edit');
Route::put('/business/application/update', [\App\Http\Controllers\BusinessController::class, 'updateApplication'])->middleware('auth')->name('business.application.update');

Route::get('/terms', [\App\Http\Controllers\PageController::class, 'terms'])->name('pages.terms');
Route::get('/contracts', [\App\Http\Controllers\PageController::class, 'contracts'])->name('pages.contracts');
Route::get('/docs', [\App\Http\Controllers\PageController::class, 'docs'])->name('pages.docs');
Route::get('/about', [\App\Http\Controllers\PageController::class, 'about'])->name('pages.about');
Route::get('/cookies', [\App\Http\Controllers\PageController::class, 'cookies'])->name('pages.cookies');
Route::get('/careers', [\App\Http\Controllers\PageController::class, 'careers'])->name('pages.careers');
Route::get('/story', [\App\Http\Controllers\PageController::class, 'story'])->name('pages.story');

Route::get('/dashboard', [\App\Http\Controllers\VendorController::class, 'dashboard'])
    ->middleware(['auth', 'role:business,admin'])
    ->name('vendor.dashboard');

Route::get('/business/{business}', [\App\Http\Controllers\BusinessController::class, 'show'])->name('business.show');
Route::get('/business/{business}/og-image', [\App\Http\Controllers\BusinessController::class, 'ogImage'])->name('business.og-image');

// Auth Routes
Route::get('/login', [\App\Http\Controllers\WebAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\WebAuthController::class, 'login'])->middleware('throttle:5,1');

Route::get('/register', [\App\Http\Controllers\WebAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\WebAuthController::class, 'register'])->middleware('throttle:3,1');

Route::post('/logout', [\App\Http\Controllers\WebAuthController::class, 'logout'])->name('logout');

// OTP Verification Routes
Route::get('/register/verify', [\App\Http\Controllers\WebAuthController::class, 'showVerifyForm'])->name('register.verify');
Route::post('/register/verify', [\App\Http\Controllers\WebAuthController::class, 'verifyRegistration'])->name('register.verify.submit');
Route::post('/register/resend-otp', [\App\Http\Controllers\WebAuthController::class, 'resendOTP'])->name('register.resend-otp');

// Social Authentication Routes
Route::get('auth/{provider}/redirect', [\App\Http\Controllers\SocialAuthController::class, 'redirectToProvider'])->name('social.redirect');
Route::get('auth/{provider}/callback', [\App\Http\Controllers\SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', [\App\Http\Controllers\WebAuthController::class, 'sendResetCode'])->middleware('guest')->name('password.email');
Route::get('/password-reset/verify', [\App\Http\Controllers\WebAuthController::class, 'showResetForm'])->middleware('guest')->name('password.reset.verify');
Route::post('/password-reset/verify', [\App\Http\Controllers\WebAuthController::class, 'verifyResetCode'])->middleware('guest')->name('password.reset.submit');

// Phone Verification Routes
Route::post('/api/phone/send-code', [\App\Http\Controllers\PhoneVerificationController::class, 'sendCode'])->name('phone.send-code');
Route::post('/api/phone/verify-code', [\App\Http\Controllers\PhoneVerificationController::class, 'verifyCode'])->name('phone.verify-code');
Route::post('/api/phone/resend-code', [\App\Http\Controllers\PhoneVerificationController::class, 'resendCode'])->name('phone.resend-code');

// Booking Flow
Route::post('/iyzico/webhook', [\App\Http\Controllers\IyzicoWebhookController::class, 'handle'])->name('iyzico.webhook');

Route::middleware(['auth'])->group(function () {
    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/reservations', [\App\Http\Controllers\ProfileController::class, 'reservations'])->name('profile.reservations');
    Route::post('/profile/photo', [\App\Http\Controllers\ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::get('/profile/favorites', [\App\Http\Controllers\ProfileController::class, 'favorites'])->name('profile.favorites');
    Route::get('/profile/support', [ProfileController::class, 'support'])->name('profile.support');
    Route::post('/profile/support/{id}/reply', [ProfileController::class, 'replyToSupport'])->name('profile.support.reply');
    Route::get('/profile/referrals', [ProfileController::class, 'referrals'])->name('profile.referrals');

    // Wallet Routes
    Route::get('/profile/wallet', [WalletController::class, 'index'])->name('profile.wallet.index');
    Route::post('/profile/wallet/topup', [WalletController::class, 'initiateTopup'])->name('wallet.topup');

    Route::post('/favorites/toggle/{businessId}', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/favorites/sync', [\App\Http\Controllers\FavoriteController::class, 'sync'])->name('favorites.sync');

    // Notification Routes
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::match(['get', 'post'], '/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::match(['get', 'post'], '/notifications/{id}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markRead');

    // Payment Routes
    // Route::get('/payment/checkout/{reservation}', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout'); // Moved to public for Signed URL access

    // Invoice Routes
    Route::get('/reservations/{reservation}/invoice', [\App\Http\Controllers\InvoiceController::class, 'download'])->name('reservations.invoice');
    Route::get('/reservations/{reservation}/invoice/stream', [\App\Http\Controllers\InvoiceController::class, 'stream'])->name('reservations.invoice.stream');
    // Booking Flow
    Route::get('/book/{business}', [\App\Http\Controllers\BookController::class, 'index'])->name('booking.checkout');
    Route::post('/book/{business}', [\App\Http\Controllers\BookController::class, 'store'])->name('booking.store');
    // Route::get('/book/confirmation/{id}', [\App\Http\Controllers\BookController::class, 'confirmation'])->name('booking.confirmation'); // Moved outside

    // Reservation Management
    Route::post('/reservations', [\App\Http\Controllers\ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservations/{id}', [\App\Http\Controllers\ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::put('/reservations/{id}', [\App\Http\Controllers\ReservationController::class, 'update'])->name('reservations.update');

    // Web Push Routes
    Route::post('/api/push/subscribe', [\App\Http\Controllers\PushSubscriptionController::class, 'subscribe'])->name('push.subscribe');
    Route::post('/api/push/unsubscribe', [\App\Http\Controllers\PushSubscriptionController::class, 'unsubscribe'])->name('push.unsubscribe');

    // Coupon Validation
    Route::post('/api/coupons/validate', [\App\Http\Controllers\CouponController::class, 'validateCoupon'])->name('coupons.validate');

    // Messaging
    Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{id}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.chat');
    Route::post('/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');

    // Comments
    Route::post('/blog/{post}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
});

// Wallet Callback (Public POST from iyzico)
Route::post('/profile/wallet/callback', [WalletController::class, 'callback'])->name('wallet.callback')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Magic Login (Public GET bridge to restore session)
Route::get('/profile/wallet/magic-login', [WalletController::class, 'magicLogin'])->name('wallet.magic-login');

// Public Payment Callback (Must be outside auth due to cross-site POST removing cookies)
Route::get('/payment/checkout/{reservation}', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');
Route::post('/payment/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/payment/magic-login', [\App\Http\Controllers\PaymentController::class, 'magicLogin'])->name('payment.magic-login');
Route::get('/book/confirmation/{id}', [\App\Http\Controllers\BookController::class, 'confirmation'])->name('booking.confirmation');

// Public Billing Callback (Cross-site POST bridge)
Route::match(['get', 'post'], '/billing/callback', [\App\Http\Controllers\BillingController::class, 'callback'])->name('billing.callback')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/billing/magic-login', [\App\Http\Controllers\BillingController::class, 'magicLogin'])->name('billing.magic-login');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users.index');
    Route::get('/users/search', [\App\Http\Controllers\AdminController::class, 'searchUsers'])->name('users.search');
    Route::get('/applications', [\App\Http\Controllers\AdminController::class, 'applications'])->name('applications.index');
    Route::get('/applications/{id}', [\App\Http\Controllers\AdminController::class, 'showApplication'])->name('applications.show');
    Route::put('/applications/{id}', [\App\Http\Controllers\AdminController::class, 'updateApplication'])->name('applications.update');
    Route::get('/applications/{id}/document/{field}', [\App\Http\Controllers\AdminController::class, 'viewDocument'])->name('applications.document');

    // New Routes
    Route::get('/platform-activity', [\App\Http\Controllers\AdminController::class, 'platformActivity'])->name('platform-activity.index');
    Route::get('/platform-activity/export', [\App\Http\Controllers\AdminController::class, 'exportActivityLog'])->name('platform-activity.export');

    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/auth', [\App\Http\Controllers\AdminController::class, 'authActivities'])->name('auth');
        Route::get('/business', [\App\Http\Controllers\AdminController::class, 'businessActivities'])->name('business');
        Route::get('/reservations', [\App\Http\Controllers\AdminController::class, 'reservationActivities'])->name('reservations');
        Route::get('/system', [\App\Http\Controllers\AdminController::class, 'systemActivities'])->name('system');
    });
    Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::resource('coupons', \App\Http\Controllers\AdminCouponController::class);
    Route::post('/coupons/{coupon}/toggle', [\App\Http\Controllers\AdminCouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    Route::get('/popular-businesses', [\App\Http\Controllers\AdminController::class, 'popularBusinesses'])->name('popular-businesses.index');
    Route::get('/support-conversations', [\App\Http\Controllers\AdminController::class, 'contactMessages'])->name('contact-messages.index');
    Route::post('/support-conversations/{id}/reply', [\App\Http\Controllers\AdminController::class, 'replyToContactMessage'])->name('contact-messages.reply');
    Route::post('/support-conversations/{id}/close', [\App\Http\Controllers\AdminController::class, 'closeContactMessage'])->name('contact-messages.close');
    Route::post('/support-conversations/{id}/notes', [\App\Http\Controllers\AdminController::class, 'updateContactMessageNotes'])->name('contact-messages.notes');

    // User Management
    Route::get('/users/{id}/edit', [\App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [\App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');

    // Reports & Settings
    Route::get('/reports', [\App\Http\Controllers\AdminController::class, 'reports'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\AdminController::class, 'exportReports'])->name('reports.export');
    Route::get('/settings', [\App\Http\Controllers\AdminController::class, 'settings'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\AdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/health', [\App\Http\Controllers\AdminController::class, 'systemHealth'])->name('health');

    // Withdrawal Management
    Route::get('/withdrawals', [\App\Http\Controllers\Admin\WithdrawalAdminController::class, 'index'])->name('withdrawals.index');
    Route::patch('/withdrawals/{withdrawal}', [\App\Http\Controllers\Admin\WithdrawalAdminController::class, 'update'])->name('withdrawals.update');

    // Review Moderation
    Route::get('/reviews', [\App\Http\Controllers\Admin\AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [\App\Http\Controllers\Admin\AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [\App\Http\Controllers\Admin\AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::post('/reviews/{review}/keep', [\App\Http\Controllers\Admin\AdminReviewController::class, 'keep'])->name('reviews.keep');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // User Moderation
    Route::post('/users/{user}/toggle-review-block', [\App\Http\Controllers\Admin\AdminReviewController::class, 'toggleBlock'])->name('users.toggle-review-block');
});

// Vendor Routes
Route::middleware(['auth', 'role:business,admin'])->prefix('vendor')->name('vendor.')->group(function () {
    // Public Vendor Pages (Always Accessible to business owners)
    Route::get('/billing', [\App\Http\Controllers\BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/upgrade/{packageId}', [\App\Http\Controllers\BillingController::class, 'upgrade'])->name('billing.upgrade');

    // Subscribed Vendor Pages (Gate access)
    Route::middleware(['subscribed'])->group(function () {
        Route::get('/setup-guide', [\App\Http\Controllers\VendorController::class, 'setup'])->name('setup.index');
        Route::get('/search', [\App\Http\Controllers\VendorSearchController::class, 'search'])->name('search');
        Route::get('/business/edit', [\App\Http\Controllers\VendorController::class, 'editBusiness'])->name('business.edit');
        Route::put('/business/update', [\App\Http\Controllers\VendorController::class, 'updateBusiness'])->name('business.update');
        Route::post('/business/reset-pos-device', [\App\Http\Controllers\VendorController::class, 'resetPosDevice'])->name('business.reset-pos-device');
        Route::post('/business/images', [\App\Http\Controllers\VendorController::class, 'storeImage'])->name('business.images.store');
        Route::delete('/business/images/{id}', [\App\Http\Controllers\VendorController::class, 'deleteImage'])->name('business.images.delete');
        Route::get('/business/hours', [\App\Http\Controllers\VendorController::class, 'editHours'])->name('business.hours.edit');
        Route::put('/business/hours', [\App\Http\Controllers\VendorController::class, 'updateHours'])->name('business.hours.update');
        Route::get('/reservations', [\App\Http\Controllers\VendorController::class, 'reservations'])->name('reservations.index');
        Route::patch('/reservations/{id}', [\App\Http\Controllers\VendorController::class, 'updateReservation'])->name('reservations.update');

        // Menu Management
        Route::resource('menus', \App\Http\Controllers\VendorMenuController::class);

        // Staff Management
        Route::resource('staff', \App\Http\Controllers\StaffController::class)->except(['show']);
        Route::patch('staff/{staff}/toggle-status', [\App\Http\Controllers\StaffController::class, 'toggleStatus'])->name('staff.toggle-status');

        // Coupon Management
        Route::resource('coupons', \App\Http\Controllers\VendorCouponController::class);
        Route::post('coupons/{coupon}/toggle', [\App\Http\Controllers\VendorCouponController::class, 'toggleStatus'])->name('coupons.toggle-status');

        // Resource (Table/Space) Management
        Route::get('resources/bulk-create', [\App\Http\Controllers\VendorResourceController::class, 'createBulk'])->name('resources.create-bulk');
        Route::post('resources/bulk-create', [\App\Http\Controllers\VendorResourceController::class, 'storeBulk'])->name('resources.store-bulk');
        Route::post('resources/bulk-action', [\App\Http\Controllers\VendorResourceController::class, 'bulkAction'])->name('resources.bulk-action');
        Route::resource('resources', \App\Http\Controllers\VendorResourceController::class);
        Route::resource('locations', \App\Http\Controllers\VendorLocationController::class);

        // CRM
        Route::get('/customers', [\App\Http\Controllers\VendorController::class, 'customers'])->name('customers');
        Route::get('/reviews', [\App\Http\Controllers\VendorController::class, 'reviews'])->name('reviews.index');

        // Finance & Withdrawals
        Route::get('/finance', [\App\Http\Controllers\VendorController::class, 'finance'])->name('finance.index');
        Route::get('/finance/export', [\App\Http\Controllers\VendorController::class, 'exportFinance'])->name('finance.export');
        Route::post('/finance/withdrawals', [\App\Http\Controllers\WithdrawalController::class, 'store'])->name('withdrawals.store');
        Route::post('/finance/iyzico-register', [\App\Http\Controllers\VendorController::class, 'registerIyzicoSubMerchant'])->name('finance.iyzico.register');

        // Occupancy Analysis
        Route::get('/occupancy', [\App\Http\Controllers\VendorController::class, 'occupancy'])->name('occupancy.index');

        // Analytics AJAX Data
        Route::get('/analytics/data', [\App\Http\Controllers\VendorController::class, 'getAnalyticsData'])->name('analytics.data');

        // POS Demo Data Seeding
        Route::post('/business/seed-demo-data', [\App\Http\Controllers\VendorController::class, 'seedDemoData'])->name('business.seed-demo-data');
        Route::post('/business/clear-demo-data', [\App\Http\Controllers\VendorController::class, 'clearDemoData'])->name('business.clear-demo-data');

        // Waiter Kiosk
        Route::get('/kiosk', [\App\Http\Controllers\WaiterKioskController::class, 'index'])->name('kiosk.index');
        Route::post('/kiosk/check-in/{reservation}', [\App\Http\Controllers\WaiterKioskController::class, 'checkIn'])->name('kiosk.check-in');
        Route::post('/kiosk/check-out/{reservation}', [\App\Http\Controllers\WaiterKioskController::class, 'checkOut'])->name('kiosk.check-out');
        Route::post('/kiosk/quick-book', [\App\Http\Controllers\WaiterKioskController::class, 'quickBook'])->name('kiosk.quick-book');
    });
});

// Admin Reports Financial Extension
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/reports/financial', [AdminController::class, 'financialReports'])->name('admin.reports.financial');
});

// Review Routes
Route::post('/business/{business}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->middleware('auth')->name('reviews.store');
Route::post('/reviews/{review}/report', [\App\Http\Controllers\ReviewController::class, 'report'])->middleware('auth')->name('reviews.report');

Route::get('/business/{business}/slots', [\App\Http\Controllers\BusinessController::class, 'getAvailableSlots'])->name('business.slots');
Route::post('/coupons/check', [\App\Http\Controllers\BusinessController::class, 'checkCoupon'])->name('coupons.check');
