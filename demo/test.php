<?php
/**
 * UrlEncoder工程
 *
 * test.php文件
 *
 * User: Administrator
 * DateTime: 2015-01-26 14:13
 */
header("Content-type:text/html;charset=utf-8");
include_once(__DIR__. "/../UrlEncoder.php");
$urlEncoder = new UrlEncoder();
$str = '中华人民共和国';
echo '加密字符串：',$str,'<br>';
$strSecurity = $urlEncoder->encode($str);
echo '加密后密文：',$strSecurity,'<br>';
echo '解密后明文：',$urlEncoder->decode($strSecurity),'<br>';

$urlEncoder->setKey('ABCDEFG1234abcdefg');
$urlEncoder->setSplitNum(1);
echo '加密字符串：',$str,'<br>';
$strSecurity = $urlEncoder->encode($str);
echo '加密后密文：',$strSecurity,'<br>';
echo '解密后明文：',$urlEncoder->decode($strSecurity),'<br>';