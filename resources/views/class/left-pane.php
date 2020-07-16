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
		}else{
			$selSession = explode(';', $selSession);
			$selSession = $selSession[0];
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
	

 	public function ResgisteredStudent($userID, $sessionID, $semesterID, $uri)
 	{
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
			$user_run = $this->conn->query("SELECT *,c.id as cid FROM lecturer_allocated_courses as l INNER JOIN courses AS c ON c.id=l.course_id INNER JOIN sessions as s ON s.id='$sessionID' WHERE l.lecturer_id='$userID' AND l.session_id=s.id AND c.semester='$semesterID'");

			//create array to store 
			$user_d = array();
             //echo mysqli_error($this->conn);
			//echo $user_run->num_rows;
            if ($user_run->num_rows>0) {
              $i = 0;
              while($row=$user_run->fetch_assoc()){
              	$id = $row['cid'];
                $uid = $row['course_code'];
                $course = $user_d[$i][] = ucfirst($row['course_title']);
            ?>
                  <tr>
                  
                  <td id="<?php echo 'list'.$id; ?>"><span class="icofont-book-alt s3"></span><span class="name"><?php echo $course;?> <b class="scolor">(<?php echo str_replace(' ', '', $uid);?>)</b></span></td>
                </tr>
                  <script>
                    document.getElementById("<?php echo 'list'.$id; ?>").onclick = function(){
                      	// continue by posting id to misc and set $update to a global variable then reload page for aplying
                      	$.post("misc/s_setinfo.php",{selectedC:'selected',ccode:'<?php echo $uid; ?>',id:<?php echo $id;?>,type:1}, function(data){
                      		$('#loader').hide();
                      		location.href='<?php echo $url; ?>';
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