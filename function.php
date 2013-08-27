<?php 
//==================================================== 
//		FileName:vfunction.php 
//		Summary: ����ϵͳ����ʹ�õ�һЩ���� 
//		 
//==================================================== 
 
/* ���� cnString($text, $length) 
** ���� ���ı��н�ȡָ�������ַ��� 
** ���� $text Ҫ��ȡ���ı� 
** ���� $length Ҫ��ȡ���ַ������� 
*/ 
function cnString($text, $length) 
{ 
	if(strlen($text) <= $length)  
	{ 
		return $text; 
	} 
 
	$str = substr($text, 0, $length) . chr(0) . "��";  
	return $str; 
} 
/* ���� textFilter($text) 
** ���� ���ı��е������ַ����й���,��HTML��Ǻͻ��з� 
** ���� Ҫ���й��˵��ı� 
*/ 
function textFilter($text) 
{ 
	$text = htmlspecialchars($text); 
	$text = nl2br($text); 
	return $text; 
} 
 
//========================================== 
// ����: alert 
// ����: JavaScript��ʾ 
// ����: $title Ҫ��ʾ������ 
// ����: $action ��ʾ��Ķ��� 
//		back ���� close �رմ��� 
//		replace �滻ҳ�� redirect ��ת 
// ����: $href ��actionΪredirectʱ��URL 
//========================================== 
function alert($title,$action='back',$href=null)  
{ 
	$htmlStr  = "<script language='javascript'>"; 
	$htmlStr .= "alert('$title');"; 
	switch ($action) { 
		case 'back': 
			$htmlStr .= "history.back();"; 
			break; 
		case 'close': 
			$htmlStr .= "window.close();"; 
			break; 
		case 'replace': 
			$htmlStr .= "location.replace(location.href);"; 
			break; 
		case 'redirect': 
			if (!empty($href))  
			{ 
				$htmlStr .= "location.href='$href'"; 
			} 
			break; 
		default: 
			break; 
	} 
	$htmlStr .= "</script>"; 
	echo $htmlStr; 
} 
 
/* ����: function sizeCount($byte) 
** ����: �����ֽ���ת������Ӧ�ĵ�λ 
** ����: $byte �ֽ����� 
** ����: ת����λ���ַ���(��1.34K,2.30M) 
*/ 
function sizeCount($byte) 
{ 
    if($byte >= 1073741824) 
    { 
        $byte = round($byte / 1073741824, 2) . " G"; 
    } 
    elseif($byte >= 1048576) 
    { 
        $byte = round($byte / 1048576, 2) . " M"; 
    } 
    elseif($byte >= 1024) 
    { 
        $byte = round($byte / 1024, 2) . " K"; 
    } 
    else 
    { 
        $byte = $byte . " bytes"; 
    } 
    return $byte; 
} 
 
/* ����: checkAction($value) 
** ����: ���action��ֵ 
** ����: $value ֵ 
*/ 
function checkAction($value) 
{ 
	if (!empty($_REQUEST['action']) && $_REQUEST['action'] == $value) 
		return true; 
	else 
		return false; 
} 
/* ����: forward($url,$param) 
** ����: ��ת������ҳ�� 
** ����: $url ҳ���ַ 
** ����: $param ��������,��ѡ 
*/ 
function forward($url, $param=null) 
{ 
	$headerStr = "Location: $url"; 
	$paramStr = ""; 
	if($param != null && is_array($param)) 
	{ 
		$paramStr = "?"; 
		$flag = 0; 
		foreach($param as $key=>$val) 
		{ 
			if($flag == 0) 
			{ 
				$paramStr .= "$key=$val"; 
				$flag = 1; 
			} 
			else 
				$paramStr .= "&$key=$val"; 
		} 
 
	} 
 
	header($headerStr . $paramStr); 
} 
 
/* ����: showMessage() 
** ����: ��ʾ��Ϣҳ�� 
** ����: �� 
*/ 
function showMessage() 
{ 
	global $errorList, $successList; 
	//����ת����� 
	if(!empty($errorList)) 
	{ 
		$param['msgList'] = serialize($errorList); 
		$param["msgType"] = "error-msg"; 
	} 
	else 
	{ 
		$param['msgList'] = serialize($successList); 
		$param["msgType"] = "success-msg"; 
	} 
	forward("message.php", $param); 
	exit(); 
} 
 
/* ���� makePage($pageParam) 
** ���� ��ʾ��ҳ�б� 
** ���� $pageParam ʵ�ַ�ҳ��Ҫ�Ĳ������� 
**      $pageParam['currentPage'] ��ǰҳ 
**      $pageParam['pageCount'] ��ҳ�� 
**      $pageParam['recordCount'] ��¼���� 
** ���� ���ɵ�HTML���� 
*/ 
function makePage($pageParam) 
{ 
	$prePage	= $pageParam['currentPage'] - 1; 
	$nextPage	= $pageParam['currentPage'] + 1; 
	$firstPage	= 1; 
	$lastPage	= $pageParam['pageCount']; 
	$currentPage= $pageParam['currentPage']; 
	$totalPage	= $pageParam['pageCount']; 
	$pageStr	= $pageParam['recordCount'] . " ����¼ "; 
	$pageStr   .= $pageParam['currentPage'] . "/" . $totalPage . " "; 
	//���ݵ�ǰURL�����µ�URL���� 
	if(empty($_SERVER['QUERY_STRING'])) 
	{ 
		$url = $_SERVER['REQUEST_URI'] . "?page="; 
	} 
	else 
	{ 
		if(isset($_GET['page'])) 
		{ 
			$url = preg_replace("|page.+|", "page=", $_SERVER['REQUEST_URI']); 
		} 
		else 
		{ 
			$url = $_SERVER['REQUEST_URI'] . "&page=";  
		} 
	} 
	//����HTML���� 
	if($currentPage > 1) 
	{ 
		$pageStr .= "<a href=\"".$url.$firstPage."\"><img src=\"".APP_PATH."images/first.gif\" alt=\"��ҳ\" /></a>\n"; 
		$pageStr .= "<a href=\"".$url.$prePage."\"><img src=\"".APP_PATH."images/previous.gif\" alt=\"��һҳ\" /></a>\n"; 
	} 
	else 
	{ 
		$pageStr .= "<img src=\"".APP_PATH."images/first.gif\" alt=\"��ҳ\" />\n"; 
		$pageStr .= "<img src=\"".APP_PATH."images/previous.gif\" alt=\"��һҳ\" />\n"; 
	} 
	if($currentPage < $totalPage) 
	{ 
		$pageStr .= "<a href=\"".$url.$nextPage."\"><img src=\"".APP_PATH."images/next.gif\" alt=\"��һҳ\" /></a>\n"; 
		$pageStr .= "<a href=\"".$url.$lastPage."\"><img src=\"".APP_PATH."images/last.gif\" alt=\"δҳ\" /></a>\n"; 
	} 
	else 
	{ 
		$pageStr .= "<img src=\"".APP_PATH."images/next.gif\" alt=\"��һҳ\" />\n"; 
		$pageStr .= "<img src=\"".APP_PATH."images/last.gif\" alt=\"δҳ\" />\n"; 
	} 
 
	return $pageStr; 
} 
?>
