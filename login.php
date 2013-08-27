<?php
ob_start();
session_start();
header("Content-Type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . "/config.php");

function login($username, $passwd){
    $conn = ldap_connect(LDAP_SERVER, LDAP_PORT) or die ("Could not connect to" . LDAP_SERVER);
    if ($conn){
        ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);
        
        $ldap_username = $username . BASEDN;
        $bind = ldap_bind($conn, $ldap_username, $passwd);
        if ($bind){
            $dn = "dc=cd,dc=ta-mp,dc=com";
            $filter = "sAMAccountName=".$username;
            $get_info = array('mail', 'description');
            $sr = ldap_search($conn, $dn, $filter, $get_info);
            $user_info = ldap_get_entries($conn, $sr);
            $_SESSION["userid"] = $username;
            $_SESSION["mail"] = $user_info[0]['mail'][0];;
            $_SESSION["username"] = $user_info[0]['description'][0];
            if ('xban' == $username && !empty($_SESSION['user'])){
                $_SESSION["admin"] = $username;
            }
            return true;
        }
        else{
            return false;
        }
        ldap_close($conn);
    }
    else{
        echo "<center><p>连接LDAP服务器失败！！！</p><a href=\"./index.php\">clik go back...</a></center>";
        echo '<meta http-equiv="refresh" content="2;url=./index.php">';
    }
}

$login_status = false;
$template_dir = 'index.php';
@$admin = $_GET['user'];

if (!empty($_POST["username"]) && !empty($_POST["password"])){
    $username = $_POST["username"];
    $passwd = $_POST["password"];
    $login_status = login($username, $passwd);
    if (!empty($admin) && "xban" != $username){
        $login_status = false;
    }
}

if (!$login_status){
    $className = "login-msg";
    $message = "用户名或密码错误，请重试。";
    if (!empty($_GET['user'])){
        $className = "not-display";
        $message = "";
        @$_SESSION['user'] = $_GET['user'];
    }
    include("templates/login.html");
}
else{
    #header("Location: templates/index.html");
    if (!empty($_SESSION['admin'])){
        header("Location: admin/index.php");
    }
    else{
        header("Location: " . $template_dir);
    }
}
?>
