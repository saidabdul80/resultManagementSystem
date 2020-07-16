<?php
use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Role;



if(session('facultyname')!=''){
  $Lecturers = Lecturer::where('department_id',session('departmentid'))->get();
//  return dd($Lecturers);
}else{
  $Lecturers = Lecturer::get();
}

$Departments = Department::all();
$Faculty = Department::all();
/*
function departmentidR($id){
  return   (session("departmentid")==$id)?"selected":"":"";
}*/
?>

@extends('layouts/master')

@section('content')

  <style type="text/css">
   
       #listt_paginate{
        margin: 15px;
        position: absolute !important;
        bottom: 10 !important;
       }
  </style>

		<div id="titlebar">
			<div  id="title">Admin <span class="lnr lnr-chevron-right"></span><i> Assign Role</i></div>

			<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
		</div>
		<!--CONTENT AREA START-->
		  <div class="innerContent mx-auto" style="">
        <div class="row">
    
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 userList" style="height: 530px;">
       @include('/class/search')
    <?php

    ?>

        <table  class="listS" id="listt">
          <thead>
            <th></th>
          </thead>
          <tbody>
            @if($Lecturers != null)

            <?php
              $exist_role = array();
              $user_d = array();
             
            $i=-1;

             foreach($Lecturers as $row){
              $i++;
              	$id = $row->id;
                $uid = $row->email;
                $fname = $user_d[$i][] = ucfirst($row->first_name).' '.ucfirst($row->surname);
                $lectID = $row->lecture_ID;
                $user_d[$i] = array();
                $user_d[$i][] = $uid;
                $user_d[$i][] = ucfirst($row->first_name).' '.ucfirst($row->surname);
                $user_d[$i][] = $row->lecture_ID;
                
                //get lecturer from user table to get role ids
                $userforRole  = User::where('email', '=', $uid)->first();

                $role_ids = array_map('intval', explode(',', $userforRole->role_id)) ;
                $userRolesData = Role::whereIn('id',$role_ids)->get();
                $rolename ='';
                $roleId ='';
                $roleArrayname =array();
                $roleArrayid =array();

                foreach ($userRolesData as $roledat) {
                  $roleArrayname[]= $roledat->role;
                  $roleArrayid[]= $roledat->id;
                  $rolename .= $roledat->role.', ';
                  $roleId .= $roledat->id.', ';
                }

                $rolename = rtrim(trim($rolename),',');
                $roleId =rtrim(trim($roleId), ',');
                ?>
                <tr>
                  
                  <td id="<?php echo 'list'.$id; ?>" ><span class="lnr  lnr-user s3"></span><span class="name">{{ $fname }} ({{ $lectID }})</span></td>
                </tr>
                  <script>
                    document.getElementById("{{ 'list'.$id }}").onclick = function(){
                      var sinfo = <?php echo json_encode($user_d[$i]);?>;
                      var roleDataname = <?php echo json_encode($roleArrayname);?>;
                      var roleDataid = <?php echo htmlentities(json_encode($roleArrayid));?>;
                      var rolename = '<?php echo $rolename; ?>';
                      var roleid = '<?php echo $roleId ;?>';
//                     console.log(rolename);
                      var options ='';
                      var roleDatas ='';

                      for (var i = 0; i < roleDataname.length; i++) {
                        options += '<option value="'+roleDataid[i]+','+roleDataname[i]+'">'+roleDataname[i]+'</option>';
                      }
                      document.getElementById('roleToremove').innerHTML = options;
                      //alert(sinfo.length);
                        document.getElementById('staffname').innerHTML = sinfo[1];
                        document.getElementById('staffid').innerHTML = sinfo[2];
                        document.getElementById('userCurrentRoleId').value = roleDataid;
                        document.getElementById('userId').value = sinfo[0];
                        document.getElementById('staffrole').innerHTML = rolename;

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
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-7 badguy brandG">
        <!-- <div class="" style="width: 90%;font-size: 0.95em; margin:0px auto;"> -->
          <!-- style="display: flex;justify-content: space-between; width: 180px;" -->
          <div style="width: auto;">
            <p class="PbrandG">Staff Name:</p>
            <p id='staffname' style="">Name</p>
          </div>
          <div style="clear: left;width: auto;">
            <p>Staff Id:</p>
            <p id='staffid' style="">1002</p>
          </div>
          <div style="clear: left; width: auto;">
            <p>Role:</p>
            <p id='staffrole' style=""></p>
          </div>
          <div style="clear: left; width: auto; display: flex; justify-content: space-between; width: 200px;"  class="brandGI"  >
            <select style="color: black; width: 180px; display: inline;" class="form-control brandGI " id="roleToAddId">
            	<option value="">Select Role</option>
              <?php
                  $roles = Role::where('status','=', 0)->get();
              
                  foreach($roles as $role){
                ?>
                    <option value="{{ $role->id }}"> {{ $role->role }} </option>
                  <?php
                  }
                  
              ?>
            </select>
            <button class="btn btnU brandGI" id='assignId' style="width: 70px;">assign</button>
          </div>
          <input type="text" id="userId" value="" style="display: none;">
          <input type="text" id="userCurrentRoleId" value="" style="display: none;">
          <div class="brandGI" style="display: flex; justify-content: space-between; width: 200px;">
          <select class="brandGI" style="color: black;  display: inline;width: 180px;" class="form-control" id="roleToremove">
          </select><button class="btn btnU brandGI" id='dassignId' style="width: 70px;">de assign</button>
            
            </div>
          <!-- </div> -->
        </div>
        </div>
      </div>
            


@include('layouts/scripts')

<script type="text/javascript">
  /*
	$(function() {
      $("#list").JPaging();
    });
        function searchList() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("search52");
    filter = input.value.toUpperCase();
    ul = document.getElementById("list");
    li = ul.getElementsByTagName("li");
   // divs=li[0].getElementsByClassName("parent-div");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByClassName("name")[0];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}*/
$(".listS").dataTable({
   "bPaginate": true,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": false,
    "iDisplayLength":6,
    "bAutoWidth": true
});
  $('#assignId').click(function(){
    var uid = $('#userId').val()
    //alert(uid);
    if(uid ==""){
      Swal.fire("Select User");
    }else{
      var rid = $('#roleToAddId').val();
      if (rid==""){
      	Swal.fire("","Select Role");
      }else{
        var userCurrentRoleIds = $('#userCurrentRoleId').val();
        userCurrentRoleIds1 = userCurrentRoleIds.split(',');
        //var oldroles = userCurrentRoleIds.replace(rid,'')
        rid = userCurrentRoleIds+','+rid;
      if(userCurrentRoleIds.includes(rid)){
          Swal.fire({
              type: 'error',
              title: 'role already exist on user',
              showConfirmButton: true,
          }); 
      }else{
        //userCurrentRoleIds = userCurrentRoleIds.filter(e=>e! == rid)
      $.ajax({
        type: 'POST',
        url:  "{{route('editUserRole')}}",
        data: {rid:rid, uid:uid, _token:'{{ csrf_token() }}' },
        success: function(data){
      	$("#loader").hide();
      	console.log(data);
            if(data.success!=200){
                Swal.fire({
                  type: 'error',
                  title: 'no connect to server',
                  showConfirmButton: true,
                }); 
              }else{
                Swal.fire({
                  type: 'success',
                  title: 'Role Assign Successfully',
                  showConfirmButton: true
                }).then((result) => {
                  location.reload();
                });
              }
          }
      });
      $("#loader").show();
      }
    }
  }
  });

  
    $('#dassignId').click(function(){
    var uid = $('#userId').val()
    //alert(uid);
    if(uid ==""){
      Swal.fire("Select User");
    }else{
      var roll = $('#roleToremove').val().split(',');
      var rid =  roll[0];
      var rname = roll[1];
      if (rid==""){
        Swal.fire("","Select Role");
      }else if(rid==5){
        Swal.fire("","Role can not be Dessign");
      }else{
        //alert(rid);
        //return 0;
        Swal.fire({
                text: 'Are you sure you want to deasign '+rname+' role',
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
              }).then((result) => {  
                if (result.value){
                   var userCurrentRoleIds = $('#userCurrentRoleId').val();
                    //userCurrentRoleIds1 = userCurrentRoleIds.split(',');
                    var oldroles = userCurrentRoleIds.replace(rid,'');
                    oldroles = oldroles.replace(/,+$/, '');
                    if (oldroles.indexOf(',')==0){
                      oldroles = oldroles.replace(',', '');
                    }
                    oldroles = oldroles.replace(',,', ',');
                   /* alert(oldroles);
                    return 0;*/
                  $.ajax({
                  type: 'POST',
                  url:  "{{route('editUserRole')}}",
                  data: {rid:oldroles,rname:rname, uid:uid, _token:'{{ csrf_token() }}' },
                  success: function(data){
                  $("#loader").hide();
                  console.log(data);
                      if(data.success!=200){
                          Swal.fire({
                            type: 'error',
                            title: 'no connect to server',
                            showConfirmButton: true,
                          }); 
                        }else{
                          Swal.fire({
                            type: 'success',
                            title: 'Role Dessigned Successfully',
                            showConfirmButton: true
                          }).then((result) => {
                            location.reload();
                          });
                        }
                    }
                });
                $("#loader").show();
                }else{}
            });
         }
      }
  });
</script>
@endsection 