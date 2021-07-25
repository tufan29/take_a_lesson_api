<?php

namespace App\Http\Controllers\courses;
//
use App\Models\courses\course_review;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseReviewController extends Controller
{
    function add_course_review(Request $request){

        try{

           $validator = Validator::make($request->all(), 
            [ 
            'cur_id' => 'required',
            'cur_review_Student_id' => 'required',
            'cur_review_Student_name' => 'required',
            'cur_review_msg' => 'required',
            'cur_review_ratting' => 'required',
           
           ]); 

          
               if ($validator->fails()) {          
                    return response()->json(['statuscode'=>"oxd2",'error'=>$validator->errors()], 200);                        
                } 
     
     else{
            
        $input = $request->all();
        $cours_reviw = new course_review;
        $cours_reviw->cur_review_id =  uniqid();
        $cours_reviw->cur_id = $request->cur_id;
        $cours_reviw->cur_review_Student_id = $request->cur_review_Student_id;
        $cours_reviw->cur_review_Student_name =  $request->cur_review_Student_name;
        $cours_reviw->cur_review_msg = $request->cur_review_msg;
        $cours_reviw->cur_review_ratting = $request->cur_review_ratting;

         $result = $cours_reviw->save();
  
             if($result){
             return response()->json(['statuscode'=>"0xd1",'message'=>"Add review successfully",],200);
              }else{

            return response()->json(['statuscode'=>"0xd2",'message'=>"Its seems some problem, please try again later",],200);
 
             }
        }

    } 
     catch (\Exception $e) {

            return response()->json(['statuscode'=>"oxd3",'message'=>"Its seems some problem, please try again later.","error"=>$e->getMessage()],200);

        }
    }

//get_course_review

function get_course_review(Request $request){
    try{

        
        $count = course_review::where('cur_id',$request->cur_id)->count();
      //  $skip = {{$request->ind==1 ? 0 :ind*5 }};
     // $skip = @if($request->ind==1) 0 @else ind*5;// @endif
     $r = $request->page_index;
     $skip = $r==1?0:($r-1)*5;

        $limit = $count - $skip; // the limit
        $users = course_review::where('cur_id',$request->cur_id)->skip($skip)->take(5)->get();

         return response()->json(['statuscode'=>"0xd1",'message'=>"ok", 'total'=>$count,'skip'=>$skip,'CourseReviewList'=>$users],200);
       

 
    }
 catch (\Exception $e) {

        return response()->json(['statuscode'=>"oxd3",'message'=>"Its seems some problem, please try again later.","error"=>$e->getMessage()],200);

    }

}

}
