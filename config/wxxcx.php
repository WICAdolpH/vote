<?php

return [
	/**
	 * 小程序APPID
	 */
    'appid' => 'wxeb3d714215c2c7ad',
    /**
     * 小程序Secret
     */
    'secret' => '084cd0b32863f76d6538c090eeeec645',
    /**
     * 小程序登录凭证 code 获取 session_key 和 openid 地址，不需要改动
     */
    'code2session_url' => "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
];
