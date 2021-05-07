<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MdRequestManage extends Model
{
    protected $table = 'eps_request_mgmt';
    protected $primaryKey = 'ma_yeu_cau';
    public $incrementing = false;
    //const CREATED_AT = 'ngay_tao';
    public $timestamps = false;

    public function phong_ban()
    {
        return $this->belongsTo('App\MdPhongBan', 'ma_phong_ban', 'ma_phong_ban');
    }
    public function user(){
        return $this->belongsTo('App\User', 'user_yeu_cau', 'username');
    }
    public function xu_ly(){
        return $this->belongsTo('App\User', 'nguoi_xu_ly', 'username');
    }

    public function files(){
        return $this->hasMany('App\MdFileUpload', 'ma_yeu_cau');
    }

    public function loai_yc(){
        return $this->belongsTo('App\MdLoaiYeuCau', 'loai_yeu_cau', 'loai_yeu_cau');
    }

    public function sub_assign(){
        return $this->hasMany('App\MdReqSubPerson', 'ma_yeu_cau');
    }
}