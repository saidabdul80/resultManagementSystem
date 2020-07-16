@extends('/layouts/links')


@section('body')

    <!-- <div class="bg-lsuccess row" style="height: 25px; margin: 0px;"></div> -->
    <div id="wholePage">
    	
    <div class="" style="height: 65px;  width: 100%; border-bottom: 1px solid #eee; margin: 0px;" id="fixingLP">
        <div id="fixedM"></div>
        <div class="" style=" margin-left: 20px; margin-top: 8px; display: flex; position: relative;top: 0px;" id="fixedC">
            <button style="font-size: 2em; cursor: pointer !important;" id="menubtn" class="navbar-toggler d-md-block d-lg-none" >
            <span class="lnr lnr-text-align-justify" ></span>
         </button>
             <div style="margin-top: 2px; font-weight: bold; color:#666; margin-right: 10px;">
            <p style="margin: 0px;">EP System</p>
            <p style="margin: 0px;">{{\App\Session::where('c_set',1)->first()->session}}</p>
        </div>
            <img src="/img/ibb logo.png" style="width:50px; height: 50px;">
        </div>
        <!-- loader-->
        <div style="position: absolute; left: 43%; top:23px; color: #666; display: none; " id="loader">
            <div style="">Please wait <div class="processing" style="display: inline-block;"></div></div> 
        </div>
        <!-- logout and user icon-->
<!-- 
        <div style="position: absolute; top: 0px; right: 0;" id="header-right">
            <a href="{{ route( 'logout' ) }}"   onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-light btn-sm" style="">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
            <center>
                <p style="margin-bottom: 0px;"><span class="lnr lnr-user" style="font-size: 2em;"></span></p>
                <p style="font-size: 0.7em; font-weight: bold;">Hi, User</p>
            </center>
        </div>
         -->
         <style type="text/css">
           
           
             #menu1:focus .Uaccount{
                border:1px solid #5a7 !important;
                border-radius: 50%;
                border-color: currentColor;
                color:#5a7;
                opacity: 0.5;
             }
             #menu1:hover {
                color:#5a7;
                box-shadow: 0px 1px 3px #ccc;
             }
         </style>
        <div class="dropdown " style="position: absolute; top: -10px; right: 0; border-radius: 10px;" id="header-right">
            <span id="menu1" style="cursor: pointer; padding: 5px;">
                <center>
                    <p style="margin-bottom: 6px;width: 50px; border-radius: 5px;"><span class="lnr lnr-user Uaccount"  tabindex="1" style="font-size: 2em;"></span></p>
                    <p style="font-size: 0.7em; font-weight: bold; margin: 0px;" class="UaccountName">Hi, {{ Auth::user()->name }}</p>
                </center>
            </span>
            
                <script >
                    $(document).ready(function(){
                            $('#menu1').click(function(){
                                $('#menushow').toggle();
                            });
                            $('wrapper11,#containerA').click(function(){
                               $('#menushow').hide();     
                            });
                    });
                    $('#menushow').show();
                </script>
            <div aria-labelledby="menu1" id="menushow" style="position: absolute; left:-22px;top:45px; width: auto;display: none; background: #fff; border:1px solid #9da; border-radius: 4px; padding: 8px 11px;">
                    <a class="text-success pl-3 mr-4" href="{{ route('logout') }}"onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
            </div>
        </div>
    </div><div id="fixedM"></div>
    <div class="wrapper" id="wrapper11" >
       <div class="sidebar bg-lsuccess d-lg-block" style="display: none;" id="dropMenu">
        @if(in_array('admin',session('userRoles')))
            <a href="">
               <ul class="menu">
                    <center>
                        <span class="lnr lnr-home ico"></span>
                        <li>Dashboard</li>
                    </center>
               </ul>
            </a>
             <ul class="menu">
                <center>
                    <span class="lnr lnr-frame-contract ico"></span>
                    <li>Admin</li>
                </center>
                    <ul class="submenu">
                        <a href="{{route('assign-role')}}"><li>Assign Role<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('configuration')}}"><li>Configuration<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('manage-course')}}"><li>Manage Courses.<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('manage-fadep')}}"><li>Manage Faculty/Depart.<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('grade')}}"><li>Manage grades.<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('manage-session')}}"><li>Manage Session<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('manage-student')}}"><li>Manage Students<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('manage-user')}}"><li>Manage User<span class="lnr lnr-chevron-right"></span></li></a>
                         <a href="a-logs"><li>Logs<span class="lnr lnr-chevron-right"></span></li></a>
                        
                    </ul>
           </ul>
        @endif

        @if(in_array('lecturer',session('userRoles')))
    

           <ul class="menu">
                <center>
                    <span class="lnr lnr-layers ico"></span>
                    <li>Lecturer</li>
                </center>
                <ul class="submenu">
                        <a href="{{route('ldashboard')}}"><li>Dashboard<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('l_manage_result')}}"><li>Manage Result<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('l_view_student')}}"><li>view students<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('l_logs')}}"><li>Logs<span class="lnr lnr-chevron-right"></span></li></a>
                    </ul>
           </ul>
        @endif
      	@if(in_array('examiner',session('userRoles')))
      		<ul class="menu">
                <center>
                    <span class="lnr lnr-pencil ico"></span>
                    <li>Examiner</li>
                </center>
                <ul class="submenu">
                        <a href="{{route('edashboard')}}"><li>Dashboard<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('e_manage_result')}}"><li>Manage Result<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('e_manage_lecturer')}}"><li>Manage Lecturer<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('e_view_result')}}"><li>View Result<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('e_logs')}}"><li>View Result<span class="lnr lnr-chevron-right"></span></li></a>
                    </ul>
           </ul>
        @endif
          
        @if(in_array('faculty',session('userRoles')))
    
           <ul class="menu">
                <center>
                    <span class="icofont-brand-fitbit ico"></span>
                    <li>Faculty</li>
                </center>
                <ul class="submenu">
                        <a href="{{route('fdashboard')}}"><li>Dashboard<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('f_timing')}}"><li>Manage Timing<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('f_view_result')}}"><li>View Result<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('f_logs')}}"><li>logs<span class="lnr lnr-chevron-right"></span></li></a>
                    </ul>
           </ul>
        @endif
        @if(in_array('sanateB',session('userRoles')))      
           <ul class="menu">
                <center>
                    <span class="lnr lnr-apartment ico"></span>
                    <li>Senate</li>
                </center>
                <ul class="submenu">
                        <a href="{{route('sdashboard')}}"><li>Dashboard<span class="lnr lnr-chevron-right"></span></li>
                        <a href="{{route('s_manage_result')}}"><li>View Result<span class="lnr lnr-chevron-right"></span></li></a>
                        <a href="{{route('s_logs')}}"><li>logs<span class="lnr lnr-chevron-right"></span></li></a>
                    </ul>
           </ul>';
        @endif

       
</div>
  <!-- /. NAV SIDE  -->
<div style="position: absolute;bottom:3% !important; right: 2%;" class="alert alert-success" id="flash-msg-login">{{session('status')}}</div>
       
    
	<div id="containerA" class="containerA" >
		@yield('content')
	</div>

<footer style="po"><span style="">© 2020 saidabdul project</span> </footer>
 </div>
@endsection
