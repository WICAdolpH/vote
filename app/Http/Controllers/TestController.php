<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class TestController extends Controller
{
    public function index() {
        /*$arr = "2017-09-01";
        $arr1 = "12:01";

        $arr2 = strtotime($arr." ".$arr1);
        echo $arr2;
        echo "********<hr>";
        echo date("Y-m-d H:i:s",$arr2);
        $arr3 = "2017-09-05";
        $arr4 = "12:01";*/

        //echo date("Y-m-d H:i:s",time());
        /*$result = DB::table('userInfo')
            ->join("vote","vote.openId",'=',"userInfo.openId")
            ->select("vote.*","userInfo.userName")
            ->get();
        $result1 = DB::table("vote")->get();
        dd($result);*/


        $id = 58;

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
