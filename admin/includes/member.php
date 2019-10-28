<?php

/**
 * $Author: p p@lansehangxian.com
 * $Date: 2019-01-17
 * blueblog.lansehangxian.com
*/

if ($action=='member_list')
{
	require_once(LSHX_BLOG_ROOT.'/includes/base.function.php');
	$pg=isset($_GET['pg'])?intval($_GET['pg']):1;
	$page_size=!empty($page_size)?$page_size:'15';
	$sql='SELECT count(*) FROM '.table('user');
	$page_count=intval(($db->getone($sql)-1)/$page_size)+1;
	$page_arr=create_page($page_count,$pg,0);

	$start=($pg-1)*$page_size;
	$sql='SELECT o.user_id,o.user_name,o.reg_time,o.last_time,u.group_name FROM '.table('user').
	" o LEFT JOIN ".table('user_group')." u on o.group_id=u.group_id".
	" ORDER BY o.user_id DESC  LIMIT ".$start.' , '.$page_size;
	$user_list=$db->getall($sql);
	foreach ($user_list as $key=>$val)
	{
		$user_list[$key]['reg_time']=pbtime($user_list[$key]['reg_time']);
		$user_list[$key]['last_time']=pbtime($user_list[$key]['last_time']);
	}

	$smarty->assign('user_list',$user_list);
	$smarty->assign('page_arr',$page_arr);
	$smarty->assign('page_count',$page_count);
	$smarty->assign('pg',$pg);
	$smarty->assign('url','admin.php?act=member_list&pg=');

	$smarty->assign('admin_title','会员列表');
	$smarty->display('member_list.html');
}


elseif ($action=='del_member')
{
	$member_id=intval($_GET['id']);
	$sql='DELETE FROM '.table('user')." WHERE user_id='".$member_id."'";
	if ($master_db?$db2->query($sql):$db->query($sql))
	{
		sys_message('删除会员成功',$referer_url);
	}
	else
	{
		sys_message('删除会员失败，请重新删除',$referer_url,2);
	}
}

elseif ($action=='add_member')
{
	$sql='SELECT group_id,group_name FROM '.table('user_group');
	$group_list=$db->getall($sql);
	$smarty->assign('group_list',$group_list);

	$smarty->assign('type','act_add_member');
	$smarty->assign('admin_title','添加会员');
	$smarty->display('add_member.html');
}

elseif ($action=='act_add_member')
{
	require_once(LSHX_BLOG_ROOT.'/includes/base.function.php');

	$new_user_name=$db->safe(trim($_POST['user_name']));
	if (empty($new_user_name))
	{
		sys_message('会员名字不能为空',$referer_url,2);
	}
	else
	{
		if (isset_member($new_user_name))
		{
			sys_message('会员名字已经存在',$referer_url,2);
		}
	}

	$email=$db->safe(trim($_POST['email']));
	if (empty($email))
	{
		sys_message('email不能为空',$referer_url,2);
	}

	$password=$_POST['password'];
	if (empty($password))
	{
		sys_message('密码不能为空',$referer_url,2);
	}

	$new_group_id=intval($_POST['group']);

	$sql='INSERT INTO '.table('user').
	" (`user_id` ,`user_name` ,`password` ,`email`,`group_id`,`reg_time`,`last_time` )
		VALUES (NULL , '".$new_user_name."', '".md5($password)."', '".$email."','".$new_group_id."','".$time."','".$time."')";

	if ($master_db?$db2->query($sql):$db->query($sql))
	{
		sys_message('添加会员成功','admin.php?act=add_member');
	}
	else
	{
		sys_message('添加会员失败，请重新返回添加','admin.php?act=add_member',2);
	}
}

elseif ($action=='edit_member')
{
	$member_id=intval($_GET['id']);
	if (empty($member_id))
	{
		sys_message('用户id不能为空',$referer_url,2);
	}
	$sql='SELECT * FROM '.table('user')." WHERE user_id='".$member_id."'";

	if ($row=$db->getrow($sql))
	{
		$smarty->assign('member',$row);
	}
	else
	{
		sys_message('读取用户数据失败，请返回重新修改',$referer_url,2);
	}

	$sql='SELECT group_id,group_name FROM '.table('user_group');
	$group_list=$db->getall($sql);

	$smarty->assign('group_list',$group_list);




	$smarty->assign('type','act_edit_member&id='.$member_id);
	$smarty->assign('admin_title','编辑会员信息');
	$smarty->display('add_member.html');
}

elseif ($action=='act_edit_member')
{
	require_once(LSHX_BLOG_ROOT.'/includes/base.function.php');

	$member_id=intval($_GET['id']);
	$new_user_name=$db->safe(trim($_POST['user_name']));
	if (empty($new_user_name))
	{
		sys_message('会员名字不能为空',$referer_url,2);
	}
	else
	{
		if (isset_member($new_user_name))
		{
			$u_name=$db->getone('SELECT user_name FROM '.table('user')." WHERE user_id='".intval($member_id)."'");
			if ($u_name!=$new_user_name)
			{
				sys_message('会员名字已经存在',$referer_url,2);
			}
		}
	}

	$email=$db->safe(trim($_POST['email']));
	if (empty($email))
	{
		sys_message('email不能为空',$referer_url,2);
	}

	$password=$_POST['password'];
	if (!empty($password))
	{
		$password="' , `password`='".md5($password);
	}

	$new_group_id=intval($_POST['group']);


	$sql='UPDATE '.table('user').
	"  SET `user_name` = '".$new_user_name."',`email` = '".$email."',`group_id` = '".$new_group_id.
	$password.
	"' WHERE user_id='".$member_id."'";

	if ($master_db?$db2->query($sql):$db->query($sql))
	{
		sys_message('修改会员成功','admin.php?act=edit_member&id='.$member_id);
	}
	else
	{
		sys_message('修改会员失败，请重新返回添加','admin.php?act=edit_member&id='.$member_id,2);
	}
}

?>