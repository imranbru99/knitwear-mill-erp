<?php

use App\Http\Controllers\DocumentPrintController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [\App\Http\Controllers\SessionController::class, 'create'])->name('login');
Route::post('/login', [\App\Http\Controllers\SessionController::class, 'store'])->middleware('throttle:10,1');
Route::post('/logout', [\App\Http\Controllers\SessionController::class, 'destroy'])->name('logout');

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/about', [SiteController::class, 'page'])->defaults('slug', 'about')->name('about');
Route::get('/capabilities', [SiteController::class, 'page'])->defaults('slug', 'capabilities')->name('capabilities');
Route::get('/sustainability', [SiteController::class, 'page'])->defaults('slug', 'sustainability')->name('sustainability');
Route::get('/quality', [SiteController::class, 'page'])->defaults('slug', 'quality')->name('quality');
Route::get('/sampling', [SiteController::class, 'page'])->defaults('slug', 'sampling')->name('sampling');
Route::get('/products', [SiteController::class, 'products'])->name('products');
Route::get('/products/{style:slug}', [SiteController::class, 'product'])->name('products.show');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::get('/faq', [SiteController::class, 'faq'])->name('faq');
Route::get('/careers', [SiteController::class, 'careers'])->name('careers');
Route::post('/careers', [SiteController::class, 'apply'])->name('careers.apply');
Route::get('/privacy', [SiteController::class, 'legal'])->defaults('slug', 'privacy')->name('privacy');
Route::get('/terms', [SiteController::class, 'legal'])->defaults('slug', 'terms')->name('terms');
Route::post('/inquiries', [InquiryController::class, 'store'])->middleware('throttle:inquiry')->name('inquiries.store');
Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SiteController::class, 'robots'])->name('robots');
Route::get('/llms.txt', [SiteController::class, 'llms'])->name('llms');
Route::get('/lang/{locale}', [SiteController::class, 'locale'])->whereIn('locale', ['en', 'bn'])->name('locale');

Route::middleware('auth')->group(function () {
    Route::get('/documents/{type}/{id}', DocumentPrintController::class)->name('documents.print');
    Route::get('/floor/{screen}', [FloorController::class, 'show'])->name('floor.show');
    Route::post('/floor/{screen}', [FloorController::class, 'store'])->name('floor.store');
    Route::get('/portal/buyer', [PortalController::class, 'buyer'])->name('portal.buyer');
    Route::post('/portal/buyer/comments', [PortalController::class, 'comment'])->name('portal.buyer.comment');
    Route::get('/portal/supplier', [PortalController::class, 'supplier'])->name('portal.supplier');
    Route::get('/assistant', [PortalController::class, 'assistant'])->name('assistant');
    Route::post('/assistant', [PortalController::class, 'ask'])->name('assistant.ask');
});

Route::get('/health', function () {
    \Illuminate\Support\Facades\DB::select('select 1');

    return response()->json(['status' => 'ok', 'time' => now()->toIso8601String()]);
})->name('health');
