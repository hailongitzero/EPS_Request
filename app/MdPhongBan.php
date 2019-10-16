<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MdPhongBan extends Model
{
    protected $table = 'eps_phong_ban';
    protected $primaryKey = 'ma_phong_ban';
    public $incrementing = false;
    public $timestamps = false;
}
