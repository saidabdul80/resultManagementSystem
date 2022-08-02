<?php
use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Role;
$Departments = Department::all();
$Faculty = Department::all();

//use Illuminate\Routing\UrlGenerator
 $uri = explode('/',url()->current());
 $uri =  end($uri);

?>


<?php
/**
 * 
 */
class CoursesList
{
	public $session;
	public $semester;
	public $log_in_user;


	
	public function setCSession($se)
	{
		$this->se = $se;	
	}
	public function setCSemester($sem)
	{
		$this->sem = $sem;	
	}

	public function advanceSearch($selSession, $selSemester){
		if ($selSession=='') {
			$selSession = $this->se;
		}else{
			$selSession =  $selSession;
			//$selSession = $selSession[0];
		}
		if($selSemester=='') {
			 $selSemester = $this->sem;
		}

		?>
		<label style="cursor: pointer;font-size: 0.8em;" id="advan" onclick="(function(){$('#myform1').slideToggle();})();">Advance>></label>
		<form action="{{url()->full()}}"  method="post" id="myform1"  <?php if($selSession!=''){ echo "class='advanceS'";}else{echo "style='display: none;' class='advanceS'";}?> >
				 {{ csrf_field() }}
				 {{ method_field('PATCH') }}

          
			<select class="form-contro mr-1 mb-1" style="width: 100px !important;border: 1px solid #eee; font-size: 0.8em;" name="selSession" id="sessionS">
				<option value="">select session</option>
				<?php 
				$SESSION = App\Session::get();
				foreach ($SESSION as $sesion) {
					?>
					<option value="{{ $sesion->id }},{{ $sesion->session }}" @if($sesion->id == $selSession) {{'selected'}} @endif >{{ $sesion->session}}</option>
					<?php
				}
				?>
				
				</select>
			
				<select class="form-control" style="width: 135px !important;border: 1px solid #eee;font-size: 0.8em;" name="selSemester" id="semesterS">
				<option value="">select Semester</option>
				<?php
					?>
						<option value="1" <?php if($selSemester==1){echo 'selected';}?> >1st Semester</option>
						<option value="2" <?php if($selSemester==2){echo 'selected';} ?> >2st Semester</option>
									
				</select>
			
			<br>
		</form>
		<script>
			$('#sessionS').change(function(){
				$('#myform1').submit();
			});
			$('#semesterS').change(function(){
				$('#myform1').submit();
			});
		</script>
		<?php
	}
	

 	public function ResgisteredStudent($sessionID, $semesterID, $uri)
 	{
 		?>
 		  

        <?php
 	}
}


?>