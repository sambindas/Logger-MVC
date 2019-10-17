<?php
use App\Issue;
use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('/incidents', 'Api\IncidentController');
Route::get('incidents', 'Api\IncidentController@incidents');
Route::get('incidents/{id}', 'Api\IncidentController@incidents');

#User Actions
Route::get('login', 'Api\UserController@login'); #login user
Route::post('register', 'Api\UserController@register'); #register user (client and support)
Route::put('activate/{id}', 'Api\UserController@activate'); #Activate a user
Route::put('deactivate/{id}', 'Api\UserController@deactivate'); #Deactivate a user
