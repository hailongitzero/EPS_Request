<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MdPhongBan extends Model
{
    protected $table = 'eps_phong_ban';
    protected $primaryKey = 'ma_phong_ban';
    public $incrementing = false;
    public $timestamps = false;

    public function user(){
        return $this->hasMany('App\User', 'ma_phong_ban', 'ma_phong_ban');
    }
}
