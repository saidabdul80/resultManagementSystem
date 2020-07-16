<table class="table table-condensed table-bordered table-hover " id="listS22">
						<thead class="">
							<th>Department</th>
							<th>Department Abbr</th>
							<th>Faculty</th>
							<th>action</th>
						</thead>
						<tbody id="tbodydep">
							<?php
								$DepartmentF = \App\Department::with('faculty')->get();
								
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
					<script type="text/javascript" src="/assets/js/dataTable/jquery.dataTables.min.js"></script>
					<script type="text/javascript">
						function tabledone(){
							
							$("#listS22").dataTable({
							   "bPaginate": true,
							    "bLengthChange": false,
							    "bFilter": false,
							    "bInfo": true,
							    "iDisplayLength":4,
							    "bAutoWidth": true
							});
						}
					
					</script>