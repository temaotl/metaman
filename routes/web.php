<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\FakeController;
use App\Http\Controllers\FederationController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ShibbolethController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return auth()->user() ? view('dashboard') : view('welcome');
})->name('home');

Route::get('blocked', function() {
    return auth()->user() ? redirect('/') : view('blocked');
});

if(App::environment(['local', 'testing']))
{
    Route::match(['get', 'post'], '/fakelogin/{id?}', [FakeController::class, 'login']);
    Route::get('fakelogout', [FakeController::class, 'logout']);
}

Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

// Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
// Route::patch('notifications/{id}', [NotificationController::class, 'update'])->name('notifications.update');
// Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

Route::get('federations/import', [FederationController::class, 'unknown'])->name('federations.unknown');
Route::post('federations/import', [FederationController::class, 'import'])->name('federations.import');
Route::get('federations/refresh', [FederationController::class, 'refresh'])->name('federations.refresh');
Route::get('federations/{id}/operators', [FederationController::class, 'operators'])->name('federations.operators');
Route::get('federations/{id}/entities', [FederationController::class, 'entities'])->name('federations.entities');
Route::get('federations/{id}/requests', [FederationController::class, 'requests'])->name('federations.requests');
Route::post('federations/{id}/request', [FederationController::class, 'request'])->name('federations.request');
Route::resource('federations', FederationController::class);

Route::get('entities/import', [EntityController::class, 'unknown'])->name('entities.unknown');
Route::post('entities/import', [EntityController::class, 'import'])->name('entities.import');
Route::get('entities/refresh', [EntityController::class, 'refresh'])->name('entities.refresh');
Route::get('entities/{id}/operators', [EntityController::class, 'operators'])->name('entities.operators');
Route::get('entities/{id}/federations', [EntityController::class, 'federations'])->name('entities.federations');
Route::post('entities/{id}/join', [EntityController::class, 'join'])->name('entities.join');
Route::post('entities/{id}/leave', [EntityController::class, 'leave'])->name('entities.leave');
Route::resource('entities', EntityController::class);

Route::get('categories/import', [CategoryController::class, 'unknown'])->name('categories.unknown');
Route::post('categories/import', [CategoryController::class, 'import'])->name('categories.import');
Route::get('categories/refresh', [CategoryController::class, 'refresh'])->name('categories.refresh');
Route::resource('categories', CategoryController::class);

Route::get('groups/import', [GroupController::class, 'unknown'])->name('groups.unknown');
Route::post('groups/import', [GroupController::class, 'import'])->name('groups.import');
Route::get('groups/refresh', [GroupController::class, 'refresh'])->name('groups.refresh');
Route::resource('groups', GroupController::class);

Route::resource('users', UserController::class)->except('edit', 'destroy');

Route::resource('memberships', MembershipController::class)->only('update', 'destroy');

Route::get('login', [ShibbolethController::class, 'create'])->name('login')->middleware('guest');
Route::get('auth', [ShibbolethController::class, 'store'])->middleware('guest');
Route::get('logout', [ShibbolethController::class, 'destroy'])->name('logout')->middleware('auth');
