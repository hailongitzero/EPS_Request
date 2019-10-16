<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Responsea
     */
    public function createPrimaryKey($oldKey, $prefix, $length){
        $prefixLength = strlen($prefix);
        $keyNum = substr($oldKey, $prefixLength, ($length-$prefixLength));
        $newNum = (int)$keyNum + 1;
        $newKey = $prefix;
        $zero = $length - $prefixLength - strlen($newNum);
        for ($i = 0; $i < $zero; $i++){
            $newKey .= '0';
        }

        $newKey .= $newNum;
        return $newKey;
    }
}
