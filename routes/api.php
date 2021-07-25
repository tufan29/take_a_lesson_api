<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\courses\CourseController;
use App\Http\Controllers\courses\CourseContentController;
use App\Http\Controllers\courses\CourseReviewController;
use App\Http\Controllers\courses\CourseCatagoryController;


use App\Http\Controllers\teacher\AuthController as Teacher;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'teacher'
    ], function () {
    Route::post('/register', [Teacher::class, 'register']);
    Route::get('/verify/{token}', [Teacher::class, 'verify']);
    Route::post('/login', [Teacher::class, 'login']);
    Route::post('/refresh', [Teacher::class, 'refresh']);

    Route::group([
        'middleware' => 'teacher.AuthTeacher'
        ], function () {
        Route::post('/logout', [Teacher::class, 'logout']);
        Route::post('/me', [Teacher::class, 'me']);
        //course route 
    });

});

Route::post("AddCourse",[CourseController::class ,"add_courses"]);
Route::post("CourseList",[CourseController::class ,"get_courses"]);

//CourseCatagory
Route::post("AddCourseCatagory",[CourseCatagoryController::class ,"add_course_catagory"]);
Route::get("AllCourseCatagoryList",[CourseCatagoryController::class ,"all_catagoty_list"]);
Route::post("DeleteCourseCatagory",[CourseCatagoryController::class ,"delete_catagoty"]);

//CourseContent
Route::post("AddCourseContent",[CourseContentController::class ,"add_course_content"]);
Route::post("AllCourseContentList",[CourseContentController::class ,"all_course_content_list"]);
Route::post("DeleteCourseContent",[CourseContentController::class ,"delete_course_content"]);

//course review
Route::post("AddCourseReview",[CourseReviewController::class ,"add_course_review"]);
Route::post("CourseReviewList",[CourseReviewController::class ,"get_course_review"]);

