

<?php
use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Role;
use \App\f_timing;
$current_session_id = \App\Session::where('c_set',1)->first()->id;
	$logged_in_usr_id = Auth::user()->id;
  $session = \App\Session::where('c_set',1)->first();
  $semester = \App\Semester::where('c_set',1)->first();
  $isLecturer = Lecturer::where('email', Auth::user()->email)->first();
  //dd($isLecturer->department_id);
  $date = date('Y-m-d');
  if (!is_null($isLecturer)) {
    $user_department_id = $isLecturer->department_id;
  
    $Lecturer_Id = $isLecturer->lecture_ID;
    $faculty_id = Department::where('id', $user_department_id )->first()->faculty_id;
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

  
?>
@extends('layouts/master')

@section('content')
<div id="titlebar">
	<div  id="title">Examiner<span class="lnr lnr-chevron-right"></span><i> logs</i></div>
	<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
</div>
<!--CONTENT AREA START-->
		<div class="innerContent" style="padding-top: 15px;">
		<div class="row" style="margin-left: -3px;">
			<div class="col-lg-10 col-md-10  mx-auto">
				<table class="table table-bordered table-hover" id="listt">
					<thead>
						<th>Descriptioin</th>
						<th>Date</th>
					</thead>
					<tbody>
				<?php
					$sql_run = $conn->query("SELECT * FROM logs WHERE user_id='$logged_in_usr_id' AND type='examiner'");
					if ($sql_run->num_rows>0) {
						while ($row = $sql_run->fetch_assoc()) {
							?>
							<tr>
								<td><?php echo $row['description'];?></td>
								<td style="white-space: nowrap;"><?php echo $row['action_date'];?></td>
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
@endsection
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
</body>
</html>   