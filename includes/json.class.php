<?php

/**
 * $Author: p p@lansehangxian.com
 * $Date: 2010-02-16
 * blueblog.lansehangxian.com
*/

//该类有待加强

class JSON
{

	function encode($arg)
	{
		return json_encode($arg);
	}

	function decode($text,$type=0) // 默认type=0返回obj,type=1返回array
	{
		if (empty($text))
		{
			return '';
		}
		elseif (!is_string($text))
		{
			return false;
		}

		return addslashes_deep_obj(json_decode(stripslashes($text),$type));

	}
}

?>