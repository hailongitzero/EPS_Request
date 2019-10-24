<?php

namespace App\Http\Controllers;

use App\MdRequestManage;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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

    public function fileManualManage(){
        if (Auth::user()){
            $userID = Auth::user()->username;
            $totalNewRequest = MdRequestManage::whereIn('trang_thai', [0])->count();
            $pendingRequest = MdRequestManage::whereIn('trang_thai', [1,2])->count();
            $totalAssignRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [1,2])->count();
            $totalMyRequest = MdRequestManage::where('user_yeu_cau', $userID)->whereIn('trang_thai', [0,1,2,3,4])->count();
            $totalMyCompleteRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [3,4])->count();
            $totalCompleteRequest = MdRequestManage::whereIn('trang_thai', [3,4])->count();
        }else{
            $totalNewRequest = 0;
            $pendingRequest = 0;
            $totalAssignRequest = 0;
            $totalMyRequest = 0;
            $totalMyCompleteRequest = 0;
            $totalCompleteRequest = 0;
        }


        $contentData = array(
            'masterData' => array(
                'activeMenu' => 9,
                'totalNewRequest' => $totalNewRequest,
                'totalAssignRequest' => $totalAssignRequest,
                'pendingRequest' =>$pendingRequest,
                'totalMyRequest' => $totalMyRequest,
                'totalMyCompleteRequest' => $totalMyCompleteRequest,
                'totalCompleteRequest' => $totalCompleteRequest,
            ),
        );
        return view('fileManage', $contentData);
    }
}
