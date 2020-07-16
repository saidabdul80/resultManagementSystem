<?php
use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Role;
$Departments = Department::all();
$Faculty = Department::all();
//use Illuminate\Routing\UrlGenerator
 $uri = explode('/',url()->current());
 $uri =  end($uri);

?>

 <label style="cursor: pointer;font-size: 0.8em;" id="advan" onclick="(function(){$('#myform1').slideToggle();})();">Advance>></label>
        <form action="{{ $uri ?? ''}}" style="width: 100%;" method="post" id="myform1"  <?php if(session('facultyname')!=''){ echo "class='advanceS'";}else{echo "style='display: none ;' class='advanceS'";}?> >
          {{ csrf_field() }}
          {{ method_field('PATCH') }}
      <select class="form-contro mr-1 mb-1" style="width: 100px !important;border: 1px solid #eee; font-size: 0.8em;clear: left;" name="selFaculty" id="selFaculty">
        <option value="">select session</option>
        <?php
        $Faculty = Faculty::with('department')->orderBy('faculty', 'ASC')->get();
            foreach ($Faculty as $faculty) {
              $idi = $faculty->id;$faculty = $faculty->faculty;
              ?>
              <option value="<?php echo $idi; ?>" <?php echo (session('facultyid') != '')? (session('facultyid')==$idi)?'selected':'':'' ;?>>{{$faculty}}</option>
              <?php
            }
          ?>
        </select>
      <div class="d-flex">
        <select class="form-control" style="width: 135px !important;border: 1px solid #eee;font-size: 0.8em;clear: " name="seldepartment" id="seldepartment">
        <option value=" ">select Semester</option>
         <?php
            if (session('departmentid')!='') {
            foreach ($Departments ?? '' as $departmt) {
              $didi = $departmt->id;$departmtname = $departmt->department_abbr;
              ?>
              <option value="<?php echo $didi; ?>" <?php echo (session('departmentid') != '')? (session('departmentid')==$didi)?'selected':'':'' ?>>{{$departmtname}}</option>
              <?php
            }
            }
         ?>

        </select><input type="submit" name="setsearchSessionA" value="go" class="btn btn-sm btn-light float-left">
      </div>
      
      <br>
    </form>
    <script type="text/javascript">
      var Faculty = <?php echo json_encode($Faculty); ?>;
      $(document).ready(function(){
        $('#selFaculty').change(function(){
          var facid = $(this).val();

          //get departments from selected faculty
          for(i in Faculty){
            if (Faculty[i]['id'] == facid){
              var departments = Faculty[i]['department'];
              break;
            }
          } 
          var optionD ='';
          var departmentIdFromSession  = <?php echo (session("departmentid") != "")? session("departmentid") :"-1"?>;
          for (var i = 0; i < departments.length; i++) {
            var did = departments[i]['id'];
             optionD += '<option value="'+did+'" ';
             if (departmentIdFromSession == did){
                optionD += ' selected >'+departments[i]['department_abbr'] +'</option>';
             }else{
                optionD +=  '>'+departments[i]['department_abbr'] +'</option>';
             }
          }
          $('#seldepartment').html(optionD);
          
        });
      });
    </script>