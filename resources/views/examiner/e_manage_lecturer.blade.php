

<?php
use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Role;
use \App\f_timing;
$current_session_id = \App\Session::where('c_set',1)->first()->id;
	$logged_in_usr_id = Auth::user()->id;
  $session = \App\Session::where('c_set',1)->first();
  $semester = \App\Semester::where('c_set',1)->first();
  $isLecturer = Lecturer::where('email', Auth::user()->email)->first();
  //dd($isLecturer->department_id);
  $date = date('Y-m-d');
  if (!is_null($isLecturer)) {
    $user_department_id = $isLecturer->department_id;
  
    $Lecturer_Id = $isLecturer->lecture_ID;
    $faculty_id = Department::where('id', $user_department_id )->first()->faculty_id;
    # code...
  }else{
    $faculty_id = 1;
  }
  //echo $date;
  //preparing this for left_pane.php
  if (isset($_POST['selSemester'])) {
    $csemester = $_POST['selSemester'];
    $csemestername = ($csemester==1)?'first semester':'second semester';
  }else{
    $csemester = $semester->id;
    $csemestername = $semester->semester;
  }

  if(isset($_POST['selSession'])){ 
    $selSession1 =  explode(';',$_POST['selSession']);
    $selSession = $selSession1[0];
    $sessionname = $selSession1[1];
  }else{
    $selSession = $session->id;
    $sessionname = $session->session;
  }

  
?>
@extends('layouts/master')
  <style type="text/css">
   
       #listt_paginate{
        margin: 15px;
        position: absolute !important;
        bottom: 0 !important;
       }
       table {
        margin: auto !important;
       }
  </style>

@section('content')
		<div id="titlebar">
			<div  id="title">Examiner<span class="lnr lnr-chevron-right"></span><i>Manage Lecturer</i></div>

			<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
		</div>

    <div class="row innerContent mx-auto mt-5" >
    <div id="Out"></div>
      <div class="col-xs-12 col-sm-6 col-md-5 col-lg-4 userList" style="height: 450px; width: 100%;">
        <table  class="listS" id="listt">
          <thead>
            <th></th>
          </thead>
          <tbody>
          <?php
            /*
            $user_run = $conn->query("SELECT *, u.id as uid FROM lecturers AS l INNER JOIN users AS u ON u.username=l.email WHERE l.status='0' AND l.department_id ='$user_department_id'");*/
            $user_run = Lecturer::where('department_id',$user_department_id)->get();
              $exist_role = array();
              $user_d = array();
             // echo mysqli_error($conn);
            if ($user_run->count()>0) {
              $i = 0;
              foreach($user_run as $row){
                $id = $row->id;
                $uid = $row->email;
                $fname = $user_d[$i][] = ucfirst($row->first_name).' '.ucfirst($row->surname);
                $lectID = $row->lecture_ID;
                ?>
                <tr>
                  
                  <td id="<?php echo 'list'.$id; ?>" ><span class="lnr  lnr-user s3"></span><span class="name"><?php echo $fname;?>(<?php echo $lectID;?>)</span></td>
                </tr>
                  <script>
                    (function(){
                       document.getElementById("<?php echo 'list'.$id; ?>").onclick = function(){

                          $.ajax({
                            type: 'POST',
                            url:  "{{route('e-managesession')}}",
                            data: {allocourse:'allocate',fname:"<?php echo $fname; ?>", lectID:'<?php echo $lectID; ?>', id:<?php echo $id;?>,type:1, _token:'{{ csrf_token() }}' },
                            success: function(data){
                              $("#loader").hide();
                              location.reload();
                              },
                              error: function(data){
                                console.log(data);
                              }
                          });                    
                        $('#loader').show();
                    }
                    })();
                  </script>

                <?php
                $i++;

              }
            }
          ?>
        </tbody>
        </table>
          
      </div>
      <div class="col-sm-1 col-md-1 col-xs-12 mt-3" style="" id="separatorAA"></div>
      <div class="col-xs-12 col-sm-5 col-md-6 col-lg-7 bg-white shadow h-100 row" style="margin-left: auto; margin-right: auto; border:1px solid #eee; padding: 5px; border-radius: 5px; box-shadow: 1px 5px 4px #ccc;">
            <?php
                if(session('allocourse') !=''){
                  $lec_id = session('sel_lect_id');
                  $lecID1 = session('lectID');
                  $l_fname = session('fname');
                      //$role = $conn->query("SELECT * FROM courses WHERE semester='$csemester'  ORDER BY course_code ASC");
                      $role = \App\Course::where('semester',$csemester)->get();
                  ?>
              
              <input type="text" id="sectionId" value="<?php echo $lec_id;?>" style='display: none;'>
              <div class="col-6"> 
              <label>Select courses to allocate to</label>
              <div class="badge badge-primary px-4" style=""><?php echo $l_fname;?></div>
              <p style="font-size: 0.9em; color: #ccc;">hold control key to select multiple courses</p>             
               <select multiple="multiple"  style="margin-left: auto; margin-right: auto; color: black; width: 100%;height: 250px !important; border-radius: 6px !important;" class="form-control" id="subjectToAddId">
                <option></option>
                  <?php
                      foreach($role as $rol){
                        ?>
                          <option value="<?php echo  $rol['id']; ?>" style='cursor: pointer;'><?php echo $rol->course_code; ?></option>
                        <?php
                      }
                  ?>
                </select>
                <br>
                <center>
                  <span class="lnr lnr-"></span>
                    <button class="btn btnU" id='addSub' style="width: 90px;">add</button>
                </center>
                </div>
                <!--Start remove subject from section-->
              <div class="col-6" style="border-left: 10px solid #eee;box-shadow: -3px 1px 3px #ccc; ">
                  <label>Current Allocated courses</label>
                <div style="height: 300px;  border-radius: 2px; margin-top: 35px;">
                  <select multiple="multiple" style="color: black; width: 100%;margin-left: auto; margin-right: auto; height: 260px !important; border-radius: 6px !important;" class="form-control" id="subjectIdToRemove">
                    <option></option>
                    <?php
                        /*$get_course_allocated = $conn->query("SELECT *,l.id as lid FROM lecturer_allocated_courses AS l INNER JOIN courses as c ON l.course_id=c.id INNER JOIN sessions as s ON c_set=1 WHERE lecturer_id='$lec_id' AND l.session_id=s.id");*/
                        $get_course_allocated = DB::table('lecturer_allocated_courses', 'l')->select('l.id as lid')
                                                ->join('courses', 'courses.id','=','l.course_id')->addSelect('courses.course_code')
                                                ->where(['l.session_id'=> $current_session_id,'l.lecturer_id'=>$lec_id])->get();
                        ?>
                        <script>
                          console.log(<?php echo json_encode($get_course_allocated);?>);
                        </script>
                        <?php
                        if($get_course_allocated->count()>0){
                          foreach($get_course_allocated as $row){
                            $clid = $row->lid;
                            $course_code = $row->course_code;
                            ?>
                             <option value="<?php echo  $clid; ?>" style='cursor: pointer;'><?php echo ucwords($course_code); ?></option>
                            <?php
                          }
                        }   
                    ?>
                  </select>
                  <br>
                  <center>
                      <button class="btn btnU" id='removebtn' style="">remove</button>
                  </center>
                </div>
              </div>
              <!--End remove subject from section-->
              <?php
                }else{
              ?>


              
        <div class="brandG" style="width: 400px;">
          <div style="">
            Select Lecturer
          </div>
        </div>
        <?php
        }
      ?>
        </div>
        
      </div>
            
@include('layouts/scripts')

<script>
  $("#listt").dataTable({
   "bPaginate": true,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": true,
    "iDisplayLength":5,
    "bAutoWidth": true
});
  $('#addSub').click(function(){
    var sel_courses = $('#subjectToAddId').val();
    var lec_id = $('#sectionId').val();
    if(lec_id ==""){
      Swal.fire("",'select lecturer');
    }else if(sel_courses=='' || sel_courses==null){
      Swal.fire("Select courses");
    }else{
      $.ajax({
        type: 'POST',
        url:  "{{route('e-managesession')}}",
        data: {subArr:sel_courses,lec_id:lec_id,dept:<?php echo $user_department_id;?>,session_id: <?php echo $current_session_id; ?>,type:2, _token:'{{ csrf_token() }}' },
        success: function(data){
           console.log(data);
          $("#loader").hide();
          if(data.success==202){
              Swal.fire({
                type: 'warning',
                title: data.exists + 'rejected: Already exists',
                showConfirmButton: true,

              }).then((result) => {
                 window.location="{{route('e_manage_lecturer')}}";
              }); 
            }else if(data.success==200){
              Swal.fire({
                type: 'success',
                title: 'Allocated successfully',
                showConfirmButton: true
              }).then((result) => {
                 window.location="{{route('e_manage_lecturer')}}";
              });
            }else if(data.success==201){
              Swal.fire({
                type: 'error',
                title: data.exists + 'Already exists',
                showConfirmButton: true
              });
            }
        },
        error: function(data){
          console.log(data);
        }
        });          
     
      $("#loader").show();
    }
  });
   $('#removebtn').click(function(){
    var sel_coursesr = $('#subjectIdToRemove').val();
    var lec_id = $('#sectionId').val();
    if(lec_id ==""){
      Swal.fire("",'select lecturer');
    }else if(sel_coursesr=='' || sel_coursesr==null){
      Swal.fire("Select courses");
    }else{
      $.ajax({
        type: 'POST',
        url:  "{{route('e-managesession')}}",
        data: {subArr:sel_coursesr,lec_id:lec_id,dept:<?php echo $user_department_id;?>,session_id: <?php echo $current_session_id; ?>,type:-2, _token:'{{ csrf_token() }}' },
        success: function(data){
          $("#loader").hide();
          if(data.success==200){
              Swal.fire({
                type: 'success',
                title: 'removed successfully',
                showConfirmButton: true,

              }).then((result) => {
                 window.location="{{route('e_manage_lecturer')}}";
              }); 
            }else{
              console.log(data);
            }
        },
        error: function(data){
          console.log(data);
        }
        });   

      
      $("#loader").show();
    }
  });
</script>
@endsection

