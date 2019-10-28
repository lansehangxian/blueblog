<?php

/**
 * $Author: p p@lansehangxian.com
 * $Date: 2019-04-16
 * blueblog.lansehangxian.com
*/

define('IN_LSHX_BLOG', true);

require(dirname(__FILE__) . '/includes/core.php');

$pg=isset($_GET['pg'])?intval($_GET['pg']):1;
$attachment_id=!empty($_GET['fid'])?intval($_GET['fid']):'';
$file_name=$db->getrow('SELECT file_name,type FROM '.table('attachments')." WHERE attachment_id='".$attachment_id."'");
if ($file_name&&is_file(LSHX_BLOG_ROOT.'/'.$file_name['file_name']))
{
	ob_end_clean();
	@header("Content-Disposition: {$headerposition}; filename=\"".basename($file_name['file_name'])."\"");
	@header("Content-Type: application/octet-stream");
	@header('Content-Length: '.filesize(LSHX_BLOG_ROOT.'/'.$file_name['file_name']));
	$fp = fopen(LSHX_BLOG_ROOT.'/'.$file_name['file_name'], 'rb');
	fpassthru($fp);
	fclose($fp);
}

?>