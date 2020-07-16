<?php
use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Semester;

$csemster = Semester::where('c_set', 1)->first();
$csession = Session::where('c_set', 1)->first();

?>

@extends('layouts/master')

@section('content')
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
		<div id="titlebar">
			<div  id="title">Admin <span class="lnr lnr-chevron-right"></span><i> Manage Session</i></div>

			<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
		</div>
		<!--CONTENT AREA START-->
		<div class="row innerContent" style="margin-left: -3px;">
			<div class="col-lg-5  col-sm-8 col-xs-8  bg-white shadow h-100 ml-5 boxInner" style="min-height:500px;">
				<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm border input-group mt-1" >
					<!--navbar-->
				  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				    <span class="navbar-toggler-icon"></span>
				  </button>

				  <div class="collapse navbar-collapse" id="navbarSupportedContent" >
				    <ul class="navbar-nav mr-auto" style="padding: 1px">
				      <li id="adccc" class="nav-item">
				        <a class="nav-link" id="addsession" onclick="(function(){$('#adccc').addClass('active');$('#editid').removeClass('active'); })();">Add Session<span class="sr-only">(current)</span></a>
				      </li>
					  <li class="nav-item active" id="editid" >
					    <a class="nav-link" onclick="(function(){$('#editid').addClass('active');$('#adccc').removeClass('active'); })();"  title="update id, email,password" tabindex="-1" aria-disabled="true">Edit Session</a>
					  </li>
				    </ul>
				  </div>
				</nav>
				<?php
				//fetch current set semester from db
					$setSemester = $csemster->id;
				?>
				<input type="radio" name="semester" id='semester1' value="1" <?php if($setSemester==1){echo 'checked=""';} ?>>first Semester
				<input type="radio" name="semester" id='semester2' value="2" <?php if($setSemester==2){echo 'checked=""';} ?>>second Semester
				<br>
				<table class="table table-condensed table-bordered table-hover listS1">
					<thead class="">
						<tr >
						<th >session</th>
						<th >action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$session = Session::get();
							if ($session->count()>0) {
								foreach($session as $rw) {
									$id = $rw->id;
									$use = $rw->status;
									$sessionn = $rw->session;
									?>
									<tr>
										<td style="width: 200px;" class="pt-1" id="sedu<?php echo $id; ?>"><?php echo $rw['session']; ?></td>
										<td style="width: 100px;">
											<button id="sed<?php echo $id; ?>" class="btn btn-sm btn-primary" style="font-size: 0.8em;margin-top: 4px;">edit</button>
											<button id="sedc<?php echo $id; ?>" class="btn btn-sm btn-success" style="font-size: 0.8em;margin-top: 4px; display: none;">done</button>
											<button id="setc<?php echo $id; ?>" class="btn btn-sm btn-warning text-white" style="font-size: 0.8em;margin-top: 4px;">set</button>

										</td>
									</tr>
										<script>
											//set session
											$('#setc<?php echo $id; ?>').click(function(){
												if (<?php echo $csession->id ;?>==<?php echo $id;?>){
													Swal.fire('session is already set');
													return 0;
												}
												Swal.fire({
														title:'Setting new current Session',
														text: 'Are you sure you want to continue',
														showConfirmButton: true,
														showCancelButton:true
													}).then((result)=>{
														if (result.value){
															///alert(result.value);
															$.ajax({
													        type: 'POST',
													        url:  "{{route('sessionCRUD')}}",
													        data: {new_session_id:<?php echo $id;?>, old_session_id:<?php echo $csession->id;?>,type:3, _token:'{{ csrf_token() }}' },
													        success: function(data){
													      	$("#loader").hide();
													      	console.log(data);
													            if(data.success==200){
													                Swal.fire({
													                  type: 'success',
													                  title: 'Set Successfully',
													                  showConfirmButton: true
													                }).then((result) => {
													                 location.reload();
													                });
													              }else{
													                Swal.fire({
													                  type: 'error',
													                  title: 'something went wrong',
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
													});
											})


											$("#sed<?php echo $id; ?>").click(function(){
												$(this).hide();
												$("#sedc<?php echo $id; ?>").show();
												document.getElementById("sedu<?php echo $id; ?>").innerHTML= "<input type='text' value='<?php echo $sessionn;?>' id='enters<?php echo $id;?>'>";
											});
											$("#sedc<?php echo $id; ?>").click(function(){
												var editedval = $("#enters<?php echo $id; ?>").val();
//													if(event.keyCode===13) {
													if(editedval !=''){
														$.ajax({
													        type: 'POST',
													        url:  "{{route('sessionCRUD')}}",
													        data: {session:editedval,id:<?php echo $id;?>,type:-1, _token:'{{ csrf_token() }}' },
													        success: function(data){
													      	$("#loader").hide();
													      	console.log(data);
													            if(data.success==200){
													                Swal.fire({
													                  type: 'success',
													                  title: 'Set Successfully',
													                  showConfirmButton: true
													                }).then((result) => {
													                 location.reload();
													                });
													              }else if(data.success==207){
													                Swal.fire({
													                  type: 'warning',
													                  title: 'Invalid entry',
													                  showConfirmButton: true,
													                }); 
													              }else if(data.success==201){
													              	Swal.fire({
													                  type: 'warning',
													                  title: 'Already exist',
													                  showConfirmButton: true,
													                }); 
													              }else{
													              	Swal.fire({
													                  type: 'error',
													                  title: 'something went wrong',
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
											
										</script>

									<?php
								}
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<!--CONTENT AREA END-->
	
@endsection	
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
$(document).ready(function(){
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
						$.ajax({
							type: 'POST',
							url:  "{{route('sessionCRUD')}}",
							data: {session:result.value[0],type:1, _token:'{{ csrf_token() }}' },
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
								}else if(data.success==207){
									Swal.fire({
										type: 'warning',
										title: 'Invalid entry',
										showConfirmButton: true,
									}); 
								}else if(data.success==201){
									Swal.fire({
										type: 'warning',
										title: 'Already exist',
										showConfirmButton: true,
									}); 
								}else{
									Swal.fire({
										type: 'error',
										title: 'something went wrong',
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
		var currentSemesterId = <?php echo $csemster->id;?>;
	$('#semester1').click(function(){
		if (currentSemesterId ==1){
			Swal.fire('semester already set');
			return 0;
		}
		$.post('misc/setsmester.php',{id:1,name:'first semester'}, function(data){
			$("#loader").hide();

			if(data==1){
					Swal.fire({
					type: 'success',
					title: 'Changed Successfully',
					showConfirmButton: true
					}).then((result) => {
						location.reload();
					});
			}else if(data==0){
				 Swal.fire({
					type: 'error',
					title: 'no connect to server',
					showConfirmButton: true,
				}); 
			}else{
				Swal.fire('Erro Line 274: please contact the higher administrator');
			}
		});
		$("#loader").show();
	});
	$('#semester2').click(function(){
		if (currentSemesterId==2){
			Swal.fire('semester already set');
			return 0;
		}
		$.post('misc/setsmester.php',{id:2,name:'second semester'}, function(data){
		//alert(data);
			$("#loader").hide();
			if(data==1){
					Swal.fire({
					type: 'success',
					title: 'Changed Successfully',
					showConfirmButton: true
					}).then((result) => {
					  location.reload();
					});
			}else if(data==0){
				 Swal.fire({
					type: 'error',
					title: 'no connect to server',
					showConfirmButton: true,
				}); 
			}else{
				Swal.fire('Erro Line 274: please contact the higher administrator');
			}
		});
		$("#loader").show();
	});
});
</script>