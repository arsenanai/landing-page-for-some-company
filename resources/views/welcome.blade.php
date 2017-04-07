<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('Welcome')</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Roboto&amp;subset=cyrillic-ext" rel="stylesheet">
	<script
	  src="https://code.jquery.com/jquery-1.12.4.min.js"
	  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
	  crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.2.0/vue.js"></script>
	<style type="text/css">
		html,body{
			font-family: 'Roboto', sans-serif;
		}
	</style>
</head>
<body>
<div id="root">
	<nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="#">Cybersec</a>
	    </div>
	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
	        <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
	        <li><a href="#">Link</a></li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
	          <ul class="dropdown-menu">
	            <li><a href="#">Action</a></li>
	            <li><a href="#">Another action</a></li>
	            <li><a href="#">Something else here</a></li>
	            <li role="separator" class="divider"></li>
	            <li><a href="#">Separated link</a></li>
	            <li role="separator" class="divider"></li>
	            <li><a href="#">One more separated link</a></li>
	          </ul>
	        </li>
	      </ul>
	      <ul class="nav navbar-nav navbar-right">
	        <li><a href="#">Link</a></li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
	          <ul class="dropdown-menu">
	            <li><a href="#">Action</a></li>
	            <li><a href="#">Another action</a></li>
	            <li><a href="#">Something else here</a></li>
	            <li role="separator" class="divider"></li>
	            <li><a href="#">Separated link</a></li>
	          </ul>
	        </li>
	      </ul>
	      <div class="navbar-form navbar-right">
	        <a class="btn btn-default" data-toggle="modal" data-target="#myLoginModal">@lang('Login')</a>
	      </div>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
	{{-- Landing page content here --}}
	<div class="modal fade" id="myLoginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">@lang('Login')</h4>
	      </div>
	      <div class="modal-body">
	        <form action="{{ route('login') }}" method="post" id="login-form">
	        	{{ csrf_field() }}
			  <div class="form-group" v-bind:class="{'has-error':(emailIsEmpty==true),'has-success':emailIsEmpty==false}">
			    <input v-model="email" name="email" type="email" class="form-control" placeholder="@lang('Email')"
			    	@@keyup="check" autofocus
			    >
			  </div>
			  <div class="form-group" v-bind:class="{'has-error':(passwordIsEmpty==true),'has-success':(passwordIsEmpty==false)}">
			    <input v-model="password" type="password" class="form-control" name="password"
			    placeholder="@lang('Password')" @@keyup="check">
			  </div>
			</form>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-primary" @@click="submit" 
	        	:disabled="(emailIsEmpty||passwordIsEmpty) ? true : false">
	        	@lang('Submit')
	        </button>
	        <a class="btn btn-default" href="{{ route('password.request') }}">@lang('Forgot password')</a>
	        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('Close')</button>
	      </div>
	    </div>
	  </div>
	</div>
</div>
<script type="text/javascript">
	var app = new Vue({
		el:"#root",
		data:{
			email: '',
			emailIsEmpty: true,
			password: '',
			passwordIsEmpty: true,
		},
		methods:{
			submit(){
				document.getElementById("login-form").submit();
			},
			check(){
				this.emailIsEmpty=this.email==''?true:false
				this.passwordIsEmpty=this.password==''?true:false
				console.log(this.emailIsEmpty+", "+this.passwordIsEmpty);
			}
		}
	})
</script>
</body>
</html>