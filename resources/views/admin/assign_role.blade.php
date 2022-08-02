<?php
use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Role;



if(session('facultyname')!=''){
  $Lecturers = Lecturer::with('user')->where('department_id',session('departmentid'))->get();
//  return dd($Lecturers);
}else{
  $Lecturers = Lecturer::with('user')->get();
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
		  <div class="innerContent mx-auto" id="app">
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
            <tr>
              <td v-for="lecturer in Lecturers" :key="'list'+lecturer.id" @click="listselected(lecturer)" ><span class="lnr  lnr-user s3"></span><span class="name">@{{ lecturer.fullname }} (@{{ lecturer.lecture_ID }})</span></td>
            </tr>
            @else
              <tr><td>No Lecturer in the system</td></tr>
            @endif
        </tbody>
        </table>
        <?php
        //var_dump($roleArrayid);
        ?>
          
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-7 badguy brandG px-3" id="app2">
        <!-- <div class="" style="width: 90%;font-size: 0.95em; margin:0px auto;"> -->
          <!-- style="display: flex;justify-content: space-between; width: 180px;" -->
          <div class="w-100 p-4 mx-3"  style="border:1px solid #eee; height:100%; padding:10px;box-shadow:1px 2px 3px #eee; border-radius:5px;" >

                    
            <table class="table w-100 text-left">
              <tr>
                <td class="w-25 bg-light text-success">
                  Staff Name:
                </td>
                <td>
                  @{{staffname}}
                </td>
              </tr>
            </table>          
            <table class="table w-100 text-left">
              <tr>
                <td class="w-25 bg-light text-success">
                  Staff ID:
                </td>
                <td>
                  @{{staffid}}
                </td>
              </tr>
            </table>          
            <table class="table w-100  text-left">
              <tr>
                <td class="w-25 bg-light text-success">
                  Staff Roles:
                </td>
                <td>
                  @{{staffrole}}
                </td>
              </tr>
          </table>                 
          <div style="clear: left; width: auto; display: flex; justify-content: space-between;"  class="brandGI w-100"  >
            <select style="color: black;" class="form-control w-75 " id="roleToAddId">
            	<option value="">Select Role</option>
               <?php
                  $roles = Role::where('status','=', 0)->get();
              
            
                ?> 
                    <option v-for="role in roles" :key="'role_'+role.id"  :value="role.id"> @{{role.role }} </option>                  
            </select>
            <button class="btn btnU brandGI w-25" id='assignId' @click="assignId()" style="width: 70px;">assign</button>
          </div>
          <input type="text" id="userId" value="" style="display: none;">
          <input type="text" id="userCurrentRoleId" value="" style="display: none;">
          <div class="brandGI w-100" style="display: flex;">
          <select class="form-control w-75" style="color: black;  display: inline;" class="" id="roleToremove">
             <option v-for="role in userRoles" :key="'role_'+role.id"  :value="role.id"> @{{role.role}} </option>       
          </select>
          <button class="btn btnU brandGI w-25" id='dassignId' @click="dassignId()" style="">de assign</button>
            
            </div>
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
 

  

//console.log();

  var app = new Vue({
            el: '#app',
            data: {
              roles: <?php echo json_encode($roles); ?>,
              Lecturers: <?php echo json_encode($Lecturers); ?>,
              userRoles:[],
              staffname:"",
              staffid:"",
              userCurrentRoleId:"",
              userId:"",
              staffrole:"",
              selectedUser:"",
              
            },
            methods:{
              userrole(){
                this.userRoles = this.Lecturers.filter((item)=>{
                  if(item.user_id == this.userId){
                    return item.roles;
                  }
                })              
              },
              listselected(lecturer){
                var sinfo = lecturer.id
                this.selectedUser = sinfo;
                var options ='';
                var roleDatas ='';
                
                lecturer.roles.forEach((item) => {
                  options += '<option value="'+item.id+','+item.role+'">'+item.role+'</option>';
                  
                });                
                document.getElementById('roleToremove').innerHTML = options;
                //alert(sinfo.length);
                  this.staffname = lecturer.fullname;
                  this.staffid = lecturer.lecture_ID;
                  this.userCurrentRoleId = lecturer.roles.map((item)=>{ return item.id });
                  this.userId = lecturer.user_id;
                  var rols = lecturer.roles.map((item)=>{ return item.role });                  
                  this.staffrole = rols.join(',');
                  this.userrole();
                  
              },
              assignId(){
                var uid = $('#userId').val(), role_ids="", $this = this;
                //alert(uid);
                if(this.selectedUser ==""){
                  Swal.fire("Select User");
                }else{
                  var newrid = $('#roleToAddId').val(),
                  rid="";
                  if (newrid==""){
                    Swal.fire("","Select Role");
                  }else{
                   // var userCurrentRoleIds1 = this.userCurrentRoleId.split(',');
                    //var oldroles = userCurrentRoleIds.replace(rid,'')
                    rolename = $("#roleToAddId option:selected").text();
                    //id = userCurrentRoleIds+','+newrid;                    
                  if(this.userCurrentRoleId.includes(Number(newrid))){
                      Swal.fire({
                          type: 'error',
                          title: 'role already exist on user',
                          showConfirmButton: true,
                      }); 
                  }else{
                    this.userCurrentRoleId.push(newrid);
                    role_ids = this.userCurrentRoleId.join(',');                    
                    //userCurrentRoleIds = userCurrentRoleIds.filter(e=>e! == rid)
                  $.ajax({
                    type: 'POST',
                    url:  "{{route('editUserRole')}}",
                    data: {rid:role_ids, uid:$this.userId, _token:'{{ csrf_token() }}' },
                    success: function(data){
                    $("#loader").hide();                    
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
                             // location.reload();
                             $this.staffrole += ','+rolename;
                             $this.userrole();
                             $this.Lecturers.map((item)=>{
                               if(item.user_id == $this.userId){
                                 item.roles.push({
                                   id: newrid,
                                   role: rolename
                                 });
                               }
                             })
                            });
                          }
                      }
                  });
                  $("#loader").show();
                  }
                }
              }
              },
              dassignId(){
                var uid = $('#userId').val()
                //alert(uid);
                var $this = this;
                if(this.userId ==""){
                  Swal.fire("Select User");
                }else{
                  var rid = $('#roleToremove').val();
                  var rname = $('#roleToremove option:selected').text();
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
                      if(result.value){
                        $this.userCurrentRoleId.splice($this.userCurrentRoleId.indexOf(rid), 1);                         
                        /* alert(oldroles);
                          return 0;*/
                        $.ajax({
                        type: 'POST',
                        url:  "{{route('editUserRole')}}",
                        data: {rid:$this.userCurrentRoleId.join(','),rname:rname, uid:$this.userId, _token:'{{ csrf_token() }}' },
                        success: function(data){
                        $("#loader").hide();
                          //console.log(data);
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
              }




            },
            created(){              
              var exist_role = [];            
              var  $this = this, role_ids = [];
              this.Lecturers.map((item, index) =>{
                
                fname = item.first_name.toUpperCase()+' '+item.surname.toUpperCase();
                item.fullname = fname;    

                role_ids  = item.user.role_id.split(',').map(Number);
                console.log(role_ids);
                item.roles = $this.roles.filter((item2) => {
                  return role_ids.includes(item2.id);
                });


              });
            }
  })

                 
              
 </script>
@endsection 