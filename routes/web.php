<?php

use Illuminate\Support\Facades\Route;

// Public Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

// Customer Controllers
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\QueueController;
use App\Http\Controllers\Customer\HistoryController;
use App\Http\Controllers\Customer\ReviewController as CustomerReviewController;
use App\Http\Controllers\Customer\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SparepartController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\SlotController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RestockController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Customer\NotificationController as CustomerNotificationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [HomeController::class, 'katalog'])->name('katalog');
Route::get('/docs', function () {
    return view('docs.preview');
})->name('docs.preview');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Customer Dashboard Routes
|--------------------------------------------------------------------------
| Currently no auth middleware applied to allow easy preview during dev.
| In production, wrap this with: Route::middleware(['auth'])->group(...)
*/
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::redirect('/', '/customer/riwayat');
    
    // Booking
    Route::get('/booking/create', [CustomerBookingController::class, 'create'])->name('booking.create');
    Route::get('/booking/check-availability', [CustomerBookingController::class, 'checkAvailability'])->name('booking.checkAvailability');
    Route::post('/booking', [CustomerBookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/success', [CustomerBookingController::class, 'success'])->name('booking.success');
    
    // Queue
    Route::get('/antrean', [QueueController::class, 'index'])->name('antrean');
    
    // History
    Route::get('/riwayat', [HistoryController::class, 'index'])->name('riwayat');
    
    // Review
    Route::get('/review/create/{bookingId?}', [CustomerReviewController::class, 'create'])->name('review.create');
    Route::post('/review', [CustomerReviewController::class, 'store'])->name('review.store');

    // Notifications
    Route::get('/notifications', [CustomerNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read', [CustomerNotificationController::class, 'markAsRead'])->name('notifications.read');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'updateInfo'])->name('profile.updateInfo');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
| Currently no auth middleware applied to allow easy preview during dev.
| In production, wrap this with: Route::middleware(['auth', 'role:admin'])->group(...)
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Booking Management
    Route::get('/booking', [AdminBookingController::class, 'index'])->name('booking.index');
    Route::patch('/booking/{id}/status', [AdminBookingController::class, 'updateStatus'])->name('booking.updateStatus');
    Route::get('/booking/riwayat', [AdminBookingController::class, 'riwayat'])->name('booking.riwayat');
    
    // Sparepart Management
    Route::resource('sparepart', SparepartController::class);
    
    // POS (Point of Sale)
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [PosController::class, 'store'])->name('pos.store');
    Route::get('/pos/invoice/{transactionId}', [PosController::class, 'invoice'])->name('pos.invoice');
    Route::get('/pos/whatsapp/{transactionId}', [PosController::class, 'whatsapp'])->name('pos.whatsapp');
    
    // Restock
    Route::get('/restock', [RestockController::class, 'index'])->name('restock.index');
    Route::post('/restock', [RestockController::class, 'store'])->name('restock.store');
    
    // Laporan
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    
    // Slot Management
    Route::get('/slot', [SlotController::class, 'index'])->name('slot.index');
    Route::post('/slot/update', [SlotController::class, 'update'])->name('slot.update');
    
    // Review Management
    Route::get('/review', [AdminReviewController::class, 'index'])->name('review.index');
    Route::post('/review/{id}/reply', [AdminReviewController::class, 'reply'])->name('review.reply');
    Route::delete('/review/{id}', [AdminReviewController::class, 'destroy'])->name('review.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'updateInfo'])->name('profile.updateInfo');
    Route::put('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});
