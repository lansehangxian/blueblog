<?php

/**
 * $Author: p p@lansehangxian.com
 * $Date: 2010-02-16
 * blueblog.lansehangxian.com
*/

define(IN_LSHX_BLOG,true);
define(IN_PBADMIN,true);

if (file_exists('../home/data/config.php')) 
{
	require_once('../home/data/config.php');
}
else 
{
	define('LSHX_BLOG_WS_ADMIN', 'admin');
}

define('LSHX_BLOG_ROOT', str_replace(LSHX_BLOG_WS_ADMIN,'',str_replace("\\", '/', dirname(__FILE__))));

require(LSHX_BLOG_ROOT.'/includes/core.php');

$u=dirname($url);

if ($_SESSION['user_id']>0) 
{
	header("location: admin.php?act=index");
}
else 
{
	header("location: admin.php?act=pre_login");
}
?>