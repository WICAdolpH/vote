<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//测试接口
Route::get('test','TestController@index');

//登陆接口
Route::group(['prefix'=>'admin','namespace'=>'Admin'],function(){
    //登陆
	Route::any("loginApi","LoginController@index");
	//用户添加
	Route::any("addUser","LoginController@addUser");
	//添加投票选项
    Route::any("addVote", "VoteController@addVote");

    //获取投票信息和自己的发起的投票
    Route::any("seekVote","VoteController@seekVote");
    //获取自己参与的投票
    Route::any("seekUserVote","VoteController@seekUserVote");

   //获取指定的投票的相关信息
    Route::any("appointVoteInfo","VoteController@appointVoteInfo");
    //开始投票
    Route::any("toVote","VoteController@toVote");
    //获取票数信息
    Route::any("getVote","VoteController@getVote");
    //删除投票信息
    Route::any("deleteVote","VoteController@deleteVote");
});

