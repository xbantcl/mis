<?php
session_cache_limiter("private, max-age=10800");
session_start();
require_once("function.php");
require_once("config.php");
require_once("class/mysql.inc.php");

if (!isset($_SESSION['userid'])){
    $className = "not-display";
    $message = "";
    require_once("templates/login.html");
    exit;
}

if (!empty($_GET['action'])){
    $db = new mysql(DB_SERVER, DB_USER, DB_PWD, DB_NAME);
    $DB_TB = 'user';
    $sql = "SELECT uid FROM " . MIS_PREFIX . $DB_TB . " WHERE email like '" . $_SESSION['mail'] . "'";
    $db->query($sql);
    $userInfo = $db->fetchRow();
    switch($_GET['action']){
        case 'product':
            $DB_TB = 'borrow_info';
            $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " where uid like '" . $userInfo['uid'] . "'";
            $db->query($sql);
            $productInfo = $db->fetchAll();
            $title = "我的物品";
            //include('templates/product_info.html');
            break;
        case 'allproduct':
            $DB_TB = 'product';
            $sql = "SELECT DISTINCT project FROM " . MIS_PREFIX . $DB_TB . " where project not like ''";
            $db->query($sql);
            $_project = $db->fetchAll();
            $sql = "SELECT project FROM " . MIS_PREFIX . $DB_TB . " WHERE project like ''";
            $db->query($sql);
            $_other = $db->fetchRow();
            $_prj = $_project[0]['project'];
            if (!isset($_POST['project'])){
                $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " WHERE project like '" . $_prj . "'";
                $db->query($sql);
                $_productInfo = $db->fetchAll();
            }
            else{
                $_prj = $_POST['project'];
                if ("other" == $_prj){
                    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " where project like ''";
                }    
                else{
                    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " where project like '" . $_prj . "'"; 
                }    
                $db->query($sql);
                $_productInfo = $db->fetchAll();
            }  
            //include('templates/allProduct.html');
            break;
        case 'history':
            $DB_TB = 'history';
            $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " where uid like '" . $userInfo['uid'] . "'";
            $db->query($sql);
            $productInfo = $db->fetchAll();
            $title = "我的物品借出记录";
            //include('templates/product_info.html');
            break;
        default:
            include('templates/product_info.html');
            break;
    }
}
if (!isset($_POST['project'])){
    $_prj = $_project[0]['project'];
}
else{
    $_prj = @$_POST['project'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<title>手机借入借出</title> 
<meta http-equiv="content-type" content="text/html;charset=utf-8" /> 
<link rel="stylesheet" href="style/class/main.css" type="text/css" />
<script type="text/javascript" src="javascript/function.js" ></script>
</head> 
 
<body onload="InitDocument([['project'], '<?php echo $_prj;?>'])"> 
<div id="head"> 
    <div id='logo'><img src="style/class/images/logo.gif"/></div> 
    <div id='text'>TCL/CD 物品记录系统</div> 
</div> 
<div id="navigator"> 
    <ul> 
        <li><a href="./">首 页</a></li>
        <li class="back"><a href="./login.php?user=admin">管理员登录</a></li>
        <li class="back"><a href="logout.php">退出系统</a></li>
    </ul>
</div> 
<div id="title">
    欢迎 <font size='5px' color='red'><?php echo $_SESSION['username'];?></font> 进入物品管理系统
</div>
<div>
    ********************************************************
</div>
<?php
if (!empty($_GET['action'])){
    switch($_GET['action']){
        case 'allproduct':
            include('templates/allProduct.html');
            break;
        default:
            include('templates/product_info.html');
            break;
    }
}
else{
?>
<div class='prdc-title'>
    <h3><image src='images/dot_1.gif'/>  <a href="index.php?action=allproduct">CD物品总信息</a></h3>
</div>
<div class='prdc-title'>
    <h3><image src='images/dot_1.gif'/>  <a href="index.php?action=product">我的物品</a></h3>
</div>
<div class='prdc-title'>
    <h3><image src='images/dot_1.gif'/>  <a href="index.php?action=history">我的物品借出记录</a></h3>
</div>
<?php 
}
?>    
</body>
</html>

