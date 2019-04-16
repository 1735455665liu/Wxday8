<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;

class Wx extends Model
{
    protected $primaryKey='id';
    protected $table="p_wx_users";
    public $timestamps = false;
}
