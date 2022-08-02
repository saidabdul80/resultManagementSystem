
{{$rtoken??''}}
<?php
use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Role;
use \App\f_timing;
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
	//$department_id = $_SESSION['department_id'];


        
	//total courses in department
	$total_course =0;
/*	$fetch_TC1 = $conn->query("SELECT *, l.lecturer_id as lid, c.id as cid FROM lecturer_allocated_courses AS l INNER JOIN courses as c ON c.id=l.course_id INNER JOIN level as le ON le.id=c.level_id INNER JOIN users as u ON u.id=l.lecturer_id WHERE l.session_id='$selSession' AND c.semester='$csemester' AND l.department_id='$logged_in_dept_id'");*/
	$fetch_TC1 = DB::table('lecturer_allocated_courses','l')->select('*', 'l.lecturer_id as lid', 'courses.id as cid')
				->join('courses', 'courses.id','=','l.course_id')
				->join('levels', 'levels.id','=', 'courses.level_id')
				->where(['l.session_id'=>$selSession, 'courses.semester'=> $csemester, 'l.department_id'=>$LECTURER->department_id])->get();		
	if($fetch_TC1->count()>0) {
		$total_course = $fetch_TC1->count();
		$lev = 100;
		$allCourse = array();
		$c = 0;
		foreach($fetch_TC1 as $rr) {
			if($lev ==$rr->level) {
				if ($c==0) {
					$allCourse[$lev] = array();
					$allCourse[$lev][$c] = array();
					$allCourse[$lev][$c][] = $rr->course_id;
					$allCourse[$lev][$c][] = $rr->course_code;
					$allCourse[$lev][$c][] = $rr->credit_unit;
					$c++;
				}else{
					$allCourse[$lev][$c] = array();
					$allCourse[$lev][$c][] = $rr->course_id;
					$allCourse[$lev][$c][] = $rr->course_code;
					$allCourse[$lev][$c][] = $rr->credit_unit;
					$c++;
				}
			}else{
				$c = 0;
				$lev = $rr->level;
				$allCourse[$lev] = array();
				$allCourse[$lev][$c] = array();
				$allCourse[$lev][$c][] = $rr->course_id;
				$allCourse[$lev][$c][] = $rr->course_code;
				$allCourse[$lev][$c][] = $rr->credit_unit;

				$c++;
			}
		}
	}
	
	//total courses uploaded
	$total_upload = 0;
	
	
?>

@extends('layouts/master')
<script>
	var allCourse = <?php echo json_encode($allCourse);?>;
</script>
<style type="text/css">
   	.paging_two_button {
        margin: 15px;
        position:relative !important;
        bottom: 20px !important;
       }
    .dataTables_info{
        position:relative !important;
        bottom: 15px !important;
        color:#aaa;
       }
        #table01> tbody> tr td{
       	padding: 4px !important;
       }
       .badge{
       	background: rgba(10,10,10,.1) !important;
       	border: 1px solid #ccc;
       	color: #555 !important;
       }
       .compileD a{
           padding:8px 10px;
           display:block;
           text-align:center;
       }
    .compileD{
    display: none;
    position: absolute;
    z-index: 10000; 
    border-radius:5px; 
    text-align: left;
	}
	.compileDShow{
    	display: flex !important;
   		flex-direction: column !important; 
	}
	.compileD > a:hover{
	    font-size: 1em;
	}
	
	#dropbtn:hover  #cd{
		display: block !important;
	}

</style>
@section('content')
	<div id="titlebar">
		<div  id="title">Examiner<span class="lnr lnr-chevron-right"></span><i>Manage Result</i></div>

		<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
	</div>
	<!--CONTENT AREA START-->
		<div class="row innerContent" style="margin:auto;">
			
				<div class="badge badge-primary p-3 mr-2 mt-1" style="height: 40px;" >Total Courses: <?php echo $total_course; ?> </div>
				<div class="badge badge-success p-3 mr-2 mt-1" id="total_upload" style="height: 40px;" >Total Courses Result Uploaded:  </div>
				<div class="badge badge-danger p-3 mt-1" id="confirmed_r" style="height: 40px;" >Total Confirmed Result: </div>
					<ul style="display: inline-block;" class="m-0 ml-2 p-0" id="dropbtn">
						<li class="btn btn-warning text-white mb-1 btn-lg mt-1 cBtnN" id="cBtn" style="font-size: 1em;">Compile Result 
						<!-- <input type="checkbox" name="" value="Compile result"> -->
							<span class="lnr lnr-chevron-down"></span>
						</li>
						<div class="bg-warning shadow compileD p-0" id="cd">
							<?php

							 if(array_key_exists(100, $allCourse)){?>
								<a href="#" id="level1"  onclick="CompileRLL(100);" class="px-3"><i class="mx-2 lnr lnr-sync"></i>100 level</a>
							<?php }?>
							<?php if(array_key_exists(200, $allCourse)){?>
								<a href="#" id="level2"><i class="mx-2 lnr lnr-sync"></i>200 level</a>
							<?php }?>
							<?php if(array_key_exists(300, $allCourse)){?>
								<a href="#" id="level3"><i class="mx-2 lnr lnr-sync"></i>300 level</a>
							<?php }?>
							<?php if(array_key_exists(400, $allCourse)){?>
								<a href="#" id="level4"><i class="mx-2 lnr lnr-sync"></i>400 level</a>
							<?php }?>
							<?php if(array_key_exists(500, $allCourse)){?>
								<a href="#" id="level5"><i class="mx-2 lnr lnr-sync"></i>500 level</a>
							<?php }?>
						</div>
					</ul>
					<script type="text/javascript">
						/*document.getElementById('dropbtn').onmouseover = function(e){
							document.getElementById('cd').classList.add('compileDShow');
						}
						document.getElementById('dropbtn').onmouseout = function(e){
							document.getElementById('cd').classList.remove('compileDShow');
						}*/

					</script>
				<!-- <span style="box-shadow: 0px 2px 4px #ccc; padding: 5px;background-color: #ccc;">

				</span> -->
			<p></p>
			<div class="row w-100 mt-4 mx-atuo">
				<div class="col-lg-6 col-md-6 mb-1" style="height: 400px; border: 1px solid #eee;">
					<p class="bg-light p-1 m-0 text-center w-100 text-secondary mb-3 mt-2"><strong>Result Uploaded By:</strong></p>
					<table class="table table-stripped table-bordered" id="table01" style="display: none;">
						<thead>
							<th>Level</th>
							<th>Lecturer</th>
							<th>Course code</th>
						</thead>
						<tbody>
							<?php
								if($fetch_TC1->count()>0) {
									$confirmed_r =0;
									$total_upload =0;
									foreach($fetch_TC1 as $fu){
										$lid = $fu->lid;
										$cid = $fu->cid;
										/*$fresult = $conn->query("SELECT * FROM users as u INNER JOIN lecturers as l ON l.email=u.username INNER JOIN result_file as r ON r.lecturer_id=l.lecture_ID WHERE u.id='$lid' AND r.course_id='$cid' AND r.session_id='$selSession' AND r.semester='$csemester' AND r.department_id='$logged_in_dept_id' ORDER BY r.result_confirm_by_lect ASC");*/
										$fresult = DB::table('lecturers', 'l')->select('*', 'result_confirm_by_lect as lc')
												   ->join('result_file', 'result_file.lecturer_id', '=', 'l.id')
												   ->where(['result_file.course_id'=>$cid, 'result_file.session_id'=>$selSession, 'result_file.semester'=>$csemester, 'result_file.department_id'=> $LECTURER->department_id])
												   ->first();
												   //->orderByRaw('result_file.id ASC')
										
										if (!is_null($fresult)) {
										//$fuu = $fresult->fetch_assoc();
										if ($fresult->result_confirm_by_lect==1) {$confirmed_r++;}
										$total_upload++;
										?>
										<tr>
											<td><?php echo $fu->level;?></td>
											<td><?php echo ucwords($fresult->first_name.' '.$fresult->surname).' '.$fresult->lecture_ID; ?></td>
											<td><?php echo $fu->course_code; echo ($fresult->result_confirm_by_lect==1)? '<i class="circle circle-green"></i>' : '<i class="circle circle-red"></i>'?> </td>
										</tr>
										<?php
										}
										
									}
								}

							?>
							<script>
								document.getElementById('confirmed_r').innerHTML += '<?php echo $confirmed_r;?>';
								document.getElementById('total_upload').innerHTML += '<?php echo $total_upload;?>';
						</script>
						</tbody>
					</table>
				</div>
				<div class="col-lg-6 col-md-6 mb-1" style="height: 400px;border: 1px solid #eee;">
					<p class="bg-light p-1 m-0 text-center text-secondary mb-3 mt-2"><strong>Result Left to be Upload By:</strong></p>
					<table class="table table-stripped table-bordered table-hover" id="table02" style="display: none;">
						<thead>
							<th>Level</th>
							<th>Lecturer</th>
							<th>Course code</th>
						</thead>
						<tbody>
							<?php
								if($fetch_TC1->count()>0) {
									$confirmed_r =0;
									$total_upload =0;
								//	$counterNum = 0;
									foreach($fetch_TC1 as $fu){
										$lid = $fu->lid;
										$cid = $fu->cid;
									
										$fuu = DB::table('lecturers', 'l')
												   ->join('result_file', 'result_file.lecturer_id', '=', 'l.id')
												   ->where(['result_file.course_id'=>$cid, 'result_file.session_id'=>$selSession, 'result_file.semester'=>$csemester, 'result_file.department_id'=> $LECTURER->department_id])
												   ->first();
												   
										if (!is_null($fuu)) {
										
										if ($fuu->result_confirm_by_lect == 1) {$confirmed_r++;}
										$total_upload++;
										}else{
										    $lecturer_info = DB::table('lecturers', 'l')
												   ->join('lecturer_allocated_courses', 'lecturer_allocated_courses.lecturer_id', '=', 'l.id')
												   ->where(['lecturer_allocated_courses.course_id'=>$cid, 'lecturer_allocated_courses.session_id'=>$selSession, 'lecturer_allocated_courses.department_id'=> $LECTURER->department_id])
												   ->first();
										    
										/*    $counterNum++;
										    $notificationID = $lecturer_info->id.$counterNum;*/
										?>
										<tr>
											<td><?php echo $fu->level;?></td>
											<td><?php echo ucwords($lecturer_info->first_name).' '.ucwords($lecturer_info->surname).' '.$lecturer_info->lecture_ID; ?></td>
											<td><?php echo $fu->course_code; ?> </td>
											
										</tr>
										<?php

										}
										
									}
								}

							?>
							<script>
						</script>
						</tbody>
					</table>
				</div>
			</div>
			
			
		</div>
		<!--CONTENT AREA END-->
		
@include('layouts/scripts')
<script>
 $(document).ready(function(){

 $("#table01").dataTable({
   "bPaginate": true,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": true,
    "iDisplayLength":6,
    "bAutoWidth": true,
    "bStateSave": true
});
 $("#table01").show();

 $("#table02").dataTable({
   "bPaginate": true,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": true,
    "iDisplayLength":6,
    "bAutoWidth": true,
    "bStateSave": true
});
 $("#table02").show();

 //$('<hr>').insertAfter('#listt_filter');
});
 function CompileRLL(lev){
 	//send semester, session, level, 

 	$.ajax({
		type: 'POST',
		url:  "{{route('process_result')}}",
		data: {allCourse: allCourse[lev], level:lev, semester:<?php echo $csemester;?>, session:<?php echo $selSession; ?>, departmentID:<?php echo $LECTURER->department_id;?>, rtoken:"<?php echo $token_raw??''; ?>", _token:'{{ csrf_token() }}',type:0},
		success: function(data){
			$("#loader").hide();
			//console.log(data.success==200);
				if(data.success==200){
					Swal.fire({
						position: 'top-end',
						title: 'Result compiled Successfully',
						type: 'success',
						showConfirmButton: false,
						timer: 2000
					}).then((result)=>{
						location.reload();
					});
				}else if(data.success == 201){
					Swal.fire('','Error: set departmental TOTAL CREDIT UNIT');
				}
				else if(data.success == 207){
					Swal.fire('', 'Error: set grading scale');
				}
				else if(data.success = 10001){
					Swal.fire({
					  title: 'Already Compiled!',
					  text: "Did you wants to continue Recompiling?",
					  type: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Yes, Recompile it!'
					}).then((result) => {
					  if (result.value) {
					  	//send for recompile

					  	$.ajax({
				        type: 'POST',
				        url:  "{{route('process_result')}}",
				        data: {allCourse: allCourse[lev], level:lev, semester:<?php echo $csemester;?>, session:<?php echo $selSession; ?>, departmentID:<?php echo $LECTURER->department_id;?>, rtoken:"<?php echo $token_raw??''; ?>",type:1, _token:'{{ csrf_token() }}' },
				        success: function(data){
					      	$("#loader").hide();
					      	//console.log(data.success==200);
					      	if(data.success==400){
				               	Swal.fire({
									position: 'top-end',
									title: 'Result compiled Successfully',
									type: 'success',
									showConfirmButton: false,
									timer: 2000
								}).then((result)=>{
									location.reload();
								});
				             }else if(data.success == 407){
				             	Swal.fire({
								  title: 'somthing went wrong!',
								  text: "please recomile result",
								  type: 'warning'
								});
				             }
				      	},
				      	error: function(data){
					      	console.log(data);
				      	}
				      });
					  	$("#loader").show();
						 //end send recompile
					  }
				})
				}
			},
		error: function(data){
			console.log(data);
		}
		});
 
 	$("#loader").show();

 }

</script>
@endsection
