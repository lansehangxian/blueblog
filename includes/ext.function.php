<?php

/**
 * 扩展函数库文件
 * $Author: p p@lansehangxian.com
 * $Date: 2010-04-15
 * blueblog.lansehangxian.com
*/

function pb_mail($to,$subject,$content,$from='mail@lansehangxian.com')
{
	$subject  = "=?UTF-8?B?".base64_encode($subject)."?=";
	$content=process_text($content);
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= "From: $from" . "\r\n";
	mail($to, $subject, $content, $headers);
}



?>