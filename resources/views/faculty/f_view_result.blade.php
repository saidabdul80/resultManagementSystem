<?php
use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Role;
use \App\f_timing;
use \App\School;
use Illuminate\Http\Request;
	

	$logged_in_usr_id = Auth::user()->id;
	$session = \App\Session::where('c_set',1)->first();
	$semester = \App\Semester::where('c_set',1)->first();
	$isLecturer = Lecturer::where('email', Auth::user()->email)->first();
	//dd($isLecturer->department_id);
	$date = date('Y-m-d');
	if (!is_null($isLecturer)) {
		
		$LECTURER = Lecturer::where('email',Auth::user()->email)->first();
		$Lecturer_Id = $LECTURER->lecture_ID;
		$DEPARTMENT = Department::where('id', $LECTURER->department_id)->first();
		$faculty_id =  $DEPARTMENT->faculty_id;

		# code...
	}else{
		$faculty_id = 1;
	}
	//echo $date;
	//preparing this for left_pane.php
	if (isset($_POST['selSemester'])) {
		$csemester = $_POST['selSemester'];
		$csemestername = ($csemester==1)?'first semester':'second semester';
	}else{
		$csemester = $semester->id;
		$csemestername = $semester->semester;
	}

	if(isset($_POST['selSession'])){ 
		$selSession1 =  explode(';',$_POST['selSession']);
		$selSession = $selSession1[0];
		$sessionname = $selSession1[1];
	}else{
		$selSession = $session->id;
		$sessionname = $session->session;
	}

	$level = $_POST['level'] ?? 1;
	//echo $course_id = $_SESSION['eselected_course_code'];	
	$store_grades='';
	//require('class/left_pane.php');

	//get set failed grades from sys
	$failG_array1 = array('D','E','F');
	$failG_array2 = array('E','F');
	$failG_array3 = array('F');
	//$getco = $conn->query("SELECT CO FROM grades WHERE c_set=1");
	$sco = DB::table('grades')->where('c_set',1)->first();
	$co_start = $sco->CO;
	if (in_array($co_start, $failG_array3)) {
		$failG_array = $failG_array3;
	}elseif(in_array($co_start, $failG_array2)) {
		$failG_array = $failG_array2;
	}elseif(in_array($co_start, $failG_array1)) {
		$failG_array = $failG_array1;
	}
		
	//getting school details
	$school = School::where('id', '=', 1)->first();
	$dept = \App\Department::where('id',$LECTURER->department_id)->first();
	$fact = \App\Faculty::where('id',$faculty_id)->first();

	//echo ;
	$ThePget = $p??'';
?>
@extends('layouts/master')
	 <style type="text/css">

   	.paging_two_button {
        margin: 15px;
        position:relative !important;
        bottom:15px !important;
       }
    .dataTables_info{
        position:relative !important;
        bottom: 6px !important ;
        margin:0px;
        color:#aaa;
       }
        .listt> tbody> tr td{
       	padding: 4px !important;
       }
       .listt_filter label{
       	float: right;
       	margin-top: -30px;
       }
 i{
       	cursor: pointer;
       }
  </style>
<script>var result_detc =0;

	function GpaR(){
		location.href = "{{ route('fchanges',221623) }}";
		//location.href ='l_manage_result.php?p=1';
	}
	function GradeR(){
		location.href = "{{ route('fchanges',221823) }}";
		//location.href ='l_manage_result.php?p=1';
	}
	function back(){
		location.href = "{{ route('f_view_result') }}";	
	}

</script>
@section('content')
	<div id="titlebar">
			<div  id="title">Faculty<span class="lnr lnr-chevron-right"></span><i onclick="location.href='{{ route('f_view_result') }}';"> View Course Result</i> <?php 
			if(isset($ThePget))
				{
					if($ThePget==221623){
						 ?>
						  <span class="lnr lnr-chevron-right"></span><i> GPA Result</i> 
						 <?php
					}elseif ($ThePget==221823) {
						 ?>
						  <span class="lnr lnr-chevron-right"></span><i> GRADE Result</i> 
						 <?php
					}
				}
				?>
			</div>

			<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
		</div>
			<br>
		<div id="cover">
			<!--options-->
		@if($ThePget == '' && $ThePget!=221623 && $ThePget!=221823)
	
			<center>
				
			<ul id="optionS">
				<li onclick='GpaR();'>GPA Result</li>
				<li onclick="GradeR();">GRADE Result</li>
			</ul>
			</center>
			
		@else
			@if($ThePget!=221623 && $ThePget!=221823 && $ThePget !='')
				<?php
				//reload for invalid request
				return redirect('f_view_result');
				?>
			@endif
		@endif

	<?php
	$Validrequest = $ThePget ?? 0;
	?>
	@if ($Validrequest == 221623) 
		<div class="innerContent" style="padding-top: 15px;">
			<div style="display: flex;  justify-content: center;">
				<form method="POST" action="{{route('echanges1',221623)}}" style="margin-bottom: 8px;" id="formTag">
					{{csrf_field()}}
					<select id="levelTag" style="width: 70px !important;" name="level">
<option value="1" <?php $selectedl = $_POST['level'] ?? 0; if($selectedl ==1){echo 'selected';} ?> >100L</option>
						<option value="2" <?php if($selectedl ==2){echo 'selected';} ?> >200L</option>
						<option value="3" <?php if($selectedl ==3){echo 'selected';} ?> >300L</option>
						<option value="4" <?php if($selectedl ==4){echo 'selected';} ?>>400L</option>
						<option value="5" <?php if($selectedl ==5){echo 'selected';} ?>>500L</option>
					</select>
					<select id="sessionTag" style="width: 120px !important;" name="selSession">
					<?php
					//	$sqli = $conn->query("SELECT * FROM sessions ORDER BY session ASC");
						$sqli = Session::orderBy('session', 'ASC')->get();
							if ($sqli->count()>0) {
							foreach($sqli as $rwi) {
								$idi = $rwi->id;$sessions = $rwi->session;
								?>
								<option value="<?php echo $idi.';'.$sessions; ?>" <?php if($selSession==$idi){echo 'selected';} ?> ><?php echo $sessions; ?></option>
											<?php
								}	
							}
					?>
					</select>
					<select id="semesterTag" style="width: 140px !important;" name="selSemester" >
						<option value="1" <?php if($csemester==1){echo 'selected';} ?>>first semester</option>
						<option value="2" <?php if($csemester==2){echo 'selected';} ?>>second semester</option>
					</select>
					<!-- <input type="sub" name=""> -->
				</form>
			</div>
			<script type="text/javascript">
				var ltag =document.getElementById('levelTag');
				var sessiontag =document.getElementById('sessionTag');
				var semestertag =document.getElementById('semesterTag');
				var formTag =document.getElementById('formTag');

				ltag.onchange = function(){
					formTag.submit();
				}
				sessiontag.onchange = function(){
					formTag.submit();
				}
				semestertag.onchange = function(){
					formTag.submit();
				}
			</script>
			<div class="row bg-white shadow p-3 ml-5 mx-auto" style="overflow-x: scroll;" id="touchTofull1" ondblclick="toFull('touchTofull1','tableView'); clearSelection();" >
				<?php
					$level = $_POST['level'] ?? 1;
					$result_array = 0;
					//$fetch_sgp = $conn->query("SELECT * FROM spread_gp as sg INNER JOIN students as s ON s.id=sg.student_id WHERE sg.year='$selSession' AND sg.semester='$csemester' AND sg.level_id='$level' AND sg.department_id='$level'");
					$fetch_sgp = DB::table('spread_gp', 'sg')
									->join('students', 'students.id','=', 'sg.students_id')
									->where(['sg.year'=>$selSession, 'sg.semester'=>$csemester, 'sg.level_id'=>$level, 'sg.department_id'=>$LECTURER->department_id])->get();
					//echo mysqli_error($conn);
					if ($fetch_sgp->count()>0) {
						$result_array =array();
						$n = 0;
						foreach($fetch_sgp as $sgp) {
							$result_array[$n] = array();
							$result_array[$n]['mat'] = $sgp->matric_number;
							$result_array[$n]['sname'] = ucwords($sgp->first_name).' '.ucwords(@$sgp->other_name[0]).' '.ucwords($sgp->surname);
							$result_array[$n]['me'] = $sgp->ME;
							$result_array[$n]['nss'] = $sgp->NSS;
							$result_array[$n]['rcu'] = $sgp->RCU;
							$result_array[$n]['ecu'] = $sgp->ECU;
							$result_array[$n]['cp'] = $sgp->CP;
							$result_array[$n]['gpa'] = $sgp->GPA;
							$result_array[$n]['trcu'] = $sgp->TRCU;
							$result_array[$n]['tecu'] = $sgp->TECU;
							$result_array[$n]['tcp'] = $sgp->TCP;
							$result_array[$n]['pcgpa'] = $sgp->PCGPA;
							$result_array[$n]['cos'] = $sgp->COs;
							 $n++;
						}
					}else{

					}
					//echo var_dump($faculty);
				?>
				<div style=" float: right !important;">
				<button class="btn btn-primary btn-sm rounded m-2" id="printPDF">pdf</button>
			
				</div><br>
				<table class="table table-condensed table-hover table-bordered mx-auto listt" style="font-size: 0.9em;width: 100%;" >
					<thead>
						<th>S/N</th>
						<th style="white-space: nowrap; ">Matric Number</th>
						<th>NAME</th>
						<th>ME</th>
						<th>NSS</th>
						<th>RCU</th>
						<th>ECU</th>
						<th>CP</th>
						<th>GPA</th>
						<th>TRCU</th>
						<th>TECU</th>
						<th>TCP</th>
						<th>PCGPA</th>
						<th style="white-space: nowrap; ">COURSES OUTSTANDING</th>
						<th>REMARK</th>
					</thead>
					<tbody>
						<?php
						$i =0;
						if ($result_array != 0) {
							
							foreach ($result_array as $key => $value) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $value['mat']; ?></td>
									<td><?php echo $value['sname']; ?></td>
									<td><?php echo $value['me']; ?></td>
									<td><?php echo $value['nss']; ?></td>
									<td><?php echo $value['rcu']; ?></td>
									<td><?php echo $value['ecu']; ?></td>
									<td><?php echo $value['cp']; ?></td>
									<td><?php echo $value['gpa']; ?></td>
									<td><?php echo $value['trcu']; ?></td>
									<td><?php echo $value['tecu']; ?></td>
									<td><?php echo $value['tcp']; ?></td>
									<td><?php echo $value['pcgpa']; ?></td>
									<td><?php if($value['cos']==''){echo 'Nill'; }else{echo $value['cos'];}?></td>
									<td><?php if($value['cos']==''){echo 'In Good Standing'; }else{echo 'Deficiency';} ;?></td>
								</tr>

								<?php
							}
						}else{
							?>
							<td style="border-right:1px solid #fff;white-space: nowrap;font-size: 1.4em;">Result not compiled</td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td ></td>
							<?php
						}

						?>
					</tbody>
				</table>
<!--*******************************************GRADE TABLE FRO PDF **********************************************  -->
<div id="tableView" style="display: none;width: 98%;margin: 0px auto;">
<table class="table table-condensed table-hover table-bordered mx-auto" style="font-size: 0.9em;width: 100%; " id="tablepdf" >
					<thead>
						<th>S/N</th>
						<th style="white-space: nowrap; ">Matric Number</th>
						<th>NAME</th>
						<th>ME</th>
						<th>NSS</th>
						<th>RCU</th>
						<th>ECU</th>
						<th>CP</th>
						<th>GPA</th>
						<th>TRCU</th>
						<th>TECU</th>
						<th>TCP</th>
						<th>PCGPA</th>
						<th style="white-space: nowrap; ">COURSES OUTSTANDING</th>
						<th>REMARK</th>
					</thead>
					<tbody>
						<?php
						$i =0;
						if ($result_array != 0) {
							
							foreach ($result_array as $key => $value) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $value['mat']; ?></td>
									<td><?php echo $value['sname']; ?></td>
									<td><?php echo $value['me']; ?></td>
									<td><?php echo $value['nss']; ?></td>
									<td><?php echo $value['rcu']; ?></td>
									<td><?php echo $value['ecu']; ?></td>
									<td><?php echo $value['cp']; ?></td>
									<td><?php echo $value['gpa']; ?></td>
									<td><?php echo $value['trcu']; ?></td>
									<td><?php echo $value['tecu']; ?></td>
									<td><?php echo $value['tcp']; ?></td>
									<td><?php echo $value['pcgpa']; ?></td>
									<td><?php if($value['cos']==''){echo 'Nill'; }else{echo $value['cos'];}?></td>
									<td><?php if($value['cos']==''){echo 'In Good Standing'; }else{echo 'Deficiency';} ;?></td>
								</tr>

								<?php
							}
						}else{
							?>
							<td style="border-right:1px solid #fff;white-space: nowrap;font-size: 1.4em;">Result not compiled</td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td style="border-right:1px solid #fff;"></td>
							<td ></td>
							<?php
						}

						?>
					</tbody>
</table>
</div>
<!--*************************************END GRADE TABLE FRO PDF **********************************************  -->

			</div>		
		</div>
	@endif
	@if($Validrequest==221823)
	<?php	
				$selectedl = $_POST['level'] ?? 1;
				if (isset($_POST['selSession'])) {
					$sess_split = explode(';',$_POST['selSession']);
					$selSession = $sess_split[0];
					$sessionname = $sess_split[1];
				}

				$csemester = $_POST['selSemester']?? $csemester;
				$department_id = $LECTURER->department_id;

				//check result trend
				$result_trend_key = -1;
				//$result_trend = $conn->query("SELECT status FROM result_trend WHERE level='$selectedl' AND semester='$selected_semester' AND session='$selected_session' AND department='$department_id'");

				$result_trend = DB::table('result_trend')->where(['level_id'=>$selectedl, 'semesters'=>$csemester, 'session'=>$selSession, 'department'=> $department_id])->first('status');
				
				if(!is_null($result_trend)){
					$result_trend_key = $result_trend->status;
				}

		?>
		<div>
			<div class="innerContent" style="padding-top: 15px;">
		
		<!--search result fields -->
			<div style="display: flex;  justify-content: center;">
				<form method="POST" action="{{route('echanges',221823)}}" style="margin-bottom: 8px;" id="formTag">
					{{csrf_field()}}
					<select id="levelTag" style="width: 70px !important;" name="level">
						<option value="1" <?php if($selectedl ==1){echo 'selected';} ?> >100L</option>
						<option value="2" <?php if($selectedl ==2){echo 'selected';} ?> >200L</option>
						<option value="3" <?php if($selectedl ==3){echo 'selected';} ?> >300L</option>
						<option value="4" <?php if($selectedl ==4){echo 'selected';} ?>>400L</option>
						<option value="5" <?php if($selectedl ==5){echo 'selected';} ?>>500L</option>
					</select>
					<select id="sessionTag" style="width: 120px !important;" name="selSession">
					<?php
					//	$sqli = $conn->query("SELECT * FROM sessions ORDER BY session ASC");
						$sqli = Session::orderBy('session', 'ASC')->get();
							if ($sqli->count()>0) {
							foreach($sqli as $rwi) {
								$idi = $rwi->id;$sessions = $rwi->session;
								?>
								<option value="<?php echo $idi.';'.$sessions; ?>" <?php if($selSession==$idi){echo 'selected';} ?> ><?php echo $sessions; ?></option>
											<?php
								}	
							}
					?>
					</select>
					<select id="semesterTag" style="width: 140px !important;" name="selSemester" >
						<option value="1" <?php if($csemester==1){echo 'selected';} ?>>first semester</option>
						<option value="2" <?php if($csemester==2){echo 'selected';} ?>>second semester</option>
					</select>
				</form>
			</div>
			<script type="text/javascript">
				var ltag =document.getElementById('levelTag');
				var sessiontag =document.getElementById('sessionTag');
				var semestertag =document.getElementById('semesterTag');
				var formTag =document.getElementById('formTag');

				ltag.onchange = function(){
					formTag.submit();
				}
				sessiontag.onchange = function(){
					formTag.submit();
				}
				semestertag.onchange = function(){
					formTag.submit();
				}
			</script>
					<!--End search result fields -->
			<div class="row bg-white shadow p-3 ml-5 mx-auto" style="overflow-x: scroll; transition: width 2s, height:2s;" ondblclick="toFull('touchTofull', 'tableView1'); clearSelection();" id="touchTofull" >
				<?php
			
					//$fetch_rgp = $conn->query("SELECT * FROM result_files as r INNER JOIN courses AS c ON c.id=r.course_id WHERE r.department_id='$department_id' AND r.session_id='$selected_session' AND r.semester='$selected_semester' AND c.level_id='$selectedl'");
					$fetch_rgp = DB::table('result_file', 'r')
									->join('courses', 'courses.id', '=', 'r.course_id')
									->where(['r.department_id'=>$LECTURER->department_id, 'r.session_id'=>$selSession, 'r.semester'=>$csemester, 'courses.level_id'=>$selectedl])->get();
					$result_tokens = array();
					if ($fetch_rgp->count()>0) {
						foreach($fetch_rgp as $rp){
							$result_tokens[] = $rp->result_token;
							//$result_tokens[] =
						}	
					}else{
						$result_tokens = null;
					}
					
					$studentsR = array();
					$ccode ='';
					$theader =array();
					if ($result_tokens!==null) {
						$trak =0;
						foreach ($result_tokens as $key => $value) {
							//$frp = $conn->query("SELECT r.final_score, r.grade, s.matric_number,s.first_name,s.surname FROM results as r INNER JOIN students as s on s.id=r.students_id WHERE r.result_token='$value'");
							$frp = DB::table('results', 'r')
									->join('students', 'students.id', '=', 'r.students_id')
									->where('r.result_token', $value)->get();
							//echo mysqli_error($conn);
							//explode(delimiter, string)
							$ccod =  explode('-',$value);
							$ccode =  end($ccod);
							//fetch course credit unit
							//$fcu = $conn->query("SELECT credit_unit FROM courses WHERE course_code='$ccode'");
							$fcf = DB::table('courses')->where('course_code', $ccode)->first();

							//$fcf = $fcu->fetch_assoc();
							$theader[] = $ccode.'+'.$fcf->credit_unit;
							foreach($frp as $fr) {
								if ($trak ==0) {
									
									$studentsR[ $fr->matric_number ][] = array($ccode,$fr->final_score,$fr->grades); 
									$trak++;
								}else{
									if(array_key_exists($fr->matric_number, $studentsR)){
										$studentsR[ $fr->matric_number ][] = array($ccode,$fr->final_score,$fr->grades); 
									}else{
										$studentsR[ $fr->matric_number ][] = array($ccode,$fr->final_score,$fr->grades); 
									}
								}
							}
						}
					}
					ksort($studentsR);
					sort($theader);
					//echo var_dump(json_encode($studentsR));
					//echo var_dump(json_encode($theader));
				?>
				<div >
				<button class="btn btn-primary btn-sm rounded m-2 d-inline" id="printPDF">pdf</button>
				
	
				<p style="min-width: 240px !important;" class="d-inline">Totoal Average Failed: <input type="text" id="tavgF" style="width: 80px !important;" disabled="" value="0"> </p>
				<p style="min-width: 240px !important;" class="d-inline">Totoal Average Pass: <input type="text" id="tavgP" style="width: 80px !important;" disabled="" value="0"></p>
				<input type="button" id="saveTrend" value="<?php echo ($result_trend_key==-1)?'not ready':'save';?>" <?php echo ($result_trend_key==-1)?'disabled': ($result_trend_key==0)?'' : 'disabled';?> class="btn <?php echo ($result_trend_key==-1)?'btn-secondary':'btn-info';?>  p-0 rounded d-inline" style='width:80px !important;'>
				</div>
				<table class="table table-condensed table-bordered table-hover listt" style="width: 1200px; ">
					<thead>
						<th>S/N</th>
						<th>Matric Number</th>
							<?php
							foreach ($theader as $key => $value) {
								$thc = explode('+', $value);
								?>
								<th style="text-align: center;"><?php echo $thc[0].'<br>'.$thc[1]; ?> <p>Failed: <span style="color:red;" id="<?php echo $thc[0];?>">0</span><span style="display:none;" id="a<?php echo $thc[0];?>">0</span></p></th>
								<?php
							}
							?>
					</thead>
					<tbody>
						<?php
						$num =0;
							foreach ($studentsR as $key => $value) {
								sort($value);
								$num++;
								//var_dump(json_encode($value));failG_array
								?>
								<tr>
									<td><?php echo $num.'.';?></td>
									<td><?php echo $key;?></td>
									<?php
									   //$stack = $theader;
										for ($i=0; $i <sizeof($value); $i++){
											$hc = explode('+', $theader[$i]);

											if($hc[0] == $value[$i][0]){
												if (in_array($value[$i][2], $failG_array)) {
													?>
													<script>
														var topM = document.getElementById('<?php echo $hc[0]; ?>');
														topM.innerHTML = parseInt(topM.innerText,10)+1;
														//alert();
													</script>
													<?php
												}
											?>
												<script>
													//count total in the course
													var topMt = document.getElementById('a<?php echo $hc[0]; ?>');
													topMt.innerHTML = parseInt(topMt.innerText,10)+1;
												</script>
												<td><?php echo $value[$i][2]; ?></td>
											<?php
											}else{
												echo "<td></td>";
											}
										}
									?>
								</tr>
								<?php
							}
							?>
							<script>var AvgF =0,AvgP=0, tcx=0,tpmt=0; </script>
							<?php
							foreach ($theader as $key => $value) {
								$thc = explode('+', $value);
								?>
								<script>
									tcx++;
									var topMx = document.getElementById('<?php echo $thc[0]; ?>');
									var topMxt = document.getElementById('a<?php echo $thc[0]; ?>').textContent;
									//total pass for each course
									tpmt += parseInt(topMxt,10) - parseInt(topM.innerText,10);

									AvgF += parseInt(topM.innerText,10);

									//alert();
								</script>
								<?php
							}
							?>
							<script>AvgF = Math.round(AvgF/tcx); AvgP = Math.round(tpmt/tcx);
								document.getElementById('tavgP').value = AvgP;
								document.getElementById('tavgF').value = AvgF;
							 </script>
							<?php

							
						?>
					</tbody>
				</table>
<!--*******************************************GRADE TABLE FOR PDF **********************************************  -->
<div id="tableView1" style="display: none;width: 98%;margin: 0px auto;">
<table class="table table-condensed table-bordered table-hover" style="width: 1200px;" id="tablepdf">
					<thead>
						<th>S/N</th>
						<th>Matric Number</th>
							<?php
							foreach ($theader as $key => $value) {
								$thc = explode('+', $value);
								?>
								<th style="text-align: center;"><?php echo $thc[0].'<br>'.$thc[1]; ?> </p></th>
								<?php
							}
							?>
					</thead>
					<tbody>
						<?php
						$num =0;
							foreach ($studentsR as $key => $value) {
								sort($value);
								$num++;
								//var_dump(json_encode($value));failG_array
								?>
								<tr>
									<td><?php echo $num.'.';?></td>
									<td><?php echo $key;?></td>
									<?php
									   //$stack = $theader;
										for ($i=0; $i <sizeof($value); $i++){
											$hc = explode('+', $theader[$i]);

											if($hc[0] == $value[$i][0]){
												if (in_array($value[$i][2], $failG_array)) {
													?>
													<script>
														
													</script>
													<?php
												}
											?>
												<script>
													//count total in the course
													
												</script>
												<td><?php echo $value[$i][2]; ?></td>
											<?php
											}else{
												echo "<td></td>";
											}
										}
									?>
								</tr>
								<?php
							}
							?>
							
					</tbody>
</table>
</div>
<!--***********************************************************************************************************  -->

			</div>		
		</div>
		</div>
	@endif
	</div>
	<div class="chartAlert" id="chartbox" style="">
	<div class="chartBody">
		<div class="close text-danger closebtn" onclick="$('#chartbox').fadeOut(100);" style="">&times</div>
		<div id="chartContainer" class="chartAlertD"></div>
		<div class="close text-danger" onclick="$('#chartbox').fadeOut(100);">&times</div>
		
	</div>
	
</div>

@include('layouts/scripts')

<script>
 $(document).ready(function(){

 $(".listt").dataTable({
   "bPaginate": true,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": true,
    "iDisplayLength":6,
    "bAutoWidth": true,
    "bwarning": 2,
    "bStateSave": true
});
 $('#saveTrend').click(function(){

 	var passFail =  $('#tavgP').val() +','+ $('#tavgF').val();
 		var slevel = '<?php echo @$selectedl;?>';
 		var ssession = '<?php echo @$selSession;?>';
 		var ssemester = '<?php echo @$csemester?>';
 		var sdepartment = '<?php echo @$LECTURER->department_id;?>';
 	$.ajax({
		type: 'PUT',
		url:  "{{route('saveTrends')}}",
		data: {pf:passFail,level:slevel,session:ssession,semester:ssemester,department:sdepartment, _token:'{{ csrf_token() }}' },
		success: function(data){
		$("#loader").hide();
		//console.log(data.success==200);
			if(data.success==200){
				Swal.fire({
				position: 'top-end',
				title: 'Result Trend Saved',
				type: 'success',
				showConfirmButton: false,
				timer: 2000
			}).then((result)=>{
				location.reload();
			});
			}else if(data.success == 407){
				Swal.fire({
					type: 'error',
					title: 'Cannot perform this action',
					text: 'maybe server down or no network',
				});
			}
		},
		error: function(data){
		console.log(data);
		}
	});
	$("#loader").show();
 });
});

 //print to pdf with jspdf and autotablejs
 $('#printPDF').click(function(){
 	var spreadRsult = document.getElementById('tablepdf');
 	var schoolname = '<?php echo ucwords($school->school_name); ?>';
 	var facultyname = '<?php echo $fact->faculty; ?>';
 	var departmentname = '<?php echo $dept->department; ?>';
 	var sessionname = '<?php echo $sessionname; ?>';
// 	$level = 3;
	//alert(<?php //echo $level; ?>);
 	var level = '<?php switch ($level??'') {
									case 1:
										echo 100;
										break;
									case 2: 
										echo 200;
										break;
									case 3:
										echo 300;
										break;
									case 4:
										echo 400;
										break;
									case 5:
										echo 500;
										break;
									default:
										echo '';							
								}?>';
 	var semester = '<?php echo $csemestername; ?>';
 	/*var levelc = document.getElementById('departmentname').innerText;
 	var sessionc = document.getElementById('departmentname').innerText;
 	var semesterc = document.getElementById('departmentname').innerText;*/

 	pdf = new jsPDF('l', 'pt', 'a4');

 	pdf.setFontSize(18);
	pdf.setFontType("bold");
	pdf.setFont("tahoma");
	pdf.text(schoolname,450, 40 , 'center');
	pdf.setFontSize(12);
	pdf.setFontType("normal");	
	pdf.text('Faculty of '+facultyname ,450, 65 , 'center');
	pdf.text('Department of '+departmentname,450, 85 , 'center' );
	pdf.setFontSize(12);
	pdf.text(semester + '  ' +  sessionname + '  ' + level + 'level',450, 105 , 'center');
	
	
	
	pdf.setFontSize(10);
 	var imgData = new Image();
	imgData.src = '/img/ibb logo.png';
 	pdf.addImage( imgData, 'PNG', 30, 20, 50,0);
 	var res = pdf.autoTableHtmlToJson(document.getElementById("tablepdf"));
  	pdf.autoTable(res.columns, res.data, {margin: {top: 130}}); 	
	pdf.save("download.pdf");

 });

function toFull(a, b){
var screenElem = document.getElementById(a);
var tableV = document.getElementById(b);
var mainTable = document.getElementById('DataTables_Table_0_wrapper');
	if(screenElem.classList.contains('fullscreen')){
    	screenElem.classList.remove('fullscreen');
    	//tableAdjust.classList.remove('tableAdjust');
    	tableV.style.display = 'none';
    	mainTable.style.display = 'block';
    }else{
    	screenElem.classList.add('fullscreen');	
    	tableV.style.display = 'block';
    	mainTable.style.display = 'none';
    	//tableAdjust.classList.add('tableAdjust');	
    }
	/*if(screenElem.classList.contains('full')){
    	screenElem.classList.remove('full');
    	screenElem.classList.add('fullscreen');
    }else{
    	screenElem.classList.remove('fullscreen');
    	screenElem.classList.add('full');
    }*/
}
</script>
@endsection
