<?php

namespace App\Http\Controllers;

use App\Mail\SendResponseMail;
use App\MdFileUpload;
use App\MdLoaiYeuCau;
use App\Notifications\requestAssignHandle;
use App\Notifications\requestAssignInform;
use App\Notifications\requestRejectHandle;
use App\Notifications\requestComplete;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\MdRequestManage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Jobs\QueueMailSend;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class RequestController extends CommonController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * lấy danh sách user
     *
     * @return void
     */
    public function getUserList(Request $request){
        $ma_phong_ban = $request->input('ma_phong_ban');
        $user = User::where('ma_phong_ban', $ma_phong_ban)->orderBy('name')->get();

        return response($user->toJson(), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Danh sách các yêu cầu mới
     *
     * @return void
     */
    public function RequestManagement()
    {
        $userID = Auth::user()->username;

        $totalNewRequest = MdRequestManage::whereIn('trang_thai', [0])->count();
        $pendingRequest = MdRequestManage::whereIn('trang_thai', [1,2])->count();
        $totalAssignRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [1,2])->count();
        $totalMyRequest = MdRequestManage::where('user_yeu_cau', $userID)->count();
        $totalMyCompleteRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [3,4])->count();
        $totalCompleteRequest = MdRequestManage::whereIn('trang_thai', [3,4])->count();
        $assignedPerson = User::whereIn('role', [1])->get();

        $dsYeuCau = MdRequestManage::with(['phong_ban','user'])->where('trang_thai', 0)->orderBy('ngay_tao', 'desc')->get();
        $loaiYc = MdLoaiYeuCau::OrderBy('loai_yeu_cau')->get();
        $contentData = array(
            'dsYeuCau' => $dsYeuCau,
            'assignedPerson' => $assignedPerson,
            'loai_yc' => $loaiYc,
            'masterData' => array(
                'activeMenu' => 3,
                'totalNewRequest' => $totalNewRequest,
                'totalAssignRequest' => $totalAssignRequest,
                'pendingRequest' => $pendingRequest,
                'totalMyRequest' => $totalMyRequest,
                'totalMyCompleteRequest' => $totalMyCompleteRequest,
                'totalCompleteRequest' => $totalCompleteRequest,
            )
        );
        return view('requestManage', $contentData);
    }

    /**
     * Danh sách các yêu cầu mới
     *
     * @return void
     */
    public function RequestPendingManagement()
    {
        $userID = Auth::user()->username;

        $userID = Auth::user()->username;

        $totalNewRequest = MdRequestManage::whereIn('trang_thai', [0])->count();
        $pendingRequest = MdRequestManage::whereIn('trang_thai', [1,2])->count();
        $totalAssignRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [1,2])->count();
        $totalMyRequest = MdRequestManage::where('user_yeu_cau', $userID)->count();
        $totalMyCompleteRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [3,4])->count();
        $totalCompleteRequest = MdRequestManage::whereIn('trang_thai', [3,4])->count();

        $assignedPerson = User::whereIn('role', [1])->get();
        $loaiYc = MdLoaiYeuCau::OrderBy('loai_yeu_cau')->get();

        $dsYeuCau = MdRequestManage::with(['phong_ban','user', 'xu_ly'])->whereIn('trang_thai', [1,2])->orderBy('trang_thai', 'asc')->orderBy('han_xu_ly', 'asc')->get();
        $contentData = array(
            'dsYeuCau' => $dsYeuCau,
            'assignedPerson' => $assignedPerson,
            'loai_yc' => $loaiYc,
            'masterData' => array(
                'activeMenu' => 4,
                'totalNewRequest' => $totalNewRequest,
                'totalAssignRequest' => $totalAssignRequest,
                'pendingRequest' => $pendingRequest,
                'totalMyRequest' => $totalMyRequest,
                'totalMyCompleteRequest' => $totalMyCompleteRequest,
                'totalCompleteRequest' => $totalCompleteRequest,
            )
        );
        return view('requestPendingManage', $contentData);
    }

    /**
     * Danh sách các yêu cầu mới
     *
     * @return void
     */
    public function RequestCompleteManagement()
    {
        $userID = Auth::user()->username;

        $totalNewRequest = MdRequestManage::whereIn('trang_thai', [0])->count();
        $pendingRequest = MdRequestManage::whereIn('trang_thai', [1,2])->count();
        $totalAssignRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [1,2])->count();
        $totalMyRequest = MdRequestManage::where('user_yeu_cau', $userID)->count();
        $totalMyCompleteRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [3,4])->count();
        $totalCompleteRequest = MdRequestManage::whereIn('trang_thai', [3,4])->count();

        $assignedPerson = User::whereIn('role', [1])->get();
        $loaiYc = MdLoaiYeuCau::OrderBy('loai_yeu_cau')->get();

        $dsYeuCau = MdRequestManage::with(['phong_ban','user', 'xu_ly'])->whereIn('trang_thai', [3,4])->orderBy('trang_thai', 'asc')->orderBy('ngay_xu_ly', 'desc')->get();
        $contentData = array(
            'dsYeuCau' => $dsYeuCau,
            'assignedPerson' => $assignedPerson,
            'loai_yc' => $loaiYc,
            'masterData' => array(
                'activeMenu' => 5,
                'totalNewRequest' => $totalNewRequest,
                'totalAssignRequest' => $totalAssignRequest,
                'pendingRequest' => $pendingRequest,
                'totalMyRequest' => $totalMyRequest,
                'totalMyCompleteRequest' => $totalMyCompleteRequest,
                'totalCompleteRequest' => $totalCompleteRequest,
            )
        );
        return view('requestCompleteManage', $contentData);
    }

    /**
     * Lấy thông tin chi tiết yêu cầu
     *
     * @return void
     */
    public function getRequest(Request $request){
        $ma_yeu_cau = $request->ma_yeu_cau;
        $yeuCau = '';
        if (isset($ma_yeu_cau)){
            $yeuCau = MdRequestManage::with(['phong_ban','user','xu_ly', 'files', 'loai_yc'])->find($ma_yeu_cau);
        }

        return response($yeuCau->toJson(), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Lấy thông tin chi tiết yêu cầu dùng cho link tử email
     *
     * @return void
     */
    public function requestAssign($ma_yeu_cau){
        $userID = Auth::user()->username;

        $totalNewRequest = MdRequestManage::whereIn('trang_thai', [0])->count();
        $pendingRequest = MdRequestManage::whereIn('trang_thai', [1,2])->count();
        $totalAssignRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [1,2])->count();
        $totalMyRequest = MdRequestManage::where('user_yeu_cau', $userID)->count();
        $totalMyCompleteRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [3,4])->count();
        $totalCompleteRequest = MdRequestManage::whereIn('trang_thai', [3,4])->count();

        $request = MdRequestManage::with('phong_ban', 'files', 'loai_yc')->where('ma_yeu_cau', $ma_yeu_cau)->orderBy('ngay_tao', 'desc')->get();
        $assignedPerson = User::whereIn('role', [1])->get();
        $loaiYc = MdLoaiYeuCau::OrderBy('loai_yeu_cau')->get();

        $contentData = array(
            'request' => $request,
            'assignedPerson' => $assignedPerson,
            'loai_yc' => $loaiYc,
            'masterData' => array(
                'activeMenu' => 3,
                'totalNewRequest' => $totalNewRequest,
                'totalAssignRequest' => $totalAssignRequest,
                'pendingRequest' => $pendingRequest,
                'totalMyRequest' => $totalMyRequest,
                'totalMyCompleteRequest' => $totalMyCompleteRequest,
                'totalCompleteRequest' => $totalCompleteRequest,
            ),
        );

        return view('requestAssign', $contentData);
    }

    public function requestAssignSet(Request $request){
        $userId = Auth::user()->username;
        $ma_yeu_cau = $request->input('ma_yeu_cau');
        $nguoi_xu_ly = $request->input('nguoi_xu_ly');
        $newStatus = $request->input('trang_thai');
        $han_xu_ly = $request->input('han_xu_ly');
        $loai_yeu_cau = $request->input('loai_yeu_cau');
        $yeu_cau_xu_ly = $request->input('yeu_cau_xu_ly');

        $timestamp = strtotime(str_replace('/', '-', $han_xu_ly));
        $date = date('Y-m-d',$timestamp);

        $updateReq = MdRequestManage::find($ma_yeu_cau);
        if ($updateReq->trang_thai != self::YEU_CAU_MOI){
            return redirect()->route('assignRequest',[$ma_yeu_cau])->with('error', 'Yêu cầu đã/đang được xử lý, không thể cập nhật.');
        } else if($nguoi_xu_ly == "" && $newStatus != self::HOAN_THANH && $newStatus != self::TU_CHOI){
            return response(['info' => 'fail', 'Content' => 'Vui lòng chọn người xử lý.'], 200)->header('Content-Type', 'application/json');
        }else{
            try{
                //admin xử lý luôn yêu cầu
                if ($newStatus == self::HOAN_THANH || $newStatus == self::TU_CHOI){
                    $updateReq->trang_thai = $newStatus;
                    $updateReq->nguoi_xu_ly = $userId;
                    $updateReq->ngay_xu_ly = Carbon::now();
                    $updateReq->thong_tin_xu_ly = $yeu_cau_xu_ly;
                    $updateReq->loai_yeu_cau = $loai_yeu_cau;
                    $updateReq->save();
                }else{
                    //assign cho nhân viên xử lý
                    $updateReq->trang_thai = self::TIEP_NHAN;
                    $updateReq->nguoi_xu_ly = $nguoi_xu_ly;
                    if (isset($han_xu_ly)){
                        $updateReq->han_xu_ly = $date;
                    }
                    $updateReq->loai_yeu_cau = $loai_yeu_cau;
                    $updateReq->yeu_cau_xu_ly = $yeu_cau_xu_ly;
                    $updateReq->save();
                }
                if ($request->hasFile('attachFile')){
                    $files = $request->file('attachFile');
                    $allowedfileExtension=['pdf','jpg','png','docx', 'xlsx', 'pptx','doc', 'xls', 'ppt'];
                    foreach ($files as $file) {
                        $filename = $file->getClientOriginalName();
                        $fileExtension = strtolower($file->getClientOriginalExtension());
                        $checkExtension = in_array($fileExtension, $allowedfileExtension);

                        if ($checkExtension){
                            $path = $file->store('public');
                            $storeFileName = pathinfo($path)['basename'];
                            try{
                                $attachFile = new MdFileUpload();
                                $attachFile->ma_yeu_cau = $ma_yeu_cau;
                                $attachFile->file_name = $filename;
                                $attachFile->store_file_name = $storeFileName;
                                $attachFile->store_url = $path;
                                $attachFile->save();
                            }catch (\Exception $exception){

                            }
                        }
                    }
                }
                try{
                    $yeuCau = MdRequestManage::with('user', 'xu_ly', 'phong_ban')->find($ma_yeu_cau);
                    $data = array(
                        'ma_yeu_cau' => $ma_yeu_cau,
                        'nguoi_gui'     => $yeuCau->user['name'],
                        'phong_ban'     => $yeuCau->phong_ban['ten_phong_ban'],
                        'tieu_de'         => $yeuCau->tieu_de,
                        'noi_dung'       => $yeuCau->noi_dung,
                        'nguoi_xu_ly'   => $yeuCau->xu_ly['name'],
                        'yeu_cau_xu_ly' => $yeuCau->yeu_cau_xu_ly,
                        'thong_tin_xu_ly' => $yeuCau->thong_tin_xu_ly,
                        'trang_thai' => ($yeuCau->trang_thai == self::MAIL_YC_MOI ? "Yêu cầu mới" : ($yeuCau->trang_thai == self::TIEP_NHAN ? "Tiếp nhận" : ($yeuCau->trang_thai == self::DANG_XU_LY ? "Đang xử lý" : ($yeuCau->trang_thai == self::HOAN_THANH ? "Hoàn thành" : "Từ chối")))),
                    );
                    if ($newStatus == self::YEU_CAU_MOI || $newStatus == self::TIEP_NHAN || $newStatus == self::DANG_XU_LY){
                        //mail to assign person
                        $assignUserData = User::where('username', $nguoi_xu_ly)->get();
                        Notification::send($assignUserData, new requestAssignHandle($data));

                        //mail to requester
                        $reqUser = User::where('username', $updateReq->user_yeu_cau)->get();
                        Notification::send($reqUser, new requestAssignInform($data));

                    }else{
                        //mail to requester
                        $reqUser = User::where('username', $updateReq->user_yeu_cau)->get();
                        Notification::send($reqUser, new requestAssignInform($data));
                    }

                    return response(['info' => 'success', 'Content' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
                }catch (\Exception $exception){
                    return response(['info' => 'success', 'Content' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
                }
            }catch (\Exception $exception){
                return response(['info' => 'fail', 'Content' => 'Cập nhật thất bại, vui lòng thử lại.'], 200)->header('Content-Type', 'application/json');
            }
        }


    }

    /**
     * Lấy danh sách những yêu cầu được giao
     *
     * @return void
     */
    public function requestHandle(){

        $userID = Auth::user()->username;
        $totalNewRequest = MdRequestManage::whereIn('trang_thai', [0])->count();
        $pendingRequest = MdRequestManage::whereIn('trang_thai', [1,2])->count();
        $totalAssignRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [1,2])->count();
        $totalMyRequest = MdRequestManage::where('user_yeu_cau', $userID)->count();
        $totalMyCompleteRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [3,4])->count();
        $totalCompleteRequest = MdRequestManage::whereIn('trang_thai', [3,4])->count();

        $dsRequest = MdRequestManage::with(['phong_ban','user'])->where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [1,2])->orderBy('trang_thai', 'desc')->orderBy('han_xu_ly', 'asc')->get();

        $contentData = array(
            'dsRequest' => $dsRequest,
            'masterData' => array(
                'activeMenu' => 6,
                'totalNewRequest' => $totalNewRequest,
                'totalAssignRequest' => $totalAssignRequest,
                'pendingRequest' => $pendingRequest,
                'totalMyRequest' => $totalMyRequest,
                'totalMyCompleteRequest' => $totalMyCompleteRequest,
                'totalCompleteRequest' => $totalCompleteRequest,
            )
        );
        return view('requestHandle', $contentData);
    }

    /**
     * Lấy danh sách những yêu cầu được giao đã hoàn thành
     *
     * @return void
     */
    public function requestHandleComplete(){
        $userID = Auth::user()->username;

        $totalNewRequest = MdRequestManage::whereIn('trang_thai', [0])->count();
        $pendingRequest = MdRequestManage::whereIn('trang_thai', [1,2])->count();
        $totalAssignRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [1,2])->count();
        $totalMyRequest = MdRequestManage::where('user_yeu_cau', $userID)->count();
        $totalMyCompleteRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [3,4])->count();
        $totalCompleteRequest = MdRequestManage::whereIn('trang_thai', [3,4])->count();

        $assignedPerson = User::whereIn('role', [1])->get();

        $dsRequest = MdRequestManage::with(['phong_ban','user'])->where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [3,4])->orderBy('ngay_xu_ly', 'desc')->get();

        $contentData = array(
            'dsRequest' => $dsRequest,
            'assignedPerson' => $assignedPerson,
            'masterData' => array(
                'activeMenu' => 7,
                'totalNewRequest' => $totalNewRequest,
                'totalAssignRequest' => $totalAssignRequest,
                'pendingRequest' => $pendingRequest,
                'totalMyRequest' => $totalMyRequest,
                'totalMyCompleteRequest' => $totalMyCompleteRequest,
                'totalCompleteRequest' => $totalCompleteRequest,
            )
        );
        return view('requestHandleComplete', $contentData);
    }

    public function requestUpdate($ma_yeu_cau){
        $userID = Auth::user()->username;
        $chkReqAuth = MdRequestManage::where('nguoi_xu_ly', $userID)->where('ma_yeu_cau', $ma_yeu_cau)->count();
        if ( $chkReqAuth == 0 ) {
            return redirect('request-handle')->with('alert', 'Bạn không được phân quyền xử lý yêu cầu này.');
        }

        $totalNewRequest = MdRequestManage::whereIn('trang_thai', [0])->count();
        $pendingRequest = MdRequestManage::whereIn('trang_thai', [1,2])->count();
        $totalAssignRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [1,2])->count();
        $totalMyRequest = MdRequestManage::where('user_yeu_cau', $userID)->count();
        $totalMyCompleteRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [3,4])->count();
        $totalCompleteRequest = MdRequestManage::whereIn('trang_thai', [3,4])->count();

        $request = MdRequestManage::with(['phong_ban','user'])->where('ma_yeu_cau', $ma_yeu_cau)->orderBy('ngay_tao', 'desc')->get();
        foreach ( $request as $key=>$val){
            if ($val->trang_thai == self::HOAN_THANH || $val->trang_thai == self::TU_CHOI){
                return redirect('request-handle')->with('alert', 'Yêu cầu này đã được xử lý.');
            }
        }
        $contentData = array(
            'request' => $request,
            'masterData' => array(
                'activeMenu' => 6,
                'totalNewRequest' => $totalNewRequest,
                'totalAssignRequest' => $totalAssignRequest,
                'pendingRequest' => $pendingRequest,
                'totalMyRequest' => $totalMyRequest,
                'totalMyCompleteRequest' => $totalMyCompleteRequest,
                'totalCompleteRequest' => $totalCompleteRequest,
            ),
        );

        return view('requestUpdate', $contentData);
    }

    public function requestUpdateStatus(Request $request){
        $ma_yeu_cau = $request->input('ma_yeu_cau');
        $newStatus = $request->input('trang_thai');
        $thong_tin_xu_ly = $request->input('thong_tin_xu_ly');

        $updateReq = MdRequestManage::find($ma_yeu_cau);
        $reqUser = User::where('username', $updateReq->user_yeu_cau)->get();
        $managerUser = User::where('role', self::QUAN_LY)->get();

        $oldStatus = $updateReq->trang_thai;

        if ($oldStatus == self::HOAN_THANH ||$oldStatus == self::TU_CHOI){
            return response(['info' => 'Fail', 'Content' => 'Yêu cầu đã được xử lý, không thể cập nhật.'], 200)->header('Content-Type', 'application/json');
//            return redirect()->route('requestUpdate', [$ma_yeu_cau])->with('error', 'Yêu cầu đã được xử lý, không thể cập nhật.');
        }else {
            try {
                $updateReq->trang_thai = $newStatus == self::TIEP_NHAN ? self::DANG_XU_LY : $newStatus;
                if ($newStatus == self::HOAN_THANH || $newStatus == self::TU_CHOI){
                    $updateReq->ngay_xu_ly = Carbon::now();
                    $updateReq->nguoi_xu_ly = Auth::user()->username;
                }
                $updateReq->thong_tin_xu_ly = $thong_tin_xu_ly;
                if ($request->hasFile('attachFile')){
                    $files = $request->file('attachFile');
                    $allowedfileExtension=['pdf','jpg','png','docx', 'xlsx', 'pptx','doc', 'xls', 'ppt'];
                    foreach ($files as $file) {
                        $filename = $file->getClientOriginalName();
                        $fileExtension = strtolower($file->getClientOriginalExtension());
                        $checkExtension = in_array($fileExtension, $allowedfileExtension);

                        if ($checkExtension){
                            $path = $file->store('public');
                            $storeFileName = pathinfo($path)['basename'];
                            try{
                                $attachFile = new MdFileUpload();
                                $attachFile->ma_yeu_cau = $ma_yeu_cau;
                                $attachFile->file_name = $filename;
                                $attachFile->store_file_name = $storeFileName;
                                $attachFile->store_url = $path;
                                $attachFile->save();
                            }catch (\Exception $exception){
                                return response(['info' => 'Success', 'Content' => 'Cập nhật thất bại, không nhận được file đính kèm.'], 200)->header('Content-Type', 'application/json');
                            }
                        }
                    }
                }

                $updateReq->save();
                $yeuCau = MdRequestManage::with('user', 'xu_ly', 'phong_ban')->find($ma_yeu_cau);
                try {
                    $data = array(
                        'ma_yeu_cau'    => $ma_yeu_cau,
                        'tieu_de'       => $yeuCau->tieu_de,
                        'noi_dung'      => $yeuCau->noi_dung,
                        'nguoi_gui'     => $yeuCau->user['name'],
                        'phong_ban'     => $yeuCau->phong_ban['ten_phong_ban'],
                        'thong_tin_xu_ly' => $yeuCau->thong_tin_xu_ly,
                        'trang_thai' => ($yeuCau->trang_thai == self::HOAN_THANH ? 'Hoàn thành' : ($yeuCau->trang_thai == self::TU_CHOI ? "Từ chối" : "")),
                    );
                    if ( $newStatus == self::HOAN_THANH || $newStatus == self::TU_CHOI) {
                        Notification::send($reqUser, new requestComplete($data));
                        if (Auth::user()->role == self::PHO_QUAN_LY){
                            Notification::send($managerUser, new requestComplete($data));
                        }
                    }
                    if ( $newStatus == self::YEU_CAU_MOI ){
                        Notification::send($managerUser, new requestRejectHandle($data));
                    }

                    return response(['info' => 'Success', 'Content' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
                } catch (\Exception $exception) {
                    return response(['info' => 'Success', 'Content' => 'Cập nhật thành công, Nhưng không thể gửi được email. Vui lòng liên hệ quản trị viên.'], 200)->header('Content-Type', 'application/json');
                }
            } catch (\Exception $exception) {
                return response(['info' => 'Fail', 'Content' => 'Cập nhật thất bại, vui lòng thử lại.'], 200)->header('Content-Type', 'application/json');
            }
        }
    }

    public function myRequest(){
        $userID = Auth::user()->username;

        $totalNewRequest = MdRequestManage::whereIn('trang_thai', [0])->count();
        $pendingRequest = MdRequestManage::whereIn('trang_thai', [1,2])->count();
        $totalAssignRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [1,2])->count();
        $totalMyRequest = MdRequestManage::where('user_yeu_cau', $userID)->count();
        $totalMyCompleteRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [3,4])->count();
        $totalCompleteRequest = MdRequestManage::whereIn('trang_thai', [3,4])->count();

        $dsYeuCau = MdRequestManage::with(['phong_ban','user'])->where('user_yeu_cau', $userID)->orderBy('trang_thai', 'asc')->orderBy('ngay_tao', 'desc')->get();
        $contentData = array(
            'dsYeuCau' => $dsYeuCau,
            'masterData' => array(
                'activeMenu' => 8,
                'totalNewRequest' => $totalNewRequest,
                'totalAssignRequest' => $totalAssignRequest,
                'pendingRequest' => $pendingRequest,
                'totalMyRequest' => $totalMyRequest,
                'totalMyCompleteRequest' => $totalMyCompleteRequest,
                'totalCompleteRequest' => $totalCompleteRequest,
            )
        );
        return view('myRequest', $contentData);
    }

    public function myRequestDetail($ma_yeu_cau){
        $userID = Auth::user()->username;
        $chkReqAuth = MdRequestManage::where('user_yeu_cau', $userID)->where('ma_yeu_cau', $ma_yeu_cau)->count();
        if ( $chkReqAuth == 0 && Auth::user()->role == 0) {
            return redirect('my-request')->with('alert', 'Bạn không tạo yêu cầu này.');
        }

        $totalNewRequest = MdRequestManage::whereIn('trang_thai', [0])->count();
        $pendingRequest = MdRequestManage::whereIn('trang_thai', [1,2])->count();
        $totalAssignRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [1,2])->count();
        $totalMyRequest = MdRequestManage::where('user_yeu_cau', $userID)->count();
        $totalMyCompleteRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [3,4])->count();
        $totalCompleteRequest = MdRequestManage::whereIn('trang_thai', [3,4])->count();

        $request = MdRequestManage::with('phong_ban')->where('ma_yeu_cau', $ma_yeu_cau)->orderBy('ngay_tao', 'desc')->get();
        $contentData = array(
            'request' => $request,
            'masterData' => array(
                'activeMenu' => 8,
                'totalNewRequest' => $totalNewRequest,
                'totalAssignRequest' => $totalAssignRequest,
                'pendingRequest' => $pendingRequest,
                'totalMyRequest' => $totalMyRequest,
                'totalMyCompleteRequest' => $totalMyCompleteRequest,
                'totalCompleteRequest' => $totalCompleteRequest,
            ),
        );

        return view('myRequestDetail', $contentData);
    }

    public function fileDownload($fileName){
        $file = MdFileUpload::where('store_file_name', $fileName)->get();
        if (isset($file[0])){
            $url = storage_path('app/').$file[0]->store_url;
            return response()->download($url, $file[0]->file_name);
        }
    }
}
