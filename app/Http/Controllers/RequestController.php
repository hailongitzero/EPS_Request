<?php

namespace App\Http\Controllers;

use App\MdFileUpload;
use App\MdLoaiYeuCau;
use App\MdPhongBan;
use App\MdReqSubPerson;
use App\Notifications\requestAssignHandle;
use App\Notifications\requestAssignInform;
use App\Notifications\requestRejectHandle;
use App\Notifications\requestComplete;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\MdRequestManage;
use App\Notifications\ExtendDateConfirm;
use App\Notifications\ExtendDateInform;
use App\Notifications\requestExtendDate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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
    public function getUserList(Request $request)
    {
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
        $assignedPerson = User::whereIn('role', [1])->get();

        $dsYeuCau = MdRequestManage::with(['phong_ban', 'user'])->where('trang_thai', 0)->orderBy('ngay_tao', 'desc')->get();

        $dsYeuCau = $this->addCheckHandleDateClass($dsYeuCau);

        $loaiYc = MdLoaiYeuCau::OrderBy('loai_yeu_cau')->get();
        $contentData = array(
            'dsYeuCau' => $dsYeuCau,
            'assignedPerson' => $assignedPerson,
            'loai_yc' => $loaiYc,
            'masterData' => $this->masterData(3),
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

        $assignedPerson = User::whereIn('role', [1])->get();
        $loaiYc = MdLoaiYeuCau::OrderBy('loai_yeu_cau')->get();

        $dsYeuCau = MdRequestManage::with(['phong_ban', 'user', 'xu_ly'])->whereIn('trang_thai', [1, 2])->orderBy('trang_thai', 'asc')->orderBy('han_xu_ly', 'asc')->get();

        $dsYeuCau = $this->addCheckHandleDateClass($dsYeuCau);

        $contentData = array(
            'dsYeuCau' => $dsYeuCau,
            'assignedPerson' => $assignedPerson,
            'loai_yc' => $loaiYc,
            'masterData' => $this->masterData(4),
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
        $assignedPerson = User::whereIn('role', [1])->get();
        $loaiYc = MdLoaiYeuCau::OrderBy('loai_yeu_cau')->get();

        $dsYeuCau = MdRequestManage::with(['phong_ban', 'user', 'xu_ly'])->whereIn('trang_thai', [3, 4])->orderBy('trang_thai', 'asc')->orderBy('ngay_xu_ly', 'desc')->get();

        $dsYeuCau = $this->addCheckHandleDateClass($dsYeuCau);

        $contentData = array(
            'dsYeuCau' => $dsYeuCau,
            'assignedPerson' => $assignedPerson,
            'loai_yc' => $loaiYc,
            'masterData' => $this->masterData(5),
        );
        return view('requestCompleteManage', $contentData);
    }

    /**
     * Lấy thông tin chi tiết yêu cầu
     *
     * @return void
     */
    public function getRequest(Request $request)
    {
        $ma_yeu_cau = $request->ma_yeu_cau;
        $yeuCau = '';
        if (isset($ma_yeu_cau)) {
            $yeuCau = MdRequestManage::with(['phong_ban', 'user', 'xu_ly', 'files', 'loai_yc', 'sub_assign.user'])->find($ma_yeu_cau);
            $mailList = explode(",", $yeuCau->cc_email);
            $yeuCau->ccMail = User::whereIn('email', $mailList)->get();
            if (Auth::user()->username == $yeuCau->nguoi_xu_ly) {
                $yeuCau->mainPerson = true;
            } else {
                $yeuCau->mainPerson = false;
            }
            
        }

        return response($yeuCau->toJson(), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Lấy thông tin chi tiết yêu cầu dùng cho link tử email
     *
     * @return void
     */
    public function requestAssign($ma_yeu_cau)
    {

        $request = MdRequestManage::with('phong_ban', 'files', 'loai_yc')->where('ma_yeu_cau', $ma_yeu_cau)->orderBy('ngay_tao', 'desc')->get();
        $assignedPerson = User::whereIn('role', [1])->get();
        $loaiYc = MdLoaiYeuCau::OrderBy('loai_yeu_cau')->get();

        $contentData = array(
            'request' => $request,
            'assignedPerson' => $assignedPerson,
            'loai_yc' => $loaiYc,
            'masterData' => $this->masterData(3),
        );

        if (Auth::user()->role == 2) {
            return view('requestAssign', $contentData);
        } elseif (MdRequestManage::where('ma_yeu_cau', $ma_yeu_cau)->where('cc_email', 'like', '%' . Auth::user()->email . '%')->count() > 0) {
            return view('requestApproveDetail', $contentData);
        } else {
            return redirect()->route('home');
        }
    }

    /**
     * Request assign handle person
     * @param Request
     * @return Response
     */
    public function requestAssignSet(Request $request)
    {
        $userId = Auth::user()->username;
        $ma_yeu_cau = $request->input('ma_yeu_cau');
        $nguoi_xu_ly = $request->input('nguoi_xu_ly');
        $newStatus = $request->input('trang_thai');
        $han_xu_ly = $request->input('han_xu_ly');
        $loai_yeu_cau = $request->input('loai_yeu_cau');
        $yeu_cau_xu_ly = $request->input('yeu_cau_xu_ly');

        $updateReq = MdRequestManage::find($ma_yeu_cau);
        
        if ($updateReq->trang_thai != self::YEU_CAU_MOI) {
            return redirect()->route('assignRequest', [$ma_yeu_cau])->with('error', 'Yêu cầu đã/đang được xử lý, không thể cập nhật.');
        } else if ($nguoi_xu_ly == "" && $newStatus != self::HOAN_THANH && $newStatus != self::TU_CHOI) {
            return response(['info' => 'fail', 'Content' => 'Vui lòng chọn người xử lý.'], 200)->header('Content-Type', 'application/json');
        } else {
            try {
                //admin xử lý luôn yêu cầu
                if ($newStatus == self::HOAN_THANH || $newStatus == self::TU_CHOI) {
                    $updateReq->trang_thai = $newStatus;
                    $updateReq->nguoi_xu_ly = $userId;
                    $updateReq->ngay_xu_ly = Carbon::now();
                    $updateReq->thong_tin_xu_ly = $yeu_cau_xu_ly;
                    $updateReq->loai_yeu_cau = $loai_yeu_cau;
                    $updateReq->save();
                } else {
                    //assign cho nhân viên xử lý
                    $updateReq->trang_thai = self::TIEP_NHAN;
                    $updateReq->nguoi_xu_ly = $nguoi_xu_ly;
                    if (isset($han_xu_ly)) {
                        $updateReq->han_xu_ly = $this->converDate($han_xu_ly, 'Y-m-d');
                    }
                    $updateReq->loai_yeu_cau = $loai_yeu_cau;
                    $updateReq->yeu_cau_xu_ly = $yeu_cau_xu_ly;
                    $updateReq->save();

                    foreach($request->all() as $key => $val){
                        if ( substr($key, 0, -1) == "nguoi_xu_ly_" ){
                            MdReqSubPerson::create(['ma_yeu_cau'=>$ma_yeu_cau, 'username'=>$val]);
                        }
                    }
                }
                if ($request->hasFile('attachFile')) {
                    $this->storageFile($request, $ma_yeu_cau);
                }
                $yeuCau = MdRequestManage::with('user', 'xu_ly', 'phong_ban', 'sub_assign')->find($ma_yeu_cau);

                $data = $this->emailData($yeuCau);
                if ($newStatus == self::YEU_CAU_MOI || $newStatus == self::TIEP_NHAN || $newStatus == self::DANG_XU_LY) {
                    //mail to assign person
                    $assignUserData = User::where('username', $nguoi_xu_ly)->orWhere('username')->get();
                    Notification::send($assignUserData, new requestAssignHandle($data));
                    //mail to sub assign person
                    $subAssignUser = $yeuCau->sub_assign;
                    Notification::send($subAssignUser, new requestAssignHandle($data));
                    //mail to requester
                    $reqUser = User::where('username', $updateReq->user_yeu_cau)->get();
                    Notification::send($reqUser, new requestAssignInform($data));
                } else {
                    //mail to requester
                    $reqUser = User::where('username', $updateReq->user_yeu_cau)->get();
                    Notification::send($reqUser, new requestComplete($data));
                }

                return response(['info' => 'success', 'Content' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
            } catch (\Exception $exception) {
                return response(['info' => 'fail', 'Content' => 'Cập nhật thất bại, vui lòng thử lại.'], 200)->header('Content-Type', 'application/json');
            }
        }
    }

    /**
     * Lấy danh sách những yêu cầu được giao
     *
     * @return void
     */
    public function requestHandle()
    {

        $userID = Auth::user()->username;

        $dsRequest = MdRequestManage::with(['phong_ban', 'user'])
            ->whereIn('trang_thai', [1, 2])    
            ->where('nguoi_xu_ly', $userID)
            ->orWhereHas('sub_assign', function($query) use ($userID){
                $query->where('username', $userID);
            })
            ->orderBy('trang_thai', 'desc')
            ->orderBy('han_xu_ly', 'asc')->get();
        $dsRequest = $this->addCheckHandleDateClass($dsRequest);

        $contentData = array(
            'dsRequest' => $dsRequest,
            'masterData' => $this->masterData(6),
        );
        return view('requestHandle', $contentData);
    }

    /**
     * Lấy danh sách những yêu cầu được giao đã hoàn thành
     *
     * @return void
     */
    public function requestHandleComplete()
    {
        $userID = Auth::user()->username;

        $assignedPerson = User::whereIn('role', [1])->get();

        $dsRequest = MdRequestManage::with(['phong_ban', 'user'])->where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [3, 4])->orderBy('ngay_xu_ly', 'desc')->get();

        $dsRequest = $this->addCheckHandleDateClass($dsRequest);

        $contentData = array(
            'dsRequest' => $dsRequest,
            'assignedPerson' => $assignedPerson,
            'masterData' => $this->masterData(7),
        );

        return view('requestHandleComplete', $contentData);
    }

    /**
     * Update Request
     * @param ma_yeu_cau
     * @return view
     */
    public function requestUpdate($ma_yeu_cau)
    {
        $userID = Auth::user()->username;
        $chkReqAuth = MdRequestManage::where('nguoi_xu_ly', $userID)->orWhereHas('sub_assign', function($query) use ($userID){
            $query->where('username', $userID);
        })->where('ma_yeu_cau', $ma_yeu_cau)->count();

        if ($chkReqAuth == 0) {
            return redirect('request-handle')->with('alert', 'Bạn không được phân quyền xử lý yêu cầu này.');
        }

        $request = MdRequestManage::with(['phong_ban', 'user', 'xu_ly', 'files', 'loai_yc', 'sub_assign.user'])->where('ma_yeu_cau', $ma_yeu_cau)->orderBy('ngay_tao', 'desc')->get();

        foreach ($request as $key => $val) {
            if ($val->trang_thai == self::HOAN_THANH || $val->trang_thai == self::TU_CHOI) {
                return redirect('request-handle')->with('alert', 'Yêu cầu này đã được xử lý.');
            }
        }
        $contentData = array(
            'request' => $request,
            'masterData' => $this->masterData(6),
        );

        return view('requestUpdate', $contentData);
    }

    /**
     * Update Request Status
     * @param Request
     */
    public function requestUpdateStatus(Request $request)
    {
        $ma_yeu_cau = $request->input('ma_yeu_cau');
        $newStatus = $request->input('trang_thai');
        $thong_tin_xu_ly = $request->input('thong_tin_xu_ly');

        //get Request
        $updateReq = MdRequestManage::with('user', 'xu_ly', 'phong_ban')->find($ma_yeu_cau);

        $reqUser = User::where('username', $updateReq->user_yeu_cau)->get();
        $managerUser = User::where('role', self::QUAN_LY)->get();

        $oldStatus = $updateReq->trang_thai;

        if ( $updateReq->nguoi_xu_ly != Auth::user()->username) {
            return response(['info' => 'Fail', 'Content' => 'Bạn không phải là người xử lý yêu cầu.'], 200)->header('Content-Type', 'application/json');
        }
        if ($oldStatus == self::HOAN_THANH || $oldStatus == self::TU_CHOI) {
            return response(['info' => 'Fail', 'Content' => 'Yêu cầu đã được xử lý, không thể cập nhật.'], 200)->header('Content-Type', 'application/json');
        } else {
            try {
                if ($updateReq->gia_han == 0 && $request->gia_han == 1) {
                    //yêu cầu gia hạn thời gian xử lý
                    $ngay_gia_han = $this->converDate($request->input('ngay_gia_han'), 'Y-m-d');
                    $updateReq->gia_han = 1;
                    $updateReq->ngay_gia_han = $ngay_gia_han;
                    $updateReq->noi_dung_gia_han = $request->input('noi_dung_gia_han');
                    $updateReq->trang_thai = self::DANG_XU_LY;
                    $updateReq->save();

                    Notification::send($managerUser, new requestExtendDate($this->emailData($updateReq)));

                } else if ($newStatus == self::HOAN_THANH || $newStatus == self::TU_CHOI) {
                    // xử lý yêu cầu
                    $updateReq->ngay_xu_ly = Carbon::now();
                    $updateReq->nguoi_xu_ly = Auth::user()->username;
                    $updateReq->thong_tin_xu_ly = $thong_tin_xu_ly;
                    $updateReq->trang_thai = $newStatus;
                    if ($request->hasFile('attachFile')) {
                        $this->storageFile($request, $ma_yeu_cau);
                    }
                    $updateReq->save();

                    Notification::send($reqUser, new requestComplete($this->emailData($updateReq)));
                    if (Auth::user()->role == self::PHO_QUAN_LY) {
                        Notification::send($managerUser, new requestComplete($this->emailData($updateReq)));
                    }
                } else if ($newStatus == self::YEU_CAU_MOI){
                    //yêu cầu chuyển người xử lý
                    $updateReq->thong_tin_xu_ly = $thong_tin_xu_ly;
                    $updateReq->trang_thai = $newStatus;
                    if ($request->hasFile('attachFile')) {
                        $this->storageFile($request, $ma_yeu_cau);
                    }
                    $updateReq->save();
                    Notification::send($managerUser, new requestRejectHandle($this->emailData($updateReq)));
                }
                return response(['info' => 'Success', 'Content' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
            } catch (\Exception $exception) {
                return response(['info' => 'Fail', 'Content' => 'Cập nhật thất bại, vui lòng thử lại.'], 401)->header('Content-Type', 'application/json');
            }
        }
    }

    /**
     * List User Request
     */
    public function myRequest()
    {
        $userID = Auth::user()->username;

        $dsYeuCau = MdRequestManage::with(['phong_ban', 'user', 'xu_ly'])->where('user_yeu_cau', $userID)->orderBy('trang_thai', 'asc')->orderBy('ngay_tao', 'desc')->get();

        $dsYeuCau = $this->addCheckHandleDateClass($dsYeuCau);

        $contentData = array(
            'dsYeuCau' => $dsYeuCau,
            'masterData' => $this->masterData(8),
        );
        return view('myRequest', $contentData);
    }

    /**
     * Detail User Request
     */
    public function myRequestDetail($ma_yeu_cau)
    {
        $userID = Auth::user()->username;
        $chkReqAuth = MdRequestManage::where('user_yeu_cau', $userID)->where('ma_yeu_cau', $ma_yeu_cau)->count();
        if ($chkReqAuth == 0 && Auth::user()->role == 0) {
            return redirect('my-request')->with('alert', 'Bạn không tạo yêu cầu này.');
        }

        $request = MdRequestManage::with('phong_ban')->where('ma_yeu_cau', $ma_yeu_cau)->orderBy('ngay_tao', 'desc')->get();
        $contentData = array(
            'request' => $request,
            'masterData' => $this->masterData(8),
        );

        return view('myRequestDetail', $contentData);
    }

    /**
     * Download file
     * @param filename
     */
    public function fileDownload($fileName)
    {
        $file = MdFileUpload::where('store_file_name', $fileName)->get();
        if (isset($file[0])) {
            $url = storage_path('app/') . $file[0]->store_url;
            return response()->download($url, $file[0]->file_name);
        }
    }

    /**
     * Approval Request
     */
    public function approvalRequest()
    {

        $assignedPerson = User::whereIn('role', [1])->get();

        $dsRequest = MdRequestManage::with(['phong_ban', 'user'])->where('cc_email', 'like', '%' . Auth::user()->email . '%')->orderBy('ngay_tao', 'desc')->get();

        $dsRequest = $this->addCheckHandleDateClass($dsRequest);

        $contentData = array(
            'dsRequest' => $dsRequest,
            'assignedPerson' => $assignedPerson,
            'masterData' => $this->masterData(10),
        );
        return view('requestApprove', $contentData);
    }

    /**
     * Request Extend
     * @param Request
     */
    public function requestExtendSet(Request $request){
        try{
            $ma_yeu_cau = $request->input('ma_yeu_cau');
            $gia_han = $request->input('gia_han');
            $extReq = MdRequestManage::with('user', 'xu_ly', 'phong_ban')->find($ma_yeu_cau);
            $reqUser = User::where('username', $extReq->user_yeu_cau)->get();
            $handleUser = User::where('username', $extReq->nguoi_xu_ly)->get();
            if ( $gia_han == 2 ){
                $extReq->gia_han = $gia_han;
                $extReq->han_xu_ly = $extReq->ngay_gia_han;
                $extReq->save();

                Notification::send($reqUser, new ExtendDateInform($this->emailData($extReq)));
            } else if ($gia_han == 3){
                $extReq->gia_han = $gia_han;
                $extReq->save();
            }
            Notification::send($handleUser, new ExtendDateConfirm($this->emailData($extReq)));

            return response(['info' => 'Success', 'Content' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response(['info' => 'Success', 'Content' => 'Cập nhật thất bại.'], 401)->header('Content-Type', 'application/json');
        }
    }
}
