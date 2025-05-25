<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/get_all_trainer', [ApiController::class, 'getAllTrainers']);
Route::get('/get_all_trainer_limit', [ApiController::class, 'getAllFeaturedTrainersWithLimit']);
Route::post('/addLeads', [ApiController::class, 'addLead']);
Route::post('/addRecruitments', [ApiController::class, 'addRecruitment']);
Route::post('/addEventData', [ApiController::class, 'addEventData']);
Route::post('/getTrainerSearchData', [ApiController::class, 'searchTrainers']);
