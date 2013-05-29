@layout('gotin::layouts.login_layout')

@section('content')

<ul class="nav nav-pills" id="gotinTabs">
	<li class="active"><a href="#login-tab">Login</a></li>
	<li><a href="#register-tab">Register</a></li>
	<li><a href="#forgot-tab">Forgot password</a></li>
</ul>

<div class="tab-content">
	<div class="tab-pane active" id="login-tab">
		@include('gotin::login.login-tab')
	</div>
	<div class="tab-pane" id="register-tab">
		@include('gotin::login.register-tab')
	</div>
	<div class="tab-pane" id="forgot-tab">
		@include('gotin::login.forgot-tab')
	</div>
</div>

@endsection

@section('scripts')
@endsection