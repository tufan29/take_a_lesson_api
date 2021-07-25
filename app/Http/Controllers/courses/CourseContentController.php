<?php

namespace App\Http\Controllers\courses;

use App\Http\Controllers\Controller;
use App\Models\courses\course_content;
use Validator;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\courses\mast_course;


class CourseContentController extends Controller
{
    
   ////add_course_contain

   function add_course_content(Request $request){

    try{

       $validator = Validator::make($request->all(), 
        [ 
        'cur_id' => 'required',
        'content_name' => 'required',
        'content_type' => 'required',
        'content_hour' => 'required',
        'content_min' => 'required',
        'content_index' => 'required',
     

    //       $request->content_file_url==0&&$request->action!="add"?
     
       
    //    :'content_file_url' => 'required|mimes:png,jpg|max:2048',
       ]); 

      
 if ($validator->fails()) {          
    return response()->json(['statuscode'=>"oxd2",'error'=>$validator->errors()], 200);                        
 } 
 
 else{
    
    if($request->action=="add"){
        
    $input = $request->all();
    $cours_content = new course_content;//contents_id
    $cours_content->contents_id =  uniqid();
    $cours_content->cur_id = $request->cur_id;
    $cours_content->content_name = $request->content_name;
    $cours_content->content_type =  $request->content_type;
    $cours_content->is_free =  $request->is_free;
    $cours_content->content_hour = $request->content_hour;
    $cours_content->content_min =  $request->content_min;
    $cours_content->content_index = $request->content_index;
   

     $data = mast_course::where('cur_id',$request->cur_id)->first();//get teacher id
    
     $extension = $input['content_file_url']->extension();

     $destinationPath = 'public/'.$data->tea_id.'/'.$cours_content->cur_id;
     $path = Storage::putFileAs(
         $destinationPath, $request->file('content_file_url'), $cours_content->contents_id.".".$extension
      );



     $cours_content->content_file_url = $path;

     $result = $cours_content->save();

         if($result){
         return response()->json(['statuscode'=>"0xd1",'message'=>"Add successfully",],200);
          }else{

             return response()->json(['statuscode'=>"0xd2",'message'=>"Its seems some problem, please try again later.",],200);

         }
    }
    else{

     

        $input = $request->all();
        $course_content = course_content::where('contents_id',$request->contents_id)->first();
        $course_content->content_name = $request->content_name;
        $course_content->content_type =  $request->content_type;
        $course_content->is_free =  $request->is_free;

        $course_content->content_hour = $request->content_hour;
        $course_content->content_min =  $request->content_min;
       

       if($request->content_file_url!=""){

    

      if(Storage::exists($course_content->content_file_url)) {

      Storage::delete($course_content->content_file_url);
      }
    
        //add file
        $data = mast_course::where('cur_id',$request->cur_id)->first();//get teacher id
    
        $extension = $input['content_file_url']->extension();
        $destinationPath = 'public/'.$data->tea_id.'/'.$course_content->cur_id;
        $path = Storage::putFileAs(
            $destinationPath, $request->file('content_file_url'), $course_content->contents_id.".".$extension
         );


     $course_content->content_file_url = $path;
         }
         $result = $course_content->save();

         if($result){
           return response()->json(['statuscode'=>"0xd1",'message'=>"update successfully",],200);
            }else{

          return response()->json(['statuscode'=>"0xd2",'message'=>"error",],200);

           }


    }



    }
    }
 catch (\Exception $e) {

        return response()->json(['statuscode'=>"oxd3",'message'=>"Its seems some problem, please try again later.","error"=>$e->getMessage()],200);

    }
}

//all_course_content_list



function all_course_content_list(Request $request){
try{

    $data = course_content::where('cur_id',$request->cur_id)->get(); //whre user id ==120
   
     return response()->json(['statuscode'=>"0xd1",'message'=>"ok", 'ContentList'=>$data],200);
   


}
catch (\Exception $e) {

    return response()->json(['statuscode'=>"oxd3",'message'=>"Its seems some problem, please try again later.","error"=>$e->getMessage()],200);

}

}

//delete_course_content
function delete_course_content(Request $request){
try{

    $course_content = course_content::where('contents_id',$request->contents_id)->first();

    if(Storage::exists($course_content->content_file_url)) {

        Storage::delete($course_content->content_file_url);
        }

    $result = course_content::where('contents_id',$request->contents_id)->delete(); 

    if($result){
       return response()->json(['statuscode'=>"0xd1",'message'=>"Content Delete successfully",],200);
        }else{

      return response()->json(['statuscode'=>"0xd2",'error'=>$result,'message'=>'Its seems some problem, please try again later.'],200);

       }
   


}
catch (\Exception $e) {

    return response()->json(['statuscode'=>"oxd3",'message'=>"Its seems some problem, please try again later.","error"=>$e->getMessage()],200);

}

}


}
