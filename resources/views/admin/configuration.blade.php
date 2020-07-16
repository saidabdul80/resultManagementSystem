<?php
use \App\User;
use \App\Session;
use \App\Lecturer;
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
	<div id="titlebar">
			<div  id="title">Admin <span class="lnr lnr-chevron-right"></span><i> Manage Grades</i></div>

			<div id="msg">Messages<a href=""><span class="badge badge-primary">3</span></a></div>
	</div>
	

@include('layouts/scripts')
@endsection 
