<?php

use Illuminate\Http\Request;
header("Cache-Control: no-cache, must-revalidate");
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
});
//get api
Route::get('/SchoolUrls', 'PrincipalapiController@schoolurls');
Route::get('/ClassRoomList', 'PrincipalapiController@ClassRoomList');
Route::get('/TeacherList', 'PrincipalapiController@TeacherList');
Route::get('/classListApi', 'PrincipalapiController@classList');
Route::get('/complaintList', 'PrincipalapiController@complaintList');
Route::get('/videos', 'PrincipalapiController@videoListAll');
//post api
Route::post('/SubjectList', 'PrincipalapiController@SubjectList');
Route::post('/createClassRoom', 'PrincipalapiController@createClassRoom');
Route::post('/createchatRoom', 'PrincipalapiController@createchatRoom');
Route::post('/createTeacherRoom', 'PrincipalapiController@createTeacherRoom');
Route::post('/TopicList', 'PrincipalapiController@TopicList');
Route::post('/searchvideo', 'PrincipalapiController@searchvideo');
Route::post('/VideoDetails', 'PrincipalapiController@VideoDetails');
Route::post('/videoByTeacher', 'PrincipalapiController@videoByTeacher');

Route::post('/login', 'PrincipalapiController@login');
Route::post('/sectionList', 'PrincipalapiController@sectionList');
Route::post('/StudentAttendance', 'PrincipalapiController@StudentAttendance');
Route::post('/TeacherAttendance', 'PrincipalapiController@TeacherAttendance');
Route::post('/TeacherProxy', 'PrincipalapiController@TeacherProxy');
Route::post('/absentteachers', 'PrincipalapiController@absentteachers');
Route::post('/dashboard', 'PrincipalapiController@dashboard');
Route::post('/totalFees', 'PrincipalapiController@totalFees');
Route::post('/todayFees', 'PrincipalapiController@todayFees');
Route::post('/transportfees', 'PrincipalapiController@transportfees');
Route::post('/enquriy', 'PrincipalapiController@enquriy');
Route::post('/enquriyDetails', 'PrincipalapiController@enquriyDetails');
Route::post('/staffAttendancenew', 'PrincipalapiController@staffAttendancenew');
Route::post('/studentAttendancenew', 'PrincipalapiController@studentAttendancenew');
Route::post('/teacherstimetable', 'PrincipalapiController@teacherstimetable');
Route::post('/events', 'PrincipalapiController@events');
Route::post('/appointments', 'PrincipalapiController@appointments');
Route::post('/appointmentsCount', 'PrincipalapiController@appointmentsCount');
Route::post('/todaytransportfees', 'PrincipalapiController@todaytransportfees');
Route::post('/workload', 'PrincipalapiController@workload');
Route::post('/feesDetailByCLassSection', 'PrincipalapiController@feesDetailByCLassSection');
Route::post('/totalClassFees', 'PrincipalapiController@totalClassFees');
Route::post('/daily_activities', 'PrincipalapiController@daily_activities');
Route::post('/vehicles_list', 'PrincipalapiController@vehicles_list');
Route::post('/totalTransportFees', 'PrincipalapiController@totalTransportFees');
Route::post('/transportStudentList', 'PrincipalapiController@transportStudentList');
Route::post('/totaltransportClassFees', 'PrincipalapiController@totaltransportClassFees');
Route::post('/totaltransportClassSectionFees','PrincipalapiController@totaltransportClassSectionFees');
Route::post('/appstatuscount', 'PrincipalapiController@appstatuscount');
Route::post('/classes', 'PrincipalapiController@classes');
Route::post('/sections', 'PrincipalapiController@sections');
Route::post('/appstatusclassSectionwise', 'PrincipalapiController@appstatusclassSectionwise');
Route::post('/teachersActivities', 'PrincipalapiController@teachersActivities');
Route::post('/updatestatus', 'PrincipalapiController@updatecomplainstatus');
Route::post('/principalseen', 'PrincipalapiController@principalseen');
Route::post('/complainstatus', 'PrincipalapiController@updatecomplainstatus');

