<?php
namespace Common\Controller;
use Think\Controller;
Class CommonController extends Controller {        //继承基本的user类库
	
/*PHPExcel导出类操作方法*/
	public function TT($expTitle,$expCellName,$expTableData){
		
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);              //列名的定义
       return  $dataNum = count($expTableData);			// 需要导出的 列数据
       /*  vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new PHPExcel();
        $cellName = array('姓名1',"购买信息1","联系方式1");
        
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  -用户购买信息---:'.date('Y-m-d H:i:s'));  
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]); 
        } 
          // Miscellaneous glyphs, UTF-8   
        for($i=0;$i<$dataNum;$i++){
          for($j=0;$j<$cellNum;$j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
          }             
        }  
        
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output'); 
        exit;    */
    }

   /*************end***********PHPExcel导出类操作方法***********/
   
   }