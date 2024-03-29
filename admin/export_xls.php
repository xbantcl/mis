<?php
header("Content-Type: text/html; charset=utf-8");
require_once ("../class/PHPExcel.php");
require_once ("../class/PHPExcel/Writer/Excel5.php");
include("../class/mysql.inc.php");
require_once("../config.php");

//连接数据库
$db = new mysql(DB_SERVER, DB_USER, DB_PWD, DB_NAME);

//创建excel对象
$objExcel = new PHPExcel();
$objWriter = new PHPExcel_Writer_Excel5($objExcel);
//设置文档基本属性
$objProps = $objExcel->getProperties();
$objProps->setTitle("Office XLS Test Document");
$objProps->setSubject("Office XLS Test Document, Demo");
$objProps->setDescription("Test document, generated by PHPExcel.");
$objProps->setKeywords("office excel PHPExcel");

$objExcel->setActiveSheetIndex(0);    
$objActSheet = $objExcel->getActiveSheet();

//sheet0
$objActSheet->setTitle('物品总信息');
$objActSheet->setCellValue('A1', 'CD 物品总信息');
$objActSheet->mergeCells('A1:J3');

//设置填充
function Fill($cell, $color){
    global $objActSheet;
    $objStyle = $objActSheet->getStyle($cell);
    $objFill = $objStyle->getFill();
    $objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objFill->getStartColor()->setARGB($color);
    return $objStyle;
}

//设置对齐方式
function Align($obj){
    $objAlign = $obj->getAlignment();
    $objAlign->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objAlign->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); 
}

$objStyleA1 = Fill("A1", '#009900');
Align($objStyleA1);

$objActSheet->mergeCells('A5:B6');
$objActSheet->setCellValue('A5', '项目名称');
$objStyleA5 = Fill('A5', '#ff0000');
Align($objStyleA5);

$DB_TB = 'product';
$sql = "SELECT DISTINCT project FROM " . MIS_PREFIX . $DB_TB;
$db->query($sql);
$array_prj = $db->fetchAll();

$index = 8;
$col = 'C';
for ($i=0; $i<count($array_prj); $i++){
    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " WHERE project like '" . $array_prj[$i]['project'] . "'";
    $db->query($sql);
    $result = $db->fetchAll();
    $objActSheet->mergeCells('B'.$index.':B'.($index+1));
    $objStyle = Fill('B'.$index, "#993300");
    Align($objStyle);
    if ('' == $array_prj[$i]['project']){
        $objActSheet->setCellValue('B'.$index, "Other");
    }
    else{
        $objActSheet->setCellValue('B'.$index, $array_prj[$i]['project']);
    }
    $index += 2;
    $objActSheet->setCellValue($col.$index, "物品名称");
    $objActSheet->setCellValue((++$col).$index, "物品单价");
    $objActSheet->setCellValue((++$col).$index, "物品剩余");
    $objActSheet->setCellValue((++$col).$index, "物品总数");
    $objActSheet->setCellValue((++$col).$index, "接收时间");
    $objActSheet->setCellValue((++$col).$index, "备    注");
    $objStyle = Fill('C'.$index.':'.$col.$index, "#33ff33");
    Align($objStyle);
    $objActSheet->getDefaultColumnDimension()->setWidth(15);
    $objActSheet->getDefaultRowDimension()->setRowHeight(15);
    $index += 1;
    $col = 'C';
    for ($j=0; $j<count($result); $j++){
        $objActSheet->setCellValue($col.$index, $result[$j]['prdc_name']);
        $objActSheet->setCellValue((++$col).$index, $result[$j]['prdc_price']);
        $objActSheet->setCellValue((++$col).$index, $result[$j]['prdc_count']);
        $objActSheet->setCellValue((++$col).$index, $result[$j]['total']);
        $objActSheet->setCellValue((++$col).$index, $result[$j]['add_date']);
        $objActSheet->setCellValue((++$col).$index, $result[$j]['comments']);
        $objStyle = Fill('C'.$index.':'.$col.$index, "#ffffff");
        Align($objStyle);
        $index += 1;
        $col = 'C';
    }
}
//sheet1
$objExcel->createSheet();
$objExcel->setActiveSheetIndex(1);
$objActSheet = $objExcel->getActiveSheet();
$objActSheet->setTitle('借出情况');
$objActSheet->setCellValue('A1', 'CD 物品借出信息');
$objActSheet->mergeCells('A1:J3');
$objStyleA1 = Fill("A1", '#009900');
Align($objStyleA1);
$objActSheet->mergeCells('A5:B6');
$objActSheet->setCellValue('A5', '项目名称');
$objStyleA5 = Fill('A5', '#ff0000');
Align($objStyleA5);

$index = 8;
$col = 'C';
for ($i=0; $i<count($array_prj); $i++){
    $DB_TB = "borrow_info";
    $sql = "SELECT * FROM " . MIS_PREFIX . $DB_TB . " WHERE project like '" . $array_prj[$i]['project'] . "'";
    $db->query($sql);
    $result = $db->fetchAll();
    if ($result){
        $objActSheet->mergeCells('B'.$index.':B'.($index+1));
        $objStyle = Fill('B'.$index, "#993300");
        Align($objStyle);
        if ('' == $array_prj[$i]['project']){
            $objActSheet->setCellValue('B'.$index, "Other");
        }
        else{
            $objActSheet->setCellValue('B'.$index, $array_prj[$i]['project']);
        }
        $index += 2;
        $objActSheet->setCellValue($col.$index, "物品名称");
        $objActSheet->setCellValue((++$col).$index, "借用人");
        $objActSheet->setCellValue((++$col).$index, "借用数量");
        $objActSheet->setCellValue((++$col).$index, "借用日期");
        $objStyle = Fill('C'.$index.':'.$col.$index, "#33ff33");
        Align($objStyle);
        $objActSheet->getDefaultColumnDimension()->setWidth(15);
        $objActSheet->getDefaultRowDimension()->setRowHeight(15);
        $index += 1;
        $col = 'C';
        for ($j=0; $j<count($result); $j++){
            $DB_TB = "user";
            $sql = "SELECT username FROM " . MIS_PREFIX . $DB_TB . " WHERE uid like '" . $result[$j]['uid'] . "'";
            $db->query($sql);
            $userInfo = $db->fetchRow();
            $objActSheet->setCellValue($col.$index, $result[$j]['prdc_name']);
            $objActSheet->setCellValue((++$col).$index, $userInfo['username']);
            $objActSheet->setCellValue((++$col).$index, $result[$j]['prdc_count']);
            $objActSheet->setCellValue((++$col).$index, $result[$j]['borrow_date']);
            $objStyle = Fill('C'.$index.':'.$col.$index, "#ffffff");
            Align($objStyle);
            $index += 1;
            $col = 'C';
        }
    }
}

$outputFileName = 'mis_product_borrow_info.xls';
header("Content-Type: application/force-download");   
header("Content-Type: application/octet-stream");   
header("Content-Type: application/download");   
header('Content-Disposition:inline;filename="'.$outputFileName.'"');   
header("Content-Transfer-Encoding: binary");   
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");   
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");   
header("Pragma: no-cache");   
$objWriter->save('php://output');

?>
