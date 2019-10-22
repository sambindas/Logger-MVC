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

//Auth
Route::post('/login', 'LoginController@Login');
Route::get('/logout', 'LoginController@logout');
Route::group(['middleware' => ['session']], function (){

    //Dashboard
    Route::get('/', 'DashboardController@Dashboard');

    Route::post('/', 'DashboardController@Dashboard');

    Route::get('/incident/media/{id}', 'DashboardController@Media');

    Route::get('/incident/edit/{id}', 'DashboardController@Edit');

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

    Route::post('/checkFacility', 'FacilityController@CheckFacility');

    Route::post('/newFacility', 'FacilityController@Create');

    Route::post('/editFacility', 'FacilityController@Edit');

    Route::post('/deleteFacility', 'FacilityController@Delete');

    Route::post('/editState', 'FacilityController@ChangeState');

    //User

    Route::get('/user', 'UserController@Dashboard');

    Route::post('/checkEmail', 'UserController@CheckEmail');

    Route::post('/register', 'UserController@Register');

    Route::post('/deactivate', 'UserController@Deactivate');

    Route::post('/activate', 'UserController@Activate');

    //Client

    Route::get('/client', 'UserController@ClientDashboard');

    Route::get('/getFacility', 'UserController@GetFacility');

    Route::post('/register', 'UserController@Register');

    Route::post('/deactivate', 'UserController@Deactivate');

    Route::post('/activate', 'UserController@Activate');

    //Analytics

    Route::get('/charts', 'AnalyticsController@Dashboard');

    #Activity
    Route::get('/activity/new', 'ActivityController@new');
    
    Route::get('/activity/edit/{id}', 'ActivityController@edit');

    Route::get('activity/delete/{id}', 'ActivityController@delete');

    Route::get('/activity', 'ActivityController@Dashboard');

    Route::get('/activity/view/{week}/{month}/{year}', 'ActivityController@ViewActivity');

    Route::post('/activity/summary/submit', 'ActivityController@SubmitSummary');

    Route::get('/activity/summary/add/{id}', 'ActivityController@Summary');

    Route::get('/fetchActivity', 'ActivityController@FetchActivity');
    
    Route::post('/activity/submit', 'ActivityController@Submit');
});