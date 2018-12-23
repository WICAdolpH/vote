<?php
    //设置字符编码方式为utf8
header("Content-Type:text/html;charset=utf-8");

    //设置数据库的地址 用户名 密码 要连接的库
    $host = 'localhost';
    $userName = 'root';
    $password = 'root';
    $dbName = 'movie';

    //进行连接 并设置mysql
    $mysqli =  mysqli_connect($host, $userName, $password, $dbName);
    //$mysqli->set_charset('utf8');

    //判断是否连接上
    if ($mysqli->connect_errno){
        die('<h2 style="color: #9A0000">链接错误</h2>'.$mysqli->connect_error);
    }

