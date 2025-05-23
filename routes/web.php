<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\YogaController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RenewalController;
use App\Http\Controllers\InvoiceController;
use App\Http\Middleware\OperationMiddleware;
use App\Http\Controllers\CronController;

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/access_denied/index/{back?}', function ($back = null) {
    return view('errors.access_denied', ['back' => $back]);
})->name('access.denied');

Route::get('/cron/update-renew-data', [CronController::class, 'updateRenewData']);

Route::middleware([OperationMiddleware::class])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Leads
    Route::get('/lead', function () {
        return view('leads');
    })->name('leads');
    Route::post('/lead', [LeadController::class, 'getLeads'])->name('lead');
    Route::match(['get', 'post'], '/lead/add', [LeadController::class, 'addLead']);
    Route::get('/lead/profile', [LeadController::class, 'viewProfile']);
    Route::post('/lead/profile', [LeadController::class, 'getProfile']);
    Route::get('/lead/edit', [LeadController::class, 'editProfile']);
    Route::post('/lead/edit', [LeadController::class, 'editLead']);
    Route::post('/lead/changeReadStatus', [LeadController::class, 'changeReadStatus']);
    Route::post('/lead/delete', [LeadController::class, 'deleteData']);
    Route::post('/lead/changeStatus', [LeadController::class, 'changeLeadStatus']);


    // telecalling
    Route::get('/telecalling', function () {
        return view('telecalling');
    })->name('telecalling');
    Route::post('/telecalling/view', [LeadController::class, 'getTellcalling'])->name('getTellcalling');
    Route::get('/telecalling/profile', [LeadController::class, 'viewProfile']);
    Route::post('/telecalling/profile', [LeadController::class, 'getProfile']);
    Route::post('/telecalling/changeStatus', [LeadController::class, 'changeStatusToYoga']);
    Route::post('/telecalling/changeStatusToLeads', [LeadController::class, 'changeStatusToLeads']);
    Route::post('/telecalling/delete', [LeadController::class, 'deleteData']);

    // Customers
    Route::get('/customer', function () {
        return view('customer');
    })->name('customer');
    Route::post('/customer/view', [LeadController::class, 'getCustomer'])->name('getCustomer');
    Route::get('/customer/profile', [LeadController::class, 'viewProfile']);
    Route::post('/customer/profile', [LeadController::class, 'getProfile']);
    Route::post('/customer/changeStatusToTelecalling', [LeadController::class, 'changeStatusToTelecalling']);
    Route::post('/customer/delete', [LeadController::class, 'deleteData']);


    // Users
    Route::get('/admin/view', [AdminController::class, 'index'])->name('admin.view');
    Route::get('/admin/add', [AdminController::class, 'add'])->name('admin.add');
    Route::post('/admin/add', [AdminController::class, 'add'])->name('admin.add');
    Route::get('/admin/list_data', [AdminController::class, 'listData'])->name('admin.listData');
    Route::post('/admin/change_status', [AdminController::class, 'changeStatus'])->name('admin.changeStatus');
    Route::get('/admin/delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');
    Route::get('/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::post('/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');

    // Trainers
    Route::get('/recruiter', function () {
        return view('recruiter');
    })->name('recruiter');
    Route::post('/recruiter/view', [TrainerController::class, 'getRecruiter'])->name('getRecruiter');
    Route::get('/recruiter/add', [TrainerController::class, 'addRecruit'])->name('addRecruit');


    Route::get('/trainers', function () {
        return view('trainer');
    })->name('trainers');

    Route::post('/trainers/view', [TrainerController::class, 'getTrainer'])->name('getTrainer');
    Route::post('/trainers/add', [TrainerController::class, 'savedata']);
    Route::get('/trainers/profile', [TrainerController::class, 'viewProfile']);
    Route::post('/trainers/profile', [TrainerController::class, 'getProfileDetails']);
    Route::post('/trainers/changeReadStatus', [TrainerController::class, 'changeReadStatus']);
    Route::get('/trainers/edit', [TrainerController::class, 'viewTrainerbyId']);
    Route::get('/trainers/edit', function () {
        return view('edit-trainer-profile');
    })->name('trainers.edit');
    Route::post('/trainers/edit', [TrainerController::class, 'viewTrainerbyId']);
    Route::post('/trainers/changeStatus', [TrainerController::class, 'changeStatus']);
    Route::post('/trainers/delete', [TrainerController::class, 'deleteData']);
    Route::post('/trainers/is_featured_trainer', [TrainerController::class, 'isFeaturedTrainer']);
    Route::post('/trainers/show_trainer', [TrainerController::class, 'showTrainer']);

    // Events
    Route::get('/event', function () {
        return view('events');
    })->name('event');

    Route::post('/event/view', [EventController::class, 'getEvent'])->name('getEvent');
    Route::match(['get', 'post'], '/event/add', [EventController::class, 'addEvents'])->name('event.add');
    Route::get('/event/edit', [EventController::class, 'editEvents'])->name('event.edit');
    Route::post('/event/profile', [EventController::class, 'getBookingProfile'])->name('getBookingProfile');
    Route::get('/event/profile/{id}', [EventController::class, 'getBookingProfile']);
    Route::post('/event/delete', [EventController::class, 'deleteData']);

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

    // Renewal
    Route::get('/renewal', [RenewalController::class, 'index'])->name('customer.renewal');
    Route::post('/renewal/view', [RenewalController::class, 'getRenewal']);
    Route::get('/renewal/edit', [RenewalController::class, 'editRenewal']);
    Route::post('/renewal/delete', [RenewalController::class, 'deleteData']);
    Route::post('/renewal/skipRenew', [RenewalController::class, 'skipRenew']);
    Route::post('/renewal/moveToRenew', [RenewalController::class, 'moveToRenew']);
});


Route::get('/counts', [DashboardController::class, 'counts'])->name('dashboard.counts');

Route::post('/get-countries', [CommonController::class, 'getCountries']);
Route::post('/get-states', [CommonController::class, 'getStates']);
Route::post('/get-cities', [CommonController::class, 'getCities']);

// All Data
Route::get('/allData', [DataController::class, 'viewAllData'])->name('allData');
Route::post('/allData', [DataController::class, 'allData']);
Route::get('/rejected', [DataController::class, 'rejectedView'])->name('rejected');
Route::post('/rejected', [DataController::class, 'rejected']);

// invoice
Route::get('/invoice/yoga', [InvoiceController::class, 'yoga']);
Route::get('/invoice/event', [InvoiceController::class, 'event']);
Route::get('/invoice', [InvoiceController::class, 'index']);
