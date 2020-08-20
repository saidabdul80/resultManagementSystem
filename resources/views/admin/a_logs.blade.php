

<?php
use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Role;
use \App\activity_log;
//use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Models\Activity;
$resultFile = 'App\result_file';
$resultTrend = 'App\result_trend';
$spreadGp = 'App\spread_gp';
$grade = 'App\grade';

  $current_session_id = \App\Session::where('c_set',1)->first()->id;
  $logged_in_usr_id = Auth::user()->id;
  $session = \App\Session::where('c_set',1)->first();
  $semester = \App\Semester::where('c_set',1)->first();
  $isLecturer = Lecturer::where('email', Auth::user()->email)->first();
  

  $date = date('Y-m-d');
  if (!is_null($isLecturer)) {
    $user_department_id = $isLecturer->department_id;
  
    $Lecturer_Id = $isLecturer->lecture_ID;
    $faculty_id = Department::where('id', $user_department_id )->first()->faculty_id;
    
  }else{
    $faculty_id = 1;
  }
  
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

  
?>
@extends('layouts/master')

@section('content')
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
<div id="titlebar">
	<div  id="title">Examiner<span class="lnr lnr-chevron-right"></span><i> logs</i></div>
	<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
</div>
<!--CONTENT AREA START-->
		<div class="innerContent" style="padding-top: 15px;">
		<div class="row w-100">
			<div class="col-lg-12 col-md-10  mx-auto ">
				<table class="table  table-hover w-100" id="listt" style="" >
					<thead>
						<th>action</th>
						<th>changes</th>
					</thead>
					<tbody>
				<?php
					
					$sql_run = activity_log::where('subject_type',$resultFile)->orWhere('subject_type',$resultTrend)->orWhere('subject_type',$grade)->orWhere('subject_type',$spreadGp)->orderBy('id', 'desc')->get();
					//dd($sql_run);
					//$ss = Activity::all();
					//dd($ss);
					if ($sql_run->count() > 0) {
						foreach($sql_run as $row) {
							//dd($row);
							$logs = json_decode($row->properties);
							if ($row->properties != '[]') {
							?>
							<tr>
								
								<td class="text-secondary w-25">
									<?php
									
										
										echo $row->description;
									
									?>
								</td>
								<td style="display: flex; word-break: break-all;">
									<?php 
											//$diff = xdiff_string_diff(old_data, new_data)
											dd($row->properties->attributes);
										foreach (json_decode($row->properties) as $key => $value) {
											//xdiff_string_diff

											if($key == 'attributes'){
												//get detail of gupnp_service_action_return(action)	
												$emaili = user::find($value->created_by)->first()->email;
												$Lecturer = Lecturer::where('email', $emaili)->first();
												if(is_null($Lecturer)) {
													$title = $emaili; 
												}else{
													$title = $Lecturer->salute.' '.$Lecturer->first_name.' ('.$Lecturer->lecture_ID.')';
												}
												$value->created_by = $title;
												?>
													<span class="text-success">
														<?php echo preg_replace("/[\"\"{}]/", " ", json_encode($value)) ; ?>
													</span>
												<?php
											}else{
												//get detail of actor
												$emaili = user::find($value->created_by)->first()->email;
												$Lecturer = Lecturer::where('email', $emaili)->first();
												if(is_null($Lecturer)) {
													$title = $emaili; 
												}else{
													$title = $Lecturer->salute.' '.$Lecturer->first_name.' ('.$Lecturer->lecture_ID.')';
												}
												$value->created_by = $title;

												?> 	
												<span class="lnr lnr-arrow-right mx-3 border pt-4 rounded  "></span>
													<span class="text-danger">
														<?php echo preg_replace("/[\"\"{}]/", " ", json_encode($value)) ; ?>
													</span>
												<?php
											}
										}
									?>
								</td>

							</tr>
							<?php
							}
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
    "iDisplayLength":3,
    "bAutoWidth": true,
    "bwarning": 2,
    "bStateSave": true
});

 //$('<hr>').insertAfter('#listt_filter');
});


</script>
@endsection
</body>
</html>