<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RecruitController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\YogaController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/access_denied/index/{back?}', function ($back = null) {
    return view('errors.access_denied', ['back' => $back]);
})->name('access.denied');

// Route::middleware(['auth'])->group(function () {
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/counts', [DashboardController::class, 'counts'])->name('dashboard.counts');
// });

Route::post('/get-countries', [CommonController::class, 'getCountries']);
Route::post('/get-states', [CommonController::class, 'getStates']);
Route::post('/get-cities', [CommonController::class, 'getCities']);

// All Data
Route::get('/allData', [DataController::class, 'viewAllData'])->name('allData');
Route::post('/allData', [DataController::class, 'allData']);
Route::get('/rejected', [DataController::class, 'rejectedView'])->name('rejected');
Route::post('/rejected', [DataController::class, 'rejected']);

// Leads
Route::get('/lead', [LeadController::class, 'index'])->name('lead');
Route::post('/lead', [LeadController::class, 'getLeads'])->name('lead');

// telecalling
Route::get('/telecalling', function () {
    return view('telecalling');
})->name('telecalling');
Route::post('/telecalling/view', [LeadController::class, 'getTellcalling'])->name('getTellcalling');

// Customers
Route::get('/renewal', [CustomerController::class, 'renewal'])->name('customer.renewal');

Route::get('/customer', function () {
    return view('customer');
})->name('customer');
Route::post('/customer/view', [LeadController::class, 'getCustomer'])->name('getCustomer');

// Trainers
Route::get('/recruiter', function () {
    return view('recruiter');
})->name('recruiter');
Route::post('/recruiter/view', [TrainerController::class, 'getRecruiter'])->name('getRecruiter');


Route::get('/trainers', function () {
    return view('trainer');
})->name('trainers');

Route::post('/trainers/view', [TrainerController::class, 'getTrainer'])->name('getTrainer');

// Events
Route::get('/event', function () {
    return view('events');
})->name('event');

Route::post('/event/view', [EventController::class, 'getEvent'])->name('getEvent');

// Yoga Center
Route::get('/yoga-bookings', function () {
    return view('yoga');
})->name('yoga-bookings');

Route::post('/yoga-bookings/view', [YogaController::class, 'getYoga'])->name('getYoga');
Route::match(['get', 'post'], '/yoga-bookings/add', [YogaController::class, 'addEvents'])->name('yoga-bookings.add');
Route::get('/yoga-bookings/edit', [YogaController::class, 'editEvents'])->name('yoga-bookings.edit');
Route::post('/yoga-bookings/profile', [YogaController::class, 'getBookingProfile'])->name('getBookingProfile');
Route::get('/yoga-bookings/profile/{id}', [YogaController::class, 'getBookingProfile']);
Route::post('/yoga-bookings/delete', [YogaController::class, 'deleteData']);

// Accounts
Route::get('/ledger', [AccountController::class, 'ledger'])->name('ledger');
Route::post('/ledger', [AccountController::class, 'ledger'])->name('ledger');
Route::get('/summary', [AccountController::class, 'summary'])->name('summary');
Route::post('/summary', [AccountController::class, 'summary'])->name('summary');
Route::get('/office-expences', [AccountController::class, 'expenses'])->name('office-expences');
Route::post('/office-expences', [AccountController::class, 'expenses'])->name('office-expences');
Route::get('/office-expences/add', [AccountController::class, 'addExpenses'])->name('office-expences/add');
Route::post('/office-expences/add', [AccountController::class, 'addExpenses'])->name('office-expences/add');
Route::get('/office-expences/edit/{id}', [AccountController::class, 'editExpenses'])->name('office-expences/edit');
Route::post('/office-expences/edit/{id}', [AccountController::class, 'editExpenses'])->name('office-expences/edit');
Route::get('/office-expences/delete/{id}', [AccountController::class, 'deleteExpenses'])->name('office-expences.delete');

// Users
Route::get('/admin/view', [AdminController::class, 'index'])->name('admin.view');
Route::get('/admin/add', [AdminController::class, 'add'])->name('admin.add');
Route::post('/admin/add', [AdminController::class, 'add'])->name('admin.add');
Route::get('/admin/list_data', [AdminController::class, 'listData'])->name('admin.listData');
Route::post('/admin/change_status', [AdminController::class, 'changeStatus'])->name('admin.changeStatus');
Route::get('/admin/delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');
Route::get('/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
Route::post('/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
