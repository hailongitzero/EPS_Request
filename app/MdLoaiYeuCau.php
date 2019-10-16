<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MdLoaiYeuCau extends Model
{
    protected $table = 'eps_loai_yeu_cau';
    protected $primaryKey = 'loai_yeu_cau';
    public $timestamps = false;
}
