<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

// --- Public Routes ---
Route::prefix('auth')->middleware('throttle:10,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/auth/profile', [\App\Http\Controllers\Api\ProfileApiController::class, 'update']);
    Route::post('/auth/profile/photo', [\App\Http\Controllers\Api\ProfileApiController::class, 'updatePhoto']);
    Route::put('/auth/profile/settings', [\App\Http\Controllers\Api\ProfileApiController::class, 'updateSettings']);
});

Route::get('/businesses', [BusinessController::class, 'index']);
Route::get('/businesses/{business}', [BusinessController::class, 'show']);
Route::get('/businesses/{id}/reviews', [ReviewController::class, 'index']);
Route::get('/businesses/{business_id}/menus', [\App\Http\Controllers\MenuController::class, 'index']);
Route::get('/businesses/{business}/available-slots', [BusinessController::class, 'getAvailableSlots']);

// --- Protected Routes (Logged in Users) ---
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Customer Actions
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations', [ReservationController::class, 'index']);

    // Favorites
    Route::post('/favorites/toggle/{businessId}', [FavoriteController::class, 'toggle']);
    Route::post('/businesses/{id}/reviews', [ReviewController::class, 'store']);
    Route::post('/businesses/{id}/favorite', [FavoriteController::class, 'toggle']);
    Route::get('/favorites', [FavoriteController::class, 'index']);

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index']);
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead']);
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead']);

    // Business Owner Actions (Vendor)
    Route::prefix('vendor')->group(function () {
        Route::post('/businesses', [BusinessController::class, 'store']); // Create initial business
        Route::get('/my-business', [VendorController::class, 'myBusiness']);
        Route::put('/my-business', [VendorController::class, 'updateBusiness']);
        Route::post('/resources', [VendorController::class, 'addResource']);
        Route::get('/reservations', [VendorController::class, 'myReservations']);
        Route::put('/reservations/{id}/status', [VendorController::class, 'updateReservationStatus']);

        // Media Upload
        Route::post('/businesses/{id}/upload-image', [MediaController::class, 'upload']);

        // Menu Management
        Route::get('/menus', [\App\Http\Controllers\MenuController::class, 'index']); // Get own menus
        Route::post('/menus', [\App\Http\Controllers\MenuController::class, 'store']);
        Route::get('/menus/{id}', [\App\Http\Controllers\MenuController::class, 'show']);
        Route::post('/menus/{id}', [\App\Http\Controllers\MenuController::class, 'update']); // Using POST for file upload support with method spoofing if needed, or stick to PUT
        Route::delete('/menus/{id}', [\App\Http\Controllers\MenuController::class, 'destroy']);
    });

    // Payments
    Route::get('/wallet/transactions', [\App\Http\Controllers\Api\WalletApiController::class, 'index']);
    Route::get('/wallet/search', [\App\Http\Controllers\Api\WalletApiController::class, 'searchRecipients']);
    Route::post('/wallet/transfer', [\App\Http\Controllers\Api\WalletApiController::class, 'transfer']);
    Route::post('/payment/initialize', [PaymentController::class, 'initialize']);

    // Web Push Notifications
    Route::post('/push/subscribe', [PushSubscriptionController::class, 'subscribe']);
    Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'unsubscribe']);

    // Admin Actions
    Route::prefix('admin')->middleware('can:admin')->group(function () {
        Route::get('/stats', [AdminController::class, 'stats']);
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/pending-businesses', [AdminController::class, 'pendingBusinesses']);
        Route::post('/businesses/{id}/approve', [AdminController::class, 'approveBusiness']);
        Route::delete('/businesses/{id}/reject', [AdminController::class, 'rejectBusiness']);
    });

    // QR Table Session (Customer Mobile) - Requires Mobile+
    Route::prefix('qr')->middleware('subscribed:mobile_access')->group(function () {
        Route::post('/session', [\App\Http\Controllers\Api\QrTableController::class, 'startSession']);
        Route::get('/session/{token}', [\App\Http\Controllers\Api\QrTableController::class, 'getSession']);
        Route::get('/session/{token}/menu', [\App\Http\Controllers\Api\QrTableController::class, 'getMenu']);
        Route::post('/session/{token}/order', [\App\Http\Controllers\Api\QrTableController::class, 'submitOrder']);
        Route::get('/session/{token}/bill', [\App\Http\Controllers\Api\QrTableController::class, 'getBill']);
        Route::post('/session/{token}/pay', [\App\Http\Controllers\Api\QrTableController::class, 'payBill']);

        // Split Bill
        Route::post('/session/{token}/split/equal', [\App\Http\Controllers\Api\SplitBillController::class, 'splitEqual']);
        Route::post('/session/{token}/split/by-item', [\App\Http\Controllers\Api\SplitBillController::class, 'splitByItem']);
        Route::post('/session/{token}/split/custom', [\App\Http\Controllers\Api\SplitBillController::class, 'splitCustom']);
        Route::get('/session/{token}/split/status', [\App\Http\Controllers\Api\SplitBillController::class, 'splitStatus']);
    });
});

// --- POS Integration API (External Systems) ---

Route::prefix('pos')->group(function () {
    Route::get('/version', [\App\Http\Controllers\Api\PosApiController::class, 'checkVersion']);
});

Route::prefix('pos')->middleware(['auth:sanctum', 'subscribed:pos_access'])->group(function () {
    Route::get('/init', [\App\Http\Controllers\Api\PosApiController::class, 'init']);
    Route::post('/deactivate', [\App\Http\Controllers\Api\PosApiController::class, 'deactivate']);
    
    Route::post('/update-occupancy', [\App\Http\Controllers\PosIntegrationController::class, 'updateOccupancy']);
    Route::get('/occupancy', [\App\Http\Controllers\PosIntegrationController::class, 'getOccupancy']);

    // Internal Desktop POS Endpoints
    Route::get('/tables', [\App\Http\Controllers\Api\PosApiController::class, 'getTables']);
    Route::get('/menu', [\App\Http\Controllers\Api\PosApiController::class, 'getMenu']);
    Route::get('/orders', [\App\Http\Controllers\Api\PosApiController::class, 'getOrders']);
    Route::get('/order/{resourceId}', [\App\Http\Controllers\Api\PosApiController::class, 'getOrder']);
    Route::post('/order/submit', [\App\Http\Controllers\Api\PosApiController::class, 'submitOrder']);
    Route::post('/order/{orderId}/pay', [\App\Http\Controllers\Api\PosApiController::class, 'processPayment']);
    Route::patch('/order/{orderId}/items/{itemId}', [\App\Http\Controllers\Api\PosApiController::class, 'updateOrderItem']);
    Route::delete('/order/{orderId}/items/{itemId}', [\App\Http\Controllers\Api\PosApiController::class, 'deleteOrderItem']);
    Route::post('/validate-pin', [\App\Http\Controllers\Api\PosApiController::class, 'validatePin']);
    Route::get('/summary', [\App\Http\Controllers\Api\PosApiController::class, 'getDailySummary']);
    Route::get('/staff', [\App\Http\Controllers\Api\PosApiController::class, 'getStaff']);
    Route::post('/staff', [\App\Http\Controllers\Api\PosApiController::class, 'storeStaff']);
    Route::delete('/staff/{id}', [\App\Http\Controllers\Api\PosApiController::class, 'deleteStaff']);
    Route::post('/staff/{id}/update-permissions', [\App\Http\Controllers\Api\PosApiController::class, 'updateStaffPermissions']);
    Route::post('/staff/{id}/update-pin', [\App\Http\Controllers\Api\PosApiController::class, 'updateStaffPin']);
    Route::post('/update-master-pin', [\App\Http\Controllers\Api\PosApiController::class, 'updateMasterPin']);
    Route::post('/verify-master-pin', [\App\Http\Controllers\Api\PosApiController::class, 'verifyMasterPin']);

    // Table Operations
    Route::post('/tables/transfer', [\App\Http\Controllers\Api\PosApiController::class, 'transferTable']);

    // Resource Management
    Route::get('/tables', [\App\Http\Controllers\Api\PosApiController::class, 'getTables']);
    Route::post('/tables', [\App\Http\Controllers\Api\PosApiController::class, 'storeResource']);
    Route::post('/tables/{id}', [\App\Http\Controllers\Api\PosApiController::class, 'updateResource']);
    Route::delete('/tables/{id}', [\App\Http\Controllers\Api\PosApiController::class, 'deleteResource']);

    // Menu Management
    Route::post('/menu/items', [\App\Http\Controllers\Api\PosApiController::class, 'storeMenuItem']);
    Route::post('/menu/items/{id}', [\App\Http\Controllers\Api\PosApiController::class, 'updateMenuItem']);
    Route::delete('/menu/items/{id}', [\App\Http\Controllers\Api\PosApiController::class, 'deleteMenuItem']);

    // KDS (Kitchen Display System) Routes
    Route::get('/kds/orders', [\App\Http\Controllers\Api\PosApiController::class, 'getKitchenOrders']);
    Route::post('/kds/items/{id}/status', [\App\Http\Controllers\Api\PosApiController::class, 'updateItemStatus']);

    // Happy Hour Management
    Route::get('/happy-hours', [\App\Http\Controllers\Api\PosApiController::class, 'getHappyHours']);
    Route::get('/happy-hours/active', [\App\Http\Controllers\Api\PosApiController::class, 'getActiveHappyHours']);
    Route::post('/happy-hours', [\App\Http\Controllers\Api\PosApiController::class, 'storeHappyHour']);
    Route::put('/happy-hours/{id}', [\App\Http\Controllers\Api\PosApiController::class, 'updateHappyHour']);
    Route::delete('/happy-hours/{id}', [\App\Http\Controllers\Api\PosApiController::class, 'deleteHappyHour']);

    // Advanced Analytics
    Route::get('/analytics/top-products', [\App\Http\Controllers\Api\PosApiController::class, 'getTopProducts']);
    Route::get('/analytics/hourly-sales', [\App\Http\Controllers\Api\PosApiController::class, 'getHourlySales']);
    Route::get('/analytics/weekly-trend', [\App\Http\Controllers\Api\PosApiController::class, 'getWeeklyTrend']);
    Route::get('/analytics/payment-breakdown', [\App\Http\Controllers\Api\PosApiController::class, 'getPaymentBreakdown']);
});
