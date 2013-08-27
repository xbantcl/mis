<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<title>MIS发布系统 </title> 
<meta http-equiv="content-type" content="text/html;charset=utf8" /> 
<meta name="Generator" content="EditPlus" /> 
<meta name="Author" content="CMS" /> 
<meta name="Keywords" content="CMS," /> 
<link rel="stylesheet" href="../style/global.css" type="text/css" /> 
<script language="javascript" src="../javascript/function.js"></script> 
</head> 
 
<body> 
<div id="menu-box"> 
<div class="menu"> 
	<div class="menu-head"> 
		<a href="javascript:void(null)" onclick="showMenu('menu1',this)">常规设置</a> 
	</div> 
	<ul id="menu1"> 
		<li><a href="main.php?action=sysInfo" target="main">系统信息</a></li> 
		<li><a href="main.php?action=userInfo" target="main">员工管理</a></li> 
        <li><a href="main.php?action=exportxls" target="main">导出excel</a></li>
		<li><a href="main.php?action=logout" target="_top">退出系统</a></li> 
	</ul> 
</div> 
<div class="menu"> 
	<div class="menu-head"> 
		<a href="javascript:void(null)" onclick="showMenu('menu2',this)">物品管理</a> 
	</div> 
	<ul id="menu2"> 
		<li><a href="main.php?action=addproduct" target="main">添加物品清单</a></li> 
		<li><a href="main.php?action=borrowproduct" target="main">借用物品</a></li> 
		<li><a href="main.php?action=updateproduct" target="main">更新物品状态</a></li> 
		<li><a href="main.php?action=addmore" target="main">批量添加物品信息</a></li> 
		<li><a href="main.php?action=outInfo" target="main">物品寄出</a></li> 
        <li><a href="main.php?action=check" target="main">物品借出查询</a></li>
		<li><a href="main.php?action=allproduct" target="main">物品总的信息</a></li> 
		<li><a href="main.php?action=deleteproduct" target="main">删除物品信息</a></li> 
	</ul> 
</div> 
<div class="menu"> 
	<div class="menu-head"> 
		<a href="javascript:void(null)" onclick="showMenu('menu4',this)">信息发布</a> 
	</div> 
	<ul id="menu4"> 
		<li><a href="main.php?action=listUser" target="main">文章推荐</a></li> 
	</ul> 
</div> 
</div> 
</body> 
</html> 
