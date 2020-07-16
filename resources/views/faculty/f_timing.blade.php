<?php
use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Semester;
use \App\Department;
use \App\Faculty;
use \App\Role;
use \App\f_timing;

$LECTURER = Lecturer::where('email',Auth::user()->email)->first();
$DEPARTMENT = Department::where('id', $LECTURER->department_id)->first();
$faculty =  $DEPARTMENT->faculty_id;
$semester = Semester::where('c_set', 1)->first()->id;
$session = Session::where('c_set', 1)->first()->id;;

//timing status
$t_status = -1;
$time1 ='';
$time2 ='';

$tm = f_timing::where(['faculty'=>$faculty,'session'=>$session, 'semester' =>$semester])->first();
//$tm = f_timing::where(['session'=>$session, 'semester'=>$semester, 'faculty'=>$faculty])->first();
//dd($tm);
if (!is_null($tm)) {
	$time1 = $tm->startsT;
	$time2 = $tm->endT;

	if ($tm->status ==1) {
		$t_status = 1;
	}else{
		$t_status = 0;
	}
}

?>

@extends('layouts/master')
<style type="text/css">
		.spaced{
			margin-left: 12px;
			float: right;
		}
		.spaced input{
			color: #555 !important;
			font-size: 0.9em;
			padding: 5px 8px;	
			border:1px solid #ccc;
			width: 95px !important;
		}
		p{
			color:#777;
		}
	</style>
@section('content')
<div id="titlebar">
			<div  id="title">Faculty<span class="lnr lnr-chevron-right"></span><i>Manage Timing</i></div>

			<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
		</div>
		<div style="margin: 20px auto; width: 97%;">
				<p>Manage Result upload timing <input type="checkbox" <?php echo ($t_status==1)? 'checked':'' ;?> id="uploadTiming"></p>
				<div style="width: 170px;">
					<p>Start:
						<i class="spaced">
							<input type="text" id="uTime1" class="datepicker-here" value="<?php echo ($t_status==1 || $t_status==0 )? $time1 :'';?>" <?php echo ($t_status==1)? '':'disabled' ;?> data-language='en' data-min-view="days" data-view="days" data-date-format="yyyy-mm-dd" />
						</i>
					</p>
					<p>Close:
						<i class="spaced">
							<input type="text" id="uTime2" class="datepicker-here" value="<?php echo ($t_status==1 || $t_status==0 )? $time2 :'';?>" <?php echo ($t_status==1)? '':'disabled' ;?> data-language='en' data-min-view="days" data-view="days" data-date-format="yyyy-mm-dd" />
						</i>
					</p>
				
				</div>
				<input type="button" class="btn btn-success"  value="Save Change" onclick="saveChanges();" name="">
			</div>
@include('layouts/scripts')
<script>
		var t1 = document.getElementById('uTime1');
		var t2 = document.getElementById('uTime2');
		
	window.onload = function() {
		/* $( ".jdate" ).datepicker({
            dateFormat:"yy-mm-dd",
            changeMonth: true,
            changeYear: true,
           
            }); */

	$('#uploadTiming').click(function(e){
		//alert(i.val());
		var session  = '<?php echo $session??''; ?>';
		var semester = '<?php echo $semester??''; ?>';
		var faculty  = '<?php echo $faculty??''; ?>';
		/*if($('#uploadTiming:checkbox:checked').length>0){
		}*/
		 $.ajax({
        type: 'POST',
        url:  '{{route("manage_timing")}}',
        data: {session:session, semester:semester, faculty:faculty, type:1, _token:'{{ csrf_token() }}' },
        success: function(data){
      	$("#loader").hide();
      	console.log(data);
            if(data.success==200){
               Swal.fire({
						position: 'top-end',
						title: 'success',
						type: 'success',
						showConfirmButton: false,
						timer: 2000
					}).then((result)=>{
						location.reload();
					});
              }else{
                Swal.fire({
                  type: 'error',
                  title: 'something went wrong',
                  showConfirmButton: true
                });
              }
          },
          error: function(err){
          	console.log(err);
          }
      });
			
	});


}
function saveChanges(){
	var tim1 = t1.value;
	var tim2 =  t2.value;

	var session  = '<?php echo $session??""; ?>';
	var semester = '<?php echo $semester??""; ?>';
	var faculty  = '<?php echo $faculty??""; ?>';

	 $.ajax({
        type: 'POST',
        url:  '{{route("manage_timing")}}',
        data: {session:session, semester:semester, faculty:faculty, type:2,tim1:tim1, tim2:tim2, _token:'{{ csrf_token() }}' },
        success: function(data){
      	$("#loader").hide();
      	console.log(data);
            if(data.success==200){
               Swal.fire({
						position: 'top-end',
						title: 'success',
						type: 'success',
						showConfirmButton: false,
						timer: 2000
					}).then((result)=>{
						location.reload();
					});
              }else{
                Swal.fire({
                  type: 'error',
                  title: 'something went wrong',
                  showConfirmButton: true
                });
              }
          },
          error: function(err){
          	console.log(err);
          }
      });

		$('#loader').show();
	/*
	var tim1 = new Date(t1.value);
	var tim2 =  new Date(t2.value);
	var td = (tim2 - tim1)/1000/60/60/24;

	if (td<0){

	}*/
}
</script>
@endsection
