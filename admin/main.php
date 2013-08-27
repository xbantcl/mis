<?php 
//==================================================== 
//		FileName:main.php 
//		Summary: 管理系统入口 
//		 
//==================================================== 
 
require_once("../function.php"); 
 
if(!empty($_GET['action'])) 
{ 
	switch($_GET['action']) 
	{ 
		//系统设置部分 
		case 'sysInfo': 
			forward("sysInfo.php"); 
			break; 
		case 'userInfo': 
			forward("userInfo.php"); 
			break; 
        case 'exportxls':
            forward("export_xls.php");
            break;
		case 'logout': 
			header("Location: logout.php"); 
			break; 
		 
		//物品管理部分 
        case 'addproduct':
            $param['action'] = "addproduct";
            forward("product_info.php", $param);
            break;
		case 'borrowproduct': 
			$param['action'] = "borrowproduct"; 
			forward("product_info.php", $param); 
			break; 
		case 'updateproduct': 
            $param['action'] = "updateproduct";
			forward("product_info.php", $param); 
			break; 
        case 'addmore':
            $param['action'] = "addmore";
            forward("product_info.php", $param);
            break;
        case 'check':
            $param['action'] = "check";
            forward("product_info.php", $param);
            break;
        case 'allproduct':
            $param['action'] = "allproduct";
            forward("product_info.php", $param);
            break;
        case 'outInfo':
            $param['action'] = "outInfo";
            forward("product_info.php", $param);
            break;
        case 'deleteproduct':
            $param['action'] = "deleteproduct";
            forward("product_info.php", $param);
            break;
		default: 
			$param["message"] = "参数错误,请重试."; 
			forward("error.php", $param);			 
			break; 
	} 
} 
else 
{ 
    echo '欢迎使用MIS信息发布系统';
} 
?> 
 
