<?php

namespace App\Http\Controllers\courses;

use App\Http\Controllers\Controller;
use App\Models\courses\mast_course;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Storage;
class CourseController extends Controller
{
    function add_courses(Request $request){

        try{

            $validator = Validator::make($request->all(), 
            [ 
            'tea_id' => 'required',
            'cur_name' => 'required',
            'crs_cat_id' => 'required',
            'cur_teacher_name' => 'required',
            'cur_price' => 'required',
            'cur_discount' => 'required',
            'cur_payable_price' => 'required',

        //       $request->cur_image==0&&$request->action!="add"?
         
           
        //    :'cur_image' => 'required|mimes:png,jpg|max:2048',
            ],
           [
            //'tea_id.required'=>'This Phone No Already taken!',
            //'tea_id.unique'=>'This Email Already taken!',
            // 'profile_pic.max'=>'Profile picture size below 1M'
        ]
        ); 

          
     if ($validator->fails()) {          
        return response()->json(['statuscode'=>"oxd2",'error'=>$validator->errors()], 200);                        
     } 
     
     else{
        
        if($request->action=="add"){
            
        $input = $request->all();
        $cours = new mast_course;
        $cours->cur_id =  uniqid();
        $cours->tea_id = $request->tea_id;
        $cours->cur_name =  $request->cur_name;
        $cours->crs_cat_id = $request->crs_cat_id;
        $cours->cur_teacher_name =  $request->cur_teacher_name;
        $cours->cur_price = $request->cur_price
        ;$cours->cur_discount =  $request->cur_discount;
        $cours->cur_payable_price = $request->cur_payable_price;


        $extension = $input['cur_image']->extension();

        $destinationPath = 'public/'.$request->tea_id;
        $path = Storage::putFileAs(
            $destinationPath, $request->file('cur_image'), $cours->cur_id.".".$extension
         );

         $cours->cur_image = $path;

         $result = $cours->save();
  
             if($result){
             return response()->json(['statuscode'=>"0xd1",'message'=>"catagory add successfully",],200);
              }else{

            return response()->json(['statuscode'=>"0xd2",'message'=>"Its seems some problem, please try again later",],200);
 
             }
        }
        else{

            $input = $request->all();
            $cours = mast_course::where('cur_id',$request->cur_id)->first();

            $cours->tea_id = $request->tea_id;
            $cours->cur_name =  $request->cur_name;
            $cours->crs_cat_id = $request->crs_cat_id;
            $cours->cur_teacher_name =  $request->cur_teacher_name;
            $cours->cur_price = $request->cur_price;
            $cours->cur_discount =  $request->cur_discount;
            $cours->cur_payable_price = $request->cur_payable_price;
    
           if($request->cur_image!=0){
          if(Storage::exists($cours->cur_image)) {

          Storage::delete($cours->cur_image);
          }
        //    else{
        //     return response()->json(['statuscode'=>"0xd1",'message'=>"tttttttttttttt successfully",],200);
        //   }
            //add file
            $extension = $input['cur_image']->extension();
            $destinationPath = 'public/'.$request->tea_id;
           $path = Storage::putFileAs(
                $destinationPath, $request->file('cur_image'),$cours->cur_id.".".$extension
            );

           
       
       //storage/app/cur_image/flutter.jpg

         $cours->cur_image = $path;
             }
             $result = $cours->save();

             if($result){
                // $data = mast_student::where('user_id',12050)->where('id',2)->get();//bouth codition
               return response()->json(['statuscode'=>"0xd1",'message'=>"update successfully",],200);
                }else{
  
              return response()->json(['statuscode'=>"0xd2",'message'=>"Its seems some problem, please try again later",],200);
   
               }


        }



        }
        }
     catch (\Exception $e) {

            return response()->json(['statuscode'=>"oxd3",'message'=>"Its seems some problem, please try again later.","error"=>$e->getMessage()],200);

        }
    }

//CourseList
function get_courses(Request $request){
    try{

        
        $count = mast_course::where('tea_id',$request->tea_id)->count();
      //  $skip = {{$request->ind==1 ? 0 :ind*5 }};
     // $skip = @if($request->ind==1) 0 @else ind*5;// @endif
     $r = $request->page_index;
     $skip = $r==1?0:($r-1)*2;

        $limit = $count - $skip; // the limit
        $users = mast_course::where('tea_id',$request->tea_id)->skip($skip)->take(2)->get();

         return response()->json(['statuscode'=>"0xd1",'message'=>"ok", 'total'=>$count,'skip'=>$skip,'CourseList'=>$users],200);
       

 
    }
 catch (\Exception $e) {

        return response()->json(['statuscode'=>"oxd3",'message'=>"Its seems some problem, please try again later.","error"=>$e->getMessage()],200);

    }

}

}
