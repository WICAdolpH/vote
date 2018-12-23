<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Iwanli\Wxxcx\Wxxcx;
use DB;

class LoginController extends Controller
{
     protected $wxxcx;

    function __construct(Wxxcx $wxxcx)
    {
        $this->wxxcx = $wxxcx;
    }


     /**
     * 小程序登录获取用户信息
     * @author 晚黎
     * @date   2017-05-27T14:37:08+0800
     * @return [type]                   [description]
     */
    public function index() {


        //code 在小程序端使用 wx.login 获取
        $code = request('code', '');
        //encryptedData 和 iv 在小程序端使用 wx.getUserInfo 获取
        $encryptedData = request('encryptedData', '');
        $iv = request('iv', '');

        //根据 code 获取用户 session_key 等信息, 返回用户openid 和 session_key
        $userInfo = $this->wxxcx->getLoginInfo($code);

        //获取解密后的用户信息
        print_r($this->wxxcx->getUserInfo($encryptedData,$iv) );
        //echo gettype($this->wxxcx->getUserInfo($encryptedData,$iv)) ;

        
    }


    //增加用户信息
    public function addUser(Request $request) {


        //获取传过来的用户信息
        $arr = json_decode($request -> input('userInfo'));
        $str = array();
        $str['openId'] = $arr[0]->openId;
        $str['userName'] = $arr[0]->name;
        $str['userImage'] = $arr[0]->userImage;
        
       
        
        //$model = new userinfo;
/*
        $model -> userName = $arr[0]->name;
        $model -> openId = $arr[0]->openId;
        $model -> userImage = $arr[0]->userImage;*/
        //$result = $model -> save();

        
        if( \DB::table('userinfo') -> where('openId',$str["openId"]) ) {
            return 1;
            exit;
        }
        $result = \DB::table('userinfo')->insert($str);
        if ($result  ) {
            echo 1;
        }else {
            echo 0;
        }
        
        
    }
}
