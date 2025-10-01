<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AdminUserController,
    DashboardController,
    ClientController,
    CoupleController,
    PersonController,
    PersonParentController,
    WeddingEventController,
    LocationController,
    GalleryImageController,
    TimelineEventController,
    BankAccountController,
    GuestController,
    GuestAttendantController,
    InvitationController,
    QrCodeController,
    GuestMessageController,
    PackageController,
    TransactionController,
    PaymentMethodController,
    PaymentTransactionController,
    ProfileController,
    OrderController
};
use App\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/welcome', function() {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::post('invitations/{invitation}/send', [InvitationController::class, 'sendInvitation'])->name('invitations.send');
Route::get('invitation/{id}', [InvitationController::class, 'showInvitation'])->name('invitation.show');
Route::post('/api/guest-messages', [GuestMessageController::class, 'store'])->name('api.guest-messages.store');
Route::post('/api/invitations/rsvp', [InvitationController::class, 'rsvp'])->name('api.invitations.rsvp');
Route::get('/api/invitations/{id}/stats', [InvitationController::class, 'getAttendanceStats'])->name('api.invitations.stats');
Route::get('guests-present',[InvitationController::class, 'present'])->name('invitation.present');
Route::post('guests-attendant',[GuestController::class, 'attendant'])->name('my-guests.attendant');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Order creation routes - available to both admins and clients
    Route::get('/create-order/step1', [OrderController::class, 'step1'])->name('create-order.step1');
    Route::post('/create-order/step1', [OrderController::class, 'processStep1'])->name('create-order.process-step1');
    Route::get('/create-order/step2', [OrderController::class, 'step2'])->name('create-order.step2');
    Route::post('/create-order/step2', [OrderController::class, 'processStep2'])->name('create-order.process-step2');
    Route::get('/create-order/step3', [OrderController::class, 'step3'])->name('create-order.step3');
    Route::post('/create-order/step3', [OrderController::class, 'processStep3'])->name('create-order.process-step3');
    Route::get('/create-order/step4', [OrderController::class, 'step4'])->name('create-order.step4');
    Route::post('/create-order/step4', [OrderController::class, 'processStep4'])->name('create-order.process-step4');
    Route::post('/create-order/cancel', [OrderController::class, 'cancel'])->name('create-order.cancel');
    Route::get('/api/wedding-events/{wedding_event_id}/guest-messages', [\App\Http\Controllers\GuestMessageController::class, 'indexForWeddingEvent'])->name('api.guest-messages.index');

});

// Admin routes (protected by admin middleware)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // All admin CRUD operations
    Route::resource('clients', ClientController::class);
    Route::resource('couples', CoupleController::class);
    Route::get('couples/{couple}/select-payment', [CoupleController::class, 'selectPayment'])->name('couples.select-payment');
    Route::post('couples/{couple}/process-payment', [CoupleController::class, 'processPayment'])->name('couples.process-payment');
    Route::resource('people', PersonController::class);
    Route::resource('wedding-events', WeddingEventController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('gallery-images', GalleryImageController::class);
    Route::resource('timeline-events', TimelineEventController::class);
    Route::resource('bank-accounts', BankAccountController::class);
    Route::resource('guests', GuestController::class);
    Route::resource('guest-attendants', GuestAttendantController::class)->only(['index', 'destroy']);
    Route::resource('invitations', InvitationController::class);
    Route::get('qr-codes/{id}/invitation-card', [QrCodeController::class, 'showInvitationCard'])->name('qr-codes.invitation-card');
    Route::resource('qr-codes', QrCodeController::class)->only(['index']);
    Route::resource('guest-messages', GuestMessageController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::resource('payment-transactions', PaymentTransactionController::class);
    
    // User management
    Route::resource('admin/users', AdminUserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
});

// Client routes (protected by client middleware)
Route::middleware(['auth', 'client'])->group(function () {
    Route::get('/client/dashboard', [DashboardController::class, 'clientIndex'])->name('client.dashboard');
    
    // Client-specific routes with full CRUD operations
    Route::resource('my-clients', ClientController::class)->only('edit','update');
    Route::resource('my-couples', CoupleController::class);
    Route::get('my-couples/{couple}/select-payment', [CoupleController::class, 'selectPayment'])->name('my-couples.select-payment');
    Route::post('my-couples/{couple}/process-payment', [CoupleController::class, 'processPayment'])->name('my-couples.process-payment');
    Route::resource('my-people', PersonController::class);
    Route::resource('my-wedding-events', WeddingEventController::class);
    Route::resource('my-gallery-images', GalleryImageController::class);
    Route::resource('my-timeline-events', TimelineEventController::class);
    Route::resource('my-bank-accounts', BankAccountController::class);
    Route::resource('my-guests', GuestController::class);
    Route::get('my-guests-present',[InvitationController::class, 'present'])->name('my-invitation.present');
    Route::post('my-guests-attendant',[GuestController::class, 'attendant'])->name('my-guests.attendant');
    Route::resource('my-guest-attendants', GuestAttendantController::class)->only(['index', 'destroy']);
    Route::resource('my-invitations', InvitationController::class);
    Route::resource('my-guest-messages', GuestMessageController::class);
    Route::resource('my-transactions', TransactionController::class);
    Route::resource('my-locations', LocationController::class);

});

require __DIR__.'/auth.php';
