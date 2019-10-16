<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MdFileUpload extends Model
{
    protected $table = 'eps_file_upload';
    protected $primaryKey = 'file_id';
    public $timestamps = false;
}
