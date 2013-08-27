<?php
require_once('../class/mysql.inc.php');
require_once('../config.php');

session_start();
#if (!empty($_SESSION['userid'])){
#    forward('../templates/login.html');
#}

if (!empty($_GET['action'])){
    switch($_GET['action']){
        case 'addData':
            $DB_TB = 'user';
            getData();
            $db = new mysql(DB_SERVER, DB_USER, DB_PWD, DB_NAME);
            if (ADMIN == $_SESSION['userid']){
                $admin = 1;
            }
            else{
                $admin = 0;
            }
            $sql = "select uid from " . MIS_PREFIX . $DB_TB . " where username=" . $user;
            $db->query($sql);
            $result = $db->fetchRow();
            if (!$result){
                $sql = "insert into " . MIS_PREFIX . $DB_TB . "(username, department, admin) " .
                    "values($user, $department, $admin)"; 
                $db->query($sql) or die("Invalid query: " . mysql_error());
                $uid = mysql_insert_id();
            }
            else{
                $uid = $result['uid'];
            }
            $DB_TB = 'product';
            $status = 0;
            $sql = "insert into " . MIS_PREFIX . $DB_TB . "(uid, prdc_name, count, status, comments, borrow_date, back_date) " .
                "values($uid, $prdc_name, $prdc_count, $status, $comments, $borrow_date, $back_date)";
            $db->query($sql) or die("Invalid query: " . mysql_error());
            $addArray_info = array(
                'user'         => $user,
                'department'   => $department,
                'prdc_name'    => $prdc_name,
                'prdc_count'   => $prdc_count,
                'borrow_date'  => $borrow_date,
                'back_date'    => $back_date,
                'comments'     => $comments
                );
            include('../templates/admin/configure.html');
    }
}

function getData(){
    global $user;
    global $department;
    global $prdc_name; 
    global $prdc_count; 
    global $borrow_date;
    global $back_date;
    global $comments;
    $user = "'" . $_POST['username'] . "'";
    $department = "'" . $_POST['department'] . "'";
    $prdc_name = "'" . $_POST['prdc_name'] . "'";
    $prdc_count = "'" . $_POST['prdc_count'] . "'";
    $borrow_date = "'" . $_POST['borrowdate'] . "'";
    $back_date = "'" . $_POST['backdate'] . "'";
    $comments = "'" . $_POST['comments'] . "'";
}
