<?php
use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Role;

$Faculty = Faculty::get();
$Department = Department::get();

?>
@extends('layouts/master')
	<title>dashboard</title>
	<style>
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
	</style>

@section('content')

	<div id="titlebar">
			<div  id="title">Admin <span class="lnr lnr-chevron-right"></span><i> Manage Faculty/Department</i></div>

			<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
	</div>
		<!--CONTENT AREA START-->
	<div class="row innerContent" style="margin-left: -3px;">
			<div class="col-lg-5 col-md-5 col-sm-8 col-xs-8  bg-white shadow h-100 ml-5 boxInner" style="min-height:500px;">
				<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm border input-group mt-1" >
					<!--navbar-->
				  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				    <span class="navbar-toggler-icon"></span>
				  </button>

				  <div class="collapse navbar-collapse" id="navbarSupportedContent" >
				    <ul class="navbar-nav mr-auto" style="padding: 1px">
				      <li id="adccc" <?php if(isset($_POST['selFaculty'])){ echo 'class="nav-item"';}else{echo 'class="nav-item active"';}?>>
				        <a class="nav-link " href="{{route('manage-fadep')}}" id="manageF" onclick="(function(){$('#adccc').addClass('active');$('#editid').removeClass('active'); })();">Manage Faculty<span class="sr-only">(current)</span></a>
				      </li>
					  <li  id="editid" <?php if(isset($_POST['selFaculty'])){ echo 'class="nav-item active"';}else{echo 'class="nav-item"';}?>>
					    <a class="nav-link" onclick="(function(){$('#editid').addClass('active');$('#adccc').removeClass('active'); $('#facultyp').hide(); $('#departmentp').show(); })();"  title="update id, email,password" tabindex="-1" aria-disabled="true">Manage Department</a>
					  </li>
				    </ul>
				  </div>
				</nav>
				<br>
				<div id="facultyp">
					<button id="addfaculty" class="btn btn-primary" style="float: right; margin: 5px;"> ADD</button>
					<table class="table table-condensed table-bordered table-hover listS1">
						<thead class="">
							<th>Faculty</th>
							<th>Faculty Abbr</th>
							<th>action</th>
						</thead>
						<tbody>
							<?php
								
								if ($Faculty->count()>0) {
									foreach($Faculty as $rw) {
										$id = $rw->id;
										$use = $rw->status;
										$faculty = $rw->faculty;
										$abbr = $rw->faculty_abbr;
										?>
										<tr>
											<td class="pt-1" id="sedu<?php echo $id; ?>"><?php echo $faculty; ?></td>
											<td class="pt-1" id="sedu1<?php echo $id; ?>"><?php echo $abbr; ?></td>
											<td>
												<button id="sed<?php echo $id; ?>" class="btn btn-sm btn-primary" style="font-size: 0.8em;margin-top: 4px;">edit</button>
											</td>
										</tr>
											<script>

												$("#sed<?php echo $id; ?>").click(function(){
													document.getElementById("sedu<?php echo $id; ?>").innerHTML= "<input type='text' value='<?php echo $faculty;?>' id='enter<?php echo $id;?>'>";
													document.getElementById("sedu1<?php echo $id; ?>").innerHTML= "<input type='text' value='<?php echo $abbr;?>' id='enter1<?php echo $id;?>'>";
													$("#enter<?php echo $id; ?>,#enter1<?php echo $id; ?>").keyup(function(event){
														if(event.keyCode===13 || event.keyCode===88 ) {
															var vv = $('#enter<?php echo $id; ?>').val();
															var uu = $('#enter1<?php echo $id; ?>').val();
															if(vv !='' && uu !=''){
																$.ajax({
														        type: 'POST',
														        url:  "{{route('fadepCRUD')}}",
														        data: {faculty:vv,abbr:uu,id:<?php echo $id;?>,type:-1, _token:'{{ csrf_token() }}' },
														        success: function(data){
														      	$("#loader").hide();
														      	console.log(data);
														            if(data.success==200){
														                Swal.fire({
														                  type: 'success',
														                  title: 'Updated Successfully',
														                  showConfirmButton: true
														                }).then((result) => {
														                  location.reload();
														                });
														              }else if(data.success==201){
														              	 Swal.fire({
														                type: 'error',
														                title: 'faculty already exist',
														                showConfirmButton: true,
														              }); 
														              }else if(data.success==207){
														              	Swal.fire({
														                type: 'error',
														                title: 'Invalid chararcter entry',
														                showConfirmButton: true,
														              }); 
														              }
														              else{
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
															}else{ Swal.fire('field must not be empty')}
														}
													});
												});
											</script>

										<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>
				<div id="departmentp" style="display: none;">
					<div style="display: flex; justify-content: space-between;">
						<form action=""  method="post" id="myform1" style="width: 80%;">
						<select class="form-control formP" name="selFaculty" id="facultyS">
							<option value="">select faculty</option>
							<?php
							if(isset($_POST['selFaculty'])){ 
								$selFaculty =$_POST['selFaculty'];
								?>
								<script>$('#facultyp').hide(); $('#departmentp').show();</script>
								<?php
							}else{ $selFaculty='';}
							$FacultyWithDep = Faculty::with('department')->get();
							if ($FacultyWithDep->count()>0) {
									foreach($FacultyWithDep as $rwi) {
										$idi = $rwi->id;$usei = $rwi->status;$facultyi = $rwi->faculty;$abbri = $rwi->faculty_abbr;
										?>
										<option value="<?php echo $idi;?>" <?php if(isset($_POST['selFaculty'])){if($_POST['selFaculty']==$idi){echo 'selected';}} ?> ><?php echo $abbri; ?></option>
										<?php
									}
								}
							?>
							<script type="text/javascript">
								//console.log(<?php  //json_encode($FacultyWithDep); ?>);
								/*var FacultyWithDep = <?php //echo json_encode($FacultyWithDep); ?>;
								(function(){
									var optiond = '';
									document.getElementById('facultyS').onchange = function(){
										for(i in FacultyWithDep){
											if(FacultyWithDep[i]['id']==this.value){
												var departments = FacultyWithDep[i]['department'];
												for(j in departments){
													optiond += '<tr><td class="pt-1" id="dsedu'+departments["id"]+'">'+departments["department"]+'</td><td class="pt-1" id="dsedu1'+departments["id"]+'">'+departments["department_abbr"]+'</td><td><button id="dsed'+departments["id"]+'" class="btn btn-sm btn-primary" style="font-size: 0.8em;margin-top: 4px;">edit</button></td></tr>';
												}
												document.getElementById(tbodydep).innerHTML = optiond;
											}
										}
									// /this.value
									}
								})();*/
								
							</script>
						</select>
						<br>
						</form>
						<button id="adddepartment" class="btn btn-sm btn-primary" style="width: 50px;height: 40px;"> ADD</button>
					</div>
						<table class="table table-condensed table-bordered table-hover listS2">
						<thead class="">
							<th>Department</th>
							<th>Department Abbr</th>
							<th>Faculty</th>
							<th>action</th>
						</thead>
						<tbody id="tbodydep">
							<?php
								$DepartmentF = Department::with('faculty')->get();
								
								if ($DepartmentF->count()>0) {
									foreach($DepartmentF as $rw) {
										$did = $rw->id;
										$duse = $rw->status;
										$department = $rw->department;
										$dabbr = $rw->department_abbr;
										?>
										<tr>
											<td class="pt-1"  style="display: none;">
											<input   id="fac<?php echo $did; ?>" value='<?php echo $rw->faculty->id ?>'>
											</td>
											<td class="pt-1" style="width: 100px !important;" id="dsedu<?php echo $did; ?>"><?php echo $department; ?></td>
											<td class="pt-1" style="width: 100px !important;" id="dsedu1<?php echo $did; ?>"><?php echo $dabbr; ?></td>
											<td class="pt-1" id="dsedu2<?php echo $did; ?>"><?php echo $rw->faculty->faculty; ?></td>
											<td on>
												<button id="dsed<?php echo $did; ?>" class="btn btn-sm btn-primary" style="font-size: 0.8em;margin-top: 4px;">edit</button>
												<button id="dsedone<?php echo $did; ?>" class="btn btn-sm btn-success" style="font-size: 0.8em;margin-top: 4px;display: none;">Done</button>
											</td>
										</tr>
											<script>

												$("#dsed<?php echo $did; ?>").click(function(){
													$(this).hide();
													$("#dsedone<?php echo $did; ?>").show();
													document.getElementById("dsedu<?php echo $did; ?>").innerHTML= "<input style='width: 100px !important;'  type='text' value='<?php echo $department;?>' id='denter<?php echo $did;?>'>";
													document.getElementById("dsedu1<?php echo $did; ?>").innerHTML= "<input  style='width: 90px !important;' type='text' value='<?php echo $dabbr;?>' id='denter1<?php echo $did;?>'>";
													facsel1 = document.getElementById('fac<?php echo $did; ?>').value;
													var FacultyWithDep = <?php echo json_encode($FacultyWithDep)?>;
													console.log(FacultyWithDep);
													var optionf = '<select id="selFacForDep<?php echo $did; ?>" style="width: 90px !important;">';
													for(i in FacultyWithDep){
														optionf += '<option value="'+FacultyWithDep[i]['id']+'"';
														//console.log(facsel1);
														if (facsel1== FacultyWithDep[i]['id']){
															optionf += 'selected >'+FacultyWithDep[i]["faculty"]+'</option>';
														}else{
														 optionf += '>'+FacultyWithDep[i]['faculty']+'</option>';
														}
													}
													optionf += '</select>';
													document.getElementById("dsedu2<?php echo $did; ?>").innerHTML= optionf;
													$("#dsedone<?php echo $did; ?>").click(function(event){
															var dvv = $('#denter<?php echo $did; ?>').val();

															var duu = $('#denter1<?php echo $did; ?>').val();
															facsel = $('#selFacForDep<?php echo $did; ?>').val();
															if(dvv !='' && duu !=''){
																$.ajax({
														        type: 'POST',
														        url:  "{{route('fadepCRUD')}}",
														        data: {department:dvv,dabbr:duu,id:<?php echo $did;?>,fid:facsel,type:-2, _token:'{{ csrf_token() }}' },
														        success: function(data){
														      	$("#loader").hide();
														      	console.log(data);
														            if(data.success==200){
														                Swal.fire({
														                  type: 'success',
														                  title: 'Updated Successfully',
														                  showConfirmButton: true
														                }).then((result) => {
														                  location.reload();
														                });
														              }else if(data.success==201){
														              	 Swal.fire({
														                type: 'error',
														                title: 'Department already exist',
														                showConfirmButton: true,
														              }); 
														              }else if(data.success==207){
														              	Swal.fire({
														                type: 'error',
														                title: 'Invalid chararcter entry',
														                showConfirmButton: true,
														              }); 
														              }
														              else{
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
															}else{ Swal.fire('field must not be empty')}
														
													});
												});
											</script>

										<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!--CONTENT AREA END-->
	
@endsection	

@include('layouts/scripts')
<script>
$(".listS2").dataTable({
   "bPaginate": true,
    "bLengthChange": false,
    "bFilter": false,
    "bInfo": true,
    "iDisplayLength":6,
    "bAutoWidth": true
});
window.onload = function(){
	
$(".listS1").dataTable({
   "bPaginate": true,
    "bLengthChange": false,
    "bFilter": false,
    "bInfo": true,
    "iDisplayLength":6,
    "bAutoWidth": true
});
$("#facultyS").change(function(){
	$("#myform1").submit();
});
$('#addfaculty').click(function(){
		Swal.mixin({
			input: 'text',
			confirmButtonText: 'Next &rarr;',
			showCancelButton: true,
			progressSteps: ['1', '2']
		}).queue([
			{
			title: 'New Faculty'
			},
			'Abbreviation'
			]).then((result) => {
				if (result.value) {
					var resultV = JSON.stringify(result.value).replace(/[\[\]"]/g,'');
					console.log(resultV);
					if(result.value[0]==''){
						alert('faculty field cannot be empty');
						return 0;
					}
					//console.log(resultV);
					 $.ajax({
				        type: 'POST',
				        url:  "{{route('fadepCRUD')}}",
				        data: {nfaculty:resultV,type:1, _token:'{{ csrf_token() }}' },
				        success: function(data){
				      	$("#loader").hide();
				      	console.log(data);
				            if(data.success==200){
				                Swal.fire({
				                  type: 'success',
				                  title: 'Created Successfully',
				                  showConfirmButton: true
				                }).then((result) => {
				                 location.reload();
				                });
				              }else if(data.success==201){
				              	 Swal.fire({
				                type: 'error',
				                title: 'faculty already exist',
				                showConfirmButton: true,
				              }); 
				              }else if(data.success==207){
				              	Swal.fire({
				                type: 'error',
				                title: 'Invalid chararcter entry',
				                showConfirmButton: true,
				              }); 
				              }
				              else{
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
					/*Swal.fire({
						title: 'successfully added',
						html: 'Your answers: <pre><code>' +  JSON.stringify(result.value) + '</code></pre>',
						confirmButtonText: 'Done!'
				    })*/
			}
			});
});
$('#adddepartment').click(function(){
	var chkdepartment = $('#facultyS').val();
	if (chkdepartment!=''){


		Swal.mixin({
			input: 'text',
			confirmButtonText: 'Next &rarr;',
			showCancelButton: true,
			progressSteps: ['1', '2']
		}).queue([
			{
			title: 'New Department'
			},
			'Abbreviation'
			]).then((result) => {
				if (result.value) {
					var resultV = JSON.stringify(result.value).replace(/[\[\]"]/g,'');
					//console.log(resultV);
					if(result.value[0]==''){
						alert('Department field cannot be empty');
						return 0;
					}
					 $.ajax({
				        type: 'POST',
				        url:  "{{route('fadepCRUD')}}",
				        data: {ndepartment:resultV,fid:chkdepartment,type:2, _token:'{{ csrf_token() }}' },
				        success: function(data){
				      	$("#loader").hide();
				      	console.log(data);
				            if(data.success==200){
				                Swal.fire({
				                  type: 'success',
				                  title: 'Created Successfully',
				                  showConfirmButton: true
				                }).then((result) => {
				                 location.reload();
				                });
				              }else if(data.success==201){
				              	 Swal.fire({
				                type: 'error',
				                title: 'faculty already exist',
				                showConfirmButton: true,
				              }); 
				              }else if(data.success==207){
				              	Swal.fire({
				                type: 'error',
				                title: 'Invalid chararcter entry',
				                showConfirmButton: true,
				              }); 
				              }
				              else{
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
					/*Swal.fire({
						title: 'successfully added',
						html: 'Your answers: <pre><code>' +  JSON.stringify(result.value) + '</code></pre>',
						confirmButtonText: 'Done!'
				    })*/
			}
			});
	}else{
		Swal.fire('', 'select Faculty');
	}
});
}
</script>