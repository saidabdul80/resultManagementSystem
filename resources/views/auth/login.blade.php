@extends('layouts/links')

@section('body')
			<style type="text/css">
				.p{
					margin: 10px;
				}
				@media only screen and (max-width:597px){
     			.card{
     				width: 80% !important;
     				background-image: url("https://covid19learn.moodlecloud.com/draftfile.php/5/user/draft/270183011/food.png");background-repeat: no-repeat;background-size: cover;
     			}
      			}
    			
    			body{
    				background-color: rgba(35, 101, 56,.2);
    			}

			</style>

<body class="">
	<div id="backgroun" class="container-fluid">
	</div>
	<div class="" style="padding: 0; height: 40px; width: 100%;" id="result"></div>
		<center>
			<div class="card shadow" style="border-radius: 0px;width: 350px; margin-top:50px ;border:1px solid #35b1a6;">
				<div id="anim" style="display: none;">
					<div class="mov"></div>
				</div>
				<div class="card-header" style="background: none !important; border: none;" >Sign In</div>
				<div class="card-body cb" >
					<form action="/login" method="post" >
						{{ csrf_field() }}
						
						<input 
							type="text"
							name="email" 
							placeholder="email"  
							class="form-control @if($errors->first('email') !=null) border-danger @endif " 
							value="{{old('email')}}" 
							style="width: 90%;  
							" id="log">

						@if(count($errors))
						<p class="text-left ml-3 my-0 text-danger" style="font-size: 0.8em;">{{ $errors->first('email') }}</p>
						@else
						<br>
						@endif
						
						<input 
							type="password" 
							name="password" 
							placeholder="password"  
							class="form-control @if($errors->first('password') !=null) border-danger @endif " 
							style="width: 90%; @error('password') border:1px solid red; @enderror" 
							id="pass">

						@if(count($errors))
						<p class="text-left ml-3 my-0 text-danger mb-3" style="font-size: 0.8em;">{{ $errors->first('password') }}</p>

						@endif
						<label>
							<input type="checkbox" unchecked class="p" id="rememberme" name="remember">Remember me<br>
							<a href="password/reset" style="color:#0a0;font-size: 0.8em;">Forgot Password</a>
						</label><br>
						
						<input type="submit" name="submit"  class="btn btn-primary p" id="submit" value="Login">
					</form>
					</div>
				</div>
			</center>
	<script>
		/*$(document).ready(function(){

			var x = document.getElementById('log');
			var y = document.getElementById('pass');
			var z = document.getElementById('submit');

			x.onclick =function(){
				if(x.value==""){
					x.style.border = "1px solid red";
				}
			}
			x.onkeyup = function(){
					x.style.border = "1px solid #ccc";	
			}

			y.onclick =function(){
				if(y.value==""){
					y.style.border = "1px solid red";
				}
			}
			y.onkeyup = function(){
				if (y.value.length < 8) {
					y.style.border = "1px solid red";
				}else{
					y.style.border = "1px solid #ccc";
				}
			}
			

			z.onclick = function(){
				var email = document.getElementById('log').value;
				var pass = document.getElementById('pass').value;
				if(x.value==""){
					x.style.border = "1px solid red";
				}else if(pass==""){
					y.style.border = "1px solid red";
				}else{	
					
				$.post("conp.php",{email:email,pass:pass},
					function(response){
						$("#anim").hide();
						$('#loginForm').submit();
						Swal.fire({
							title:'',
							text: response
						}).then((result)=>{
							window.location = "index.php";
						});
					//$('#result').html(response);
				});
				$("#anim").show();
				}
			}
		//	$('#loginForm').submit(function(event){preventDefault();});
		});
*/
	</script>
	@endsection