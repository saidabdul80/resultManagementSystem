<?php
	include '../php/dbconnect.php';
	$logged_in_user_department_id = $_SESSION['department_id'];
	$logged_in_usr_id = $_SESSION['user_id'];

	if (isset($_POST['selSemester'])) {
		$csemester = $_POST['selSemester'];
	}else{
		$csemester = $_SESSION['current_set_semester_id'];
	}

	if(isset($_POST['selSession'])){ 
		$selSession = $_POST['selSession'];
	}else{
		$selSession = $_SESSION['current_set_session_id'];
	}

	require('class/left_pane.php');
	
	//echo ;
?>
<!DOCTYPE html>
<html>
<head>
	<title>dashboard</title>
	<?php	require  '../php/css2.php';	?>
	 <style type="text/css">
   
   	.paging_two_button {
        margin: 15px;
        position:absolute;
        bottom: -60px;
       }
    .dataTables_info{
        position:absolute;
        bottom: -20px ;
        margin:10px;
        color:#aaa;
       }
       td{
       	padding: 2px !important;
       }
       td p,td{
       	margin: 1px;
       	font-size: 0.9em;
       }
  </style>
</head>
<body class="">
<?php
	include '../php/header2.php';
	include '../php/checklogin.php';
?>
	<div id="containerA" class="containerA">
		<div id="titlebar">
			<div  id="title">Examiner<span class="lnr lnr-chevron-right"></span><i> logs</i></div>

			<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
		</div>
		<!--CONTENT AREA START-->
		<div class="innerContent" style="padding-top: 15px;">
		<div class="row" style="margin-left: -3px;">

			<div class="col-lg-10 col-md-10  mx-auto">
				<table class="table table-bordered table-hover" id="listt">
					<thead>
						<th>Descriptioin</th>
						<th>Date</th>
					</thead>
					<tbody>
				<?php
					$sql_run = $conn->query("SELECT * FROM logs WHERE user_id='$logged_in_usr_id' AND type='examiner'");
					if ($sql_run->num_rows>0) {
						while ($row = $sql_run->fetch_assoc()) {
							?>
							<tr>
								<td><?php echo $row['description'];?></td>
								<td style="white-space: nowrap;"><?php echo $row['action_date'];?></td>
							</tr>
							<?php
						}
					}
				?>
			</tbody>
		</table>
			</div>
		</div>		
		</div>
	</div>
            
		<!--CONTENT AREA END-->
	</div>

</div>
	<footer><span style="">© 2020 saidabdul project</span> </footer>


<?php
  include '../php/js2.php';
?>
<script>
 $(document).ready(function(){

 $("#listt").dataTable({
   "bPaginate": true,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": true,
    "iDisplayLength":6,
    "bAutoWidth": true
});

 //$('<hr>').insertAfter('#listt_filter');
});


</script>
</body>
</html>