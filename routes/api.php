<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\API\DiscountController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\MidtransCallbackController;
use Symfony\Component\Routing\Route as RoutingRoute;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER
|--------------------------------------------------------------------------
| Ambil data user yang sedang login
*/

route::get('/authenticated-user', function (Request $request) {
    return User::all();
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
| AUTHENTICATION (PUBLIC)
|--------------------------------------------------------------------------
| Endpoint TANPA token
*/
Route::prefix('auth')->group(function () {

    // Login
    Route::post('/login', [AuthController::class, 'login']);

    // Register
    Route::post('/register', [AuthController::class, 'register']);

    // Forgot password (kirim email)
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);

    // Reset password (submit password baru)
    Route::post('/reset-password', [AuthController::class, 'reset']);
});

/*
|--------------------------------------------------------------------------
| RESET PASSWORD LINK (WEB)
|--------------------------------------------------------------------------
| Dibuka dari email (browser)
*/
Route::get('/reset-password/{token}/{email}', function ($token, $email) {

    $exists = DB::table('password_reset_tokens')
        ->where('email', $email)
        ->where('token', $token)
        ->exists();

    if (! $exists) {
        return response()->json([
            'status'  => false,
            'message' => 'Token reset password tidak valid atau sudah kadaluarsa'
        ], 400);
    }

    return view('emails.reset-password2', compact('token', 'email'));
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (AUTH:SANTUM)
|--------------------------------------------------------------------------
| Semua endpoint di bawah WAJIB login
*/
Route::middleware('auth:sanctum')->group(function () {







    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | TESTING
    |--------------------------------------------------------------------------
    */
    Route::get('/tes', fn () => response()->json(true));

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{id}', [ProductApiController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | DISCOUNTS
    |--------------------------------------------------------------------------
    */
    Route::post('/discounts', [DiscountController::class, 'store']);
    Route::post('/products/{product}/apply-discount', [DiscountController::class, 'applyDiscount']);

    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/items', [CartController::class, 'store']);
    Route::put('/cart/items/{itemId}', [CartController::class, 'update']);
    Route::delete('/cart', [CartController::class, 'clear']);



    // checkout
        Route::post('/checkout', [CheckoutController::class, 'store']);
        Route::post('/preview-checkout', [CheckoutController::class, 'preview']);




});


Route::get('/orders/{order}/snap-token', [PaymentController::class, 'snapToken'])
->middleware('auth:sanctum');

Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle']);



Route::get('/Midtrans/{id}', function($id) {


return view('midtrans', ['id' => $id]);


});
