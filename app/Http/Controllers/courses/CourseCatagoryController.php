<?php

namespace App\Http\Controllers\courses;

use App\Http\Controllers\Controller;
use App\Models\courses\course_cat;
use Validator;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\courses\mast_course;

class CourseCatagoryController extends Controller
{
     //add_catagoty_list

    function add_course_catagory(Request $request){
 
     try{
 
        $validator = Validator::make($request->all(), 
         [ 
         'cat_name' => 'required',
    
      
 
     //       $request->content_file_url==0&&$request->action!="add"?
      
        
     //    :'cat_name' => 'required|mimes:png,jpg|max:2048',
        ]); 
 
       
  if ($validator->fails()) {          
     return response()->json(['statuscode'=>"oxd2",'error'=>$validator->errors()], 200);                        
  } 
  
  else{
     
     if($request->action=="add"){
         
     $input = $request->all();
     $cours_cat = new course_cat;
     $cours_cat->cat_id =  uniqid();
     $cours_cat->cat_name = $request->cat_name;
    
      
      $extension = $input['cat_image']->extension();
 
      $destinationPath = 'public/Course_Catagory_Image';
      $path = Storage::putFileAs(
          $destinationPath, $request->file('cat_image'), $cours_cat->cat_id.".".$extension
       );
 
 
 
      $cours_cat->cat_image = $path;
 
      $result = $cours_cat->save();
 
          if($result){
          return response()->json(['statuscode'=>"0xd1",'message'=>"Catagory Add successfully",],200);
           }else{
 
              return response()->json(['statuscode'=>"0xd2",'message'=>"Its seems some problem, please try again later.",],200);
 
          }
     }


     else{
 
      
 
         $input = $request->all();
         $cours_cat = course_cat::where('cat_id',$request->cat_id)->first();
         $cours_cat->cat_name = $request->cat_name;
        
 
        if($request->cat_image!=""){
 
     
 
       if(Storage::exists($cours_cat->cat_image)) {
 
       Storage::delete($cours_cat->cat_image);
       }
     
         //add file     
         $extension = $input['cat_image']->extension();
 
         $destinationPath = 'public/Course_Catagory_Image';
         $path = Storage::putFileAs(
             $destinationPath, $request->file('cat_image'), $cours_cat->cat_id.".".$extension
          );
    ///http://localhost:8000/storage/Course_Catagory_Image/60fc79b45880b.jpg
 
 
      $cours_cat->cat_image = $path;
          }
          $result = $cours_cat->save();
 
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


//all_catagoty_list



function all_catagoty_list(Request $request){
    try{
    
        $data = course_cat::all(); //whre user id ==120
       
         return response()->json(['statuscode'=>"0xd1",'message'=>"ok", 'CatagoryList'=>$data],200);
       
    
    
    }
    catch (\Exception $e) {
    
        return response()->json(['statuscode'=>"oxd3",'message'=>"Its seems some problem, please try again later.","error"=>$e->getMessage()],200);
    
    }
    
    }
    
    //delete_catagoty
    function delete_catagoty(Request $request){
    try{
    
        $course_cat = course_cat::where('cat_id',$request->cat_id)->first();
    
        if(Storage::exists($course_cat->cat_image)) {
    
            Storage::delete($course_cat->cat_image);
            }
    
        $result = course_cat::where('cat_id',$request->cat_id)->delete(); 
    
        if($result){
           return response()->json(['statuscode'=>"0xd1",'message'=>"Catagory Delete successfully",],200);
            }else{
    
          return response()->json(['statuscode'=>"0xd2",'error'=>$result,'message'=>'Its seems some problem, please try again later.'],200);
    
           }
       
    
    
    }
    catch (\Exception $e) {
    
        return response()->json(['statuscode'=>"oxd3",'message'=>"Its seems some problem, please try again later.","error"=>$e->getMessage()],200);
    
    }
    
    }




}
