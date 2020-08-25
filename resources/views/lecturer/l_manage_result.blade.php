
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

		$session = \App\Session::where('c_set',$selSession)->first();
	}else{
		$selSession = $session->id;
		$sessionname = $session->session;
	}

	//get timing
	//$getTime = $conn->query("SELECT  endT FROM `f_timing` WHERE  `faculty`='$faculty_id' AND `session`='$selSession' AND  `semester`='$csemester'");
    $getTime = f_timing::where(['faculty'=>$faculty_id,'session'=>$selSession, 'semester' =>$csemester])->first();
	//$getTime  = f_timing::where(['faculty'=>$faculty_id,'session'=>$selSession, 'semester' =>$csemester])->first();

	//checking if result session is not current year
#***************************** this will be implemented before adopting ***********************

	$allowY = -1;
	$leftY = 0;
	$splitgt = explode('/',$session->session);

	if (!is_null($splitgt)) {
	
		$cYear = date('Y');
		$selectedSessionYear = $splitgt[1];
		$leftY = $cYear - $selectedSessionYear;
		
		//echo $date1->diff($date2)->format("%R");
		if($leftY > 0){
			$allowY = 0;
		}else{
			$allowY = 1;
		}
	}	
#***************************** End this will be implemented before adopting ***********************
	$allow = -1;
	$left = 0;
	if (!is_null($getTime)) {
		///echo "string";
		//$gt = $getTime->fetch_assoc();
		$cdate = new DateTime($date);
		$endDate = new DateTime($getTime->endT);

		$sign = $cdate->diff($endDate)->format("%R");
		$left = $cdate->diff($endDate)->format("%R%d");
		//echo $date1->diff($date2)->format("%R");
		if($sign== '-'){
			$allow = 0;
		}else{
			$allow = 1;
		}
	}	
	function gradeSys($a,$b1,$b2,$b3,$b4,$b5,$b6)
	{
		switch (true) {
			case ($a>=$b1):
				return 'A';
				break;
			case ($a>=$b2):
				return 'B';
				break;
			case ($a>=$b3):
				return 'C';
				break;
			case ($a>=$b4):
				return 'D';
				break;
			case ($a>=$b5):
				return 'E';
				break;
			case ($a>=$b6):
				return 'F';
				break;
		}
	}
	$date = date('Y-m-d');
	$token_raw = str_replace('/', '-', $sessionname).'-'.$semester->id.'-'.session('selected_course_code')??'';
	$uri = explode('/',url()->current());
 	//echo $path??'';
		$ThePget = $p??'';
 		
?>
@extends('layouts/master')
@include('lecturer/class/left_pane')
<script>var result_detc =0;

	function DPageUpload(){
		location.href = "{{ route('lchanges',1) }}";
		//location.href ='l_manage_result.php?p=1';
	}
	function DPageVUpload(){
		location.href = "{{ route('lchanges',2) }}";
		//location.href ='l_manage_result.php?p=1';
	}
	function back(){
		location.href = "{{ route('l_manage_result') }}";	
	}

</script>
	<style>
		
		#cover{
			width: 100%;
		}
		.paging_two_button {
        margin: 15px;
        position:absolute;
        bottom: -10px;
        display: inline;
       }
    .dataTables_info{
        position:absolute;
        bottom: 20px ;
        margin:0px;
        color:#aaa;
       }
       #listt> tbody> tr td{
       	padding: 3px !important;
       }
       i{
       	cursor: pointer;
       }
	</style>
@section('content')

			<div id="titlebar">
			<div  id="title">Lecturer<span class="lnr lnr-chevron-right"></span><i onclick="location.href='{{route('l_manage_result')}}';"> Manage Result</i> <?php 
			if($ThePget != '')
				{
					if($ThePget==1){
						 ?>
						  <span class="lnr lnr-chevron-right"></span><i onclick="location.reload();"> Upload Result</i> 
						 <?php
					}elseif ($ThePget==2) {
						 ?>
						  <span class="lnr lnr-chevron-right"></span><i onclick="location.reload();"> View Uploaded Result</i> 
						 <?php
					}
				}
				?>
			</div>

			<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
		</div>
		<!--CONTENT AREA START-->
		
		@if(session('sendmsg'))

			@if(session('sendmsg') === 'success')
				<div id="ups1" class="alert alert-success">Uploaded Successfully</div>
				<script type="text/javascript">
					setTimeout(function(){
							document.getElementById('ups1').style.display = 'none';
					}, 5000);
				</script>
			@else
				<div class="alert alert-warning"  id="ups2"><span class="close" onclick="(function(){document.getElementById('ups2').style.display='none';})()">x</span><?php echo session('sendmsg'); ?></div>
			@endif
		@endif

		<div id='lock' style="display: none;" class="alert <?php echo ($allow==-1)?'alert-warning': 'alert-danger'?> text-center"> <?php echo ($allow==-1)? ucwords($csemestername).' '.$sessionname.' Result upload not activated':ucwords($csemestername).' '.$sessionname. ' result upload closed'?>
		</div>
		<!--CONTENT AREA START-->
		
			<!--options-->
	<?php
		if ($ThePget =='' || $ThePget!=1 && $ThePget!=2) {
	?>
			<div class="innerContent" style="padding-top: 15px;">
			<center>
				
			<ul id="optionS">
				<li onclick='DPageUpload();'>Upload Result</li>
				<li onclick="DPageVUpload();">View Uploaded Result</li>
			</ul>
			</center>
		</div>
	
	<?php
	}
	?>
	<?php
	if ($ThePget !='' && $ThePget==1 || $ThePget==2) {
	?>
	<!--`````````````````````````````````````Search section for both upload result and view uploaded result```````````-->

				<!-- <i class="btn btn-secondary btn-sm lnr lnr-home " onclick="back();"></i> -->
				<div style="display:none ;" id="errorFileTyp" class="alert alert-danger">
					<span>Invalid File Type: File must be .xls </span>
					<span class="close" onclick="$('#errorFileTyp').hide();">&times</span>
				</div>
				<div style="display:none ;" id="errorFileTyp1" class="alert alert-danger">
					<span>Result File Already Exist </span>
					<span class="close" onclick="$('#errorFileTyp1').hide();">&times</span>
				</div>
				<div class="innerContent" style="padding-top: 15px; width: 100%;">
					<div class="row " style="">
					<!--Seached content Area-->
					<div class="col-xs-12 col-sm-6 col-md-5 col-lg-4 userList">			
					<!-- Advance search section -->
					<script type="text/javascript" src="/assets/js/jquery-1.10.2.js"></script>
					<script type="text/javascript" src="/assets/js/dataTable/jquery.dataTables.min.js"></script>
					<?php
					//course list for logged in user in a pane
						$obj = new CoursesList;

						$obj->setCSession($selSession);
						$obj->setCSemester($csemester);
						$obj->advanceSearch('','');	
						
					?>
		<table  class="listS" style="width: 100%;">
          <thead>
            <th></th>
          </thead>
          <tbody>

 		<?php
 		
 		//fetch lecture courses
 		
			$user_run =  DB::table('lecturer_allocated_courses', 'l')
						->select('l.id as lid')
						->join('courses', 'courses.id','=','l.course_id')
						->addSelect('courses.course_code')
						->addSelect('courses.id as cid')
						->addSelect('courses.course_title')
						->where(['l.lecturer_id'=> $isLecturer->id, 'l.session_id'=>$selSession, 'courses.semester'=>$csemester])
						->get();
			//create array to store 
			$user_d = array();
             //echo mysqli_error($this->conn);
			//echo $user_run->num_rows;
            if ($user_run->count()>0) {
              $i = 0;
              foreach($user_run as $row){
              	$id = $row->cid;
                $uid = $row->course_code;
                $course = $user_d[$i][] = ucfirst($row->course_title);
            ?>
                  <tr>
                  
                  <td id="<?php echo 'list'.$id; ?>"><span class="icofont-book-alt s3"></span><span class="name"><?php echo $course;?> <b class="scolor">(<?php echo str_replace(' ', '', $uid);?>)</b></span></td>
                </tr>
                  <script>

                    document.getElementById("<?php echo 'list'.$id; ?>").onclick = function(){
                      	// continue by posting id to misc and set $update to a global variable then reload page for aplying
                      	
                      	$.ajax({
				        type: 'POST',
				        url:  '{{route("lchange", $ThePget)}}',
				        data: {selectedC:'selected',ccode:'<?php echo $uid; ?>',id:<?php echo $id;?>, _token:'{{ csrf_token() }}' },
				        success: function(data){
				      	$("#loader").hide();
				      	console.log(data);
				            if(data.success==200){
								location.reload();
				              }else{
				              }
				          },
				          error: function(err){
				          	console.log(err);
				          }
				      });
                      	$('#loader').show();
                    }
                  </script>

                <?php
                $i++;

              }
            }
          ?>
          </tbody>
        </table>
		<script>
					$(".listS").dataTable({
					   "bPaginate": true,
					    "bLengthChange": false,
					    "bFilter": true,
					    "bInfo": true,
					    "iDisplayLength":4,
					    "bAutoWidth": true,
    					"bSelectedOnly": true,
					    "bStateSave": true
					});
					//$(".listS").page( 'next' ).draw( 'page' );
		</script>
						<!--end Seached content Area-->
						</div>	
				<div class="col-sm-1 col-md-1 col-xs-12 mt-3" style="" id="separatorAA"></div>

	
		
	<?php
	}
	?>
<!--/////////////////////////////View Upload result\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\-->
	
		@if ($ThePget == 2) 
	
		<!--View Selected Content Area-->
					<div class="col-xs-12 col-sm-5 col-md-6 col-lg-7 bg-white shadow h-120 p-2 pb-5">
						<p class="text-center border bg-light shadow mb-4 text-white p-1" style="height: 40px;">
						<span class="text-dark"  style="text-align:center;font-weight: bolder;text-transform: uppercase;/*color:#d99;*/"><i class="icofont-book-alt text-dark"></i>
							<?php $note = 0; if(session('selected_course_code')!=''){ $note=1;?>
							{{ session('selected_course_code')}}
							 <?php
							}?> Uploaded Result</span>		
						
						</p>
					<center>
						
					<div class="top-info">
						<button style="float: left; background: transparent;" class="btn btn-sm btn-light icofont-thumbs-up text-success" id="accept"></button>
						<button style="float: left; background: transparent;" class="btn btn-sm btn-light icofont-trash text-danger" id="delete"></button><br>
							<?php
						
			?>
					</div>
					</center><br>
			<?php
			
				//echo $course_id = session('selected_course_id'];
			?>
						<table class="table table-bordered table-hover" id="listt">
								<thead>
									<th>Matric Number</th>
									<th>Score</th>
									<th>grade</th>
								</thead>
								<tbody>
									<?php
									//$chk = $conn->query("SELECT * FROM result_files WHERE result_token='$token_raw'");
									$chk = \App\Result_file::where('result_token', $token_raw)->first();;
									if (!is_null($chk)) {
										//$rF = $chk->fetch_assoc();
										$confirm = $chk->result_confirm_by_lect;
										//if result has been confirm diable confirm button
										if($confirm==1){
											?><script>
												document.getElementById('accept').disabled=true;
												document.getElementById('accept').title='result is confirmed';
											</script><?php
										}
										if(session('selected_course_code')!=''){
											$course_id = session('selected_course_id');
											//course id alread specific to a semester
										/*	$fetch_result = $conn->query("SELECT * FROM `results` as r INNER JOIN students as s ON r.students_id=s.id WHERE result_token='$token_raw'");*/
											$fetch_result = DB::table('results','r')
															->join('students', 'students.id','=','r.students_id')->where('result_token', $token_raw)->get();
											if ($fetch_result->count()>0) {
												//create js to make aware that result is found
												?><script>result_detc =1;</script><?php
												foreach($fetch_result as $row) {
													?>
													<tr>
														<td><?php echo $row->matric_number;?></td>
														<td><?php echo $row->final_score;?></td>
														<td><?php echo $row->grades;?></td>
													</tr>
													<?php
												}
											}
										}else{
												//echo "string";
										}
									}else{
										//no result found
										//set to auto set status to 0 whenever result file is empty
									}
									?>
								</tbody>
							</table>
						</div>
			<!--View Selected Content Area-->
						</div>		
					</div>
	
	@endif

<!--////////////////////////////////Upload result\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\-->
		<?php
			if ($ThePget=!'' && $ThePget==1) {
		?>
			<div class="col-xs-12 col-sm-5 col-md-6 col-lg-7 bg-white shadow h-100">
			<p class="text-center border shadow mb-4 text-secondary p-2">Upload Result</p>
			
		<?php
			//view student from the selected 
			//unset(session('selected_course_id']);
			$student_r_courses = array();
			$student_r_courses_id = array();

			/*check the selected courses*/
			if(session('selected_course_id') !=''){
				$course_id = session('selected_course_id');

				if (isset($_POST['selSession'])){
					$session_id = $selSession;
					$session_name = $sessionname;
				}else{
					$session_id = $session;
					$session_name =  $sessionname;
				}							
				$studentDATA = DB::table('Students_registered_courses','sr')
						->join('students', 'students.id','=','sr.student_id')
						->join('courses', 'courses.id','=','sr.course_id')
						->where(['sr.session_id'=>$session->id, 'sr.semester'=>$semester->id, 'sr.course_id'=>session('selected_course_id')??''])->get();
				//		dd($studentDATA);
				
				/*
				if ($run->count()>0) {
					foreach ($run as $student_r ) {
						$student_r_courses[]=  $student_r->matric_number;
						$student_r_courses_id[$student_r->matric_number]= $student_r->student_id;
					}	
				}else{
					$student_r_courses = 0;
					$student_r_courses_id = 0;
				}*/
			}
		?>
		<h4  style="text-align:center;font-weight: bolder;text-transform: uppercase;color:#d99;">
		<?php $note = 0; if(session('selected_course_code')!=''){ $note=1; echo session('selected_course_code');}?> Selected</h4>
		<hr style="border:1px dotted #eee;">
		<form method="post" action="{{route('lchanges',1)}}" class="p-5 border" enctype="multipart/form-data">
			{{csrf_field()}}
			<p id="noted" style="display: none;" class="alert alert-danger">Please Select course</p>
			<input type="text" name="course_idUpload" class="toTime" required="" value="@if(session('selected_course_id')!='') {{session('selected_course_id')}} @endif" style='display: none;'>
			<input type="text" name="type" class="toTime" value="1" style='display: none;'>
			<input type="text" name="id" class="toTime" value="{{$LECTURER->id}}" style='display: none;'>
			<input type="text" name="p" class="toTime" value="{{ $ThePget??''  }}" style='display: none;'>
			<input type="text" name="token_raw" class="toTime" value="{{ $token_raw??''  }}" style='display: none;'>
			<input type="file" name="fileUpload" class="toTime" id="fileUpload" accept=".xlsx,.csv,.xls" required=""><br><br>
			<input type="submit" name="UploadFile" class="toTime" onclick="//validateWithJs();" value="Upload" class="form-control btn btn-success" style="width: 150px !important; display: block;">
			<script>
				/*function validateWithJs(event){
					event.preventDefault();
					var file = document.getElementById('fileU');
					if(fileU.files.length){
						var parts = fileU.files[0].split('.');
						alert(parts[1]);
					}
				}*/
			</script>
		</form>
		<br>
		<!-- $(this).click(function(){if(<?php// echo $note; ?>==0){$('#noted').show(200);}}) -->
		
		<?php
			}
		?>
			
			@if($path??'' != '')
			<?php
				$date = date('Y-m-d_h');
          		$allData = array();
          		$existMatric = '';
          		$FailedLines = '';
          		$error = 0;
          		$succex = 0;
          		$successMat = array();
				$course_idUpload = $course_idUpload;
				$ex = explode('.', basename($path));
          		
          		$file_type = $ex[1];
			?>
				@if($course_idUpload == '')
					<script type="text/javascript">Swal.fire('please select course');</script>
				@else
					
					@if($file_type =='xlsx' || $file_type =='csv' || $file_type =='xls')
					<?php
						//check if already exist
						$sqlcheck = \App\Result_file::where('result_token', $token_raw)->get();
					?>
						@if($sqlcheck->count()>0)
						
						<!-- 	//Request::session()->forget('selected_course_code');
							//Resultequest::session()->forget('selected_course_id'); -->
							<script type="text/javascript">
								if (window.history.replaceState){
									window.history.replaceState(null, null, window.location.href);
								}
								$('#errorFileTyp1').show();
							</script>
							<!-- <div class="alert alert-danger">Result Already Uploaded</div> -->
						@else
							<?php
							require_once('assets/spreadsheet-reader/php-excel-reader/excel_reader2.php');
							require_once('assets/spreadsheet-reader/SpreadsheetReader.php');
							//insert result file to db
							
							
							//$sqlinsert_run = \App\RE
							//fetch grading scale
							//$get_grade = $conn->query("SELECT * FROM grades WHERE c_set=1");
							$grade_r = \App\Grade::where('c_set',1)->first();
							//$grade_r = $get_grade->fetch_assoc();
							$Reader = new SpreadsheetReader($path);
        					$sheetCount = count($Reader->sheets());
       						//for($i=0;$i<$sheetCount;$i++){
							$Reader->ChangeSheet(0);
							$num = 0;
        					$count = 0;
							foreach ($Reader as $Row)
							{
								if($num== 0){
								}else{
								    $count++;
									$matric = "";
							 		//echo "string";
									if($Row[0]??''!='') {
									    $matric = $Row[0];
									}
									$score = "";
									if($Row[1]??'' !='') {
								
										$score = $Row[1];
									}
									$grade = gradeSys($score, $grade_r->A, $grade_r->B, $grade_r->C, $grade_r->D, $grade_r->E, $grade_r->F);

									$student_id ='';
									foreach($studentDATA as $rw) {
				          				if($rw->matric_number == $matric ){
				          					$successMat[] = $rw->matric_number;
				          					$student_id =  $rw->student_id;
				          					?>
				          						<script>
				          						//	alert('{{ $rw->student_id }}');
				          						</script>
				          					<?php
				          				}else{
				          				
				          				}
				          			}
									
									if($student_id !='') {
								       	$allData[]=[
							 				'students_id'=> $student_id,
							 				'course_id'=>$course_idUpload,
							 				'session_id'=>$selSession,
							 				'final_score'=>$score,
							 				'grades'=>$grade,
							 				'lecturer_id'=>$LECTURER->id,
							 				'result_token'=>$token_raw,
							 				'status' => 1
							 			];
									 $succex = 1;
									}else{

										if ($matric !='' AND $score != ''){
										$error =1;
										//$existMatric .= .'';
										$FailedLines .= '<p>Line '.$num.": didn't register this course ".$matric."</p>+";
										}
									}
								}
							
								$num++;
							}


							if($succex > 0 ){
								
								$sendmsg= 'success';
								
								$up000 = \App\Grade::where('c_set',1)->first();
								$updateGrade = \App\Grade::find($up000->id);
								$updateGrade->status = 1;
								
									$sqlinsert_run = new \App\Result_file;
									$sqlinsert_run->lecturer_id = $LECTURER->id;
									$sqlinsert_run->result_token = $token_raw;
									$sqlinsert_run->course_id = $course_idUpload;
									$sqlinsert_run->department_id = $LECTURER->department_id;
									$sqlinsert_run->session_id = $selSession;
									$sqlinsert_run->semester = $csemester;
									$sqlinsert_run->status = 0;
								
								if ($error <1) {
									$updateGrade->save();
									DB::table('results')->insert($allData);
									$sqlinsert_run->save();

									//logs activity
									$injson = [

							            "attributes"=>[
							                $ex[0]." Result file was uploaded Successfully",
							                "created_by" => Auth::user()->id,
							                "created_on"=> $date
							            ]

							        ];
							   		//end logs activity   

								}else{
									$updateGrade->save();
									DB::table('results')->insert($allData);
									$sqlinsert_run->save();

									$sendmsg = "<b>ISSUES ENCOUNTER</b>";
									$sendmsg .= "<div style='font-family:arial; color:#a11; font-size:0.9em;' id='isu111'>";
									$FailedL = explode('+', $FailedLines);
									foreach($FailedL as $key => $val){ 
									    $sendmsg .= $val;
									}
									   	$sendmsg .= "</div>";

									//logs activity
									$injson = [
							            "attributes"=>[
							                $ex[0]." Result file uploaded Successfull on ".implode($successMat, ','),
							                "created_by" => Auth::user()->id,
							                "created_on"=> $date
							            ]

							        ];
							    	//end logs activity   
								}
								
								

								
							}else{
								$sendmsg = "<b>ISSUES ENCOUNTER</b>";
								$sendmsg .= "<div style='font-family:arial; color:#a11; font-size:0.9em;' id='isu111'>";
								$FailedL = explode('+', $FailedLines);
								foreach($FailedL as $key => $val){ 
								  	//$sendmsg .= " <span class='badge badge-dark text-white'> ".$val. "</span>";
								    $sendmsg .= $val;
								}
								$sendmsg .= "</div>";
								
								//logs activity
								$injson = [

							            "attributes"=>[
							                $ex[0]."Result file upload attempt was not Successfull",
							                "created_by" => Auth::user()->id,
							                "created_on"=> $date
							            ]

							        ];
							    //end logs activity
							}
							
							//create logs 
							//subject_type will be used as uploaded file name/path
							\DB::table('activity_log')->insert(['id'=>null,'log_name'=>'default','description'=>'Result Upload','subject_id'=>Auth::user()->id, 'subject_type' => $path, 'causer_id' => Auth::user()->id, 'causer_type' => 'App\Result_file','properties' => json_encode($injson), 'created_at' => $date, 'updated_at' => $date ]);
							//end create logs
										
						?>
						<form method="POST" action="{{route('lchanges')}}" id="formMsg1">
							{{csrf_field()}}
							<input type="text" name="sendmsg" value="<?php echo $sendmsg; ?>" style='display: none;'>
							<input type="text" name="type" value="11" style='display: none;' >
						</form>
						<script type="text/javascript">
							//		alert(<?php// echo $sendmsg;?>)
							document.getElementById('formMsg1').submit();
						</script>
				@endif

		@else
			<script>
				$('#errorFileTyp').show();
			</script>
		@endif
			
	</div>
	@endif
			<!--View Selected Content Area-->
	</div>		
					
@endif
</div>
@include('layouts/scripts')

<script>
 $(document).ready(function(){

 $("#listt").dataTable({
   "bPaginate": true,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": true,
    "iDisplayLength":9,
    "bAutoWidth": true,
    "bSelectedOnly": true,
    "bStateSave": true
});
 //$(".listt").page( 'next' ).draw( 'page' );

 //$('<hr>').insertAfter('#listt_filter');
});

$(document).ready(function(){

$('#accept').click(function(){
	if ('{{session("selected_course_code")??""}}?>' != '')
	{
		if(result_detc==1) {
			Swal.fire({
	            title: 'You Are About to Confirm {{session("selected_course_code")??""}} ',
	            text: 'Are you sure? You want to continue',
	            type: 'warning',
	            showCancelButton: true,
	            confirmButtonColor: '#3085d6',
	            cancelButtonColor: '#d33',
	            confirmButtonText: 'Yes'
	        }).then((result) => {
	            if (result.value) {
	            	
	            	 $.ajax({
				        type: 'PUT',
				        url:  "{{route('confirmResult', $ThePget??'')}}",
				        data: {token_raw:'{{$token_raw??""}}', from:'lecturer', type:2, _token:'{{ csrf_token() }}' },
				        success: function(data){
					      	$("#loader").hide();
					      	//console.log(data.success==200);
					      	if(data.success==200){
				               	Swal.fire({
									position: 'top-end',
									title: 'Result Accepted by You',
									type: 'success',
									showConfirmButton: false,
									timer: 2000
								}).then((result)=>{
									location.reload();
								});
				             }
				      	},
				      	error: function(data){
					      	console.log(data);
				      	}
				      });
						
			         $('#loader').show();
	            	
				}
	          });
	        }else{
			Swal.fire('no result found for this course');	
		}
		}else{
		  Swal.fire('please select a course');
			}
		});
/*------------------------------end of accept result script-------------------*/		
		$('#delete').click(function(){
			if ('<?php echo session("selected_course_code")??"";?>' !='')
			{
			if(result_detc==1) {
			Swal.fire({
	            title: 'You Are About to delete  {{session("selected_course_code")??""}}',
	            text: 'Are you sure? You want to continue',
	            type: 'warning',
	            showCancelButton: true,
	            confirmButtonColor: '#3085d6',
	            cancelButtonColor: '#d33',
	            confirmButtonText: 'Yes'
	        }).then((result) => {
	            if (result.value) {
	             $.ajax({
				        type: 'DELETE',
				        url:  "{{route('deleteResult', $ThePget??'')}}",
				        data: {token_raw:'{{$token_raw??""}}', from:'lecturer', type:2, _token:'{{ csrf_token() }}' },
				        success: function(data){
					      	$("#loader").hide();
					      	//console.log(data.success==200);
					      	if(data.success==200){
				               	Swal.fire({
									position: 'top-end',
									title: 'Result deleted by You',
									type: 'success',
									showConfirmButton: false,
									timer: 2000
								}).then((result)=>{
									location.reload();
								});
				             }
				      	},
				      	error: function(data){
					      	console.log(data);
				      	}
				      });
						
			          $('#loader').show();
	            	
				}
	          });
			}else{
				Swal.fire('no result found for this course');	
			}
	}else{
		Swal.fire('please select a course');
	}
		});
})


	var allow = "<?php echo $allow; ?>";
	var tt = document.querySelectorAll('.toTime');
	if(allow=='0' || allow==-1){
		for (var i = 0; i < tt.length; i++) {
			tt[i].disabled = true;
		}
		$('#lock').show();
	}
	

</script>


@endsection	

