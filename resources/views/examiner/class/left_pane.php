<?php
/**
 * 
 */
class CoursesList
{
	public $session;
	public $semester;
	public $log_in_user;


	function __construct()
	{
		$connectdir =  '../php/connect.php';
		include $connectdir;
		$this->conn = $conn;

	}
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
		}
		if($selSemester=='') {
			 $selSemester = $this->sem;
		}

		?>
		<label style="cursor: pointer;font-size: 0.8em;" id="advan" onclick="(function(){$('#myform1').slideToggle();})();">Advance>></label>
		<form action=""  method="post" id="myform1"  <?php if($selSession!=''){ echo "class='advanceS'";}else{echo "style='display: none;' class='advanceS'";}?> >
			<select class="form-contro mr-1 mb-1" style="width: 100px !important;border: 1px solid #eee; font-size: 0.8em;" name="selSession" id="sessionS">
				<option value="">select session</option>
				<?php
					$sqli = $this->conn->query("SELECT * FROM sessions ORDER BY session ASC");
						if ($sqli->num_rows>0) {
						while ($rwi=$sqli->fetch_assoc()) {
							$idi = $rwi['id'];$sessions = $rwi['session'];$facultyi = $rwi['c_set'];
							?>
							<option value="<?php echo $idi.';'.$sessions;?>" <?php if($selSession==$idi){echo 'selected';} ?> ><?php echo $sessions; ?></option>
										<?php
									}	
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
	

 	public function ResgisteredStudent($userID, $sessionID, $semesterID)
 	{
 		//echo $sessionID;
 		?>
 		  <table  class="listS" style="width: 100%;">
          <thead>
            <th></th>
          </thead>
          <tbody>

 		<?php
 		//fetch all assigned courses courses
			$user_run = $this->conn->query("SELECT f.faculty, dp.department,c.id as cid,c.course_code as course_code, c.course_title as course_title FROM courses as c INNER JOIN lecturers as l ON l.user_id = '$userID' INNER JOIN lecturer_allocated_courses as le INNER JOIN departments as dp ON c.department_id=dp.id INNER JOIN faculty as f ON f.id=dp.faculty_id WHERE c.department_id=l.department_id AND le.course_id = c.id AND le.session_id = '$sessionID' AND c.semester ='$semesterID'");

			//create array to store 
			$user_d = array();

             echo mysqli_error($this->conn);
            if ($user_run->num_rows>0) {
              $i = 0;
              while($row=$user_run->fetch_assoc()){
              	$id = $row['cid'];
                $uid = $row['course_code'];
                $faculty = $row['faculty'];
                $departmentname = $row['department'];
                $course = $user_d[$i][] = ucfirst($row['course_title']);
            ?>
                  <tr>
                  
                  <td id="<?php echo 'list'.$id; ?>"><span class="icofont-book-alt s3"></span><span class="name"><?php echo $course;?> <b class="scolor">(<?php echo str_replace(' ', '', $uid);?>)</b></span></td>
                </tr>
                  <script>
                    document.getElementById("<?php echo 'list'.$id; ?>").onclick = function(){
                      	// continue by posting id to misc and set $update to a global variable then reload page for aplying
                      	$.post("misc/setinfo.php",{selectedC:'selected',ccode:'<?php echo $uid; ?>',department:'<?php echo $departmentname; ?>',faculty:'<?php echo $faculty; ?>',id:<?php echo $id;?>,type:3}, function(data){
                      		$('#loader').hide();
                      		location.reload();
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

        <?php
 	}
}


?>