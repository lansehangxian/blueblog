<?php

define('IN_LSHX_BLOG', true);
define('LSHX_BLOG_ROOT', str_replace('install','',str_replace("\\", '/', dirname(__FILE__))));

error_reporting(E_ALL & ~E_NOTICE);

require(LSHX_BLOG_ROOT.'/includes/main.function.php');							//主要的函数文件
require(LSHX_BLOG_ROOT.'/includes/base.function.php');							//一些基本的函数文件
require(LSHX_BLOG_ROOT.'/includes/mysqli.class.php');							//数据库类文件

if (PHP_VERSION>5.1)
{
	if (empty($timezone))
	{
		$timezone='Etc/GMT-8';
	}
	date_default_timezone_set($timezone);
}

// 对传入的变量过滤
if (!get_magic_quotes_gpc())
{
	$_GET  	  = empty($_GET)?'':input_filter($_GET);
	$_POST    = empty($_POST)?'':input_filter($_POST);
	$_COOKIE  = empty($_COOKIE)?'':input_filter($_COOKIE);
}


$setup=!empty($_POST['setup'])?$_POST['setup']:'check';

if (file_exists(LSHX_BLOG_ROOT.'home/data/config.php'))
{
	require_once(LSHX_BLOG_ROOT.'home/data/config.php');
}

if ($install_lock&&$setup!='finish')
{
	header('location: ../index.php');
	exit;
}

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="renderer" content="webkit">
    <meta id="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <title>安装蓝色航线博客系统</title>
    <link href="../themes/default/css/common.css" rel="stylesheet">
    <link href="../themes/default/css/home.css" rel="stylesheet">
    <link href="../themes/default/css/iconfont.css" rel="stylesheet">
    <script src="../js/jquery.js"></script>
</head>
<body>
<div class="head">
    <div class="k">
        <div class="headTop">
            <img src="../images/logo.png" />
        </div>
    </div>
</div>
<div class="loginbody">
    <div class="loginMain stepBox">
        <div class="stepPad">
            <ul class="bwizard-steps">

                <li <?php if ($setup=='check') echo'class="active"' ?>>
                <span class="label badge-inverse">1</span>
                系统检测
                </li>
                <li <?php if ($setup=='config') echo'class="active"' ?>>
                <span class="label badge-inverse">2</span>
                参数配置
                </li>
                <li <?php if ($setup=='finish') echo'class="active"' ?>>
                <span class="label badge-inverse">3</span>
               安装完成
                </li>
            </ul>
<?php
if ($setup=='check')
{
?>
<form action="index.php" method="post"  name="install" id="install">
    <div class="box mt30">
        <div class="boxHead">
            <h2 class="boxTitle">服务器信息</h2>
        </div>
        <div class="boxMain">
            <div class="p15 f12 c_6">
<p>服务器信息：<?=$_SERVER['SERVER_SOFTWARE']?></p>
<p>PHP版本：<?=PHP_VERSION?></p>
            </div>
        </div>
    </div>
    <div class="box mt30">
        <div class="boxHead">
            <h2 class="boxTitle">文件权限检测</h2>
        </div>
        <div class="boxMain">
            <div class="p15 f12 c_6">
<?php
$file=array();
//检查目录权限
if (!check_write(LSHX_BLOG_ROOT.'/home/data/',1))
{
	$file[]='/home/data/目录不可写，安装将无法继续进行';
	$set=1;
}

if (!check_write(LSHX_BLOG_ROOT.'/home/admin_compiled/'))
{
	$file[]='/home/admin_compiled/目录不可写，程序将无法正常运行';;
	$set=1;
}

if (!check_write(LSHX_BLOG_ROOT.'/home/compiled/'))
{
	$file[]='/home/compiled/目录不可写，程序将无法正常运行';
	$set=1;
}

if (!check_write(LSHX_BLOG_ROOT.'/home/cache/'))
{
	$file[]='/home/cache/目录不可写，程序将无法正常运行';
	$set=1;
}

if (!check_write(LSHX_BLOG_ROOT.'/home/upload/'))
{
	$file[]='/home/upload/目录不可写，此目录放置上传的文件，建议将其设置可写';
}
if (!check_write(LSHX_BLOG_ROOT.'/home/backup/'))
{
	$file[]='/home/backup/目录不可写，此目录用于备份数据库，建议将其设置可写';;
}

if (!check_write(LSHX_BLOG_ROOT.'/themes/',1,2))
{
	$file[]='/themes/目录及其所有子目录没有写权限，建议将其设置可写';
}
foreach ($file as $val)
{
	echo "<p>{$val}</p>";
}

?>

            </div>
        </div>
    </div>
    <div class="mt30">

    <?php

if ($set==1)
{
	echo '<br /><p><input type="submit" class="btn btnBlock b_a" name="button" id="button" value="下一步" disabled/></p>';
}
else
{
	echo '<p>文件权限设置正确</p><br /><p><input class="btn btnBlock b_a" type="submit" name="button" id="button" value="下一步" /></p>';
}
echo '<input name="setup" type="hidden" value="config"></form>';

}

elseif ($setup=='config')
{
	?>
<form action="index.php" method="post"  name="install" id="install">

    <div class="box mt30">
        <div class="boxHead">
            <h2 class="boxTitle">数据库配置</h2>
        </div>
        <div class="boxMain">
            <div class="p15 f12 c_6">
                <ul class="fromUl">
                    <li class="left">数据库地址：</li>
                    <li class="right">  <input name="host" class="inputText inputBlock"  type="text" id="host" value="localhost"  /></li>
                </ul>
                <ul class="fromUl"><li class="left">数据库名：</li><li class="right"><input class="inputText inputBlock" type="text" name="dbname" id="dbname" /></li></ul>
                <ul class="fromUl"><li class="left">数据库用户名：</li><li class="right"><input class="inputText inputBlock" type="text" name="dbuser" id="dbuser" /> </li></ul>
                <ul class="fromUl"><li class="left">数据库密码：</li><li class="right"><input  class="inputText inputBlock" name="dbpass" type="text" id="dbpass" /> </li></ul>
                <ul class="fromUl"><li class="left">数据库前缀：</li><li class="right"><input  class="inputText inputBlock" name="dbprefix" type="text" id="dbprefix" value="lshx_" /> </li></ul>
            </div>

        </div>
    </div>
    <div class="box mt30">
        <div class="boxHead">
            <h2 class="boxTitle">博客信息配置</h2>
        </div>
        <div class="boxMain">
        <div class="p15 f12 c_6">
            <ul class="fromUl"><li class="left">管理员用户名：</li><li class="right"><input  class="inputText inputBlock" name="admin_user" type="text" id="admin_user" /></li></ul>
            <ul class="fromUl"><li class="left">管理员密码：</li><li class="right"><input  class="inputText inputBlock" name="admin_pass" type="text" id="admin_pass" /></li></ul>
            <ul class="fromUl"><li class="left">博客名字：</li><li class="right"><input  class="inputText inputBlock" name="blogname" type="text" id="blogname" /></li></ul>
            <ul class="fromUl"><li class="left">博客简介：</li><li class="right"><textarea style="margin: 0px; width: 408px; height: 81px;"  class="inputText inputBlock" name="blogdesc" cols="40" rows="5" id="blogdesc"></textarea></li></ul>
        </div>
    </div>
    </div>
    <div class="mt30">
        <input name="setup" type="hidden" value="finish"><input class="btn btnBlock b_a" type="submit" name="button" id="button" value="开始安装" />
    </div>

</form>
	<?php
}

elseif ($setup=='finish')
{
    ?>
        <div class="box mt30">
            <div class="boxHead">

    <?php
	$error=array();
	if (empty($_POST['host']))
	{
		$error[]='请填写数据库地址';
	}
	if (empty($_POST['dbname']))
	{
		$error[]='请填写数据库';
	}
	if (empty($_POST['dbuser']))
	{
		$error[]='请填写数据库用户名';
	}
	if (empty($_POST['admin_user']))
	{
		$error[]='请填写管理员账号';
	}
	if (empty($_POST['admin_pass']))
	{
		$error[]='请填写管理员密码';
	}
	if (empty($_POST['blogname']))
	{
		$error[]='请填写博客名字';
	}

	if ($error)
	{
	    echo '                <h2 class="boxTitle">安装失败，请查看提示信息</h2>
            </div>					<div class="boxMain">
						<div class="p15 f12 c_6">';
		foreach ($error as $val)
		{
			echo "<p>$val</p>";
		}
		echo '						</div>
					</div>
				</div>				<div class="mt30">
					<a href="javascript:history.go(-1);" class="btn btnBlock b_a">返回上一步</a>
				</div>';
		exit;
	}

	$dbhost=$_POST['host'];
	$dbuser=$_POST['dbuser'];
	$dbpw=$_POST['dbpass'];
	$dbname=$_POST['dbname'];
	$charset   = 'utf8';
	$db=new cls_mysql();
	if ($db->connect($dbhost,$dbuser,$dbpw,$dbname,$charset,$pconnect))
	{
		$error[]='数据库连接错误';
	}

	if (empty($_POST['dbprefix']))
	{
		$dbprefix='fb_';
	}
	else
	{
		$dbprefix=$_POST['dbprefix'];
	}

	$admin_user=$_POST['admin_user'];
	$admin_pass=$_POST['admin_pass'];
	$blogname=$_POST['blogname'];
	$blogdesc=$_POST['blogdesc'];
	$blog_keyword=$_POST['blogkeyword'];

	$domain=dirname(url());
	$domain=str_replace("install", '', $domain);
	$cookie_path='/';
	$session='1440';
	$hash_secret=substr(md5(time()),5,15);


	$time=time();

	//写入配置文件
	$blog_config="<?php

/**
 * $Author: p p@lansehangxian.com
 * $Date: 2019-02-04
 * blueblog.lansehangxian.com 
*/ \n\n
define('LSHX_BLOG_WS_INCLUDES', 'includes');\n
define('LSHX_BLOG_WS_ADMIN', 'admin');
\n\n/*数据库信息*/ \n";
	$blog_config.='$dbhost   = \''.$dbhost."';\n//数据库主机地址\n";
	$blog_config.='$dbname   = \''.$dbname."';\n//数据库名字\n";
	$blog_config.='$dbuser   = \''.$dbuser."';\n//用户名\n";
	$blog_config.='$dbpw   = \''.$dbpw."';\n//数据库密码\n";
	$blog_config.='$dbprefix   = \''.$dbprefix."';\n//表前缀\n";
	$blog_config.='$pconnect   = \''.$pconnect."';\n//是否保持连接\n";

	$blog_config.="\n/*会话、cookie设置*/ \n";
	$blog_config.='$cookie_path   = \''.$cookie_path."';\n";
	$blog_config.='$cookie_domain   = \''.$cookie_domain."';\n";
	$blog_config.='$session   = \''.$session."';\n";

	$blog_config.="\n/*网站编码，暂时只支持utf8*/ \n";
	$blog_config.='$charset   = \''.$charset."';\n";

	$blog_config.="\n/*安全哈希密码*/ \n";
	$blog_config.='$hash_secret   = \''.$hash_secret."';\n//此处与全站的md5相关\n";

	$blog_config.="\$install_lock=true;  \n//博客是否已经安装\n";

	$blog_config.="\n?>";

	//将博客配置保存到文件中
	$fp=@fopen(LSHX_BLOG_ROOT.'home/data/config.php',"w") or die('can not open file');
	flock($fp,LOCK_EX);
	fwrite($fp,$blog_config);
	fclose($fp);

	//导入数据库文件
	$sql=file_get_contents('lshx_blog.sql');

	$sql_list = explode(";\n\n", $sql);

	$sql_num=count($sql_list);

	for ($i=0;$i<$sql_num;$i++)
	{
		if (!empty($sql_list[$i]))
		{
			$sql_list[$i]=str_replace("fb_", $dbprefix, $sql_list[$i]);
			$db->query($sql_list[$i]);
		}
	}

	//将配置写入到数据库

	$sql='UPDATE '.table('config')."  SET `value`='".$blogname."' WHERE `key`='blog_name'";
	$db->query($sql);

	$sql='UPDATE '.table('config')."  SET `value`='".$blogdesc."' WHERE `key`='blog_desc'";
	$db->query($sql);

	$sql='UPDATE '.table('config')."  SET `value`='".$blog_keyword."' WHERE `key`='blog_keyword'";
	$db->query($sql);

	$sql='UPDATE '.table('config')."  SET `value`='".$domain."' WHERE `key`='domain'";
	$db->query($sql);

	$sql="INSERT INTO ".table('user')." (`user_id`,`user_name`,`password`,`email`,`group_id`,`reg_time`,`last_time`,`reg_ip`,`last_ip`,`visit_count`,`msn`,`qq`,`home` ) VALUES ( '1','".$admin_user."','".md5($admin_pass)."','p@lansehangxian.com','1','".$time."','".$time."',NULL,NULL,NULL,NULL,NULL,NULL);
";
	$db->query($sql);

	?>


                        <h2 class="boxTitle">安装成功</h2>
                    </div>
                    <div class="boxMain">
                        <div class="p15 f12 c_6">
                            <ul class="fromUl">
                                <li class="left">管理员用户名：</li>
                                <li class="right"><?=$admin_user?></li>
                            </ul>
                            <ul class="fromUl">
                                <li class="left">管理员密码：</li>
                                <li class="right"><?=$admin_pass?></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="mt30">
                    <a href="../" class="btn btnBlock b_a">前往前台</a>
                </div>
                <div class="mt10">
                    <a href="../admin/" class="btn btnBlock b_a">前往后台</a>
                </div>
	<?php
}

function check_write($path,$path_type=1,$check_type=1) {

    $path=str_replace('//','/',$path);
	if ($path_type==1)
	{
		if (is_dir($path))
		{
			if ($check_type==1)
			{
				$testfile = $path.'/test.tmp';
			}
			else
			{
				check_write($path);
				$testfile = $path.'default'.'/test.tmp';
			}

			@chmod($testfile,0777);
			$fp = @fopen($testfile,'ab');
			@unlink($testfile);
			if ($fp===false)
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	elseif ($path_type==2)
	{
		@chmod($path,0777);
		$fp = @fopen($path,'ab');
		if ($fp===false)
		{
			return false;
		}
	}

	return true;

}

?>


    </div>
        </div>
    </div>
</div>
<div class="foot">
    &copy;2015-2019 Powered by <a href="https://blueblog.lansehangxian.com/" target="_blank">蓝色航线博客系统 v1.0</a>
</div>