
<?php
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Grade;

	
	if(session('grade_selected_id')!=''){
 	$update = session('grade_update');
	$selid  = session('grade_selected_id');
	$users = Grade::where('id', $selid)->get();
	//$conn->query("SELECT * FROM grades WHERE id ='$selid'");
	
	if($users->count()>0){
		foreach($users as $ur) {
			$gid = $ur->id;
			$gname = $ur->name;
			$aa = $ur->A;
			$bb = $ur->B;
			$cc = $ur->C;
			$dd = $ur->D;
			$ee = $ur->E;
			$ff = $ur->F;
			$co = $ur->CO;
			$gstate = $ur->status;
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
@extends('layouts/master')
@section('content')
<style>
	.paging_two_button {
        margin: 15px;
        position:absolute !important;
        font-size: 0.8em;
        bottom: 5px !important;
       }
    .dataTables_info{
        position:absolute;
        bottom: 36px ;
        margin:10px;
        font-size: 0.8em;
        color:#aaa;
       }

       .dist .formP input{
       	width: 60px !important;
       	height: 30px !important;
       	font-size: 0.9em !important;
       }
       .dist .formP label{
       	margin-bottom: 1px !important;
       	font-size: 0.9em !important;
       }
	</style>
<script type="text/javascript" src="/assets/js/jquery-1.10.2.js"></script>

	<div id="titlebar">
		<div  id="title">Admin <span class="lnr lnr-chevron-right"></span><i> Configuration</i></div>

		<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
	</div>
	<div class="innerContent" style="padding-top: 15px;">
		<div class="row " style="margin-left: -3px;">

		<div class="col-lg-3 col-md-4 userList" style="height: 76vh;padding-bottom: 10px;">
				
        <table  class="listS" style="width: 100%;">
          <thead>
            <th></th>
          </thead>
          <tbody>
            
          <?php
			//fetch all students
         
            $Grades = Grade::get();
            //$conn->query("SELECT *, id as cid FROM grades");

              //$exist_role = array();
              $user_d = array();
             // echo mysqli_error($conn);
            if ($Grades->count() > 0) {
              $i = 0;
              foreach ($Grades as $row) {
              	$id = $row->id;
                $uid = $row->name;
                //$matric = $row['matric_number'];
                ?>
                  <tr>
                  
                  <td id="<?php echo 'list'.$id; ?>"><span class="icofont-book-alt s3"></span><span class="name"><?php echo ucwords($uid);?></span></td>
                </tr>
                  <script>
                    document.getElementById("<?php echo 'list'.$id; ?>").onclick = function(){
                      	// continue by posting id to misc and set $update to a global variable then reload page for aplying
                      	$.ajax({
                 			 type: 'POST',
                  			 url:  "{{ route('selectGradeScale') }}",
                  			 data: {update:'update', id:<?php echo $id;?>, type:2, _token:'{{ csrf_token() }}'},
                  			 success: function(data){
	                      		$('#loader').hide();
	                      		location.reload();
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
        			<script type="text/javascript" src="/assets/js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="/assets/js/dataTable/jquery.dataTables.min.js"></script>
        <script>
	
$(".listS").dataTable({
   "bPaginate": true,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": true,
    "iDisplayLength":6,
    "bAutoWidth": true
});
        </script>
          
			</div>	
			<div class="col-lg-8 col-md-7  bg-white shadow h-100 ml-5">
				<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm border input-group mt-1 mb-2" >
					<!--navbar-->
				  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				    <span class="navbar-toggler-icon"></span>
				  </button>

				  <div class="collapse navbar-collapse" id="navbarSupportedContent" >
				    <ul class="navbar-nav mr-auto" style="padding: 1px">
				      <li id="adccc" style="font-size: 0.8em;" <?php if (isset($update)){echo "class='nav-item '";}else{echo "class='nav-item active'";}?>>
				        <a class="nav-link" onclick="(function(){$.post('{{ route('selectGradeScale') }}',{update:'update',id:'id',type:-2,  _token:'{{ csrf_token() }}'},function(data){$('#loader').hide();location.reload();});$('#loader').show();})()" >Create New Grades<span class="sr-only">(current)</span></a>
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
				    </ul>
				  </div>
				</nav>
				<div class="row mb-2">

					{{$namess ?? ''}}
					<?php
					if (session('current_set_grade_id') != '' && isset($selid)) {
						$csgid = session('current_set_grade_id');
						if (session('current_set_grade_id')==@$selid) {
							?>
								<div style="font-size: 0.8em;" class="p-2 w-100 rounded text-lignt alert-success"> current set grading</div>
							<?php
						}else{
							?>
							<div style="margin-left: 15px;width: 90%; border-radius: 5px; margin-top: 10px;padding: 5px; font-size: 0.8em;">
								<span class="btn btn-dark btn-sm  toggle-off" onclick="setGrade('<?php echo @$csgid; ?>','<?php echo @$gid; ?>','<?php echo @$gname; ?>');" title="click to set as current grading" style=""> set <i class="lnr lnr-enter-down"></i></span>
								<i style="color:#888;"> <span class="icofont-hand-left"></span> click to set as current grading</i>
							</div>
							<?php
						}
					}else{
						?>
						<script>
							$(document).ready(function(){

							//	Swal.fire('unable to retrieve previous grading');
							});
						</script>
						<?php
					}
					?>
				</div>
			
				<div class="row" id="inform"  style="font-size: 0.9em;" >	
					<div class="col-lg-8 col-md-8 dist">
					<div class="input-group mt-1 formP">
						<label class='input-group-prepend'>Grading Name:</label>
						<input value="<?php if(isset($gname)){echo $gname; }?>" type="text" id="gname" name="gname"  class="form-control" >
					</div>
					<div class="input-group mt-1 formP">
						<label class='input-group-prepend'>A:</label>
						<input value="<?php if(isset($aa)){echo $aa; }?>" <?php if(@$gstate==1){echo "disabled";}?>  type="number" id="aa" name="aa"  class="" >
					</div>
					<div class="input-group mt-1 formP">
						<label class='input-group-prepend'>B:</label>
						<input value="<?php if(isset($bb)){echo $bb; }?>" <?php if(@$gstate==1){echo "disabled";}?>  type="number" id="bb" name="bb"  class="" >
					</div>
					<div class="input-group mt-1 formP">
						<label class='input-group-prepend'>C:</label>
						<input value="<?php if(isset($cc)){echo $cc; }?>" <?php if(@$gstate==1){echo "disabled";}?>  type="number" id="cc" name="cc"  class="" >
					</div>
					<div class="input-group mt-1 formP">
						<label class='input-group-prepend'>D:</label>
						<input value="<?php if(isset($dd)){echo $dd; }?>" <?php if(@$gstate==1){echo "disabled";}?>  type="number" id="dd" name="dd"  class="" >
					</div>
					<div class="input-group mt-1 formP">
						<label class='input-group-prepend'>E:</label>
						<input value="<?php if(isset($ee)){echo $ee; }?>" <?php if(@$gstate==1){echo "disabled";}?>  type="number" id="ee" name="ee"  class="" >
					</div>
					<div class="input-group mt-1 formP">
						<label class='input-group-prepend'>F:</label>
						<input value="<?php if(isset($ff)){echo $ff; }?>" <?php if(@$gstate==1){echo "disabled";}?>  type="number" id="ff" name="ff"  class="" >
					</div>
					<div class="input-group mt-1 formP">
						<label class='input-group-prepend'>C/O:</label>
						<input value="<?php if(isset($co)){echo $co; }?>" <?php if(@$gstate==1){echo "disabled";}?>  type="text" id="co" name="co"  class="" >
					</div>

					</div>
					<div style="width: 65% !important; margin-top: -10px; margin-bottom: 10px;">
						<br>
					<?php
						if(isset($update)){
					?>
					<script>var checking='update';</script>
					<center>
						<button class="btn btn-success mt-1 p-1" style="width: 140px;" id="" onclick="saveBtn();">Update</button>
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
//Swal.fire(checking);
function saveBtn(){
	var gname = $("#gname").val();
	var aa = $("#aa").val();
	var bb = $("#bb").val();
	var cc = $("#cc").val();
	var dd = $("#dd").val();
	var ee = $("#ee").val();
	var ff = $("#ff").val();
	var co = $("#co").val();
	if (aa.match(/[\'[¬`\|<>£\$%\^\+=]/g)){
		Swal.fire('','invalid character in A entry');
		return 0;
	}else if(bb.match(/[\'[¬`\|<>£\$%\^\+=]/g)){
		Swal.fire('','invalid character in B code entry');
		return 0;
	}
	else if(cc.match(/[\'[¬`\|<>£\$%\^\+=]/g)){
		Swal.fire('','invalid character in C code entry');
		return 0;
	}else if(dd.match(/[\'[¬`\|<>£\$%\^\+=]/g)){
		Swal.fire('','invalid character in D code entry');
		return 0;
	}else if(ee.match(/[\'[¬`\|<>£\$%\^\+=]/g)){
		Swal.fire('','invalid character in E code entry');
		return 0;
	}else if(ff.match(/[\'[¬`\|<>£\$%\^\+=]/g)){
		Swal.fire('','invalid character in F code entry');
		return 0;
	}
	else if(ff.match(/[\'[¬`\|<>£\$%\^\+=]/g)){
		Swal.fire('','invalid character in F code entry');
		return 0;
	}else if(co.match(/[\'[¬`\|<>£\$%\^\+=]/g)){
		Swal.fire('','invalid character in C/O name code entry');
		return 0;
	}			

	if (gname ==''){
		Swal.fire('',"grading Name can't be empty ");
	}else if(aa==''){ 
		Swal.fire('',"A can't be empty ");
	}else if(bb==''){
		Swal.fire('',"B can't be empty ");
	}else if(cc==''){
		Swal.fire('',"C code can't be empty ");
	}else if(dd==''){
		Swal.fire('',"D code can't be empty ");
	}else if(ee==''){
		Swal.fire('',"E code can't be empty ");
	}else if(ff==''){
		Swal.fire('',"F code can't be empty ");
	}else if(co==''){
		Swal.fire('',"C/O code can't be empty ");
	}else{
		if(checking == 'update'){
			$.ajax({
				//headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              	type: 'POST',
                url:  "{{ route('selectGradeScale') }}",
                data: {id:'<?php echo @$selid; ?>',_token:'{{ csrf_token() }}',aa:aa,bb:bb,cc:cc,dd:dd,ee:ee,ff:ff,co:co,gname:gname, type:-1},
                success: function(data){
					//alert(1);
	                $('#loader').hide();
	                //$('body').html(data);
	                //location.reload();
              		console.log(data);
					$("#loader").hide();
					if(data.success==200){
				        Swal.fire({
				        	type: 'success',
				            title: 'created Successfully',
				            showConfirmButton: true
				        }).then((result) => {
				           location.reload();
				        });
				    }else if(data.success==201){
				        Swal.fire({
				        type: 'error',
				        title: 'Grade Name already exist',
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
				 	$.ajax({
                 		type: 'POST',
                  		url:  "{{ route('selectGradeScale') }}",
                  		data: {_token:'{{ csrf_token() }}',aa:aa,bb:bb,cc:cc,dd:dd,ee:ee,ff:ff,co:co,gname:gname, type:1},
                  		success: function(data){
	                    	$('#loader').hide();
	                      	//location.reload();
              				//alert(data);
              				if(data.success==200){
						        Swal.fire({
						        	type: 'success',
						            title: 'created Successfully',
						            showConfirmButton: true
						        }).then((result) => {
						           location.reload();
						        });
						    }else if(data.success==201){
						        Swal.fire({
						        type: 'error',
						        title: 'Grade Name already exist',
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

@endsection 
<script type="text/javascript">
	function setGrade(csgid, gid,gnm) {
		$.ajax({
				//headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url:  "{{ route('selectGradeScale') }}",
        data: {ogid:csgid,csgid:gid,gnm:gnm,_token:'{{ csrf_token() }}', type:3},
        success: function(data){
		$('#loader').hide();
		if(data.success==200){
			Swal.fire({
				type: 'success',
				title: 'set Successfully',
				showConfirmButton: true
			}).then((result) => {
				location.reload();
			});
		}else{
			console.log(data);
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
$('#loader').show();
	}
</script>


<!-- 
	<script>var checking='save';</script> -->

