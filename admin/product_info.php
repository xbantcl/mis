<?php 
//==================================================== 
//		FileName: product_info.php 
//		Summary: 物品信息(添加,删除,修改) 
//		 
//==================================================== 
header("Cache-control: private");
session_cache_limiter("private, max-age=10800");
session_start();
header("Content-Type:text/html;charset=utf-8");
require_once("../function.php"); 
require_once("../config.php");
require_once("../class/mysql.inc.php");
require_once("../class/timer.inc.php");

$errorList	 = array(); 
$dataList = array(); 
$_obj = array();

$errorClass = array( //错误显示style
   "username"	=> "",
   "department"	=> "",
   "prdc_name"	=> "",
   "prdc_count"	=> "",
   "borrowdate"	=> ""
);

$configure = false;
$db = new mysql(DB_SERVER, DB_USER, DB_PWD, DB_NAME);
$queryTime = $db->getQueryTimes();
$timer = new timer();
$executeTime = $timer->getExecuteTime();

$_project = '';
$_other = '';

if(!empty($_GET['action']))	//负责显示表单 
{ 
	switch($_GET['action']) 
	{ 
		case 'addproduct':		//添加物品信息 
			$_obj = array( 
                "title"			=> "添加物品信息",
                "type"          => "添加时间",                   
                "username"		=> "", 
                "project"       => "",
                "prdc_name"		=> "", 
                "prdc_count"	=> "", 
                "borrowdate"	=> "", 
                "backdate"		=> "", 
                "comments"	    => "", 
                "action"        => "addPrdc",
                "buttonValue"	=> "添 加",
                "queryTime"     => $queryTime,
                "executeTime"   => $executeTime
            ); 
            $titleClass = array(
                "username"   => 'not-display',
                "project"    => 'light-row',
                "prdc_name"  => 'dark-row',
                "prdc_count" => 'light-row',
                "prdc_price" => 'dark-row',
                "from"       => 'light-row',
                "dest"       => 'not-display',
                "curdate"    => 'dark-row', 
                "backdate"   => 'not-display',
                "comments"   => 'light-row'
                );
            include('../templates/admin/admin_product_info.html');
			break; 

		case 'borrowproduct':		//借用信息添加 
			$_obj = array( 
                "title"			=> "借用物品信息", 
                "type"          => "借出时间",
                "username"		=> "", 
                "prdc_name"		=> "", 
                "prdc_count"	=> "", 
                "borrowdate"	=> "", 
                "backdate"		=> "", 
                "comments"	    => "", 
                "action"        => "borrowPrdc",
                "buttonValue"	=> "借 出",
                "queryTime"     => $queryTime,
                "executeTime"   => $executeTime
            ); 
            $titleClass = array(
                "username"   => 'dark-row',
                "project"    => 'light-row',
                "prdc_name"  => 'dark-row',
                "prdc_count" => 'light-row',
                "prdc_price" => 'not-display',
                "from"       => 'not-display',
                "dest"       => 'not-display',
                "curdate"    => 'dark-row', 
                "backdate"   => 'not-display',
                "comments"   => 'light-row'
                );
            include('../templates/admin/admin_product_info.html');
			break; 
 
        case 'edit':
            $uid = $_GET['uid'];
            $_prj = $_GET['prj'];
            if ("Other" == $_prj){
                $_prj = '';
            }
            $prdc_name = $_GET['prdc_name'];
            $dest = $_GET['local'];
            $DB_TB = 'user';
            $sql = "SELECT username FROM " . MIS_PREFIX . $DB_TB . " WHERE uid like '"  . $uid . "'";
            $db->query($sql);
            $userInfo = $db->fetchRow();
            $DB_TB = 'borrow_info';
            $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " WHERE uid like '" . $uid . "'" .
                " and prdc_name like '" . $prdc_name . "' and project like '" .$_prj . "'";
            if ("寄出" == $dest){
                $DB_TB = 'out_info';
                $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " WHERE prdc_name like '" . $prdc_name . "' and project like '" .$_prj . "'";
            }
            $db->query($sql);
            $borrowInfo = $db->fetchRow();
			$_obj = array( 
                "title"			=> "借用信息修改",
                "type"          => "归还时间", 
                "username"		=> $userInfo['username'], 
                "project"       => $_prj,
                "prdc_name"		=> $borrowInfo['prdc_name'], 
                "prdc_count"	=> $borrowInfo['prdc_count'], 
                "dest"          => $borrowInfo['destination'],
                "curdate"	    => "", 
                "backdate"		=> date("Y-m-d"), 
                "comments"	    => "", 
                "action"        => "modify",
                "buttonValue"	=> "修 改",
                "queryTime"     => $queryTime,
                "executeTime"   => $executeTime
            );
            $titleClass = array(
                "username"   => 'dark-row',
                "project"    => 'light-row',
                "prdc_name"  => 'dark-row',
                "prdc_count" => 'light-row',
                "prdc_price" => 'not-display',
                "from"       => 'not-display',
                "dest"       => 'not-display',
                "curdate"    => 'not-display', 
                "backdate"   => 'dark-row',
                "comments"   => 'light-row'
                );
            if ("寄出" == $dest){
                $_obj['title'] = '寄用信息修改';
                $titleClass['username'] = 'not-display';
                $titleClass['dest'] = 'dark-row';
                $titleClass['backdate'] = 'light-row';
                $titleClass['comments'] = 'dark-row';

            }
            include('../templates/admin/admin_product_info.html');
            break;

        case 'updateproduct':	    //借用信息修改 
            /*
            $DB_TB = 'borrow_info';
            $sql = "SELECT DISTINCT prdc_name FROM " . MIS_PREFIX . $DB_TB;
            $db->query($sql);
            $result = $db->fetchAll();
            for ($i=0; $i<count($result); $i++){
                $_product[] = $result[$i]['prdc_name']; //所借物品种类
            }
            $sql = "SELECT DISTINCT uid FROM " . MIS_PREFIX . $DB_TB;
            $db->query($sql);
            $result = $db->fetchAll();
            for ($i=0; $i<count($result); $i++){
                $_userId[] = $result[$i]['uid']; //员工ID
            }
            for ($i=0; $i<count($_userId); $i++){
                for ($j=0; $j<count($_product); $j++){
                    $DB_TB = 'borrow_info';
                    $count = 0;
                    $sql = "SELECT prdc_count FROM " . MIS_PREFIX . $DB_TB . " where uid like '" . $_userId[$i] . "'" .
                        " and prdc_name like '" . $_product[$j] . "'";
                    $db->query($sql);
                    $prdc_count = $db->fetchRow();
                    $count += (int)$prdc_count['prdc_count'];
                    $DB_TB = 'user';
                    $sql = "SELECT username FROM " . MIS_PREFIX . $DB_TB . " where uid like '" . $_userId[$i] . "'";
                    $db->query($sql);
                    $result = $db->fetchRow();
                    if ($count > 0){
                        $_borrowInfo[$_product[$j]][] = array(
                            'uid'        => $_userId[$i],
                            'username'   => $result['username'],
                            'prdc_count' => $count);
                    }
                }
            }*/
            $DB_TB = 'product';
            selectDb($DB_TB);
            $action = 'updateproduct';
            $_pla = "本地";
            $_dpt = 'All';
            $_prj = $_project[0]['project'];
            if (!isset($_POST['prdc_status'])){
                $_pla = "本地";
            }
            else{
                $_pla = $_POST['prdc_status'];
                $_prj = $_POST['project'];
                $_dpt = $_POST['department'];
            }
            $DB_TB = 'borrow_info';
            if ("寄出" == $_pla){
                $DB_TB = 'out_info';
            }
            if (!isset($_POST['prdc_status']) && !isset($_POST['project'])){
                $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " WHERE project like '" . $_prj . "'";
                $db->query($sql);
                $_borrowInfo = $db->fetchAll();
            }
            else{
                if ("Other" == $_prj){
                    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " where project like ''";
                }
                else{
                    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " where project like '" . $_prj . "'";
                }
                $db->query($sql);
                $_borrowInfo = $db->fetchAll();
            }
            if ($_borrowInfo && '本地' == $_pla){
                $DB_TB = 'user';
                $total = count($_borrowInfo);
                $index = 0;
                for ($i=0; $i<$total; $i++){
                    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " WHERE uid like '" . $_borrowInfo[$index]['uid'] . "'";
                    $db->query($sql);
                    $userInfo = $db->fetchRow();
                    if ('All' != $_dpt){
                        if ($_dpt != $userInfo['department']){
                            array_splice($_borrowInfo, $index, 1);
                        }
                        else{
                            $_borrowInfo[$i]['username'] = $userInfo['username'];
                            if ('Other' == $_prj){
                                $_borrowInfo[$i]['project'] = "Other";
                            }
                            $index++;
                        }
                    }
                    else{
                        $_borrowInfo[$i]['username'] = $userInfo['username'];
                        if ('Other' == $_prj){
                            $_borrowInfo[$i]['project'] = "Other";
                        }
                        $index++;
                        continue;
                    }
                } 
            }
            else if ($_borrowInfo && '寄出' == $_pla){
                for ($i=0; $i<count($_borrowInfo); $i++){
                    if ('Other' == $_prj){
                        $_borrowInfo[$i]['project'] = "Other";
                    }
                }
            }
            include('../templates/admin/admin_update_info.html');
			break;

        case 'addmore':      //批量借用物品
            $DB_TB = 'product';
            selectDb($DB_TB);
            $_prj = $_project[0]['project'];
            $_dpt = "Integration";
            $_obj['buttonValue'] = "提交";
            $_obj['action'] = "addmore";
            if(!empty($_GET['select_value'])){
                $select_value = split(',', $_GET['select_value']);
                $_prj = $select_value[0];
                $_dpt = $select_value[1];
                $DB_TB = "product";
                if ('Other' !=  $_prj){
                    $sql = "select * from " . MIS_PREFIX . $DB_TB . " where project like '" . $_prj . "'";
                }
                else{
                    $sql = "select * from " . MIS_PREFIX . $DB_TB . " where project like ''";
                }
                $db->query($sql);
                $_productInfo = $db->fetchAll();
                $DB_TB = 'user';
                if ('All' != $_dpt){
                    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " where department like '" . $_dpt . "'";
                    $db->query($sql);
                    $_userInfo = $db->fetchAll();
                }
                else{
                    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB;
                    $db->query($sql);
                    $_userInfo = $db->fetchAll();
                }
            }
            else{
                $DB_TB = "product";
                $sql = "select * from " . MIS_PREFIX . $DB_TB . " where project like '" . $_prj . "'";
                $db->query($sql);
                $_productInfo = $db->fetchAll();
                $DB_TB = "user";
                $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " where department like '" . $_dpt . "'";
                $db->query($sql);
                $_userInfo = $db->fetchAll();
            }
            include('../templates/admin/admin_addMoreProduct.html');
            break;

        case 'check':
            $DB_TB = 'product';
            selectDb($DB_TB);
            $action = 'check';
            $DB_TB = 'history';
            if (!isset($_POST['prdc_status'])){
                $_pla = "本地";
                $_dpt = 'All';
                $_prj = $_project[0]['project'];
            }
            else{
                $_pla = $_POST['prdc_status'];
                $_prj = $_POST['project'];
                $_dpt = $_POST['department'];
                if ("Other" == $_prj){
                    $_prj = '';
                }
            }
            $DB_TB = 'history';
            $sql = "SELECT DISTINCT uid,project,prdc_name FROM " . MIS_PREFIX . $DB_TB . " WHERE project like '" . $_prj . "'";
            if ("寄出" == $_pla){
                $DB_TB = 'out_history';
                $sql = "SELECT DISTINCT project,prdc_name,destination FROM " . MIS_PREFIX . $DB_TB . " WHERE project like '" . $_prj . "'";
            }
            $db->query($sql);
            $array_info = $db->fetchAll();
            if ("寄出" == $_pla){
                $DB_TB = 'out_info';
                $_borrowInfo = array();
                for ($i=0; $i<count($array_info); $i++){
                    $sql = "select * from " . MIS_PREFIX . $DB_TB . " where project like '" . $_prj . "'" .
                       " and prdc_name like '" . $array_info[$i]['prdc_name'] . "' and destination like '" . $array_info[$i]['destination'] . "'";
                    $db->query($sql);
                    $result = $db->fetchall();
                    if ($result){
                        $_borrowInfo = array_merge($_borrowInfo, $result);
                    }
                    else{
                        $_borrowInfo[] = array(
                            'project'    => $array_info[$i]['project'],
                            'prdc_name'  => $array_info[$i]['prdc_name'],
                            'prdc_count' => 0,
                            'dest'       => $array_info[$i]['destination']
                        );
                    }
                }
            }
            else{
                $DB_TB = "borrow_info";
                $_borrowInfo = array();
                for ($i=0; $i<count($array_info); $i++){
                    $sql = "select * from " . MIS_PREFIX . $DB_TB . " where uid like '" . $array_info[$i]['uid'] . "'" .
                        " and project like '" . $_prj . "' and prdc_name like '" . $array_info[$i]['prdc_name'] . "'";
                    $db->query($sql);
                    $result = $db->fetchall();
                    if ($result){
                        $_borrowInfo = array_merge($_borrowInfo, $result);
                    }
                    else{
                        $_borrowInfo[] = array(
                            'uid'        => $array_info[$i]['uid'],
                            'project'    => $array_info[$i]['project'],
                            'prdc_name'  => $array_info[$i]['prdc_name'],
                            'prdc_count' => 0
                        );
                    }
                }
            }
            if ('' == $_prj){
                $_prj = 'Other';
            }
            if ($_borrowInfo && "本地" == $_pla){
                $DB_TB = 'user';
                $total = count($_borrowInfo);
                $index = 0;
                for ($i=0; $i<$total; $i++){
                    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " WHERE uid like '" . $_borrowInfo[$index]['uid'] . "'";
                    $db->query($sql);
                    $userInfo = $db->fetchRow();
                    if ('All' != $_dpt){
                        if ($_dpt != $userInfo['department']){
                            array_splice($_borrowInfo, $index, 1);
                        }
                        else{
                            $_borrowInfo[$i]['username'] = $userInfo['username'];
                            if ('Other' == $_prj){
                                $_borrowInfo[$i]['project'] = "Other";
                            }
                            $index++;
                        }
                    }
                    else{
                        $_borrowInfo[$i]['username'] = $userInfo['username'];
                        if ('Other' == $_prj){
                            $_borrowInfo[$i]['project'] = "Other";
                        }
                        $index++;
                        continue;
                    }
                } 
            }
            else if ($_borrowInfo && '寄出' == $_pla){
                for ($i=0; $i<count($_borrowInfo); $i++){
                    if ('Other' == $_prj){
                        $_borrowInfo[$i]['project'] = "Other";
                    }
                }
            }
            include('../templates/admin/admin_check_info.html');
            break;

        case 'allproduct':
            $DB_TB = 'product';
            selectDb($DB_TB);
            $action = 'allproduct';
            $_pla = "本地";
            $_prj = $_project[0]['project'];
            if (!isset($_POST['prdc_status']) && !isset($_POST['project'])){
                $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " WHERE project like '" . $_prj . "'";
                $db->query($sql);
                $_productInfo = $db->fetchAll();
            }
            else{
                $_pla = $_POST['prdc_status'];
                $_prj = $_POST['project'];
                if ("Other" == $_prj){
                    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " where project like ''";
                }
                else{
                    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " where project like '" . $_prj . "'";
                }
                $db->query($sql);
                $_productInfo = $db->fetchAll();
            }
            $display = 'not-display';
            include('../templates/admin/admin_allProduct.html');
            break;

        case 'detail':
            $uid = $_GET['uid'];
            $_prj = $_GET['prj'];
            $prdc_name = $_GET['prdc_name'];
            $dest = $_GET['local'];
            $product_info['prdc_name'] = $prdc_name;
            $DB_TB = 'history';
            if ('Other' == $_prj){
                $_prj = '';
            }
            $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " WHERE uid like '" . $uid . "' and prdc_name like '" . $prdc_name . "'" .
                " and project like '" . $_prj . "'";
            if ("寄出" == $dest){
                $DB_TB = 'out_history';
                $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " WHERE prdc_name like '" . $prdc_name . "'" .
                    " and project like '" . $_prj . "'";
            }
            $db->query($sql);
            $_borrowInfo = $db->fetchAll();
            if ('' == $_prj){
                $_prj = 'Other';
            }
            include('../templates/admin/admin_history.html');
            break;

        case 'outInfo':
			$_obj = array( 
                "title"			=> "寄出物品", 
                "type"          => "寄出时间",
                "username"		=> "", 
                "prdc_name"		=> "", 
                "prdc_count"	=> "", 
                "borrowdate"	=> "", 
                "backdate"		=> "", 
                "comments"	    => "", 
                "action"        => "sendOut",
                "buttonValue"	=> "寄 出",
                "queryTime"     => $queryTime,
                "executeTime"   => $executeTime
            ); 
            $titleClass = array(
                "username"   => 'not-display',
                "project"    => 'dark-row',
                "prdc_name"  => 'light-row',
                "prdc_count" => 'dark-row',
                "prdc_price" => 'not-display',
                "from"       => 'not-display',
                "dest"       => 'light-row',
                "curdate"    => 'dark-row', 
                "backdate"   => 'not-display',
                "comments"   => 'light-row'
                );
            include('../templates/admin/admin_product_info.html');
            break;
        case 'deleteproduct':
            $DB_TB = 'product';
            selectDb($DB_TB);
            $action = 'deleteproduct';
            $_pla = "本地";
            $_prj = $_project[0]['project'];
            if (!isset($_POST['prdc_status']) && !isset($_POST['project'])){
                if (!empty($_GET['prj'])){
                    if ("Other" == $_GET['prj']){
                        $_prj = '';
                    }
                    else{
                        $_prj = $_GET['prj'];
                    }
                    $sql = "DELETE FROM " . MIS_PREFIX . $DB_TB .
                        " where project like '" . $_prj . "' and prdc_name like '" . $_GET['prdc_name'] . "'";
                    $db->query($sql);
                }
                $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " WHERE project like '" . $_prj . "'";
                $db->query($sql);
                $_productInfo = $db->fetchAll();
                if (!empty($_GET['prj'])){
                    $_prj = $_GET['prj'];
                }
            }
            else{
                $_pla = $_POST['prdc_status'];
                $_prj = $_POST['project'];
                if ("Other" == $_prj){
                    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " where project like ''";
                }
                else{
                    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " where project like '" . $_prj . "'";
                }
                $db->query($sql);
                $_productInfo = $db->fetchAll();
            }
            $display = '';
            include('../templates/admin/admin_allProduct.html');
            break;
 
		default: 
			$param["message"] = "参数错误,请重试."; 
			forward("error.php", $param);			 
			break; 
	} 
} 

else if(!empty($_POST['action']))	//负责表单提交后的数据处理 
{ 
	switch($_POST['action']) 
	{ 
        case 'addPrdc':
            $titleClass = 'not-display';
            getData();
            $_obj = $dataList;
            $_obj['title'] = '添加物品信息';
            $_obj['type'] = '添加时间';
            $_obj['action'] = 'addPrdc';
            $_obj['buttonValue'] = "添 加";
            $_obj['queryTime'] = $queryTime;
            $_obj['executeTime'] = $executeTime;
            $titleClass = array(
                "username"   => 'not-display',
                "project"    => 'light-row',
                "prdc_name"  => 'dark-row',
                "prdc_count" => 'light-row',
                "prdc_price" => 'dark-row',
                "from"       => 'light-row',
                "dest"       => 'not-display',
                "curdate"    => 'dark-row', 
                "backdate"   => 'not-display',
                "comments"   => 'light-row'
                );
            validateForm();

            $DB_TB = 'product';
            if (!empty($dataList['project'])){
                $sql = "select * from " . MIS_PREFIX . $DB_TB . " where prdc_name like '" . $dataList['prdc_name'] . "'" .
                    " and project like '" . $dataList['project'] . "'";
            }
            else{
                $sql = "select * from " . MIS_PREFIX . $DB_TB . " where prdc_name like '" . $dataList['prdc_name'] . "'";
            }
            $db->query($sql);
            $result = $db->fetchRow();
            if (!$result){
                $status = 1; //表示没有该物品
                $sql = "insert into " . MIS_PREFIX . $DB_TB . "(project, prdc_name, prdc_price, prdc_count, status, comments, add_date, total, comefrom) " .
                    "values(" . 
                    "'" . $dataList['project'] . "'," .
                    "'" . $dataList['prdc_name'] . "'," .
                    "'" . $dataList['prdc_price'] . "'," .
                    "'" . $dataList['prdc_count'] . "'," .
                    "'" . $status . "'," .
                    "'" . $dataList['comments'] . "'," . 
                    "'" . $dataList['curdate'] . "'," .
                    "'" . $dataList['prdc_count'] . "'," .
                    "'" . $dataList['from'] . "')";
                $flag = $db->query($sql);
                $message = "添加成功.";
                if (!$flag){
                    $message = "添加失败.";
                    exit;
                }
            }
            else{
                $sql = "UPDATE " . MIS_PREFIX . $DB_TB . " SET prdc_count='" . ((int)$result['prdc_count']+(int)$dataList['prdc_count']) .
                    "', total='" . ((int)$result['total']+(int)$dataList['prdc_count']) .
                    "' where prdc_name like '" . $dataList['prdc_name'] . "' and project like '" . $dataList['project'] . "'";
                $db->query($sql); 
            }
            include('../templates/admin/admin_configure.html');
            break;

        case 'borrowPrdc':
            $DB_TB = 'user';
            getData();

            $_obj = $dataList;
            $_obj['title'] = '借用信息添加';
            $_obj['type'] = '借出时间';
            $_obj['action'] = 'borrowPrdc';
            $_obj['buttonValue'] = "添 加";
            $_obj['queryTime'] = $queryTime;
            $_obj['executeTime'] = $executeTime;

            $titleClass = array(
                "username"   => 'dark-row',
                "prdc_name"  => 'light-row',
                "prdc_count" => 'dark-row',
                "prdc_price" => 'not-display',
                "from"       => 'not-display',
                "dest"       => 'not-display',
                "curdate"    => 'light-row', 
                "backdate"   => 'not-display',
                "comments"   => 'dark-row'
                );

			validateForm(); 
            $sql = "select uid from " . MIS_PREFIX . $DB_TB . " where username=" . "'" . $dataList['username'] . "'";
            $db->query($sql);
            $result = $db->fetchRow();
            if (!$result){
                $message = "没有此人信息，请添加.";
                include('../templates/message.html');
                exit;
            }   
            else{
                $uid = $result['uid'];
            }   
            $DB_TB = 'product';
            $status = 0; //表示借出
            $sql = "select * from " . MIS_PREFIX . $DB_TB . " where prdc_name like '" . $dataList['prdc_name'] . "'" .
                " and project like '" . $dataList['project'] . "'";
            $db->query($sql);
            $result = $db->fetchRow();
            if (!$result){
                $message = "抱歉，没有你想要的物品.";
                include('../templates/message.html');
                exit;
            }
            if ((int)$result['prdc_count'] > (int)$dataList['prdc_count']){
                $DB_TB = "borrow_info";
                $sql = "select * from " . MIS_PREFIX . $DB_TB . " where uid like '" . $uid . "' and prdc_name like '" . $dataList['prdc_name'] . "'" .
                    " and project like '" . $dataList['project'] . "'";
                $db->query($sql);
                $result = $db->fetchRow();
                if (!$result){ 
                    $sql = "insert into " . MIS_PREFIX . $DB_TB . "(uid, project, prdc_name, prdc_count, status, borrow_date) " .
                        "values($uid," .
                        "'" . $dataList['project'] . "'," . 
                        "'" . $dataList['prdc_name'] . "'," .
                        "'" . $dataList['prdc_count'] . "'," .
                        "'" . $status . "'," .
                        "'" . $dataList['borrow_date'] . "')";
                }
                else{
                    $sql = "update " . MIS_PREFIX . $DB_TB . " set prdc_count='" . ((int)$dataList['prdc_count']+(int)$result['prdc_count']) . "'" .
                        " where uid like '" . $uid . "' and prdc_name like '" . $dataList['prdc_name'] . "' and project like '" . $dataList['project'] . "'";
                }
                $flag = $db->query($sql);
                $message = "借出成功.";
                if (!$flag){
                    $message = "借出失败.";
                    exit;
                }
                $DB_TB = 'product';
                $sql = "select prdc_count from " . MIS_PREFIX . $DB_TB . " where prdc_name like '" . $dataList['prdc_name'] . "'" .
                    " and project like '" . $dataList['project'] . "'";
                $db->query($sql);
                $result = $db->fetchRow();
                $sql = "update " . MIS_PREFIX . $DB_TB . " set prdc_count='" . ((int)$result['prdc_count']-(int)$dataList['prdc_count']) . "'" .
                    " where prdc_name like '" . $dataList['prdc_name'] . "' and project like '" . $dataList['project'] . "'";
                $db->query($sql);
                $DB_TB = 'history';
                $sql = "insert into " . MIS_PREFIX . $DB_TB . "(uid, project, prdc_name, prdc_count, status, borrow_date, comments) " .
                    "values('" . $uid . "'," . 
                    "'" . $dataList['project'] . "'," .
                    "'" . $dataList['prdc_name'] . "'," .
                    "'" . $dataList['prdc_count'] . "'," .
                    "'" . $status . "'," .
                    "'" . $dataList['curdate'] . "'," .
                    "'" . $dataList['comments'] . "')"; 
                $db->query($sql); 
            }
            else{
                $message = "此物品数量不足.";
                include('../templates/message.html');
                exit;
            }
            include('../templates/admin/admin_configure.html');
			break; 
 
        case 'addmore':
            $productList = $_POST['productlist'];
            $userList = $_POST['userlist'];
            $_prj = $_POST['project'];
            if ("Other" == $_prj){
                $_prj = '';
            }
            if (empty($productList) || empty($userList)){
                echo "Please choose object";
                exit;
            }
            $status = 0;
            for ($i=0; $i<count($productList); $i++){
                $DB_TB = "product";
                $sql = "SELECT prdc_count FROM " . MIS_PREFIX . $DB_TB . " where project like '" . $_prj . "' and prdc_name like '" . $productList[$i] . "'"; 
                $db->query($sql);
                $result = $db->fetchRow();
                $prdc_count = $result['prdc_count'];
                if ((int)$prdc_count < count($userList)){
                    $message = $productList[$i] . " 数量不足.";
                    include('../templates/message.html');
                    exit;
                }
            }
            for($i=0; $i<count($productList); $i++){
                $productCount[$productList[$i]] = 0;
            }
            for ($i=0; $i<count($userList); $i++){
                for ($j=0; $j<count($productList); $j++){
                    $DB_TB = "borrow_info";
                    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " where uid like '" . $userList[$i] . "'" .
                        " and project like '" . $_prj . "' and prdc_name like '" . $productList[$j] . "'";
                    $db->query($sql);
                    $result = $db->fetchRow();
                    if (!$result){
                        $sql = "INSERT INTO " . MIS_PREFIX . $DB_TB . "(uid, project, prdc_name, prdc_count, status, borrow_date) " .
                            "values($userList[$i]," .
                            "'" . $_prj . "'," .
                            "'" . $productList[$j] . "'," .
                            "'" . $_POST[$userList[$i].'count'] . "'," .
                            "'" . $status . "'," .
                            "'" . $_POST[$userList[$i].'borrowdate'] . "')";
                    }
                    else{
                        $sql = "UPDATE " . MIS_PREFIX . $DB_TB . " SET prdc_count='" . ((int)$_POST[$userList[$i].'count']+(int)$result['prdc_count']) .
                           "' where uid like '" . $userList[$i] . "' and project like '" . $_prj . "' and prdc_name like '" . $productList[$j] . "'"; 
                    }
                    $productCount[$productList[$j]] += (int)$_POST[$userList[$i].'count'];
                    $db->query($sql) or die("借出失败");
                    //记录借出信息
                    $DB_TB = 'history';
                    $sql = "INSERT INTO " . MIS_PREFIX . $DB_TB . "(uid, project, prdc_name, prdc_count, status, borrow_date, comments) " .
                        "values($userList[$i]," . 
                        "'" . $_prj . "'," .
                        "'" . $productList[$j] . "'," .
                        "'" . $_POST[$userList[$i].'count'] . "'," .
                        "'" . $status . "'," .
                        "'" . $_POST[$userList[$i].'borrowdate'] . "'," .
                        "'" . $_POST[$userList[$i].'comments'] . "')"; 
                    $db->query($sql); 
                }
            }
            $DB_TB = 'product';
            for($i=0; $i<count($productList); $i++){
                $sql = "SELECT prdc_count from " . MIS_PREFIX . $DB_TB . " where project like '" . $_prj . "' and prdc_name like '" . $productList[$i] . "'";
                $db->query($sql);
                $result = $db->fetchRow();
                $excessCount = (int)$result['prdc_count'];
                $borrowCount = (int)$productCount[$productList[$i]];
                if ($excessCount >= $borrowCount ){
                    $sql = "UPDATE " . MIS_PREFIX . $DB_TB . " SET prdc_count='" . ($excessCount - $borrowCount) . "'" .
                        " where project like '" . $_prj . "' and prdc_name like '" . $productList[$i] . "'";
                    $db->query($sql) or die("借出统计失败");
                }
                else{
                    echo "物品" . $productList[$i] . "数量不够.";
                }
            }
            $param['action'] = "addmore";
            forward('product_info.php', $param);
            break;

        case 'modify':
            getData();
            $flag = false;
            $DB_TB = 'user';
            $sql = "SELECT uid FROM " . MIS_PREFIX . $DB_TB . " WHERE username like '" . $dataList['username'] . "'";
            $db->query($sql);
            $userId = $db->fetchRow(); 
            $DB_TB = 'borrow_info';
            $sql = "SELECT prdc_count FROM " . MIS_PREFIX . $DB_TB . " WHERE uid like '" . $userId['uid'] . "'" .
                " and project like '" . $dataList['project'] . "' and prdc_name like '" . $dataList['prdc_name'] . "'";
            if (!empty($_POST['dest'])){
                $DB_TB = 'out_info';
                $sql = "SELECT prdc_count FROM " . MIS_PREFIX . $DB_TB . " WHERE project like '" .$dataList['project'] . "'" .
                    " and prdc_name like '" . $dataList['prdc_name'] . "'";
            }
            $db->query($sql);
            $borrow_count = $db->fetchRow();
            $sql = "update " . MIS_PREFIX . $DB_TB . " set prdc_count='" . $dataList['prdc_count'] . "'" .
                " where uid like '" . $userId['uid'] . "' and project like '" .$dataList['project'] . "' and prdc_name like '" . $dataList['prdc_name'] . "'";
            if (!empty($_POST['dest'])){
                $sql = "update " . MIS_PREFIX . $DB_TB . " set prdc_count='" . $dataList['prdc_count'] . "'" .
                    " where project like '" .$dataList['project'] . "' and prdc_name like '" . $dataList['prdc_name'] . "'";
            }
            $db->query($sql);
            $DB_TB = 'product';
            $sql = "SELECT prdc_count FROM " . MIS_PREFIX . $DB_TB . " where project like '" . $dataList['project'] . "'" .
                " and prdc_name like '" . $dataList['prdc_name'] . "'";
            $db->query($sql);
            $prdc_count = $db->fetchRow();
            $sql = "UPDATE " . MIS_PREFIX . $DB_TB . " SET prdc_count='" . ((int)$prdc_count['prdc_count']+(int)$borrow_count['prdc_count']-(int)$dataList['prdc_count']) .
                "' where project like '" . $dataList['project'] . "' and prdc_name='" . $dataList['prdc_name'] . "'";
            $db->query($sql); 
            //删除已经归还记录
            if (0 == (int)$dataList['prdc_count']){
                $DB_TB = 'borrow_info';
                $sql = "DELETE FROM " . MIS_PREFIX . $DB_TB . " WHERE uid like '" . $userId['uid'] . "' and prdc_name like '" . $dataList['prdc_name'] . "'";
                if (!empty($_POST['dest'])){
                    $DB_TB = "out_info";
                    $sql = "DELETE FROM " . MIS_PREFIX . $DB_TB . " WHERE project like '" . $dataList['project'] . "' and prdc_name like '" . $dataList['prdc_name'] . "'";
                }
                $db->query($sql);
            }
            $status = 0; //借出
            if ((int)$borrow_count['prdc_count'] > (int)$dataList['prdc_count']){
                $status = 1; //归还
                $DB_TB = 'history';
                $sql = "insert into " . MIS_PREFIX . $DB_TB . "(uid, project, prdc_name, prdc_count, status, back_date, comments) " .
                    "values('" . $userId['uid'] . "'," . 
                    "'" . $dataList['project'] . "'," .
                    "'" . $dataList['prdc_name'] . "'," .
                    "'" . abs((int)$borrow_count['prdc_count']-(int)$dataList['prdc_count']) . "'," .
                    "'" . $status . "'," .
                    "'" . $dataList['back_date'] . "'," .
                    "'" . $dataList['comments'] . "')"; 
                if (!empty($_POST['dest'])){
                    $DB_TB = 'out_history';
                    $sql = "insert into " . MIS_PREFIX . $DB_TB . "(project, prdc_name, prdc_count, status, back_date, comments) " .
                        "values('" . $dataList['project'] . "'," . 
                        "'" . $dataList['prdc_name'] . "'," .
                        "'" . abs((int)$borrow_count['prdc_count']-(int)$dataList['prdc_count']) . "'," .
                        "'" . $status . "'," .
                        "'" . $dataList['back_date'] . "'," .
                        "'" . $dataList['comments'] . "')"; 
                }
               $flag = $db->query($sql); 
               $message = "归还成功.";
                if (!$flag){
                    $message = "归还成功.";
                    exit;
                }
            }
            else if ((int)$borrow_count['prdc_count'] < (int)$dataList['prdc_count']){
                $DB_TB = 'history';
                $sql = "insert into " . MIS_PREFIX . $DB_TB . "(uid, project, prdc_name, prdc_count, status, borrow_date, comments) " .
                    "values('" . $userId['uid'] . "'," . 
                    "'" . $dataList['project'] . "'," .
                    "'" . $dataList['prdc_name'] . "'," .
                    "'" . abs((int)$borrow_count['prdc_count']-(int)$dataList['prdc_count']) . "'," .
                    "'" . $status . "'," .
                    "'" . $dataList['back_date'] . "'," .
                    "'" . $dataList['comments'] . "')"; 
                if (!empty($_POST['dest'])){
                    $DB_TB = 'out_history';
                    $sql = "insert into " . MIS_PREFIX . $DB_TB . "(project, prdc_name, prdc_count, status, borrow_date, comments) " .
                        "values('" . $dataList['project'] . "'," . 
                        "'" . $dataList['prdc_name'] . "'," .
                        "'" . abs((int)$borrow_count['prdc_count']-(int)$dataList['prdc_count']) . "'," .
                        "'" . $status . "'," .
                        "'" . $dataList['back_date'] . "'," .
                        "'" . $dataList['comments'] . "')"; 
                }
                $flag = $db->query($sql); 
                $message = "借出成功.";
                if (!$flag){
                    $message = "借出成功.";
                    exit;
                }
            }
            include('../templates/admin/admin_configure.html');
            /*
            $param['action'] = "updateproduct";
            forward('main.php', $param);*/
            break;
 
        case 'sendOut':
            getData();
            $_obj = $dataList;
            $_obj['title'] = '物品寄出信息添加';
            $_obj['type'] = '寄出时间';
            $_obj['action'] = 'sendOut';
            $_obj['buttonValue'] = "寄  出";
            $_obj['queryTime'] = $queryTime;
            $_obj['executeTime'] = $executeTime;

            $titleClass = array(
                "username"   => 'not-display',
                "project"    => 'dark-row',
                "prdc_name"  => 'light-row',
                "prdc_count" => 'dark-row',
                "prdc_price" => 'not-display',
                "from"       => 'not-display',
                "dest"       => 'light-display',
                "curdate"    => 'dark-row', 
                "backdate"   => 'not-display',
                "comments"   => 'light-row'
                );

			validateForm(); 
            $DB_TB = 'product';
            $status = 0; //表示借出
            $sql = "select prdc_name from " . MIS_PREFIX . $DB_TB . " where prdc_name like '" . $dataList['prdc_name'] . "'" .
                " and project like '" . $dataList['project'] . "'";
            $db->query($sql);
            $result = $db->fetchrow();
            if (!$result){
                $message = "抱歉，没有你想要的物品.";
                include('../templates/message.html');
                exit;
            }
            $DB_TB = "out_info";
            $sql = "select * from " . MIS_PREFIX . $DB_TB . " where prdc_name like '" . $dataList['prdc_name'] . "'" .
                " and project like '" . $dataList['project'] . "' and destination like '" . $dataList['dest'] . "'";
            $db->query($sql);
            $result = $db->fetchrow();
            if (!$result){ 
                $sql = "insert into " . MIS_PREFIX . $DB_TB . "(project, prdc_name, prdc_count, status, borrow_date, destination) " .
                    "values(" .
                    "'" . $dataList['project'] . "'," . 
                    "'" . $dataList['prdc_name'] . "'," .
                    "'" . $dataList['prdc_count'] . "'," .
                    "'" . $status . "'," .
                    "'" . $dataList['borrow_date'] . "'," .
                    "'" . $dataList['dest'] . "')";
            }
            else{
                $sql = "update " . MIS_PREFIX . $DB_TB . " set prdc_count='" . ((int)$dataList['prdc_count']+(int)$result['prdc_count']) . "'" .
                    " where prdc_name like '" . $dataList['prdc_name'] . "' and project like '" . $dataList['project'] . "'" .
                    " and destination like '" . $dataList['dest'] . "'";
            }
            $flag = $db->query($sql);
            $message = "寄出成功.";
            if (!$flag){
                $message = "寄出失败.";
                exit;
            }
            $DB_TB = 'product';
            $sql = "select prdc_count from " . MIS_PREFIX . $DB_TB . " where prdc_name like '" . $dataList['prdc_name'] . "'" .
                " and project like '" . $dataList['project'] . "'";
            $db->query($sql);
            $result = $db->fetchrow();
            $sql = "update " . MIS_PREFIX . $DB_TB . " set prdc_count='" . ((int)$result['prdc_count']-(int)$dataList['prdc_count']) . "'" .
                " where prdc_name like '" . $dataList['prdc_name'] . "' and project like '" . $dataList['project'] . "'";
            $db->query($sql);
            $DB_TB = 'out_history';
            $sql = "insert into " . MIS_PREFIX . $DB_TB . "(project, prdc_name, prdc_count, status, borrow_date, destination, comments) " .
                "values(" . 
                "'" . $dataList['project'] . "'," .
                "'" . $dataList['prdc_name'] . "'," .
                "'" . $dataList['prdc_count'] . "'," .
                "'" . $status . "'," .
                "'" . $dataList['curdate'] . "'," .
                "'" . $dataList['dest'] . "'," .
                "'" . $dataList['comments'] . "')"; 
            $db->query($sql); 
            include('../templates/admin/admin_configure.html');
            break;
		default: 
			$param["message"] = "参数错误,请重试."; 
			forward("error.php", $param);			 
			break; 
	} 
} 
else 
{ 
	$param["message"] = "参数错误,请重试."; 
	forward("error.php", $param);	 
} 

function selectDb($DB_TB){
    global $_project, $_other, $db;
    $sql = "SELECT DISTINCT project FROM " . MIS_PREFIX . $DB_TB . " where project not like ''";
    $db->query($sql);
    $_project = $db->fetchAll();
    $sql = "SELECT project FROM " . MIS_PREFIX . $DB_TB . " WHERE project like ''";
    $db->query($sql);
    $_other = $db->fetchRow();
    
}

//验证表单验证函数
function validateForm() 
{ 
    global $errorList, $_obj, $titleClass; 
    if(empty($_POST['username'])) 
    { 
        if ('borrowPrdc' == $_POST['action']){
            $errorList['username'] =  "借用人不能为空."; 
            $errorClass['username'] = "error-msg";
        }
    } 
	if(empty($_POST['prdc_name'])) 
	{ 
		$errorList['prdc_name'] = "物品名称不能为空."; 
        $errorClass['prdc_name'] = "error-msg";
	} 
	if(empty($_POST['prdc_count'])) 
	{ 
        $errorList['prdc_count'] = "物品数量不能为空."; 
        $errorClass['prdc_count'] = "error-msg";
    }
    else
    {
        if(!preg_match('/^\d*$/', $_POST['prdc_count']))
        {
            $errorList['prdc_count'] = "此项必须是数字.";
            $errorClass['prdc_count'] = "error-msg";
        } 
    } 
    if (empty($_POST['from'])){
        if ('addPdc' == $_POST['action']){
            $errorList['from'] = "物品来源不能为空.";
            $errorClass['from'] = "error-msg";
        }
    }
    if (empty($_POST['dest'])){
        if ('sendOut' == $_POST['action']){
            $errorList['dest'] = "物品来源不能为空.";
            $errorClass['dest'] = "error-msg";
        }
    }
	if(empty($_POST['curdate'])) 
	{ 
		$errorList['curdate'] = "借出日期不能为空.";		
        $errorClass['curdate'] = "error-msg"; 
	} 
	if(!empty($errorList))	//处理错误 
	{ 
        include('../templates/admin/admin_product_info.html');
		exit(); 
	}	 
} 

function getData(){
    global $dataList;
    $dataList = array(
        "username"     =>    $_POST['username'],
        "project"      =>    $_POST['project'],
        "prdc_name"    =>    $_POST['prdc_name'],
        "prdc_price"   =>    $_POST['prdc_price'],
        "prdc_count"   =>    $_POST['prdc_count'],
        "curdate"      =>    $_POST['curdate'],
        "back_date"    =>    $_POST['backdate'],
        "comments"     =>    $_POST['comments'],
        "from"         =>    $_POST['from'],
        "dest"         =>    $_POST['dest']
    );
}

?> 
