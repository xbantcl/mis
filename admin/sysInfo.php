<?php 
//==================================================== 
//		FileName:sysInfo.php 
//		Summary: 显示系统信息 
//		 
//==================================================== 
require_once("../class/mysql.inc.php");
require_once("../class/timer.inc.php");
require_once("../config.php");
require_once("../function.php"); 

//取出MYSQL版本
$db = new mysql(DB_SERVER, DB_USER, DB_PWD, DB_NAME); 
$mysqlVersion = $db->getVersion(); 
//取出相关配置信息 
if(@ini_get("file_uploads")) 
{ 
    $fileUpload = "允许 | 文件:".ini_get("upload_max_filesize")." | 表单：".ini_get("post_max_size"); 
} 
else 
{ 
    $fileUpload = "<span class=\"red-font\">禁止</span>"; 
} 
 
//取得系统占用数据库空间大小 
$dbsize = $db->getDBSize(DB_NAME, MIS_PREFIX); 
$dbsize = $dbsize ? sizeCount($dbsize) : "未知"; 
$queryTime = $db->getQueryTimes(); 
$timer = new timer();
$executeTime = $timer->getExecuteTime(); 

$systemInfo = array("webServer"	  => PHP_OS . " | " .$_SERVER["SERVER_SOFTWARE"], 
					"domain"	  => $_SERVER["SERVER_NAME"], 
					"phpVer"	  => PHP_VERSION, 
					"mysqlVer"	  => $mysqlVersion, 
					"upload"	  => $fileUpload, 
					"dbsize"	  => $dbsize, 
                    "queryTime"   => $queryTime,
                    "executeTime" => $executeTime
					); 
include('../templates/admin/admin_sysInfo_htm.html'); 
?> 
 
