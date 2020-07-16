<?php

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require_once '../../assets/PhpSpreadsheet/phpoffice/phpspreadsheet/src/Bootstrap.php';

include_once('../../php/connect.php');

//fetch school name
$schoolname = '';
$address = '';
$sch = $conn->query('SELECT * FROM `school` LIMIT 1');
if ($sch->num_rows>0) {
    $ch = $sch->fetch_assoc();
    $schoolname = $ch['school_name'];
    $address = $ch['address'];
}else{
    header('location:../../404.php');
}

$data = json_decode($_POST['data'],true);
//echo is_array($data[0]);
//$coursename = $_POST['course_name'];
$deptID = $_POST['department'];
$dept = $conn->query("SELECT * FROM departments as d INNER JOIN faculty as f ON f.id=d.faculty_id WHERE d.id='$deptID'");
$dt = $dept->fetch_assoc();
$faculty = $dt['faculty'];
$departmentname = $dt['department'];
$session = $_POST['session'];
$semester = $_POST['semester'];
//echo var_dump($data);

$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

    return;
}

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
    ->setLastModifiedBy('Maarten Balliauw')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Test result file');

// Add some data
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('E1', $faculty)
    ->setCellValue('D2',  'DEPARTMENT: '.$departmentname)
    ->setCellValue('E2', 'SESSION: '.$session)
    ->setCellValue('F2', 'SEMESTER: '.$semester)
    ->setCellValue('A3', 'S/N')
    ->setCellValue('B3', 'Matric Number')
    ->setCellValue('C3', 'NAME')
    ->setCellValue('D3', 'ME')
    ->setCellValue('E3', 'NSS')
    ->setCellValue('F3', 'RCU')
    ->setCellValue('G3', 'ECU')
    ->setCellValue('H3', 'CP')
    ->setCellValue('I3', 'GPA')
    ->setCellValue('J3', 'TRCU')
    ->setCellValue('K3', 'TECU')
    ->setCellValue('L3', 'TCP')
    ->setCellValue('M3', 'PCGPA')
    ->setCellValue('N3', 'OUTSTANDING COURSES')
    ->setCellValue('O3', 'REMARK');
    $num = 4;
    $n =0;
    foreach ($data as $key => $value) {
        $r1 ='';
        $r2 ='';
        if($value['cos']==''){ $r1= 'Nill'; }else{ $r1= $value['cos']; }
        if($value['cos']==''){ $r2= 'In Good Standing'; }else{ $r2= 'Deficiency'; }
        $n++;
        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A'.$num, $n)
        ->setCellValue('B'.$num, $value['mat'])
        ->setCellValue('C'.$num, $value['sname'])
        ->setCellValue('D'.$num, $value['me'])
        ->setCellValue('E'.$num, $value['nss'])
        ->setCellValue('F'.$num, $value['rcu'])
        ->setCellValue('G'.$num, $value['ecu'])
        ->setCellValue('H'.$num, $value['cp'])
        ->setCellValue('I'.$num, $value['gpa'])
        ->setCellValue('J'.$num, $value['trcu'])
        ->setCellValue('K'.$num, $value['tecu'])
        ->setCellValue('L'.$num, $value['tcp'])
        ->setCellValue('M'.$num, $value['pcgpa'])
        ->setCellValue('N'.$num,  $r1)
        ->setCellValue('O'.$num, $r2);
        $num++;
    }

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Simple');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xls)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="result_upload_template.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xls');
$writer->save('php://output');
exit;
