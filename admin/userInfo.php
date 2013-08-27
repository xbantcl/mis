<?php
//==================================================== 
//      FileName:userManager.php 
//      Summary: 员工信息操作 
//       
//====================================================
require_once('../config.php');
require_once('../function.php');
require_once('../class/mysql.inc.php');
require_once('../class/timer.inc.php');

$db = new mysql(DB_SERVER, DB_USER, DB_PWD, DB_NAME);
$queryTime = $db->getQueryTimes();
$timer = new timer();
$executeTime = $timer->getExecuteTime();

$DB_TB = 'user';
$sql = "select * from " . MIS_PREFIX . $DB_TB . " where username not like 'admin'";
$db->query($sql);
$result = $db->fetchAll();

$_obj['title'] = '员工信息';
$_obj['buttonValue'] = '添 加';
$_obj['action'] = 'add';
$_obj['queryTime'] = $queryTime;
$_obj['executeTime'] = $executeTime;

if (!empty($_GET['uid']) && !empty($_GET['type']))
{
    if ('modify' == $_GET['type'])
    {
        $_obj['title'] = '修改员工信息';
        $_obj['buttonValue'] = '修 改';
        $_obj['action'] = 'modify';
        $sql = "select * from " . MIS_PREFIX . $DB_TB . " where uid like '" . $_GET['uid'] . "'";
        $db->query($sql);
        $result = $db->fetchRow();
        $_userInfo['uid'] = $result['uid'];
        $_userInfo['username'] = $result['username'];
        $_userInfo['department'] = $result['department'];
        $_userInfo['email'] = $result['email'];
        include('../templates/admin/admin_modifyUserInfo.html');
        exit;
    }
    else
    {
        $sql = "delete from " . MIS_PREFIX . $DB_TB . " where uid like '" . $_GET['uid'] . "'";
        $result = $db->query($sql);
        if($result){
            forward("userInfo.php");
        }
    }
}
else if(!empty($_POST['action']) && 'add' == $_POST['action'])
{
    $_obj['title'] = '添加员工信息';
    $_obj['action'] = 'adduser';
    include('../templates/admin/admin_modifyUserInfo.html');
    exit;
}
else if(!empty($_POST['action']) && 'adduser' == $_POST['action'])
{
    $sql = "INSERT INTO " . MIS_PREFIX . $DB_TB . "(username, department, email, admin) " .
        "values('" . $_POST['username'] . "','" . $_POST['department'] . "','" . $_POST['email'] . "'," . "'0')";
    if (empty($_POST['username'])){
        $errorList['username'] = '名字不能为空.';
        $errorClass['username'] = "error-msg";
    }
    if(empty($_POST['email'])){
        $errorList['email'] = '邮箱不能为空.';
        $errorClass['email'] = "error-msg";
    }
    if (!empty($errorList)){
        include('../templates/admin/admin_modifyUserInfo.html');
        exit;
    }
    $result = $db->query($sql);
    if($result){
        forward("userInfo.php");
    }
}
else if(!empty($_POST['action']) && 'modify' == $_POST['action'])
{
    $sql = "UPDATE " . MIS_PREFIX . $DB_TB . " SET username='" . $_POST['username'] . "'," .
        "department='" . $_POST['department'] . "',email='" . $_POST['email'] . "' where uid='" . $_POST['uid'] . "'";
    $result = $db->query($sql);
    if ($result){
        forward("userInfo.php");
    }
}
else
{
    include('../templates/admin/admin_userInfo.html');
    exit;
}
?>
