<?php

namespace App\Http\Controllers;

use App\Jobs\QueueMailSend;
use App\MdFileUpload;
use App\MdLoaiYeuCau;
use Illuminate\Http\Request;
use App\MdRequestManage;
use Illuminate\Support\Facades\Auth;
use App\MdPhongBan;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Notifications\newRequest;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class HomeController extends CommonController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function getTotalRequestByDepartment(){
        $sql = 'SELECT PB.TEN_PHONG_BAN, COUNT(REQ.MA_PHONG_BAN) AS TOTAL';
        $sql.= ' FROM EPS_PHONG_BAN PB LEFT JOIN EPS_REQUEST_MGMT REQ ON PB.MA_PHONG_BAN = REQ.MA_PHONG_BAN';
        $sql.= ' GROUP BY PB.TEN_PHONG_BAN ORDER BY PB.TEN_PHONG_BAN ';
        return DB::select(DB::raw($sql));
    }

    public function getTotalRequestByWeekAndStatus(){
        $sql = " SELECT STS, GROUP_CONCAT(CNT ORDER BY SEQ SEPARATOR ', ') AS CNT, SUM(CNT) AS TOT";
        $sql .= " FROM ( SELECT SEQ, STS, COALESCE(CNT, 0) AS CNT";
        $sql .= " FROM ( SELECT SEQ, STS FROM (SELECT SEQ FROM SEQ_0_TO_6 ) SEQ JOIN ( SELECT SEQ AS STS FROM SEQ_0_TO_4 ) STS";
        $sql .= " WHERE SEQ.SEQ <= DATE_FORMAT(CURDATE(), '%w') ORDER BY STS, SEQ ) ALS LEFT JOIN";
        $sql .= " ( SELECT DATE_FORMAT(NGAY_TAO,'%w') AS TM, COUNT(TRANG_THAI) AS CNT, TRANG_THAI FROM `EPS_REQUEST_MGMT`";
        $sql .= " WHERE YEARWEEK(NGAY_TAO, 1) = YEARWEEK(CURDATE(), 1) GROUP BY DATE_FORMAT(NGAY_TAO,'%w'), TRANG_THAI";
        $sql .= " ORDER BY DATE_FORMAT(NGAY_TAO,'%w'), TRANG_THAI ) DATA ON ALS.SEQ = DATA.TM AND ALS.STS = DATA.TRANG_THAI";
        $sql .= " ORDER BY STS,SEQ ) RSL GROUP BY STS ORDER BY STS";
        return DB::select(DB::raw($sql));
    }

    public function getTotalRequestByStatus(){
        $sql = 'SELECT YC.LOAI_YEU_CAU , YC.TEN_LOAI_YEU_CAU , COUNT(REQ.LOAI_YEU_CAU) AS TOTAL FROM EPS_LOAI_YEU_CAU YC LEFT JOIN EPS_REQUEST_MGMT REQ';
        $sql .= ' ON YC.LOAI_YEU_CAU = REQ.LOAI_YEU_CAU GROUP BY YC.LOAI_YEU_CAU, YC.TEN_LOAI_YEU_CAU';
        return DB::select(DB::raw($sql));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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

        $phongBan = MdPhongBan::orderBy('ma_phong_ban')->get();
        $loaiYc = MdLoaiYeuCau::OrderBy('loai_yeu_cau')->get();

        $totalReq = MdRequestManage::whereIn('trang_thai', [0,1,2,3,4])->count();
        $totalNewReq = MdRequestManage::where('trang_thai', '=', 0)->count();
        $totalRecpReq = MdRequestManage::where('trang_thai', '=', 1)->count();
        $totalHandleReq = MdRequestManage::where('trang_thai', '=', 2)->count();
        $totalCompReq = MdRequestManage::where('trang_thai', '=', 3)->count();
        $totalRejReq = MdRequestManage::where('trang_thai', '=', 4)->count();

        $contentData = array(
            'masterData' => array(
                'activeMenu' => 1,
                'totalNewRequest' => $totalNewRequest,
                'totalAssignRequest' => $totalAssignRequest,
                'pendingRequest' =>$pendingRequest,
                'totalMyRequest' => $totalMyRequest,
                'totalMyCompleteRequest' => $totalMyCompleteRequest,
                'totalCompleteRequest' => $totalCompleteRequest,
            ),
            'WeeklyAnalyData' => $this->getTotalRequestByWeekAndStatus(),
            'getTotalReqByDepartment' => $this->getTotalRequestByDepartment(),
            'totalReqByStatus' => $this->getTotalRequestByStatus(),
            'totalReq' => $totalReq,
            'totalNewReq' => $totalNewReq,
            'totalRecpReq' => $totalRecpReq,
            'totalHandleReq' => $totalHandleReq,
            'totalCompReq' => $totalCompReq,
            'totalRejReq' => $totalRejReq,
            'phongBan' => $phongBan,
            'loai_yc' => $loaiYc,
        );
        return view('index', $contentData);
    }

    /**
     * Form tạo yêu cầu mới
     *
     * @return void
     */
    public function Request(){
        if (Auth::user()){
            $userID = Auth::user()->username;
            $totalNewRequest = MdRequestManage::whereIn('trang_thai', [0])->count();
            $pendingRequest = MdRequestManage::whereIn('trang_thai', [1,2])->count();
            $totalAssignRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [1,2])->count();
            $totalMyRequest = MdRequestManage::where('user_yeu_cau', $userID)->count();
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
        $phongBan = MdPhongBan::orderBy('ma_phong_ban')->get();
        $loaiYc = MdLoaiYeuCau::OrderBy('loai_yeu_cau')->get();
        $userPb = MdPhongBan::with('user')->get();

        $contentData = array(
            'masterData' => array(
                'activeMenu' => 2,
                'totalNewRequest' => $totalNewRequest,
                'totalAssignRequest' => $totalAssignRequest,
                'pendingRequest' =>$pendingRequest,
                'totalMyRequest' => $totalMyRequest,
                'totalMyCompleteRequest' => $totalMyCompleteRequest,
                'totalCompleteRequest' => $totalCompleteRequest,
            ),
            'phongBan' => $phongBan,
            'loai_yc' => $loaiYc,
            'userPb' => $userPb,
        );
        return view('request', $contentData);
    }

    /**
     * Nhập yêu cầu mới
     *
     * @return \Illuminate\Http\Responsea
     */
    public function receiptRequest(Request $request)
    {
        $userID = $request->input('ho_ten');
        $dien_thoai = $request->input('dien_thoai');
        $phong_ban = $request->input('phong_ban');
        $email = $request->input('email');
        $uu_tien = $request->input('uu_tien');
        $loai_yeu_cau = $request->input('loai_yeu_cau');
        $han_xu_ly = $request->input('han_xu_ly');
        $tieu_de = $request->input('tieu_de');
        $noi_dung = $request->input('noi_dung');
        $nguoi_gui = '';
        $nguoi_gui_info = User::where('username', $userID)->get();
        foreach ($nguoi_gui_info as $key=>$val){
            $nguoi_gui = $val->name;
        }
        $ccEmail = $request->input('cc_email');
        if ($ccEmail != null){
            $ccEmail = implode(",",$ccEmail);
        }

        $maxKey = MdRequestManage::max('ma_yeu_cau');
        if ($maxKey === NULL){
            $ma_yeu_cau = 'R000001';
        }else{
            $ma_yeu_cau = $this->createPrimaryKey($maxKey, 'R', 7);
        }

        $user = User::where('username', $userID)->get();
        if (isset($user[0]->dien_thoai) || $dien_thoai != $user[0]->dien_thoai){
            User::where('username', $userID)->update(['dien_thoai' => $dien_thoai]);
        }
        if ($user[0]->email == null && isset($email)){
            User::where('username', $userID)->update(['email' => $email]);
        }

        $newReq = new MdRequestManage();
        $newReq->ma_yeu_cau = $ma_yeu_cau;
        $newReq->user_yeu_cau = $userID;
        $newReq->ma_phong_ban = $phong_ban;
        $newReq->do_uu_tien = $uu_tien;
        $newReq->tieu_de = $tieu_de;
        $newReq->noi_dung = $noi_dung;
        $newReq->loai_yeu_cau = $loai_yeu_cau;
        $newReq->cc_email = $ccEmail;
        if (isset($han_xu_ly)){
            $curentDate = date('Y-m-d');
            $timestamp = strtotime(str_replace('/', '-', $han_xu_ly));
            if ( date('Y-m-d', $timestamp) < $curentDate){
                return redirect()->route('request')->with('error', 'Vui lòng chọn ngày xử lý lớn hơn ngày hiện tại.');
            }
            $newReq->han_xu_ly = date('Y-m-d', $timestamp);
        }else{
            return redirect()->route('request')->with('error', 'Yêu cầu nhập ngày hạn xử lý yêu cầu.');
        }

        try{
            $newReq->save();
            if($request->hasFile('dinh_kem'))
            {
                $files = $request->file('dinh_kem');
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
                $yeuCauMoi = MdRequestManage::find($ma_yeu_cau);
                $data = array(
                    'ma_yeu_cau'    => $ma_yeu_cau,
                    'nguoi_gui'     => $yeuCauMoi['user']->name,
                    'phong_ban'     => $yeuCauMoi['phong_ban']->ten_phong_ban,
                    'cc_email'      => explode(',', $ccEmail),
                    'tieu_de'       => $tieu_de,
                    'noi_dung'      => $noi_dung,
                    'ngay_tao'      => date('d/m/Y H:i:s', strtotime($yeuCauMoi->ngay_tao)),
                    'ma_trang_thai' => self::YEU_CAU_MOI,
                );

                $managerUser = User::where('role', self::QUAN_LY)->get();

                Notification::send($managerUser, new newRequest($data));
                return redirect()->route('request')->with('success', 'Yêu cầu của bạn đã được tiếp nhận.');
            }catch (\Exception $exception){
                return redirect()->route('request')->with('success', 'Yêu cầu của bạn đã được tiếp nhận.'. $exception);
            }
        }catch (\Exception $exception){
            return redirect()->route('request')->with('error', 'Yêu cầu của bạn chưa được tiếp nhận, vui lòng thử lại');
        }
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
     * thống kê dữ liệu request theo tuần
     *
     * @return json array
     */
    public function getRequestAnalysisByWeek(Request $request){
        $status1 = DB::select(DB::raw("SELECT DATE_FORMAT(ngay_tao,'%d') as 'ngay' ,count(trang_thai) as 'cnt' FROM eps_request_mgmt WHERE DATE_FORMAT(ngay_tao,'%Y%m') = DATE_FORMAT(CURDATE(),'%Y%m') and trang_thai = 0 GROUP BY  DATE_FORMAT(ngay_tao,'%d')"));
        $status2 = DB::select(DB::raw("SELECT DATE_FORMAT(ngay_tao,'%d') as 'ngay' ,count(trang_thai) as 'cnt' FROM eps_request_mgmt WHERE DATE_FORMAT(ngay_tao,'%Y%m') = DATE_FORMAT(CURDATE(),'%Y%m') and trang_thai = 1 GROUP BY  DATE_FORMAT(ngay_tao,'%d')"));
        $status3 = DB::select(DB::raw("SELECT DATE_FORMAT(ngay_tao,'%d') as 'ngay' ,count(trang_thai) as 'cnt' FROM eps_request_mgmt WHERE DATE_FORMAT(ngay_tao,'%Y%m') = DATE_FORMAT(CURDATE(),'%Y%m') and trang_thai = 2 GROUP BY  DATE_FORMAT(ngay_tao,'%d')"));
        $status4 = DB::select(DB::raw("SELECT DATE_FORMAT(ngay_tao,'%d') as 'ngay' ,count(trang_thai) as 'cnt' FROM eps_request_mgmt WHERE DATE_FORMAT(ngay_tao,'%Y%m') = DATE_FORMAT(CURDATE(),'%Y%m') and trang_thai = 3 GROUP BY  DATE_FORMAT(ngay_tao,'%d')"));
        $status5 = DB::select(DB::raw("SELECT DATE_FORMAT(ngay_tao,'%d') as 'ngay' ,count(trang_thai) as 'cnt' FROM eps_request_mgmt WHERE DATE_FORMAT(ngay_tao,'%Y%m') = DATE_FORMAT(CURDATE(),'%Y%m') and trang_thai = 4 GROUP BY  DATE_FORMAT(ngay_tao,'%d')"));
        $result = array(
            'yc_moi' => $status1,
            'yc_tiep_nhan' => $status2,
            'yc_xu_ly' => $status3,
            'yc_hoan_thanh' => $status4,
            'yc_tu_choi' => $status5,
        );
        return $result;
    }

    public function getTotalRequestByDepartmentAndDate(Request $request){
        $dateFrom = $request->tu_ngay;
        $dateTo = $request->den_ngay;
        $loai_yeu_cau = $request->loai_yeu_cau;
        $checked = $request->checked;

        $result = null;

        if ($checked == "1"){
            $dateFrom = strtotime(str_replace('/', '-', $dateFrom));
            $dateTo = strtotime(str_replace('/', '-', $dateTo));

            $sql = 'SELECT PB.TEN_PHONG_BAN, COUNT(REQ.MA_PHONG_BAN) AS TOTAL';
            $sql .= ' FROM EPS_PHONG_BAN PB LEFT JOIN EPS_REQUEST_MGMT REQ ON PB.MA_PHONG_BAN = REQ.MA_PHONG_BAN';
            $sql .= ' AND Date_Format(REQ.NGAY_TAO, \'%Y-%m-%d\') BETWEEN \''.date('Y-m-d', $dateFrom).'\' AND \''.date('Y-m-d', $dateTo).'\'';
            if (isset($loai_yeu_cau)){
                $sql .= ' AND REQ.LOAI_YEU_CAU = "'. $loai_yeu_cau . '"';
            }
            $sql .= ' GROUP BY PB.TEN_PHONG_BAN ORDER BY PB.TEN_PHONG_BAN ';
            $result = DB::select(DB::raw($sql));
        }else{
            $sql = 'SELECT PB.TEN_PHONG_BAN, COUNT(REQ.MA_PHONG_BAN) AS TOTAL';
            $sql.= ' FROM EPS_PHONG_BAN PB LEFT JOIN EPS_REQUEST_MGMT REQ ON PB.MA_PHONG_BAN = REQ.MA_PHONG_BAN';
            $sql.= ' GROUP BY PB.TEN_PHONG_BAN ORDER BY PB.TEN_PHONG_BAN ';
            $result = DB::select(DB::raw($sql));
        }
        return $result;
    }

    public function getTotalRequestByRequestTypeAndDate(Request $request){
        $dateFrom = $request->tu_ngay;
        $dateTo = $request->den_ngay;
        $checked = $request->checked;

        $result = null;

        if ($checked == "1"){
            $dateFrom = strtotime(str_replace('/', '-', $dateFrom));
            $dateTo = strtotime(str_replace('/', '-', $dateTo));

            $sql = 'SELECT YC.LOAI_YEU_CAU , YC.TEN_LOAI_YEU_CAU , COUNT(REQ.LOAI_YEU_CAU) AS TOTAL FROM EPS_LOAI_YEU_CAU YC LEFT JOIN  EPS_REQUEST_MGMT REQ';
            $sql .= ' ON YC.LOAI_YEU_CAU = REQ.LOAI_YEU_CAU AND Date_Format(REQ.NGAY_TAO, \'%Y-%m-%d\') BETWEEN \''.date('Y-m-d', $dateFrom).'\' AND \''.date('Y-m-d', $dateTo).'\'';
            $sql .= ' GROUP BY YC.LOAI_YEU_CAU, YC.TEN_LOAI_YEU_CAU';
            $result = DB::select(DB::raw($sql));
        }else{
            $sql = 'SELECT YC.LOAI_YEU_CAU , YC.TEN_LOAI_YEU_CAU , COUNT(REQ.LOAI_YEU_CAU) AS TOTAL FROM EPS_LOAI_YEU_CAU YC LEFT JOIN  EPS_REQUEST_MGMT REQ';
            $sql .= ' ON YC.LOAI_YEU_CAU = REQ.LOAI_YEU_CAU';
            $sql .= ' GROUP BY YC.LOAI_YEU_CAU, YC.TEN_LOAI_YEU_CAU';

            $result = DB::select(DB::raw($sql));
        }
        return $result;
    }

    public function getUserInfo(Request $request){
        $userId = $request->input('username');
        return User::where('username', $userId)->get();
    }
}
