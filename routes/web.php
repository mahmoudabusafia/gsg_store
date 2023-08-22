<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ConfigsController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\ProfilesController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ProductsController as Products;
use App\Http\Controllers\RatingsController;
use App\Http\Controllers\UserProfileController;
use App\Http\Middleware\CheckUserType;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Support\Facades\App;

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

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// login (login)
// require __DIR__ . '/auth.php';

// admin/login (admin.login)-> Admin/Auth/AuthenticateSessionController
// Route::prefix('admin')->namespace('Admin')->as('admin.')->group(function(){
//     require __DIR__ . '/auth.php';
// });

Route::prefix('admin')
    // ->middleware('auth:admin')
    ->group(function () {

        Route::get('settings', [ConfigsController::class, 'create'])->name('settings');
        Route::post('settings', [ConfigsController::class, 'store']);
        Route::get('clear-cache', [ConfigsController::class, 'clearCache'])->name('clearCache');


        Route::get('user-profile', [UserProfileController::class, 'index'])->name('user-profile');

        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
        Route::get('notifications/{id}', [NotificationController::class, 'show'])->name('notifications.read');
        Route::get('notificationsAll', [NotificationController::class, 'readAll'])->name('notifications.readAll');

        Route::group([
            'prefix' => '/categories',
            'as' => 'categories.'
        ], function () {
            Route::get('/', [CategoriesController::class, 'index'])->name('index');
            Route::get('/create', [CategoriesController::class, 'create'])->name('create');
            Route::post('/', [CategoriesController::class, 'store'])->name('store');
            Route::get('/{category}', [CategoriesController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [CategoriesController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CategoriesController::class, 'update'])->name('update');
            Route::delete('/{id}', [CategoriesController::class, 'destroy'])->name('destroy');
        });

        Route::get('/products/trash', [ProductsController::class, 'trash'])->name('products.trash');

        Route::put('/products/trash/{product?}', [ProductsController::class, 'restore'])
            ->name('products.restore')
            ->middleware(['can:restore,product']);

        Route::delete('/products/trash/{id?}', [ProductsController::class, 'forceDelete'])->name('products.force-delete');

        Route::resource('/products', ProductsController::class);

        Route::resource('/roles', RolesController::class);

        Route::resource('/countries', CountriesController::class);

        Route::get('/profiles/{profile}', [ProfilesController::class, 'show']);
    });

Route::get('products', [Products::class, 'index'])->name('products');
Route::get('products/{slug}', [Products::class, 'show'])->name('product.details');

Route::post('ratings/{type}', [RatingsController::class, 'store'])
    ->where('type', 'profile|product');


Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart', [CartController::class, 'store']);


Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store']);

Route::get('/orders', function(){
    return Order::all();
})->name('orders');

Route::get('chat', [MessagesController::class, 'index'])->name('chat');
Route::post('chat', [MessagesController::class, 'store'])->name('chat.store');

Route::get('/test-fcm', function(){
    User::find(5)->notify(new OrderCreatedNotification(new Order));
});


if(App::environment('production')){
    Route::get('storage/{file}', function($file){
        $filepath = storage_path('storage/app/public'. $file);
        if(!is_file($filepath)){
            abort(404,'the file you look for it not found!');
        }
    });
}