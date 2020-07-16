<script>var checking='save';</script>

<?php
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Grade;
use \App\Course;
use \App\Level;
$Departments = Department::all();
$Level = Level::all();
$Faculty = Department::all();

	if(session('course_selected_id') != ''){
 	$update = session('course_update');
	$selid  = session('course_selected_id');
	$users = Course::where('id',$selid)->get();
//	$users = $conn->query("SELECT * FROM courses WHERE id ='$selid'");
	if($users->count() >0){
		foreach($users as $ur) {
			$deptid = $ur->department_id;
			$levelid = $ur->level_id;
			$ctitle = $ur->course_title;
			$ccode = $ur->course_code;
			$cdesc = $ur->course_description;
			$cunit = $ur->credit_unit;
			$csemester = $ur->semester;
		}
	}
}
if(isset($update)){
?>
	<script>var checking='update';</script>
<?php
	}else{	
?>
	<script>var checking='save';</script>
<?php
}
?>

<style type="text/css">
	.paging_two_button {
        margin: 15px;
        position:relative !important;
        font-size: 0.8em;
        bottom: -10px !important;
       }
    .dataTables_info{
        position:relative;
        bottom: 30px !important ;
        margin:10px;
        font-size: 0.8em;
        color:#aaa;
       }
       .dataTables_filter>label{
       	font-size: 0.8em;
       }
       @media only screen and (max-width: 988px){
       	.innerContent{
       		margin:15px auto !important;
       		padding: 0px !important;
       	}
       
       } 
</style>
@extends('layouts/master')

@section('content')
		<div id="containerA" class="containerA">
		<div id="titlebar">
			<div  id="title">Admin <span class="lnr lnr-chevron-right"></span><i> Manage Courses</i></div>

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
		<div class="innerContent" style="padding-top: 15px;">
		<div class="row w-100 container-fluid" style="margin: 0px 0px 10px 0px;">

				<div class="col-xs-12 col-sm-5 col-md-4 col-lg-3 userList " style="/*height: 100%; border:1px solid #eee; border-radius: 5px;padding-bottom: 10px; padding-top: 10px; margin: 0px !important;*/">
			@include('class/search')
				 <table  class="listS" style="width: 100%;font-size: 0.9em;">
          <thead>
            <th></th>
          </thead>
          <tbody>
            
          <?php
			//fetch all students
          if (session('departmentid')!='') {
          	$facultyid =session('facultyid');
          	if (session('departmentid') !='') {
          		$departmentid =session('departmentid');
            	//$user_run = $conn->query("SELECT *, c.id as cid FROM courses AS c INNER JOIN departments AS d ON d.id=c.department_id WHERE c.department_id='$departmentid' AND d.faculty_id='$selDepartment'");
              $user_run = Course::with('department')->where('department_id',$departmentid)->get();
          	}
          }else{
  //          $user_run = $conn->query("SELECT *, id as cid FROM courses");
            $user_run = Course::all();
          }
           $uri = explode('/',url()->current());
           $uri =  end($uri);
              //$exist_role = array();
              $user_d = array();
             // echo mysqli_error($conn);
            if ($user_run->count()>0) {
              $i = 0;
              foreach($user_run as $row){
              	$id = $row->id;
                $uid = $row->course_code;
                $course = $user_d[$i][] = ucfirst($row->course_title);
                //$matric = $row['matric_number'];
                ?>
                  <tr>
                  
                  <td id="<?php echo 'list'.$id; ?>"><span class="icofont-book-alt s3"></span><span class="name"><?php echo $course;?> <b class="scolor">(<?php echo str_replace(' ', '', $uid);?>)</b></span></td>
                </tr>
                  <script>
                    document.getElementById("<?php echo 'list'.$id; ?>").onclick = function(){
                      	// continue by posting id to misc and set $update to a global variable then reload page for aplying
                      	$.ajax({
                          type: 'PATCH',
                          url: "{{ route($uri) }}",
                          data: {_token:'{{ csrf_token() }}',update:'update',id:<?php echo $id;?>,type:1}, 
                          success: function(data){
                      		$('#loader').hide();
                      		location.reload();
                          },
                          error: function(e){
                            console.log(e);
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
       
        		<script type="text/javascript" src="../assets/js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="../assets/js/dataTable/jquery.dataTables.min.js"></script>
        <script>
	
$(".listS").dataTable({
   "bPaginate": true,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": true,
    "iDisplayLength":4,
    "bAutoWidth": true
});
        </script>
          
			</div>	
			<div class="col-sm-1 col-md-1 col-xs-12 mt-3" style="" id="separatorAA"></div>
			<div class="col-xs-12  col-sm-6 col-md-7  bg-white shadow h-100" style="font-size: 0.9em;" >
				<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm border input-group mt-1" >
					<!--navbar-->
				  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				    <span class="navbar-toggler-icon"></span>
				  </button>

				  <div class="collapse navbar-collapse" id="navbarSupportedContent" >
				    <ul class="navbar-nav mr-auto" style="padding: 1px">
				      <li id="adccc" style="font-size: 0.8em;" <?php if (isset($update)){echo "class='nav-item '";}else{echo "class='nav-item active'";}?>>
				        <a class="nav-link" onclick="unsetValues();" >Create course<span class="sr-only">(current)</span></a>
				      </li>
				      <?php
				      	if (isset($update)){
				      		?>
					      <li class="nav-item active" id="editid" style="font-size: 0.8em;">
					        <a class="nav-link"  href="">Edit Course</a>
					      </li>

				      		<?php
				      	}else{}
				      ?>
				      <li  style="font-size: 0.8em;" id="uppp">
				        <a style="cursor: pointer;" class="nav-link" onclick="(function(){$('#inform').hide();$('#inform2').slideDown();$('#editid,#strictid,#adccc').removeClass('active');$('#uppp').addClass('active');})()" >Upload Course <span class="sr-only">(current)</span></a>
				      </li>
				    </ul>
				  </div>
				</nav>
			<!--upload courses-->
				<div class="row mt-3" id="inform2" style="display: none;">	
					<div class="col-lg-6 col-md-8">
							<a href="/students_temp" target="blank" style="color:blue;text-align: center !important;">Download Template</a>
						<form action="{{route('cuourseCRUD')}}" method="post" id="" class="" enctype="multipart/form-data" >	
								{{ csrf_field() }}
								<input type="text" name="type" value="2" style="display:none;">
							<div class="" style="margin-top: 7px 0px 0px 20px;">
								<div class="input-group mt-1 formP">
									<label class='input-group-prepend'>Department:</label>
								    <select  class="form-control" style="" id="departmentu" name="departmentu" required="">
									   	<option value="" >Select Department</option>
									    <?php
										   // $sql = "select * from departments where status='0' order by departments.department asc";
									        
									    	foreach( $Departments as $r)
									        {
									        	echo '<option value="'.$r->id.'">'.$r->department.'</option>';
									        }
									    ?>
									  </select>
								</div>
								
							</div>
								<div class="input-group mt-1 formP">
									<label class='input-group-prepend'>Select file</label>
						            <input type="file" name="file" id="file" accept=".xls,.xlsx" required="">
						        </div>               
						        <center>
									<button type="submit" name="import" class="btn btn-success mt-4">upload </button>
						        </center>            
						</form>
					</div>
					
				</div>
				<div class="row mt-3 " id="inform" >	
					<div class="col-lg-8 col-md-12 ">
					<div class="input-group mt-1 formP w-100">
						<label class='input-group-prepend'>Department:</label>
						<select  id="dept" name="department" class="mb-1 form-control" style="">
							<option value="">select department</option>
						<?php
							
							if ($Departments->count() >0) {
								foreach ($Departments as $dr) {
									$did = $dr->id;
						?>
							<option value="<?php echo $did;?>"  <?php if(isset($deptid) && $deptid==$did){echo "selected=''";}?> ><?php echo $dr->department;?></option>
						<?php
								}
							}
						?>
						</select>
					</div>
					<div class="input-group mt-1 formP">
						<label class='input-group-prepend'>Level:</label>
						<select  id="level" name="level" class="form-control" >
							<option value="">select level</option>
						<?php
							
							if ($Level->count() >0) {
								foreach ($Level as $lv ) {
									$lid = $lv->id;
						?>
							<option value="<?php echo $lid;?>"  <?php if(isset($levelid) && $levelid==$lid){echo "selected=''";}?> ><?php echo $lv->level;?></option>
						<?php
								}
							}
						?>
						</select>
					</div>
					<div class="input-group mt-1 formP">
						<label class='input-group-prepend'>Course Title:</label>
						<input value="<?php if(isset($ctitle)){echo $ctitle; }?>"  type="text" id="ctitle" name="ctitle"  class="form-control" >
					</div>
					<div class="input-group mt-1 formP">
						<label class='input-group-prepend'>Course code:</label>
						<input value="<?php if(isset($ccode)){echo $ccode; }?>"  type="text" id="ccode" name="ccode"  class="form-control" >
					</div>
					<div class="input-group mt-1 formP">
						<label class='input-group-prepend'>Course Description:</label>
						<input value="<?php if(isset($cdesc)){echo $cdesc; }?>"  type="text" id="cdesc" name="cdesc"  class="form-control" >
					</div>
					<div class="input-group mt-1 formP" >
						<label class='input-group-prepend'>Credit Unit:</label>
						<input value="<?php if(isset($cunit)){echo $cunit;}?>"  onkeyup='(function(){if($("#cunit").val()>3 || $("#cunit").val()<1){$("#cunit").val("");}})()' type="number" max='3' min='1' id="cunit" name="cdesc"  class="form-control">
					</div>
					<div class="input-group mt-1 formP" >
						<label class='input-group-prepend'>Semester:</label>
						<select class="form-control" name="csemester" id="csemester">
							<option></option>
							<option value="1" <?php if(isset($csemester) && @$csemester==1){echo 'selected=""';}?>>First Semester</option>
							<option value="2" <?php if(isset($csemester) && @$csemester==2){echo 'selected=""';}?>>Second Semester</option>
						</select>
					</div>
					</div>
					<div style="width: 65% !important; margin-top: -10px; margin-bottom: 10px;">
					<?php
						if(isset($update)){
					?>
					<script>var checking='update';</script>
					<center>
						<div id="updateBtn">
							<button class="btn btn-success mt-1 p-1" style="width: 140px;" id="" onclick="saveBtn();">Update</button>
						</div>
					</center>
					<?php
						}else{
					?>
						<script>var checking='save';</script>
						<center>
							<button  class="btn btn-success mt-1 p-1" style="width: 140px;"  id="save" onclick="saveBtn();">Save
								</button>
						</center>
							<?php
						}
							?>
						</div>
					</div>
					</div>
			</div>		
		</div>
		<br>
	</div>
	@include('layouts/scripts')

<script>
	
$(".listS1").dataTable({
   "bPaginate": true,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": true,
    "iDisplayLength":6,
    "bAutoWidth": true
});
$('#departmentS').click(function(){
	$('#departmentS1').val('');
});
$('#departmentS').change(function(){
	$('#myform1').submit();
});
$('#departmentS1').change(function(){
	$('#myform1').submit();
});
$('#addsession').click(function(){
	Swal.mixin({
			input: 'text',
			confirmButtonText: 'Create',
			showCancelButton: true,
		}).queue([
			{
			title: 'Enter New Session'
			}]).then((result) => {
				if (result.value) {
					//console.log(;
					$.post('misc/create_session.php',{nsession:result.value[0]}, function(data){
						//alert(data);
							$("#loader").hide();
							if(data==0){
				              Swal.fire({
				                type: 'error',
				                title: 'no connect to server',
				                showConfirmButton: true,
				              }); 
				            }else if(data==1){
				              Swal.fire({
				                type: 'success',
				                title: 'Changed Successfully',
				                showConfirmButton: true
				              }).then((result) => {
				                location.reload();
				              });
				            }else if(data==2){
				                Swal.fire({
				                type: 'error',
				                title: 'session already exist',
				                showConfirmButton: true,
				              }); 
				            }else if(data==3){
				                Swal.fire({
				                type: 'error',
				                title: 'Invalid session entry',
				                showConfirmButton: true,
				              }); 
				            }
						});
					$("#loader").show();
					/*Swal.fire({
						title: 'successfully added',
						html: 'Your answers: <pre><code>' +  JSON.stringify(result.value) + '</code></pre>',
						confirmButtonText: 'Done!'
				    })*/
			}
			});
});

function unsetValues(){
			 $("#ctitle").val('');
			$("#ccode").val('');
			$("#cdesc").val('');
			$("#dept").val('');
			$("#level").val('');
			$("#cunit").val('');
			$("#csemester").val('');
							
			$("#updateBtn").html('<button  class="btn btn-success mt-1 p-1" style="width: 140px;"  id="save" onclick="saveBtn();">Save</button>');
}


function saveBtn(){
			var ctitle = $("#ctitle").val();
			var ccode = $("#ccode").val();
			var cdesc = $("#cdesc").val();
			var dept = $("#dept").val();
			var level = $("#level").val();
			var cunit = $("#cunit").val();
			var csemester = $("#csemester").val();

				if(ctitle.match(/[\'[¬`\|<>£\$%\^\+=]/g)){
					Swal.fire('','invalid character in course entry');
					return 0;
					//alert(email);
				}
				if(ccode.match(/[\'[¬`\|<>£\$%\^\+=]/g)){
					Swal.fire('','invalid character in course code entry');
					return 0;
				}

			if (dept ==''){
				Swal.fire('',"Department can't be empty ");
			}else if(level==''){
				Swal.fire('',"course title can't be empty ");
			}else if(level==''){
				Swal.fire('',"level can't be empty ");
			}else if(ccode==''){
				Swal.fire('',"course code can't be empty ");
			}else if(cunit==''){
				Swal.fire('',"course credit Unit can't be empty ");
			}else if(csemester==''){
				Swal.fire('',"course Semester can't be empty ");
			}else{
				if(checking == 'update'){
				 $.ajax({
                  type: 'POST',
                  url:  "{{route('cuourseCRUD')}}",
                  data: {id:<?php if(isset($selid)){echo $selid;}else{echo 'empty';}  ?>,cunit:cunit,ctitle:ctitle,ccode:ccode,cdesc:cdesc,dept:dept,level:level,csemester:csemester, _token:'{{ csrf_token() }}',type:-1 },
                  success: function(data){
                  $("#loader").hide();
                  console.log(data);
                      if(data.success==200){
                          Swal.fire({
				                type: 'success',
				                title: 'saved Successfully',
				                showConfirmButton: true
				              }).then((result) => {
				                location.reload();
				              });
                        }else if(data.success==201){
                        	Swal.fire({
				                type: 'error',
				                title: 'Course code already exist',
				                showConfirmButton: true,
				              }); 
                        }else{

				              Swal.fire({
				                type: 'error',
				                title: 'no connect to server',
				                showConfirmButton: true,
				              }); 
                        }
                    },
                    error: function(e){
                    	console.log(e);
                    }
                });
					$("#loader").show();
				}else{
					//alert(csemester);
					 $.ajax({
	                  type: 'POST',
	                  url:  "{{route('cuourseCRUD')}}",
	                  data: {cunit:cunit,ctitle:ctitle,ccode:ccode,cdesc:cdesc,dept:dept,level:level,csemester:csemester, _token:'{{ csrf_token() }}', type:1},
              		 success: function(data){
		                  $("#loader").hide();
		                  console.log(data);
		                      if(data.success==200){
		                          Swal.fire({
						                type: 'success',
						                title: 'saved Successfully',
						                showConfirmButton: true
						              }).then((result) => {
						                location.reload();
						              });
		                        }else if(data.success==201){
		                        	Swal.fire({
						                type: 'error',
						                title: 'Course code already exist',
						                showConfirmButton: true,
						              }); 
		                        }else{

						              Swal.fire({
						                type: 'error',
						                title: 'no connect to server',
						                showConfirmButton: true,
						              }); 
		                        }
		                    },
                    error: function(e){
                    	console.log(e);
                    }
                });
					$("#loader").show();
				}

			}

	
}
</script>
@if($path??''!= '')

<?php
	//echo $path ?? '';
	include('assets/spreadsheet-reader/php-excel-reader/excel_reader2.php');
	include('assets/spreadsheet-reader/SpreadsheetReader.php');
       
  	$allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
  
 
  	$success =0;
  	$error =0;
  	$FailedLines ='';
  	$existMatric ='';
  	$departmentID = $departmentuid;

    $targetPath = $path;
    $Reader = new SpreadsheetReader($targetPath);
        
    $sheetCount = count($Reader->sheets());
        $allData = array();
    for($i=0;$i<$sheetCount;$i++)
    {
            $Reader->ChangeSheet($i);
            $num = 0;
           foreach ($Reader as $Row)
            {
            	if($num== 0){
            	}else{
          		 $ctitle = "";
                if(isset($Row[0])) {
                   $ctitle = $Row[0];
                }
                $ccode = "";
                if(isset($Row[1])) {
                    $ccode = $Row[1];
                }
                $cdesc= "";
                if(isset($Row[2])) {
                	if ($Row[2] =='') {
                		$cdesc  ='nill';
                	}else{
                    	$cdesc = $Row[2];
                	}
                }
                
                $level = "";
                if(isset($Row[3])) {
                    $level = $Row[3];
                    if($level=='100'){ $level=1;}elseif($level=='200'){$level=2;}elseif($level=='300'){$level=3;}elseif($level=='400'){$level=4;}elseif($level==500){$level=5;}
                }
                $cunits= "";
                if(isset($Row[4])) {
                    $cunits = $Row[4];
                }
                $semesti= "";
                if(isset($Row[5])) {
                	if ($Row[5] == 1 || $Row[5]==2) {
                    	$semesti = $Row[5];
                	}
                }
                
                if (!empty($ctitle) AND !empty($ccode) AND !empty($level) AND !empty($cunits) AND !empty($semesti)){
                	$run = Course::where(['course_code'=> $ccode,'department_id'=>$departmentID])->get();
                	//$run = $conn->query("SELECT * FROM courses WHERE course_code='$ccode'");
 					if($run->count()<1){
 						//echo "string";
 						$allData[]=[
 								'course_title'=> $ctitle,
 								'course_code'=> $ccode,
 								'course_description'=>$cdesc,
 								'credit_unit'=>$cunits,
 								'level_id'=>$level,
 								'department_id'=>$departmentID,
 								'semester'=>$semesti
 						];
 						
					 }else{
					 	$error =1;
						$FailedLines .= '<p>Line '.$num.": Course Code already exist(".$ccode.")</p>,";
					 }
                }else{
                	if ($ctitle!='' AND $ccode !='' AND $level !='' AND $cunits!='' AND $semesti!='' AND $cdesc !=''){
                		$error =1;
                		if ($num!=0) {		
	                		$FailedLines .=  "<p> Line ".$num.": All fields must not be empty</p>,";  
	                	}
	                }
                }
            }
           		$num++;
          }
        
        }
         if($error <1){
          	$sendmsg= 'success';
          	\DB::table('courses')->insert($allData);
          }else{
          	$sendmsg = "<b>ISSUES ENCOUNTER</b>";
          	$sendmsg .= "<div style='font-family:arial; color:#a11; font-size:0.9em;' id='isu111'>";
          	$FailedL = explode(',', $FailedLines);
          	foreach($FailedL as $key => $val){ 
          	//$sendmsg .= " <span class='badge badge-dark text-white'> ".$val. "</span>";
          	$sendmsg .= $val;
            }
        	$sendmsg .= "</div>";
          }
             
             //return view('admin.manage_student', ['sendmsg'=>'$sendmsg']);
           //return  Redirect::route('manage-student', 'ManageStudentController@Redil');
//          return redirect()->back()->with(['msgsendi'=>$sendmsg]);
          ?>
          <form method="POST" action="{{route('studentCRUD')}}" id="formMsg1">
          	{{csrf_field()}}
          	<input type="text" name="sendmsg" value="<?php echo $sendmsg; ?>" style='display: none;'>
          	<input type="text" name="type" value="11" style='display: none;' >
          </form>
	<script type="text/javascript">
//		alert(<?php// echo $sendmsg;?>)
		document.getElementById('formMsg1').submit();
	</script>
@endif
@endsection	
