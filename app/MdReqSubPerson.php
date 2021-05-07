<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MdReqSubPerson extends Model
{
    protected $table = 'eps_req_sub_user';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ma_yeu_cau', 'username'];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'username', 'username');
    }
}
