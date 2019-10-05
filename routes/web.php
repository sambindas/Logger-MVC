<?php

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
//Dashboard
Route::get('/', 'DashboardController@Dashboard');

Route::post('/', 'DashboardController@Dashboard');

Route::get('/incident/media/{id}', 'DashboardController@Media');

Route::get('/incident/edit/{id}', 'DashboardController@Edit');

//Auth
Route::post('/login', 'LoginController@Login');

//Issue

Route::post('/submitIssue', 'IssueController@Submit');

Route::post('/issueType', 'IssueController@issueType');

Route::post('/issueLevel', 'IssueController@issueLevel');

Route::get('/incident/new', 'IssueController@Create');

Route::post('/fetchTable', 'IssueController@FetchTable');

Route::post('/incident/media/ProcessMediaUpload', 'IssueController@UploadMedia');

Route::post('/updateCaption', 'IssueController@updateCaption');

Route::post('/deleteMedia', 'IssueController@deleteMedia');

Route::post('/incident/edit', 'IssueController@Edit');

//Issue Modal Actions

Route::post('/processAction', 'IssueController@ProcessAction');

//Facility

Route::get('/facility', 'FacilityController@Dashboard');
