<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class VoteController extends Controller
{
    //添加投票
    /*
     * array(7) { ["end_date"]=> string(10) "2018-12-01" ["end_time"]=> string(5) "12:01" ["optionList"]=> string(51) "[{"icon":"","value":"33"},{"icon":"","value":"44"}]" ["voteTitle"]=> string(2) "11" ["voteContent"]=> string(2) "22" ["voteType"]=> string(53) "["单选","多选，最多2项","多选，无限制"]" ["voteTypeIndex"]=> string(1) "1" }
     *
     * */
    public function addVote(Request $request){

        $end_date = $request -> get('end_date');

        $end_time = $request -> get('end_time');

        $optionList = json_decode($request -> get('optionList'));//发起投票的选项标题
        $voteTitle = $request -> get("voteTitle");//发起投票的标题
        $voteContent = $request -> get('voteContent');//发起投票的内容说明
        $voteType = $request -> get('voteType');//单选 双选（也可以选一个）  多选
        $openId = $request -> get('openId');

        $end_at =  strtotime($end_date." ".$end_time);

/*
        var_dump(isset($optionList[0] -> value) ? $optionList[0] -> value : null );
        var_dump("******");*/

        //var_dump($optionList[2] -> value ? $optionList[2] -> value : NULL);


        $data = array(
            'title' => $voteTitle,
            'select1' => isset($optionList[0] -> value) ? $optionList[0] -> value : NULL,
            'select2' => isset($optionList[1] -> value) ? $optionList[1] -> value : NULL,
            'select3' => isset($optionList[2] -> value) ? $optionList[2] -> value : NULL,
            'select4' => isset($optionList[3] -> value) ? $optionList[3] -> value : NULL,
            'select5' => isset($optionList[4] -> value) ? $optionList[4] -> value : NULL,
            'select6' => isset($optionList[5] -> value) ? $optionList[5] -> value : NULL,
            'openId' => $openId,
            'voteType' => $voteType,
            'create_at' => time(),
            'end_at' => $end_at,
            'content' => $voteContent
        );

        if( $data['title'] == null || $data['select1'] == null || $data['select2'] == null || $data['content'] == null ) {
            return 2;
        }
        $data_result = array(
            'id'      => rand(1,1000),
            'select1' => isset($optionList[0] -> value) ? 0 : NULL,
            'select2' => isset($optionList[1] -> value) ? 0 : NULL,
            'select3' => isset($optionList[2] -> value) ? 0 : NULL,
            'select4' => isset($optionList[3] -> value) ? 0 : NULL,
            'select5' => isset($optionList[4] -> value) ? 0 : NULL,
            'select6' => isset($optionList[5] -> value) ? 0 : NULL,
            'openId'  => $openId
        );
        $data_result_id = array("voteResultId" => $data_result['id']);
        $data = array_merge_recursive($data,$data_result_id);



        //将数据压入数据库
        $result_vote = DB::table("vote_result")->insert($data_result);
       // $result_vote_id = DB::table("")
        $result = \DB::table('vote')->insert($data);


        if($result && $result_vote) {
            return 1;
        }else {
            return 0;
        }





    }


    //获取所有投票信息
    public function seekVote(Request $request){
        $openId = $request->get('openId');
        if( isset($openId) ){
            $vote = DB::table("vote")
                ->where("vote.openId",$request->get('openId'))
                ->join("userInfo","userInfo.openId",'=',"vote.openId")
                ->select("vote.*","userInfo.userName","userInfo.userImage")
                ->get();
            foreach ($vote as $value) {
                $value->create_at = date("Y-m-d H:i:s",$value->create_at);
                $value->end_at = date("Y-m-d H:i:s",$value->end_at);
            }
            //print_r($vote);
            $json_vote = json_encode($vote);
            print_r($json_vote);
        }else {
            $vote = DB::table("vote")
                ->join("userInfo","userInfo.openId",'=',"vote.openId")
                ->select("vote.*","userInfo.userName","userInfo.userImage")
                ->get();
            foreach ($vote as $value) {
                $value->create_at = date("Y-m-d H:i:s",$value->create_at);
                $value->end_at = date("Y-m-d H:i:s",$value->end_at);
            }
            //print_r($vote);
            $json_vote = json_encode($vote);
            print_r($json_vote);
        }




    }

    //获取自己参与投票信息
    public function seekUserVote(Request $request){
        $openId = $request->get('openId');
        if( isset($openId) ){
            $vote = DB::table("vote")
                ->where("vote.openId",$request->get('openId'))
                ->join("userInfo","userInfo.openId",'=',"vote.openId")
                ->select("vote.*","userInfo.userName","userInfo.userImage")
                ->get();
            foreach ($vote as $value) {
                $value->create_at = date("Y-m-d H:i:s",$value->create_at);
                $value->end_at = date("Y-m-d H:i:s",$value->end_at);
            }
            //print_r($vote);
            $json_vote = json_encode($vote);
            print_r($json_vote);
        }





    }

    //获取单个投票信息
    public function appointVoteInfo(Request $request){

        $id = $request->get('id');
        $voteInfo = DB::table("vote")
            ->where('vote.id',$id)
            ->join("userInfo","userInfo.openId","=","vote.openId")
            ->select("vote.*","userInfo.userName","userInfo.userImage")
            ->get();
        foreach ($voteInfo as $value) {
            $value->create_at = date("Y-m-d H:i:s",$value->create_at);
            $value->end_at = date("Y-m-d H:i:s",$value->end_at);
        }
        $json_voteInfo = json_encode($voteInfo);
        print_r($json_voteInfo);
    }

    //开始投票
    public function toVote(Request $request){
        /*
         * 返回1代表成功 0代表失败 2代表已经投过票
         * $id 是vote表的主键
         * $checkboxItems 是选中的票
         * $voteInfo 是获取结果信息 并进行数据更改
         * $voteUserInfo 获取投票人的openId 判断是否投票过
         *
         * */
        $id = $request->get('id');
        $voteUserInfo = json_decode($request->get('userInfo'),true);
        $checkboxItems = json_decode($request->get('checkboxItems'),true);
        //dd($checkboxItems);
        //dd($request->all());


        $voteInfo = \DB::table("vote")
            ->where("vote.id",$id)
            ->join("vote_result","vote_result.id","=","vote.voteResultId")
            ->select("vote_result.*")
            ->get();
        $voteInfo = $voteInfo[0];

        //$voteInfo = json_decode($voteInfo);
        //dd($voteInfo);
        //判断这个人是否投过票
        $voteUserId = $voteInfo->voteopenId;
        $voteUserId = rtrim($voteUserId,",");
        $voteUserId = explode(",", $voteUserId);

        foreach ($voteUserId as $value) {
            if($value == $voteUserInfo['openId']) {
                return 2;
            }
        }

        //如果没有投票过 把票数进行相加
        $voteResultId = $voteInfo -> id;
        foreach($checkboxItems as $key => $value) {
            if($value['checked'] == true) {
                $key1 = $key+1;

                DB::table("vote_result")
                    ->where("id",$voteResultId)
                    ->increment('vote_result.select'.$key1,1);
            }
        }
        //投完票以后把投票人的id加入到vote_result表中

        //获取voteopenId 并加入新用户
        $voteopenId = $voteInfo->voteopenId.",".$voteUserInfo['openId'];

        //数据进行更新
        DB::table("vote_result")
            ->where("id",$voteResultId)
            ->update(["voteopenId"=>$voteopenId]);

        //票数统计

        $voteResultInfo = DB::table("vote_result")->where("id",$voteResultId)->get();
        $voteResultInfo = $voteResultInfo[0];
        $voteResult = array(
            'select1' => $voteResultInfo->select1,
            'select2' => $voteResultInfo->select2,
            'select3' => isset($voteResultInfo->select3) ? $voteResultInfo->select3 : null,
            'select4' => isset($voteResultInfo->select4) ? $voteResultInfo->select4 : null,
            'select5' => isset($voteResultInfo->select5) ? $voteResultInfo->select5 : null,
            'select6' => isset($voteResultInfo->select6) ? $voteResultInfo->select6 : null,
        );
        
        return json_encode($voteResult);

    }

    //获取投票
    public function getVote(Request $request){
        $id = $request->get('id');
        $voteInfo = DB::table("vote")
            ->where("vote.id",$id)
            ->join("vote_result","vote_result.id","=","voteResultId")
            ->select("vote_result.*")
            ->get();
        return $voteInfo;
    }

    //删除投票
    public function deleteVote(Request $request){
        $id = $request->get('id');

        $result = DB::table("vote")
            ->where("id","=",$id)
            ->get();

        $resultVoteId = $result[0]->voteResultId;

        $delResultVote = DB::table("vote_result")->where("id","=",$resultVoteId)->delete();
        $delResult = DB::table("vote")->where("id","=",$id)->delete();

        if($delResult && $delResultVote) {
            return 1;
        }else {
            return 0;
        }
    }
}
