<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\MerchantController as AdminMerchantController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;

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

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/tentang-kami', [IndexController::class, 'tentangKami'])->name('about');
Route::get('/panduan', [IndexController::class, 'panduan'])->name('panduan');
Route::get('/produk', [ProductController::class, 'index'])->name('product.index');
Route::get('/produk/detail/{slug}', [ProductController::class, 'detail'])->name('product.detail');
Route::get('/produk/review/{id_trans}/{id_product}', [ProductController::class, 'review'])->name('product.review');
Route::post('/produk/review/{id_trans}/{id_product}', [ProductController::class, 'reviewPost'])->name('product.review.post');
Route::get('/unitdagang', [MerchantController::class, 'index'])->name('merchant.index');
Route::get('/unitdagang/detail/{code}', [MerchantController::class, 'detail'])->name('merchant.detail');
Route::get('/blog', [IndexController::class, 'blogIndex'])->name('blog');
Route::get('/blog/detail/{id}', [IndexController::class, 'blogDetail'])->name('blog.detail');
Route::get('/kontak', [IndexController::class, 'kontak'])->name('contact');

Route::group(['prefix' => 'galeri', 'as' => 'gallery.'], function() {
    Route::get('/', [GalleryController::class, 'index'])->name('index');
    Route::get('/detail/{slug}', [GalleryController::class, 'detail'])->name('detail');
});

Route::group(['prefix' => 'cart', 'as' => 'cart.'], function() {
    Route::get('/view', [IndexController::class, 'cartView'])->name('view');
    Route::get('/checkout', [IndexController::class, 'cartCheckout'])->name('checkout');
});
Route::post('/checkout', [IndexController::class, 'checkout'])->name('checkout');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/order', [HomeController::class, 'order'])->name('order');
Route::get('/order/cancel/{id}', [HomeController::class, 'order_cancel'])->name('order.cancel');
Route::get('/order/detail/{id}', [HomeController::class, 'order_detail'])->name('order.detail');
Route::get('/order/whatsapp/{id}', [HomeController::class, 'order_wa'])->name('order.wa');
Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
Route::post('/profile/update/login', [HomeController::class, 'profile_update_login'])->name('profile.update.login');
Route::post('/profile/update/info', [HomeController::class, 'profile_update_info'])->name('profile.update.info');

Route::post('login', [LoginController::class, 'login'])->name('user.login');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::get('login', [AdminAuthController::class, 'getLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'postLogin'])->name('login.post');
    Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/home', [AdminController::class, 'index'])->name('home');
    Route::resource('merchants', AdminMerchantController::class);
    Route::resource('products', AdminProductController::class);
    Route::resource('blog', AdminBlogController::class);
    Route::resource('trans', TransactionController::class);

    Route::get('/setting', [AdminController::class, 'settingIndex'])->name('setting');
    Route::put('/setting/update', [AdminController::class, 'settingPost'])->name('setting.update');

    Route::group(['prefix' => 'report', 'as' => 'report.'], function() {
        Route::get('/index', [AdminReportController::class, 'index'])->name('index');
        Route::get('/kategori/kategori', [AdminReportController::class, 'kategori'])->name('kategori');
        Route::get('/kategori/utama', [AdminReportController::class, 'utama'])->name('utama');
        Route::get('/kategori/unitdagang', [AdminReportController::class, 'unitdagang'])->name('unitdagang');
        Route::get('/kategori/kue', [AdminReportController::class, 'kue'])->name('kue');
        Route::get('/kategori/konsumen', [AdminReportController::class, 'konsumen'])->name('konsumen');
        Route::get('/kategori/pesanan', [AdminReportController::class, 'pesanan'])->name('pesanan');
        Route::get('/kategori/detil/{id}', [AdminReportController::class, 'detil'])->name('detil');
    });

    Route::resource('categories', CategoryController::class);
    Route::group(['prefix' => 'categories/child', 'as' => 'categories.child.'], function() {
        Route::get('index/{cat}', [CategoryController::class, 'childIndex'])->name('index');
        Route::get('create/{cat}', [CategoryController::class, 'childCreate'])->name('create');
        Route::post('store/{cat}', [CategoryController::class, 'childStore'])->name('store');
        Route::get('edit/{cat}/{id}', [CategoryController::class, 'childEdit'])->name('edit');
        Route::put('update/{cat}/{id}', [CategoryController::class, 'childUpdate'])->name('update');
        Route::delete('destroy/{cat}/{id}', [CategoryController::class, 'childDestroy'])->name('destroy');
    });


    Route::resource('gallery', AdminGalleryController::class);
    Route::group(['prefix' => 'gallery/photo', 'as' => 'gallery.photo.'], function() {
        Route::get('index/{product}', [AdminGalleryController::class, 'photoIndex'])->name('index');
        Route::get('create/{product}', [AdminGalleryController::class, 'photoCreate'])->name('create');
        Route::post('store/{product}', [AdminGalleryController::class, 'photoStore'])->name('store');
        Route::get('edit/{product}/{idphoto}', [AdminGalleryController::class, 'photoEdit'])->name('edit');
        Route::put('update/{product}/{idphoto}', [AdminGalleryController::class, 'photoUpdate'])->name('update');
        Route::delete('destroy/{product}/{idphoto}', [AdminGalleryController::class, 'photoDestroy'])->name('destroy');
    });

    Route::group(['prefix' => 'products/photo', 'as' => 'products.photo.'], function() {
        Route::get('index/{product}', [AdminProductController::class, 'photoIndex'])->name('index');
        Route::get('create/{product}', [AdminProductController::class, 'photoCreate'])->name('create');
        Route::post('store/{product}', [AdminProductController::class, 'photoStore'])->name('store');
        Route::get('edit/{product}/{idphoto}', [AdminProductController::class, 'photoEdit'])->name('edit');
        Route::put('update/{product}/{idphoto}', [AdminProductController::class, 'photoUpdate'])->name('update');
        Route::delete('destroy/{product}/{idphoto}', [AdminProductController::class, 'photoDestroy'])->name('destroy');
    });

});
