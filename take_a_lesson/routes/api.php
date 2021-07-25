<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user_controller;
use App\Http\Controllers\course_controller;
use App\Http\Controllers\course_reviews__controller;
use App\Http\Controllers\crs_cat_controller;//
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
Route::get("data",[user_controller::class ,"get_data"]);
Route::post("add_course_catagory",[crs_cat_controller::class ,"add_cat"]);
Route::post("add_course",[course_controller::class ,"add_courses"]);
Route::post("CourseList",[course_controller::class ,"get_courses"]);

//CourseContent
Route::post("AddCourseContent",[course_controller::class ,"add_course_content"]);
Route::post("AllCourseContentList",[course_controller::class ,"all_course_content_list"]);
Route::post("DeleteCourseContent",[course_controller::class ,"delete_course_content"]);

//course review
Route::post("AddCourseReview",[course_reviews__controller::class ,"add_course_review"]);
Route::post("CourseReviewList",[course_reviews__controller::class ,"get_course_review"]);


