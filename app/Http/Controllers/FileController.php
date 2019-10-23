<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class FileController extends CommonController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function ckUploadImage(Request $request){
        if ($request->hasFile('upload')){
            $files = $request->file('upload');
            $allowedfileExtension=['jpg','png'];
            $path = '';
            $filename = strtotime(date("D M d, Y G:i")) . '_' . $files->getClientOriginalName();

            $fileExtension = strtolower($files->getClientOriginalExtension());
            $checkExtension = in_array($fileExtension, $allowedfileExtension);

            if ($checkExtension){
                $path = $files->storeAs('public/ckeditor', $filename);
            }
        }

        $return = array(
            "uploaded"=> 1,
            "fileName"=> $filename,
            "url" => Storage::url('ckeditor/'.$filename),
//            "url" => asset('storage/ckeditor/'.$filename),
        );
        return response(json_encode($return), 200)->header('Content-Type', 'application/json');
    }
}
