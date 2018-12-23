<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class userinfo extends Model
{
    //定义关联的数据表
    protected $table = 'userinfo';
    //禁用时间
    public $timestamps = false;

    protected $field = ['name','userImage','openId'];
}
