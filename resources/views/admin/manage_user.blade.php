<?php

use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Setudent;
use \App\Department;
use \App\Faculty;
use \App\Role;

/*
//$conn = \DB::CONNECTION();
if(session('s_selected_id') != ''){
	$update = session('s_update');
	$selid  = session('s_selected_id');

	$users = Setudent::where('id',$selid)->get();;
	if($users->count() >0){
		foreach($users as $ur) {
			$first_name = $ur->first_name;
			$surname = $ur->surname;
			$other_namen = $ur->other_name;
			$lecturerIDn = $ur->lecturerID_number;
			$departmentID = $ur->department_id;
			$phone = $ur->phone;
			$email = $ur->email;
			$state = $ur->state_of_origin;
			$nkn = $ur->nxt_of_kin_name;
			$nka = $ur->nxt_of_kin_address;
			$nkp = $ur->nxt_of_kin_phone;
			$country = $ur->country;
			$address = $ur->address;
			$lga = $ur->lga;
		}
	}
}


*/
?>
@extends('layouts/master')


@section('content')
<script>var checking='save';</script>
<script>
	
	//validating email
	function isEmail(email) { 
    return /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(email);
} 

function saveBtn(){
			var lecturerID = $("#lecturerID").val();
			var first_name = $("#first_name").val();
			var surname = $("#surname").val();
			var deptID = $("#dept").val();
			var country = $("#country").val();
			var state = $("#state").val();
			var lga = $("#lga").val();
			var phone = $("#phone").val();
			var email = $("#email").val();
			var addr = $("#address").val();
			var nkn = $("#nkn").val();
			var nka = $("#nka").val();
			var nkp = $("#nkp").val();
			var salute = $("#salute").val();
			var upd = 1;
			
			
			lecturerID = lecturerID.replace(/[\[\]()?!\\\:;+=*&^\'%")(,#~`¬|£']/g,'');
			if (isEmail(email)){
				//escape invlid characters in email
				email = email.replace(/[\[\]()?!\\/\:;+=*&^\'%")(,#~`¬|£']/g,'');
				//alert(email);
			}else{
				Swal.fire('','invalid email');
				return 0;
			}
			if(phone.length<11){
				Swal.fire('','Phone numbers not complete');
				return 0;
			}
			
			if (lecturerID ==''){
				Swal.fire('',"lecturer ID can't be empty ");
			}else if(first_name==''){
				Swal.fire('',"first name can't be empty ");
			}else if(surname==''){
				Swal.fire('',"surname can't be empty ");
			}else if(deptID==''){
				Swal.fire('',"Please Select Department ");
			}else if(phone==''){
				Swal.fire('',"phone number can't be empty ");
			}else if(email==''){
				Swal.fire('',"email can't be empty ");
			}else{
				if(checking == 'update'){
				    var id = $('#lecturerid1').val();
					$.ajax({
						type: 'POST',
						url:  "{{route('lecturerCRUD')}}",
						data: {id:id,salute:salute,lecturerID:lecturerID,first_name:first_name,surname:surname,deptID:deptID,country:country,state:state,lga:lga,phone:phone,email:email,address:addr,nkn:nkn,nka:nka,nkp:nkp,type:-1, _token:'{{ csrf_token() }}' },
						success: function(data){
							$("#loader").hide();
							console.log(data);
							if(data.success==200){
								Swal.fire({
									type: 'success',
									title: 'Saved Successfully',
									showConfirmButton: true
								}).then((result) => {
									location.reload();
								});
							}else if(data.success==207){
								Swal.fire({
									type: 'error',
									title: 'Invalid chararcter entry',
									showConfirmButton: true,
								}); 
							}
							else{
								$('body').html(data);
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
					$.ajax({
						type: 'POST',
						url:  "{{route('lecturerCRUD')}}",
						data: {lecturerID:lecturerID,salute:salute,first_name:first_name,surname:surname,deptID:deptID,country:country,state:state,lga:lga,phone:phone,email:email,address:addr,nkn:nkn,nka:nka,nkp:nkp,type:1, _token:'{{ csrf_token() }}' },
						success: function(data){
							$("#loader").hide();
							console.log(data);
							if(data.success==200){
								Swal.fire({
									type: 'success',
									title: 'Saved Successfully',
									showConfirmButton: true
								}).then((result) => {
									location.reload();
								});
							}else if(data.success==201){
								Swal.fire({
									type: 'error',
									title: 'Lecturer ID  already exist',
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
				}

			}

	
}
	</script>
<style type="text/css">
		.paging_two_button {
        margin: 15px;
        position:relative !important;
        font-size: 0.8em;
        bottom: 5px !important;
       }
    .dataTables_info{
        position:relative;
        bottom: 0px !important ;
        margin:10px;
        font-size: 0.8em;
        color:#aaa;
       }
       .dataTables_filter>label{
       	font-size: 0.8em;
       }
	label{
		margin: 0px;
	}
	tr td{
		border:none !important;
		font-size: 0.8em !important;

	}
	
</style>
	<div id="titlebar">
		<div  id="title">Admin <span class="lnr lnr-chevron-right"></span><i> Manage Lecturers</i></div>

		<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
	</div>
	
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
		<!--CONTENT AREA START-->
				<div class="row innerContent mx-auto mt-5" style="">
		      <div class="col-xs-12 col-sm-6 col-md-5 col-lg-4 userList " style="height: 530px;">
       @include('/class/search')

    <?php
		if (session('departmentid')!='') {
       		$departmentid =session('departmentid');
           	$user_run = \App\Lecturer::with('department')->where('department_id',$departmentid)->get();
		          }else{
		          	  $user_run = \App\Lecturer::with('department')->get();
		          }

    ?>

        <table  class="listS" id="listt">
          <thead>
            <th></th>
          </thead>
          <tbody>
            @if($user_run != null)

            <?php
              $exist_role = array();
              $user_d = array();
             
            $i=-1;

             foreach($user_run as $row){
              $i++;
              	$id = $row->id;
                $uid = $row->email;
                $fname = $user_d[$i][] = ucfirst($row->first_name).' '.ucfirst($row->surname);
                $lectID = $row->lecture_ID;
                $user_d[$i] = array();
                $user_d[$i][] = $uid;
                $user_d[$i][] = ucfirst($row->first_name).' '.ucfirst($row->surname);
                $user_d[$i][] = $row->lecture_ID;
                
                
                ?>
                <tr>
                  
                  <td id="<?php echo 'list'.$id; ?>" ><span class="lnr  lnr-user s3"></span><span class="name">{{ $fname }} ({{ $lectID }})</span></td>
                </tr>
                  <script>
                    document.getElementById("<?php echo 'list'.$id; ?>").onclick = function(){
                    	var sid = <?php echo $id; ?>;
                    	var alluser = <?php echo json_encode($user_run); ?>;
                    	//console.log(alluser);
                    	for(i in alluser){
                    		if (alluser[i]['id']== sid ){
                    			var lecturer = alluser[i];
                    			$("#lecturerID").val(lecturer['lecture_ID']);
                    			document.getElementById('lecturerID').disabled = true;
                    			document.getElementById('email').disabled = true;
								$("#first_name").val(lecturer['first_name']);
								$("#surname").val(lecturer['surname']);
								//$("#department1").text(lecturer['department']['department']);
								$("#dept").val(lecturer['department']['id']);
								if (lecturer['country'] ==-1 || lecturer['country']=='nill' || lecturer['country']=='' ) {}else{
									$("#country").val(lecturer['country']).change();
									$("#state").val(lecturer['state']).change();
								}

								$("#lga").val(lecturer['lga']);
								$("#phone").val(lecturer['phone']);
								$("#email").val(lecturer['email']);
								$("#address").val(lecturer['address']);
								$("#nkn").val(lecturer['nkn']);
								$("#nka").val(lecturer['nka']);
								$("#nkp").val(lecturer['nkp']);
								$("#salute").val(lecturer['salute']);
								
								$('#inform2,#inform1').hide();
								$('#inform').slideDown();
								$('#editid').addClass('active');
								$('#editid').show();
								$('#strictid').show();
								$('#uppp,#adccc,#strictid').removeClass('active');
								
								$('#save').hide();
								$('#upbtn').show();
								checking = 'update';

                    			$("#lecturerid1").val(lecturer['id']);
                    			//$("#departmentid1").val(lecturer['department']['id']);
                    			$("#country11").val(lecturer['country']);
                    			//$("#state11").val(lecturer['state']);

                    		}

                    	}
                      	
                    }
                  </script>


                  <?php
                }
                ?>
            @else
              <tr><td>No Lecturer in the system</td></tr>
            @endif
        </tbody>
        </table>
        <?php
        //var_dump($roleArrayid);
        ?>
          
      </div>
      <br><br>
        		<script type="text/javascript" src="/assets/js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="/assets/js/dataTable/jquery.dataTables.min.js"></script>
        <script>
	
$(".listS").dataTable({
   "bPaginate": true,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": true,
    "iDisplayLength":5,
    "bAutoWidth": true
});
        </script>
          
			
			
				<div class="col-sm-1 col-md-1 col-xs-12 mt-3" style="" id="separatorAA"></div>
			<div class="col-xs-12 col-sm-5 col-md-6 col-lg-7  bg-white shadow h-100" style="font-size: 0.9em;" >
				<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm border input-group mt-1" >
					<!--navbar-->
				  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				    <span class="navbar-toggler-icon"></span>
				  </button>

				  <div class="collapse navbar-collapse" id="navbarSupportedContent" >
				    <ul class="navbar-nav mr-auto" style="padding: 1px">
				      <li id="adccc" style="font-size: 0.8em;" <?php if (isset($update)){echo "class='nav-item '";}else{echo "class='nav-item active'";}?>>
				        <a class="nav-link" onclick="(function(){clearInputA(); $('#inform2,#inform1').hide();$('#inform').slideDown();$('#adccc').addClass('active');$('#uppp,#editid,#strictid').removeClass('active');})()" >Add Account<span class="sr-only">(current)</span></a>
				      </li>
				      
					      <li class="nav-item active" id="editid" style="font-size: 0.8em; display: none;">
					        <a class="nav-link" onclick="(function(){$('#inform2,#inform1').hide();$('#inform').slideDown();$('#editid').addClass('active');$('#uppp,#adccc,#strictid').removeClass('active');})()">Edit Account</a>
					      </li>
					      <li class="nav-item" id="strictid" style="font-size: 0.8em; display: none">
					        <a class="nav-link" onclick="(function(){$('#inform2,#inform').hide();$('#inform1').slideDown(); $('#strictid').addClass('active');$('#editid,#adccc,#uppp').removeClass('active'); })();"  title="update id, email,password" tabindex="-1" aria-disabled="true">Strict Changes</a>
					      </li>
				      <li  style="font-size: 0.8em;" id="uppp">
				        <a style="cursor: pointer;" class="nav-link" onclick="(function(){$('#inform,#inform1').hide();$('#inform2').slideDown();$('#editid,#strictid,#adccc').removeClass('active');$('#uppp').addClass('active');})()" >Upload lecturers<span class="sr-only">(current)</span></a>
				      </li>
				    </ul>
				  </div>
				</nav>
				<div class="row mt-3" id="inform1" style="display: none;">				
							<ul style="" class="ul_li">
								<input type="text" id="lecturerid1" style="display: none;">
								<input type="text" id="country11" style="display: none;">
								<input type="text" id="state11" style="display: none;">
								<li id="st1">change lecturerID Number</li>
								<li id="st2">change email</li>
							</ul>
						
				</div>
				<div class="row mt-3" id="inform2" style="display: none;">	
					<form action="{{route('lecturerCRUD')}}" method="POST" id="" class="" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="ml-5" style="margin-top: 7px;">
							<div class="form-group my-3">
								<input type="text" name="type" value="10" style="display: none;">
							    <select  class="form-control" style="" id="departmentu" name="departmentu" required="" >
								   	<option value="" >Select Department</option>
								    <?php
									    
									    $Department = Department::get();
								    	foreach($Department as $r )
								        {
								        	echo '<option value="'.$r->id.'">'.$r->department.'</option>';
								        }
								    ?>
								  </select>
							</div>
						</div>
							<div class=" ml-5">	
					            <input type="file" name="file" id="file" accept=".xls,.xlsx" required="">				    
					         <br>                     
					         <br>                     
							<button type="submit" name="import" class="btn btn-primary ml-3">upload </button>
					        </div> 
					        <br>    

					</form>
				</div>
				<div class="row mt-3" id="inform" >	
					
						<div class="col-lg-6 col-md-6 ">
							<div class="form-group ">
								<div class="input-group mt-1 formP">
								<label class='input-group-prepend'>Mr./Mrs.</label>
									<select id="salute" class="form-control">
										<option value="Mr.">Mr.</option>
										<option value="Mrs.">Mrs.</option>
										<option value="Engr.">Engr.</option>
										<option value="Dr.">Dr.</option>
										<option value="Prof.">Prof.</option>
									</select>
									</div>
								<div class="input-group mt-1 formP">
									<label class='input-group-prepend'>Lecturer ID</label><input value="<?php if(isset($lecturerID)){echo $lecturerID; }?>" <?php if(isset($lecturerID)){echo "disabled";} ?>  type="text" id="lecturerID" name="lecturerID"  class="form-control" >
								</div>
								<div class="input-group mt-1 formP">
									<label class='input-group-prepend'>First Name:</label><input value="<?php if(isset($first_name)){echo $first_name;}?>" type="text" id="first_name" name="first_name" class="form-control">
								</div>
								<div class="input-group mt-1 formP">
									<label class='input-group-prepend'>Surname:</label><input value="<?php if(isset($surname)){echo $surname;} ?>" type="text" id="surname" name="surname" class="form-control">
								</div>
								<div class="input-group mt-1 formP">
									<label class='input-group-prepend'>Department</label>
									<select  id="dept" name="department" class="form-control">
										<option value="">select department</option>
										<?php
											
											if ($Department->count()>0) {
												foreach($Department as $dr) {
													$did = $dr->id;
													?>
													<option value="<?php echo $did;?>"  <?php if(isset($departmentID) && $departmentID==$did){echo "selected=''";}?> ><?php echo $dr->department;?></option>
													<?php
												}
											}
										?>
									
									</select>
								</div>
								<div class="input-group mt-1 formP">
									<label class='input-group-prepend'>Country:</label><select value="<?php echo $country??''; ?>"  id="country" name ="country"  class="form-control">
									</select>
								</div>								
									<div class="text-center text-secondary" id="country1">
									</div>
									
								<div class="input-group mt-1 formP">
										<label class='input-group-prepend'>State</label>
										<select value="" id="state" name ="state" class="form-control" style="width: 50% !important;">
										</select>
										<span class="tooltiptext" id="tooltip1">Please select Your Country</span>
								</div>
								<div class="text-center text-secondary" id="state1">
								</div>
								<script type="text/javascript" src="/assets/js/Country-Select-Box-Plugin/countries.js"></script>
								<script>
									populateCountries("country", "state"); // first parameter is id of country drop-down and second parameter is id of state drop-down
									
	
								</script>
								<div class="input-group mt-1 formP">
										<label class='input-group-prepend'>LGA:</label><input value="<?php if(isset($lga)){echo  $lga;}?>" type="text" id="lga" name="lga" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
								<div class="input-group mt-1 formP">
										<label class='input-group-prepend'>Address:</label><input value="<?php if(isset($address)){echo  $address;}?>" type="text" id="address" name="address" class="form-control">
								</div>
							<div class="input-group mt-1 formP">
								<label class='input-group-prepend'>Email</label>
								<input value="<?php if(isset($email)){echo $email;} ?>" <?php if(isset($email)){echo "disabled";} ?> type="text" id="email" name="email" class="input-group-append p-1 form-control">
							</div>
							<div class="input-group mt-1 formP">
									<label class='input-group-prepend' >Phone No.:</label><input value="<?php if(isset($phone)){echo  $phone;}?>" type="text" id="phone" name="phone" class="form-control">
								</div>
							<div class="input-group mt-1 formP">
									<label class='input-group-prepend'>Nxt Kin Name:</label><input value="<?php if(isset($nkn)){echo  $nkn;} ?>" type="text" id="nkn" name="nkn" class="form-control" >
							</div>
							<div class="input-group mt-1 formP">
									<label class='input-group-prepend'>Nxt Kin addr:</label><input value="<?php if(isset($nka)){echo  $nka;} ?>" type="text" id="nka" name="nka" class="form-control" >
							</div>
							<div class="input-group mt-1 formP">
									<label class='input-group-prepend'>Nxt Kin phone:</label><input value="<?php if(isset($nkp)){echo  $nkp;} ?>" type="text" id="nkp" name="nkp" class="form-control" >
							</div>
						</div>
						
						<div style="width: 65% !important; margin-top: -10px; margin-bottom: 10px;">
							

							<center>
								
							<button class="btn btn-success mt-1 p-1" style="width: 140px;display: none;" id="upbtn" onclick="saveBtn();">Update</button>
									<button  class="btn btn-success mt-1 p-1" style="width: 140px;"  id="save" onclick="saveBtn();">Save</span>
								</button>
							</center>

						</div>
					
					</div>
			</div>		
			<br><br><br>
				
			</div>		
		<!--CONTENT AREA END-->
	
@include('layouts/scripts')
<script>
	function clearInputA(){
		$("#lecturerID").val('');
		$("#first_name").val('');
		$("#surname").val('');
		$("#other_name").val('');
		//$("#department1").text('');
		$("#country1").text('');
		$("#state1").text('');
		$("#lga").val('');
		$("#phone").val('');
		$("#email").val('');
		$("#address").val('');
		$("#nkn").val('');
		$("#nka").val('');
		$("#nkp").val('');
		$('#salute').val('');
		$('#save').show();
		$('#upbtn').hide();
		$('#editid').hide();
		$('#strictid').hide();
		document.getElementById('lecturerID').disabled = false;
		document.getElementById('email').disabled = false;
		checking = 'save';
                    			
	}


		$("#state").click(function(){
			var country = $("#country").val();
			if(country == -1){
				$("#country").css("border", "1px solid red");
				$(".tooltiptext").fadeIn(900);
			}
		});
		$("#country").change(function(){
			$(this).css("border", "1px solid #bbb");
			$("#tooltip1").fadeOut(900);
		});

	$('#st1').click(function(){
		lecturerid = $('#lecturerid1').val();
		Swal.mixin({
			input: 'text',
			confirmButtonText: 'Change',
			showCancelButton: true,
		}).queue([
			{
			title: 'New lecturerID Number'
			}]).then((result) => {
					

				if (result.value) {
					//console.log(;
					$.ajax({
						type: 'POST',
						url:  "{{route('lecturerCRUD')}}",
						data: {id:lecturerid,nlecturerID:result.value[0],type:4, _token:'{{ csrf_token() }}' },
						success: function(data){
							$("#loader").hide();
							console.log(data);
							if(data.success==200){
								Swal.fire({
									type: 'success',
									title: 'Saved Successfully',
									showConfirmButton: true
								}).then((result) => {
									location.reload();
								});
							}else if(data.success==201){
								Swal.fire({
									type: 'warning',
									title: 'lecturerID number already exist',
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
	$('#st2').click(function(){
		lecturerid = $('#lecturerid1').val();
		Swal.mixin({
			input: 'email',
			confirmButtonText: 'Change',
			showCancelButton: true,
		}).queue([
			{
			title: 'Enter Correct Email'
			}]).then((result) => {
				if (result.value) {
					//console.log(;
					$.ajax({
						type: 'POST',
						url:  "{{route('lecturerCRUD')}}",
						data: {id:lecturerid,email:result.value[0],type:-4, _token:'{{ csrf_token() }}' },
						success: function(data){
							$("#loader").hide();
							console.log(data);
							if(data.success==200){
								Swal.fire({
									type: 'success',
									title: 'Saved Successfully',
									showConfirmButton: true
								}).then((result) => {
									location.reload();
								});
							}else if(data.success==201){
								Swal.fire({
									type: 'warning',
									title: 'email already exist',
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
  	$existlecturerID ='';
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
          		 $lecturerID = "";
                if(isset($Row[0])) {
                   $lecturerID = $Row[0];
                }
                $f_name = "";
                if(isset($Row[1])) {
                    $f_name = $Row[1];
                }
                $s_name = "";
                if(isset($Row[2])) {
                    $s_name = $Row[2];
                }
                $o_name = "";
                if(isset($Row[3])) {
                    $o_name = $Row[3];
                    if($o_name==''){ $o_name='nill';}
                }
                $email = "";
                if(isset($Row[4])) {
                    $email = $Row[4];
                }
                 $phone = "";
                if(isset($Row[5])) {
                    $phone = $Row[5];
                    if($phone==''){ $phone='nill';}
                }
                 $gender = "";
                if(isset($Row[6])) {
                    $gender = $Row[6];
                    if($gender==''){ $gender='nill';}
                }
                $country = "";
                if(isset($Row[7])) {
                    $country = $Row[7];
                    if($country==''){ $country='nill';}
                }
                $state = "";
                if(isset($Row[8])) {
                    $state = $Row[8];
                    if($state==''){ $state='nill';}
                }
                $lga = "";
                if(isset($Row[9])) {
                    $lga = $Row[9];
                    if($lga==''){ $lga='nill';}
                }
                $nkn = "";
                if(isset($Row[10])) {
                    $nkn = $Row[10];
                    if($nkn==''){ $nkn='nill';}
                }
                $nkp = "";
                if(isset($Row[11])) {
                    $nkp = $Row[11];
                    if($nkp==''){ $nkp='nill';}
                }
                $address = "";
                if(isset($Row[12])) {
                    $address = $Row[12];
                    if($address==''){ $address='nill';}
                }
                $level = "";
                if(isset($Row[13])) {
                    $level = $Row[13];
                    if($level=='100'){ $level=1;}elseif($level=='200'){$level=2;}elseif($level=='300'){$level=3;}elseif($level=='400'){$level=4;}elseif($level==500){$level=5;}
                }
                	
                if ($f_name !='' || $lecturerID != '' || $email !='' || $level != ''){
                	 $chk = \App\lecturer::where('lecturerID_number', $lecturerID)->get();
                	 //$chk = \App\lecturer::where('lecturerID_number', $lecturerID)->orWhere('email',$email)->get();
                	 $chk1 = \App\lecturer::where('email',$email)->get();
			         if ($chk->count()<1) {
			         	if ($chk1->count()<1) {
 						//echo "string";
 						$allData[]=[
 								'first_name'=> $f_name,
 								'surname'=> $s_name,
 								'other_name'=>$o_name,
 								'lecturerID_number'=>$lecturerID,
 								'gender'=>$gender,
 								'phone'=>$phone,
 								'email'=>$email,
 								'country'=>$country,
 								'state_of_origin'=>$state,
 								'lga'=>$lga,
 								'address'=>$address,
 								'nxt_of_kin_name'=>$nkn,
 								'nxt_of_kin_phone'=>$nkp,
 								'department_id'=>$departmentID,
 								'level_id'=>$level,
 								'status' => 0
 						];
	 					}else{
	 						//email exist
	 						$error =1;
						 	$existlecturerID .= $lecturerID.'';
						 	$FailedLines .= '<p>Line '.$num.": Email already exist(".$email.")</p>,";
	 					}
					 }else{
	                	$error =1;
					 	//$existlecturerID .= $lecturerID.'';
					 	$FailedLines .= "<p>Line ".$num.": lecturerID already exist(".$lecturerID.")</p>,";   
					 }
                }else{
                	if ($f_name !='' AND $lecturerID != '' AND $phone !='' AND $level != '' AND $f_name != '' AND $s_name != '' AND $o_name != '' AND $gender != ''){
	                	$error =1;
	                	if ($num!=0) {
	                		$FailedLines .=  "<p> Line ".$num.": FirstName and lecturerID and email and level fields cannot be empty</p>,";  
	                	}
                	}
                }
            }
           		$num++;
          }
        
        }
         if($error <1){
          	$sendmsg= 'success';
          	\DB::table('lecturers')->insert($allData);
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
             
             //return view('admin.manage_lecturer', ['sendmsg'=>'$sendmsg']);
           //return  Redirect::route('manage-lecturer', 'ManagelecturerController@Redil');
//          return redirect()->back()->with(['msgsendi'=>$sendmsg]);
          ?>
          <form method="POST" action="{{route('lecturerCRUD')}}" id="formMsg1">
          	{{csrf_field()}}
          	<input type="text" name="sendmsg" value="<?php echo $sendmsg; ?>" style='display: none;'>
          	<input type="text" name="type" value="11" style='display: none;' >
          </form>
	<script type="text/javascript">
//		alert(<?php// echo $sendmsg;?>)
		document.getElementById('formMsg1').submit();
	</script>
          <?php

            /* if($success== 1 AND $error == 1){
             }else if($success== 0 AND $error == 1){
             }else if($success== 1 || $success!= 1  AND $existlecturerID != '' || $FailedLines !=''){
             	?>
             		<script>
             				//document.getElementById('msgA1').style.display = 'none';
             				//document.getElementById('msgA2').style.display = 'block';
             				document.getElementById('innermsgA2').innerHTML ="<div style='font-family:arial;'><b>Issues encounter</b><p style='color:#a11;'>lecturers lecturerID Either exisit[not uploaded]: <?php $mattt = explode(',', $existlecturerID); foreach($mattt as $key => $val){ ?> <span class='badge badge-light'> <?php echo $val; ?></span> <?php } ?></p><b>Failed Lines: <?php echo implode('Line: ', explode(',', $FailedLines));?></b></div>";
             		</script>
             	<?php
             }else if($success == 1){
             	?>
             	<script>
             		Swal.fire('','Uploaded Successfully').then((result) => {
				        window.location = 'manage_lecturer.php';
				     });
				</script>
             	<?php
             }*/
 

?>
@endif
@endsection	
