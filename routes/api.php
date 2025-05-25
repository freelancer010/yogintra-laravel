<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/trainers', [ApiController::class, 'getAllTrainers']);
Route::get('/trainers/featured', [ApiController::class, 'getAllFeaturedTrainersWithLimit']);
Route::post('/leads', [ApiController::class, 'addLead']);
Route::post('/recruitments', [ApiController::class, 'addRecruitment']);
Route::post('/events', [ApiController::class, 'addEventData']);
Route::post('/trainers/search', [ApiController::class, 'searchTrainers']);
