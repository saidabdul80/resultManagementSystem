<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="/assets/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="/assets/bootstrap/css/basic.css">
	<link rel="stylesheet" type="text/css" href="/assets/bootstrap/css/sweetalert2.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/bootstrap/css/datepicker.css">
	<link rel="stylesheet" type="text/css" href="/assets/Linearicons/Web Font/style.css">
	<link rel="stylesheet" type="text/css" href="/assets/icofont/icofont.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/js/listjs/PagingStyle.css">
	<script type="text/javascript" src="/assets/js/jquery-1.10.2.js"></script>
	<script type="text/javascript" src="/assets/js/diff.js"></script>
	<script type="text/javascript" src="/js/vue.js"></script>
	 <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smartresult') }}</title>

    <!-- Styles -->
   

</head>
<body>
	
@yield('body')



</body>
</html>