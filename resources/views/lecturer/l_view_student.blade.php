<?php
use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Role;
use \App\f_timing;



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
	 // dd($_POST['selSession']); 
		$selSession1 =  explode(',',$_POST['selSession']);
		$selSession = $selSession1[0];
		$sessionname = $selSession1[1];
	}else{
		$selSession = $session->id;
		$sessionname = $session->session;
	}
	
	//echo ;
?>
@extends('layouts/master')
@section('content')
@include('lecturer/class/left_pane')

	 <style type="text/css">
   
   	.paging_two_button {
        margin: 15px;
        position:absolute;
        bottom: -0px;
       }
    .dataTables_info{
        position:absolute;
        bottom: 40px ;
        margin:10px;
        color:#aaa;
       }
        #listt> tbody> tr td{
       	padding: 4px !important;
       }

  </style>
		<div id="titlebar">
			<div  id="title">Lecturer<span class="lnr lnr-chevron-right"></span><i> View Registered Student</i></div>

			<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
		</div>
		<!--CONTENT AREA START-->
		<div class="innerContent" style="padding-top: 15px;">
		<div class="row" style="margin-left: -3px;">

		<div class="col-xs-12 col-sm-6 col-md-5 col-lg-4 userList">

				
        
<script type="text/javascript" src="../assets/js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="../assets/js/dataTable/jquery.dataTables.min.js"></script>
		<?php
					//course list for logged in user in a pane
						$obj = new CoursesList;

						$uri = $_SERVER['REQUEST_URI'];
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
 		$urlM = explode('/',$uri);
 		$url = end($urlM);
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
				        url:  '{{route("lchange")}}',
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
						$(document).ready(function(){

					$(".listS").dataTable({
					   "bPaginate": true,
					    "bLengthChange": false,
					    "bFilter": true,
					    "bInfo": true,
					    "iDisplayLength":4,
					    "bAutoWidth": true
					});
						});
					</script>
          
			</div>	
			<div class="col-sm-1 col-md-1 col-xs-12 mt-3" style="" id="separatorAA"></div>
			<div class="col-xs-12 col-sm-5 col-md-6 col-lg-7 bg-white shadow  p-3" style="overflow-x: scroll;">
			<?php
			//view student from the selected 
			//unset($_SESSION['selected_course_id']);
				$student_r_courses = array();

				/*check the selected courses*/
				if(session('selected_course_id') !=''){
					$course_id = session('selected_course_id');
					$session_id = session('current_set_session_id');

						$session_name =  session('current_set_session');
					
						$run = DB::table('Students_registered_courses', 'sr')
								->join('students', 'sr.student_id','=','students.id')
								->join('courses', 'courses.id','=','sr.course_id')
								->join('levels', 'sr.level_id','=','levels.id')
								->where(['sr.session_id'=>$selSession, 'sr.semester'=> $csemester,'sr.course_id'=>$course_id])->get();
						
						if ($run->count()) {
							$n =0;
							foreach($run as $student_r) {
								$student_r_courses[$n]= array();
								$student_r_courses[$n][] =  $student_r->matric_number;
								$student_r_courses[$n][] =  $student_r->first_name;
								$student_r_courses[$n][] =  $student_r->level;
								$n++;
							}
						}else{
							$student_r_courses = 0;
						}
					}
			?>
			<!--Excel file Button Generator-->
			<form action="{{route('result_upload_template')}}" method="post" style=" width: 200px;" target="_blank" >
				<input type="text" name="student_registered" value="<?php echo htmlentities(json_encode($student_r_courses)); ?>" style="display:none;">
				<input type="text" name="type" value="1" style="display:none;">
				{{ csrf_field() }}
          		{{ method_field('PATCH') }}
				<button class="btn btn-primary mb-3 mr-3" style="float: left;">Generate Temp</button>
			</form>
			<div style="float: right;margin-right: 40%;font-weight: bolder;text-transform: uppercase;color:#666; "><?php echo session('selected_course_code')??''; ?></div>
			<!--Table -->
			<table class="table table-bordered table-hover" id="listt" style="min-width: 380px !important;">
					<thead>
						<th>Matric Number</th>
						<th>Name</th>
						<th>Level</th>
					</thead>
					<tbody>
						<?php
							if ($student_r_courses==0) {
								?><tr><td>No student has registered for this course<td><td></td></tr><?php
							}else{	
								foreach ($student_r_courses as $key => $value) {
									?>
									<tr>
										<?php
										foreach ($value as $ky => $val) {
											?>
											<td><?php echo $val; ?></td>
											<?php
										}
										?>
									</tr>
									<?php
								}
							}
						?>
					</tbody>
			</table>

			</div>
			</div>		
		</div>
	

@include('layouts/scripts')

<script>
 $(document).ready(function(){

 $("#listt").dataTable({
   "bPaginate": true,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": true,
    "iDisplayLength":6,
    "bAutoWidth": true
});

 //$('<hr>').insertAfter('#listt_filter');
});


</script>
@endsection	
