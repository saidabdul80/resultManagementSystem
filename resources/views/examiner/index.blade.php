<?php
use \App\User;
use \App\Lecturer;
use \App\Session;
use \App\Department;
use \App\Faculty;
use \App\Role;

$userEmail = Auth::user()->email;
$Lecturer = Lecturer::where('email', $userEmail)->first();
$department = $Lecturer->department_id;

    //$Trend = $conn->query("SELECT r.passFail,s.session,r.semesters FROM result_trend as r INNER JOIN sessions as s ON s.id=r.session WHERE r.department ='$department' AND r.level=1;");
	$Trend = DB::table('result_trend')
             ->join('sessions', 'sessions.id', '=','result_trend.session')
             ->where(['result_trend.department'=>$department, 'result_trend.level_id'=>1])->get();
	//echo mysqli_error($conn);
	$AVGP100 = array(0);
	$AVGF100 = array(0);
	$S100    = array(0);
	if($Trend->count()>0) {
		$AVGP100 = array();
		$AVGF100 = array();
		$S100    = array();
		foreach($Trend as $rw1) {
			$ex = explode(',', $rw1->passFail);
			$AVGP100[] = $ex[0];			
			$AVGF100[] = $ex[1];
			$sm = ($rw1->semesters==1)? '1st': '2nd';
			$S100[] = $sm.' '.$rw1->session;			
		}
	}
	
	//200
	//$Trend2 = $conn->query("SELECT r.passFail,s.session,r.semesters FROM result_trend as r INNER JOIN sessions as s ON s.id=r.session WHERE r.department ='$department' AND r.level=2;");
    $Trend2 = DB::table('result_trend')
             ->join('sessions', 'sessions.id', '=','result_trend.session')
             ->where(['result_trend.department'=>$department, 'result_trend.level_id'=>2])->get();
	//echo mysqli_error($conn);
	$AVGP200 = array(0);
	$AVGF200 = array(0);
	$S200    = array(0);
	if($Trend2->count()>0) {
		$AVGP200 = array();
		$AVGF200 = array();
		$S200    = array();
		while($rw2 = $Trend->fetch_assoc()) {
			$ex = explode(',', $rw2->passFail);
			$AVGP200[] = $ex[0];			
			$AVGF200[] = $ex[1];
			$sm = ($rw1->semesters==1)? '1st': '2nd';
			$S200[] = $sm.' '.$rw2->session;			
		}
	}
	  
		//300
    $Trend3 = DB::table('result_trend')
             ->join('sessions', 'sessions.id', '=','result_trend.session')
             ->where(['result_trend.department'=>$department, 'result_trend.level_id'=>3])->get();
             /*
	$Trend3 = $conn->query("SELECT r.passFail,s.session,r.semesters FROM result_trend as r INNER JOIN sessions as s ON s.id=r.session WHERE r.department ='$department' AND r.level=3;");*/
	//echo mysqli_error($conn);
	$AVGP300 = array(0);
	$AVGF300 = array(0);
	$S300    = array(0);
	if($Trend3->count()>0) {
		$AVGP300 = array();
		$AVGF300 = array();
		$S300    = array();
		while($rw3 = $Trend->fetch_assoc()) {
			$ex = explode(',', $rw3->passFail);
			$AVGP300[] = $ex[0];			
			$AVGF300[] = $ex[1];
			$sm = ($rw1->semesters==1)? '1st': '2nd';
			$S300[] = $sm.' '.$rw3->session;			
		}
	}

	//400
    $Trend4 = DB::table('result_trend')
             ->join('sessions', 'sessions.id', '=','result_trend.session')
             ->where(['result_trend.department'=>$department, 'result_trend.level_id'=>4])->get();
	/*$Trend4 = $conn->query("SELECT r.passFail,s.session,r.semesters FROM result_trend as r INNER JOIN sessions as s ON s.id=r.session WHERE r.department ='$department' AND r.level=4;");*/
	//echo mysqli_error($conn);
	$AVGP400 = array(0);
	$AVGF400 = array(0);
	$S400    = array(0);
	if($Trend4->count()>0) {
		$AVGP400 = array();
		$AVGF400 = array();
		$S400    = array();
		while($rw4 = $Trend->fetch_assoc()) {
			$ex = explode(',', $rw4->passFail);
			$AVGP400[] = $ex[0];			
			$AVGF400[] = $ex[1];
			$sm = ($rw1->semesters==1)? '1st': '2nd';
			$S400[] = $sm.' '.$rw4->session;			
		}
	}

				//500
    $Trend5 = DB::table('result_trend')
             ->join('sessions', 'sessions.id', '=','result_trend.session')
             ->where(['result_trend.department'=>$department, 'result_trend.level_id'=>5])->get();
	/*$Trend5 = $conn->query("SELECT r.passFail,s.session,r.semesters FROM result_trend as r INNER JOIN sessions as s ON s.id=r.session WHERE r.department ='$department' AND r.level=5;");
	echo mysqli_error($conn);*/
	$AVGP500 = array(0);
	$AVGF500 = array(0);
	$S500    = array(0);
	if($Trend5->count()>0) {
		$AVGP500 = array();
		$AVGF500 = array();
		$S500    = array();
		while($rw5 = $Trend->fetch_assoc()) {
			$ex = explode(',', $rw5->passFail);
			$AVGP500[] = $ex[0];			
			$AVGF500[] = $ex[1];
			$sm = ($rw1['semesters']==1)? '1st': '2nd';
			$S500[] = $sm.' '.$rw5->session;			
		}
	}
?>
@extends('layouts/master')

@section('content')
	<div id="containerA" class="containerA">
		<div id="titlebar">
			<div  id="title">Examiner<span class="lnr lnr-chevron-right"></span><i>Dashboard</i></div>

			<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
	</div>
		<!--CONTENT AREA START-->
			<center>
				<h1 class="h4 text-secondary">Result Trend</h1>
			</center>
			<div style="width: 97%; border: 1px solid #ccc;padding: 5px;  border-radius: 8px; margin: 0px auto; overflow-y: scroll; max-height: 540px; ">
				<div id="chart100" style="height: 300px;background: #efefef;">
					
				</div>
				<hr>
				<div id="chart200" style="height: 300px;">
					
				</div>
				<hr>
				<div id="chart300" style="height: 300px;">
					
				</div>
				<hr>
				<div id="chart400" style="height: 300px;">
					
				</div>
				<hr>
				<div id="chart500" style="height: 300px;">
					
				</div>
			</div>
		<!--CONTENT AREA END-->
	</div>

</div>
	


@include('layouts/scripts')
<script type="text/javascript">
	window.onload = function(){

/*100 level chart*/
 Highcharts.chart('chart100', {
        chart: {
            type: 'line'
        },
        title: {
            text: '100 Level'
        },

        subtitle: {
            text: '(Linear Scale)'
        },

        xAxis: {
            categories: <?php echo str_replace('\\', '', json_encode($S100));?>,
            categories: <?php echo str_replace('\\', '', json_encode($S100));?>},

        yAxis: {
            title: {
                text: 'Total Average Pass/Fail Count'
            }


        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        credits: {
            enabled: false
        },


        series: [{
            name: 'Average Pass',
            color: 'rgb(100,200,210)',
            
            lineWidth: 5,
            data: <?php echo str_replace('"', '', json_encode($AVGP100)); ?>      
        },
        	{
            name: 'Average Fail',
            color: 'rgb(200,4,30)',
            lineWidth: 5,
            data: <?php echo str_replace('"', '', json_encode($AVGF100)); ?>       
        }
        ],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 800
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

    });
/*100 level chart end*/

/*200 level chart*/
 Highcharts.chart('chart200', {
        chart: {
            type: 'line'
        },
        title: {
            text: '200 Level'
        },

        subtitle: {
            text: '(Linear Scale)'
        },

        xAxis: {
            categories: <?php echo str_replace('\\', '', json_encode($S200));?>,
            categories: <?php echo str_replace('\\', '', json_encode($S200));?>},
        yAxis: {
            title: {
                text: 'Total Average Pass/Fail Count'
            }


        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        credits: {
            enabled: false
        },


        series: [{
            name: 'Average Pass',
            color: 'rgb(100,200,210)',
            
            lineWidth: 5,
            data: <?php echo str_replace('"', '', json_encode($AVGP200)); ?>      
        },
        	{
            name: 'Average Fail',
            color: 'rgb(200,4,30)',
            lineWidth: 5,
            data: <?php echo str_replace('"', '', json_encode($AVGF200)); ?>       
        }
        ],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 800
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

    });
/*200 level chart end*/
/*300 level chart*/
 Highcharts.chart('chart300', {
        chart: {
            type: 'line'
        },
        title: {
            text: '300 Level'
        },

        subtitle: {
            text: '(Linear Scale)'
        },

        xAxis: {
            categories: <?php echo str_replace('\\', '', json_encode($S300));?>,
            categories: <?php echo str_replace('\\', '', json_encode($S300));?>},

        yAxis: {
            title: {
                text: 'Total Average Pass/Fail Count'
            }


        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        credits: {
            enabled: false
        },


        series: [{
            name: 'Average Pass',
            color: 'rgb(100,200,210)',
            
            lineWidth: 5,
            data: <?php echo str_replace('"', '', json_encode($AVGP300)); ?>      
        },
        	{
            name: 'Average Fail',
            color: 'rgb(200,4,30)',
            lineWidth: 5,
            data: <?php echo str_replace('"', '', json_encode($AVGF300)); ?>       
        }
        ],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 800
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

    });
/*300 level chart end*/
/*400 level chart*/
 Highcharts.chart('chart400', {
        chart: {
            type: 'line'
        },
        title: {
            text: '400 Level'
        },

        subtitle: {
            text: '(Linear Scale)'
        },

        xAxis: {
            categories: <?php echo str_replace('\\', '', json_encode($S400));?>,
            categories: <?php echo str_replace('\\', '', json_encode($S400));?>},

        yAxis: {
            title: {
                text: 'Total Average Pass/Fail Count'
            }


        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        credits: {
            enabled: false
        },


        series: [{
            name: 'Average Pass',
            color: 'rgb(100,200,210)',
            
            lineWidth: 5,
            data: <?php echo str_replace('"', '', json_encode($AVGP400)); ?>      
        },
        	{
            name: 'Average Fail',
            color: 'rgb(200,4,30)',
            lineWidth: 5,
            data: <?php echo str_replace('"', '', json_encode($AVGF400)); ?>       
        }
        ],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 800
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

    });
/*400 level chart end*/
/*500 level chart*/
 Highcharts.chart('chart500', {
        chart: {
            type: 'line'
        },
        title: {
            text: '500 Level'
        },

        subtitle: {
            text: '(Linear Scale)'
        },

        xAxis: {
            categories: <?php echo str_replace('\\', '', json_encode($S500));?>,
            categories: <?php echo str_replace('\\', '', json_encode($S500));?>},

        yAxis: {
            title: {
                text: 'Total Average Pass/Fail Count'
            }


        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        credits: {
            enabled: false
        },


        series: [{
            name: 'Average Pass',
            color: 'rgb(100,200,210)',
            
            lineWidth: 5,
            data: <?php echo str_replace('"', '', json_encode($AVGP500)); ?>      
        },
        	{
            name: 'Average Fail',
            color: 'rgb(200,4,30)',
            lineWidth: 5,
            data: <?php echo str_replace('"', '', json_encode($AVGF500)); ?>       
        }
        ],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 800
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

    });
/*500 level chart end*/
	}
</script>
@endsection