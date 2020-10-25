<?php

namespace App\Http\Controllers;

use App\MdFileUpload;
use App\MdPhongBan;
use App\MdRequestManage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommonController extends Controller
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
     * Phân quyền
     */
    const NHAN_VIEN = 0;
    const PHO_QUAN_LY = 1;
    const QUAN_LY = 2;

    /**
     * Trang thái xử lý
     */
    const YEU_CAU_MOI = 0;
    const TIEP_NHAN = 1;
    const DANG_XU_LY = 2;
    const HOAN_THANH = 3;
    const TU_CHOI = 4;

    /**
     * Loại email thông báo
     */
    const MAIL_YC_MOI = 1;
    const MAIL_YC_XU_LY = 2;
    const MAIL_THONG_BAO_DA_YC_XU_LY = 3;
    const MAIL_THONG_BAO_HOAN_THANH = 4;

    /**
     * Độ ưu tiên
     */
    const UT_THAP = 0;
    const UT_TRUNG_BINH = 1;
    const UT_CAO = 2;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Responsea
     */
    public function createPrimaryKey($oldKey, $prefix, $length)
    {
        $prefixLength = strlen($prefix);
        $keyNum = substr($oldKey, $prefixLength, ($length - $prefixLength));
        $newNum = (int)$keyNum + 1;
        $newKey = $prefix;
        $zero = $length - $prefixLength - strlen($newNum);
        for ($i = 0; $i < $zero; $i++) {
            $newKey .= '0';
        }

        $newKey .= $newNum;
        return $newKey;
    }

    /**
     * @param RequestModel
     */
    public function addCheckHandleDateClass($requestCollection)
    {
        $requestCollection->map(function ($item) {
            if ($item->ngay_xu_ly != null) {
                if (date("Y-m-d", strtotime($item->ngay_xu_ly)) > date("Y-m-d", strtotime($item->han_xu_ly))) {
                    $item['class'] = 'important-red';
                } else {
                    $item['class'] = '';
                }
            } else {
                if (date("Y-m-d", strtotime($item->han_xu_ly)) < date("Y-m-d") && $item->han_xu_ly != null) {
                    $item['class'] = 'important-red';
                } else {
                    $item['class'] = '';
                }
            }

            if ($item->trang_thai == self::YEU_CAU_MOI) {
                $item['statusMn'] = 'Yêu cầu mới';
                $item['statusClass'] = 'label-info';
            } else if ($item->trang_thai == self::TIEP_NHAN) {
                $item['statusMn'] = 'Tiếp nhận';
                $item['statusClass'] = 'label-warning';
            } else if ($item->trang_thai == self::DANG_XU_LY && $item->gia_han == 1) {
                $item['statusMn'] = 'Gia hạn xử lý';
                $item['statusClass'] = 'label-inverse';
            } else if ($item->trang_thai == self::DANG_XU_LY) {
                $item['statusMn'] = 'Đang xử lý';
                $item['statusClass'] = 'label-magenta';
            } else if ($item->trang_thai == self::HOAN_THANH) {
                $item['statusMn'] = 'Hoàn thành';
                $item['statusClass'] = 'label-success';
            } else if ($item->trang_thai == self::TU_CHOI) {
                $item['statusMn'] = 'Từ chối';
                $item['statusClass'] = 'label-important';
            } else {
                $item['statusMn'] = '';
                $item['statusClass'] = '';
            }

            if ($item->do_uu_tien == self::UT_THAP) {
                $item['prioMn'] = 'Thấp';
                $item['prioClass'] = 'label-info';
            } else if ($item->do_uu_tien == self::UT_TRUNG_BINH) {
                $item['prioMn'] = 'Trung Bình';
                $item['prioClass'] = 'label-success';
            } else if ($item->do_uu_tien == self::UT_CAO) {
                $item['prioMn'] = 'Cao';
                $item['prioClass'] = 'label-important';
            } else {
                $item['prioMn'] = '';
                $item['prioClass'] = '';
            }

            return $item;
        });

        return $requestCollection;
    }

    /**
     * Master data
     * @param $activeId
     */
    public function masterData($activeId)
    {
        $userID = Auth::user()->username;

        $totalNewRequest = MdRequestManage::whereIn('trang_thai', [0])->count();
        $pendingRequest = MdRequestManage::whereIn('trang_thai', [1, 2])->count();
        $totalAssignRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [1, 2])->count();
        $totalMyRequest = MdRequestManage::where('user_yeu_cau', $userID)->count();
        $totalMyCompleteRequest = MdRequestManage::where('nguoi_xu_ly', $userID)->whereIn('trang_thai', [3, 4])->count();
        $totalCompleteRequest = MdRequestManage::whereIn('trang_thai', [3, 4])->count();

        $data = array(
            'activeMenu' => $activeId,
            'totalNewRequest' => $totalNewRequest,
            'totalAssignRequest' => $totalAssignRequest,
            'pendingRequest' => $pendingRequest,
            'totalMyRequest' => $totalMyRequest,
            'totalMyCompleteRequest' => $totalMyCompleteRequest,
            'totalCompleteRequest' => $totalCompleteRequest,
        );
        return $data;
    }

    /**
     * get Email Data function
     * @param $request
     */
    public function emailData($requestInfo)
    {
        $phongBanXuLy = MdPhongBan::find($requestInfo->xu_ly['ma_phong_ban']);

        $data = array(
            'ma_yeu_cau'    => $requestInfo->ma_yeu_cau,
            'tieu_de'       => $requestInfo->tieu_de,
            'noi_dung'      => $requestInfo->noi_dung,
            'nguoi_gui'     => $requestInfo->user['name'],
            'phong_ban'     => $requestInfo->phong_ban['ten_phong_ban'],
            'nguoi_xu_ly'   => $requestInfo->xu_ly['name'],
            'nguoi_xu_ly_pb' => $phongBanXuLy['ten_phong_ban'],
            'thong_tin_xu_ly' => $requestInfo->thong_tin_xu_ly,
            'ngay_tao'      => date('d/m/Y H:i:s', strtotime($requestInfo->ngay_tao)),
            'yeu_cau_xu_ly' => $requestInfo->yeu_cau_xu_ly,
            'ngay_xu_ly'    => date("d/m/Y H:i:s", strtotime($requestInfo->ngay_xu_ly)),
            'gia_han'       => $requestInfo->gia_han,
            'ngay_gia_han'  => date('d/m/Y H:i:s', strtotime($requestInfo->ngay_gia_han)),
            'noi_dung_gia_han'  => $requestInfo->noi_dung_gia_han,
            'trang_thai' => ($requestInfo->trang_thai == self::HOAN_THANH ? 'Hoàn thành' : ($requestInfo->trang_thai == self::TU_CHOI ? "Từ chối" : ($requestInfo->trang_thai == self::YEU_CAU_MOI ? "Chuyển xử lý" : ""))),
            'ma_trang_thai' => $requestInfo->trang_thai,
        );

        return $data;
    }

    /**
     * convert date type
     * @param $date
     * @param $type
     */
    public function converDate($date, $type)
    {
        $strtime = strtotime(str_replace('/', '-', $date));
        $date = date($type, $strtime);

        return $date;
    }
    public function storageFile($request, $reqId)
    {
        $files = $request->file('attachFile');
        $allowedfileExtension = ['pdf', 'jpg', 'png', 'docx', 'xlsx', 'pptx', 'doc', 'xls', 'ppt'];
        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
            $fileExtension = strtolower($file->getClientOriginalExtension());
            $checkExtension = in_array($fileExtension, $allowedfileExtension);

            if ($checkExtension) {
                $path = $file->store('public');
                $storeFileName = pathinfo($path)['basename'];
                try {
                    $attachFile = new MdFileUpload();
                    $attachFile->ma_yeu_cau = $reqId;
                    $attachFile->file_name = $filename;
                    $attachFile->store_file_name = $storeFileName;
                    $attachFile->store_url = $path;
                    $attachFile->save();
                } catch (\Exception $exception) {
                    return response(['info' => 'Success', 'Content' => 'Cập nhật thất bại, không nhận được file đính kèm.'], 200)->header('Content-Type', 'application/json');
                }
            }
        }
    }
}
