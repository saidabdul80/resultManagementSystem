	


<script type="text/javascript" src="/assets/js/Country-Select-Box-Plugin/countries.js"></script>
<script type="text/javascript" src="/assets/js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="/assets/js/canvasjs.min.js"></script>
<script type="text/javascript" src="/assets/js/dataTable/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/assets/js/custom1.js"></script>
<script type="text/javascript" src="/assets/js/sweetalert2.js"></script>
<script type="text/javascript" src="/assets/js/print.js"></script>
<script type="text/javascript" src="/assets/js/listjs/paging.js"></script>
<script type="text/javascript" src="/assets/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="/assets/bootstrap/js/datepicker.js"></script>

<script type="text/javascript" src="/assets/js/highcharts.js"></script>
	@if(session('status')!='')
		<script type="text/javascript">
			
			window.onload = function() {
				setTimeout(function() {
					$('#flash-msg-login').fadeOut(200);
				}, 7000);
			}
		</script>
	@else
		<style type="text/css">
			#flash-msg-login{
				display: none;
			}
		</style>
	@endif
