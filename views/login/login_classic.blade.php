@layout('gotin::layouts.login_layout')

@section('content')

{{Form::open('/login','post',array('class'=>'form-signin classic','id'=>'login-form'))}}
<h2 class="form-signin-heading">{{__('gotin::gotin.sign_in_header')}}</h2>
{{$flash}}
{{Form::token()}}
<div class="control-group">
    <div class="controls">
        {{Form::text('email-login','',array('class'=>'input-xlarge','id'=>'email','placeholder'=>__('gotin::gotin.email_placeholder')))}}
    </div>
</div>
<div class="control-group">
    <div class="controls">
        {{Form::password('password-login',array('class'=>'input-xlarge','id'=>'password','placeholder'=>__('gotin::gotin.password')))}}
    </div>
</div>

<label class="checkbox">
	<input type="checkbox" name="remember-login" value="remember"> {{__('gotin::gotin.remember_me')}}
</label>

{{Form::button(__('gotin::gotin.login_button'), array('class' => 'btn btn-large btn-primary pull-right','type'=>'submit'))}}

<div class="login-links">
{{HTML::link('register',__('gotin::gotin.register_link'),array('id'=>'register-link'))}}
<br/>
{{HTML::link('forgot-password',__('gotin::gotin.forgot_password_link'))}}
</div>

{{Form::close()}}

@endsection