<?php
    function my_error($mysql,$sql){

        $res = mysqli_query($mysql,$sql);

        if(mysqli_connect_errno()){
            echo "SQL语句有语法错误，错误信息是".mysqli_connect_error();

            exit;
        }

        return $res;
    }

