<?php

use App\Http\Controllers\FixturesController;
use App\Http\Controllers\PredictionsController;
use App\Http\Controllers\StandingsController;
use App\Http\Controllers\TeamsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/predictions', [PredictionsController::class, 'getPredictions']);

Route::get('/standings', [StandingsController::class, 'getStandings']);

Route::get('/teams', [TeamsController::class, 'getTeams']);

Route::get('/current-tour', [FixturesController::class, 'getCurrentTour']);
Route::get('/fixtures', [FixturesController::class, 'getGeneratedFixtures']);
Route::get('/fixtures/exist', [FixturesController::class, 'fixturesExist']);
Route::get('/fixtures/{tour}', [FixturesController::class, 'getFixtures']);
Route::post('/fixtures/generate', [FixturesController::class, 'generate']);
Route::delete('/fixtures/reset', [FixturesController::class, 'resetFixtures']);
Route::post('/fixtures/simulate', [FixturesController::class, 'simulateChampionship']);
Route::post('/fixtures/simulate/tour', [FixturesController::class, 'simulate']);
