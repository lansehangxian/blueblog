<?php

/**
 * HTML5文件上传类
 * $Author: p p@lansehangxian.com
 * $Date: 2019-02-05
 * blueblog.lansehangxian.com
*/

if (!defined('IN_LSHX_BLOG'))
{
	die('Access Denied');
}


class cls_upload {

	var $error='';

	function upload($upload,$type='img')
	{

		//对html5文件上传方式进行处理
		if(isset($_SERVER['HTTP_CONTENT_DISPOSITION']))
		{
			if(preg_match('/attachment;\s+name="(.+?)";\s+filename="(.+?)"/i',$_SERVER['HTTP_CONTENT_DISPOSITION'],$info))
			{
				$file['name']=$info[2];
				//对上传类别判断
				if (!$this->check_file($file))
				{
					$this->error='此文件类别不允许上传';
					return false;
				}

				//生成文件名
				$file_name=time().rand(10000,60000).'.'.substr($file['name'], strrpos($file['name'], '.')+1);
				$s = new SaeStorage();
				$s->write( 'simplelog' , $file_name , file_get_contents("php://input") );
				return $s->getUrl( 'simplelog' , $file_name);
			}
		}
		else
		{
			//检查文件上传情况
			if ($upload['error']==1)
			{
				$this->error='文件大小超出了服务器的空间大小';
				return false;
			}
			elseif ($upload['error']==2)
			{
				$this->error='要上传的文件大小超出浏览器限制';
				return false;
			}
			elseif ($upload['error']==3)
			{
				$this->error='文件仅部分被上传';
				return false;
			}
			elseif ($upload['error']==4)
			{
				$this->error='没有找到要上传的文件';
				return false;
			}
			elseif ($upload['error']==5)
			{
				$this->error='服务器临时文件夹丢失';
				return false;
			}
			elseif ($upload['error']==6)
			{
				$this->error='文件写入到临时文件夹出错';
				return false;
			}

			//对上传类别判断
			if (!$this->check_type($upload,$type))
			{
				$this->error='此文件类别不允许上传';
				return false;
			}

			$upload['tmp_name']=str_replace('\\\\', '\\', $upload['tmp_name']);		//对加斜线部分处理

			//生成文件名
			$file_name=time().rand(10000,60000).'.'.substr($upload['name'], strrpos($upload['name'], '.')+1);

			$s = new SaeStorage();
			return $s->upload('simplelog',$file_name,$upload['tmp_name']);
		}
	}

	function check_file($file_name)
	{
		$allow_exp='txt,rar,zip,jpg,jpeg,gif,png,swf,flv,wmv,avi,wma,mp3,mid';

		//文件后缀和类型是否在允许上传范围
		$point = strrpos($file_name['name'], '.');
		$ext=strtolower(substr($file_name['name'], $point+1, strlen($file_name['name']) - $point));
		$types=explode(',',$allow_exp);
		if (in_array($ext,$types))
		{
			return true;
		}
	}


	function check_type($file_name,$type='img')
	{
		$file_types['img']='gif|jpg|jpeg|png|bmp';
		$file_types['flash']='flv|swf';
		$file_types['file'] = 'zip|doc|rar|pdf|txt|mp3|wav|mpeg';

		$file_mine['img']=array(
		'image/gif',
		'image/jpeg',
		'image/pjpeg',
		'image/png',
		'image/x-png',
		'image/bmp'
		);

		$file_mine['flash']=array(
		'application/octet-stream',
		'application/x-shockwave-flash'
		);

		$file_mine['file']=array(
		'application/octet-stream',
		'application/rar',
		'application/zip',
		'application/x-zip-compressed',
		'audio/mpeg',
		'audio/wav',
		'text/plain',
		'application/msword',
		'application/vnd.ms-excel',
		'application/vnd.ms-powerpoint',
		'application/pdf'
		);

		//文件后缀和类型是否在允许上传范围
		$point = strrpos($file_name['name'], '.');
		$ext=strtolower(substr($file_name['name'], $point+1, strlen($file_name['name']) - $point));
		$types=explode('|',$file_types[$type]);
		if (in_array($ext,$types)&&in_array($file_name['type'],$file_mine[$type]))
		{
			return true;
		}
	}

	function error()
	{
		return $this->error;
	}


}
?>