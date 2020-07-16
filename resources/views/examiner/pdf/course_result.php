
<?php
//echo $dataPDF;

/* Send headers
*/
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

$data = json_decode($_POST['data']);
//echo is_array($data[0]);
//$coursename = $_POST['course_name'];
$deptID = $_POST['department'];
$dept = $conn->query("SELECT * FROM departments as d INNER JOIN faculty as f ON f.id=d.faculty_id WHERE d.id='$deptID'");
$dt = $dept->fetch_assoc();
$faculty = $dt['faculty'];
$departmentname = $dt['department'];
$session = $_POST['session'];
$semester = $_POST['semester'];
/*
if($_POST['semester']==1){
	$semester = 'first semester';
}else{
	$semester = 'second semester';
}*/

include_once('../../assets/fpdf/setasign/fpdf/rotation.php');


	class PDF extends PDF_Rotate
	{

    
		function Rotate($angle,$x=-1,$y=-1) { 

		    if($x==-1) 
		        $x=$this->x; 
		    if($y==-1) 
		        $y=$this->y; 
		    if($this->angle!=0) 
		        $this->_out('Q'); 
		    $this->angle=$angle; 
		    if($angle!=0) 

		    { 
		        $angle*=M_PI/180; 
		        $c=cos($angle); 
		        $s=sin($angle); 
		        $cx=$x*$this->k; 
		        $cy=($this->h-$y)*$this->k; 

		        $this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
		     } 
		  } 

	function FancyTable($schoolname,$faculty,$department,$session,$semester)
	{

				//$this->Image('../img/userlogo.jpg',90,18,45,45);
				//$this->Image('../img/userlogo.jpg',50,148,90,90);
				$this->SetTextColor(0,0,0);
				$this->SetFont('Times','B', 14);
				$this->cell(290,7,$schoolname,0,1,'C');
				$this->Multicell(290,6,'Faculty of '.ucwords(strtolower($faculty)),0,'C');
				//$this->Multicell(190,6,ucwords(strtolower($address)),0,'C');

				//$this->SetTextColor(0,80,19);
				$this->SetFont('Arial','B', 11);
				$this->Multicell(290,6,'DEPARTMENT OF '.strtoupper($department),0,'C');

				/*
				$this->setX(20);
				$this->SetFont('Times','', 11);
				$this->cell(30,10,'COURSE CODE:',0,0);
				$this->cell(28,10,strtoupper($coursename),0,0);
*/
				$this->cell(20,10,'SESSION:',0,0);
				$this->cell(28,10,strtoupper($session),0,0);

				$this->cell(25,10,'SEMESTER:',0,0);
				$this->cell(28,10,strtoupper($semester),0,1);
				$W = 13;
				$this->setX(10);
				$this->SetFont('Arial','B', 8);
				$this->cell($W,8,'S/N',1,0);
				$this->cell(40,8,'MATRIC NUMBER',1,0);
				$this->cell(40,8,'NAME',1,0);
				$this->cell($W,8,'ME',1,0);
				$this->cell($W,8,'NSS',1,0);
				$this->cell($W,8,'RCU',1,0);
				$this->cell($W,8,'ECU',1,0);
				$this->cell($W,8,'CP',1,0);
				$this->cell($W,8,'GPA',1,0);
				$this->cell($W,8,'TRCU',1,0);
				$this->cell($W,8,'TECU',1,0);
				$this->cell($W,8,'TCP',1,0);
				$this->cell($W,8,'PCGPA',1,0);
				$this->cell($W,8,'CGPA',1,0);
				$this->cell(40,8,'COURSE OUTSTANDING',1,0);
				$this->cell(15,8,'REMARK',1,0);
				
			}
		//$this->Cell(array_sum($w),0,'','T');
	}
	//[218.268, 311.811]

	$pdf = new PDF('L');
	// Column headings
	
	// Data loading
	
	$pdf->SetFont('Arial','',14);
	$pdf->AddPage();

	$pdf->FancyTable($schoolname,$faculty,$departmentname,$session,$semester);/*
	for ($i=0; $i < sizeof($data); $i++) { 
		$pdf->SetFont('Times','', 11);
		$pdf->setX(20);
		$num = $i+1;
		$pdf->cell(20,8, $num.'.',1,0);
		$pdf->cell(80,8,strtoupper($data[$i][0]),1,0);
		$pdf->cell(35,8,$data[$i][1],1,0);
		$pdf->cell(35,8,$data[$i][2],1,1);
		
	}
*/
	$pdf->Output('I','filename.pdf');
