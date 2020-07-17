<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Author: https://www.fengxiaopeng.cn
 * Date: 2020/7/15
 * Time: 14:56
 */

//引入phpExcel类
include "./PHPExcel/Classes/PHPExcel/IOFactory.php";
//引入函数文件
include "./function.php";
include "./zip.php";

//获取EXCEL文件资源
$uploadFileName = $_FILES['file'];
//var_dump($uploadFileName);
$inputFileName = $uploadFileName['tmp_name'];
if ($inputFileName == ''){
    die("请选择要上传的Excel文件！");
}
//设置时区
date_default_timezone_set('PRC');
//读取Excel文件
$startTime = time(); //返回当前时间的Unix 时间戳
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);//获取sheet表格数目
$sheetCount = $objPHPExcel->getSheetCount();//默认选中sheet0表
$sheetSelected = 0;$objPHPExcel->setActiveSheetIndex($sheetSelected);//获取表格行数
$rowCount = $objPHPExcel->getActiveSheet()->getHighestRow();//获取表格列数
$columnCount = $objPHPExcel->getActiveSheet()->getHighestColumn();
//echo "<div>Sheet Count : ".$sheetCount."　　行数： ".$rowCount."　　列数：".$columnCount."</div><hr>";
$dataArr = array();
//创建文件夹
$path = $startTime. '/';
mkdir($path, 0777);
//获取Excel文件的数据，$row=2表示从第二行开始获取数据
for ($row = 1; $row <= $rowCount; $row++){
    for ($column = 'A'; $column <= $columnCount; $column++) {
        $dataArr[] = $objPHPExcel->getActiveSheet()->getCell($column.$row)->getValue();
        $myfile = fopen($path.$column.$row. ".txt", 'w');
        fwrite($myfile, iconv('UTF-8', 'GBK', $objPHPExcel->getActiveSheet()->getCell($column.$row)->getValue()));
        fclose($myfile);
    }

    $endTime = time();

//    var_dump($dataArr);
    $dataArr = NULL;

//    echo "<div>写入文件完成，当前的时间为：".date("Y-m-d H:i:s")."  总共消耗的时间为：".(($endTime - $startTime))."秒</div><hr>".$row."<br>".$rowCount."<hr>";
//    echo "<br/>消耗的内存为：".(memory_get_peak_usage(true) / 1024 / 1024)."M";
}
HZip::zipDir($path, $endTime. '.zip');
echo $endTime. '.zip';