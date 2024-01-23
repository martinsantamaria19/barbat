<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\QrTestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SettingsController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rastrear envío

Route::get('/rastreo', [PackageController::class, 'rastreo'])->name('rastreo');
Route::get('/track-package', [PackageController::class, 'trackPackage'])->name('track-package');


// Grupo de rutas que requieren autenticación
Route::middleware('auth')->group(function () {
  // Main Page Route
  Route::get('/', [HomePage::class, 'index'])->name('pages-home');
  Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

  // Locale Route
  Route::get('lang/{locale}', [LanguageController::class, 'swap']);

  // Misc Pages
  Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

  // Categories
  Route::get('/product-categories', [ProductCategoryController::class, 'index'])->name('product-categories');
  Route::get('/product-categories-list', [ProductCategoryController::class, 'getProductCategoriesList']);
  Route::post('/create-product-category', [ProductCategoryController::class, 'store']);
  Route::post('/product-categories/{categoryId}/update', [ProductCategoryController::class, 'update'])->name('product-categories.update');
  Route::post('/product-categories/{id}/delete', [ProductCategoryController::class, 'delete']);

  // Products
  Route::get('/products', [ProductController::class, 'index'])->name('products');
  Route::get('/products-list', [ProductController::class, 'getProductsList']);
  Route::get('/add-product', [ProductController::class, 'create'])->name('add-product');
  Route::post('/create-product', [ProductController::class, 'store']);
  Route::post('/update-status', [ProductController::class, 'updateStatus']);
  Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
  Route::put('/products/{id}/update', [ProductController::class, 'update'])->name('products.update');
  Route::post('/delete-product/{productId}', [ProductController::class, 'destroy']);

  // Activity
  Route::get('/activity', [ActivityController::class, 'index'])->name('activity');
  Route::get('/activity-list', [ActivityController::class, 'getActivityList']);


  // Clients
  Route::get('/clients', [ClientController::class, 'index'])->name('clients');
  Route::get('/clients-list', [ClientController::class, 'getClientsList']);
  Route::get('/clients/{clientId}', [ClientController::class, 'show'])->name('client.show');
  Route::post('/create-client', [ClientController::class, 'store']);
  Route::get('/clients/{id}/edit', [ClientController::class, 'edit'])->name('clients.edit');
  Route::put('/clients/{id}/update', [ClientController::class, 'update'])->name('clients.update');
  Route::post('/delete-client/{clientId}', [ClientController::class, 'destroy']);


  // Branches
  Route::get('/branches', [BranchController::class, 'index'])->name('branches');
  Route::get('/branches-list', [BranchController::class, 'getBranchesList']);
  Route::get('/get-branches/{client_id}', [PackageController::class, 'getBranches']);
  Route::post('/create-branch', [BranchController::class, 'store'])->name('branch.store');
  Route::get('branches/{id}/edit', [BranchController::class, 'edit'])->name('branches.edit');
  Route::put('branches/{id}/update', [BranchController::class, 'update'])->name('branches.update');
  Route::post('/delete-branch/{branchId}', [BranchController::class, 'destroy']);

  // Users
  Route::get('/users', [UserController::class, 'index'])->name('users');
  Route::get('/users-list', [UserController::class, 'getUsersList']);
  Route::post('/create-user', [UserController::class, 'store'])->name('user.store');
  Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
  Route::get('/users/{id}/show', [UserController::class, 'show'])->name('users.show');
  Route::put('/users/{id}/update', [UserController::class, 'update'])->name('users.update');
  Route::put('/users/{id}/suspend', [UserController::class, 'suspend'])->name('user.suspend');
  Route::put('/users/{id}/activate', [UserController::class, 'activate'])->name('user.activate');
  Route::post('/delete-user/{userId}', [UserController::class, 'destroy']);
  Route::post('/user/password/update', [UserController::class, 'updatePassword'])->name('user.password.update'); // SOLO DEL USUARIO AUTENTICADO
  Route::post('/users/{id}/change-password', [UserController::class, 'changeUserPassword'])->name('users.change-password'); // CUALQUIER USUARIO POR ID


  // Packages
  Route::get('/packages', [PackageController::class, 'index'])->name('packages');
  Route::get('/packages-list', [PackageController::class, 'getPackagesList']);
  Route::get('/add-package', [PackageController::class, 'create'])->name('add-package');
  Route::get('/package/{packageId}', [PackageController::class, 'show'])->name('package.show');
  Route::get('/package/{packageId}/qr-code', [PackageController::class, 'showQrCode'])->name('package.qr-code');
  Route::get('/package/{packageId}/label', [PackageController::class, 'showLabel'])->name('package.label');
  Route::post('/create-package', [PackageController::class, 'store'])->name('package.store');
  Route::post('/add-product-to-package', [PackageController::class, 'addProductToPackage'])->name('add-product-to-package');
  Route::post('/packages/{packageId}/change-status', [PackageController::class, 'changeStatus']);


  // Activity
  Route::get('/package/{packageId}/activities', [ActivityController::class, 'showActivities'])->name('activities.show');
  Route::post('/activities/store', [ActivityController::class, 'store'])->name('activities.store');
  Route::get('/activities/delivered-data', [ActivityController::class, 'getDeliveredActivitiesData']);


  // Roles and Permissions
  Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
  Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
  Route::put('/roles/{id}/update', [RoleController::class, 'update'])->name('roles.update');
  Route::post('/create-role', [RoleController::class, 'store'])->name('role.store');
  Route::delete('roles/{id}/delete', [RoleController::class, 'destroy'])->name('roles.destroy');
  Route::resource('permissions', PermissionController::class);

  // Settings
  Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
  Route::get('/settings/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');
  Route::get('/settings/security', [SettingsController::class, 'security'])->name('settings.security');
  Route::get('/settings/my-account', [SettingsController::class, 'myAccount'])->name('settings.my-account');
  Route::put('/settings/notifications/update', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');


  // Notificaciones
  Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
  Route::get('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');


  // Dashboard and Profile Routes (Jetstream Routes)
  Route::get('/dashboard', function () {
      return view('dashboard');
  })->name('dashboard');
  Route::get('/profile', function () {
      return view('profile.show');
  })->name('profile.show');
});
