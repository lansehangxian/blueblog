<?php

/**
 * $Author: p p@lansehangxian.com
 * $Date: 2019-01-17
 * blueblog.lansehangxian.com
*/
if ($action=='setting')
{
	foreach ($config as $key=>$val)
	{
		$smarty->assign($key,$val);
	}

	if (empty($config['domain']))
	{
		$domain=str_replace(LSHX_BLOG_WS_ADMIN, '', dirname($url));
		$smarty->assign('domain',$domain);
	}

	$smarty->assign('type','act_setting');
	$smarty->display('setting.html');
}

elseif ($action=='act_setting')
{
	if (empty($_POST))
	{
		sys_message('请填写数据',$referer_url,2);
	}
	else
	{

        //循环得到传递过来的数据并为写入配置做准备
		foreach ($_POST as $key => $val)
		{
			if ($db->getone('SELECT * FROM '.table('config')." WHERE `key`='".$key."'"))
			{
				$sql='UPDATE '.table('config')."  SET `key`='".$key."' , `value`='".$val."' WHERE `key`='".$key."'";
			}
			else
			{
				$sql='INSERT INTO '.table('config')." (`key` ,`value`) VALUES ('".$key."' ,'".$val."')";
			}

			if (!$db->query($sql))
            {
                echo $sql;
            }
		}

		clear_tpl();

		sys_message('博客设置成功','admin.php?act=setting');

	}

}
?>