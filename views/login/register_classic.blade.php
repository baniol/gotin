@layout('gotin::layouts.login_layout')

@section('content')

{{Form::open('/register','post',array('class'=>'form-signin classic register','id'=>'register-form'))}}

<h2 class="form-signin-heading">{{__('gotin::gotin.register_header')}}</h2>

{{$flash}}
{{Form::token()}}

<?php
	// @todo move to controller
	$value_username = !Input::old('username-register') ? $data->username : Input::old('username-register');
	$value_email = !Input::old('email-register') ? $data->email : Input::old('email-register');
?>

<div class="row-fluid">
  <div class="span6">

  	<div class="control-group {{ $errors->has('username-register') ? 'error' : '' }}">
	  <div class="controls">
	  	@if($errors->has('username-register'))
			<span class="help-inline">{{$errors->first('username-register')}}</span>
		@endif
		{{Form::text('username-register',$value_username,array('placeholder'=>__('gotin::gotin.username')))}}
	  </div>
	</div>

  	<div class="control-group {{ $errors->has('email-register') ? 'error' : '' }}">
	  <div class="controls">
	  	@if($errors->has('email-register'))
			<span class="help-inline">{{$errors->first('email-register')}}</span>
		@endif
		{{Form::email('email-register',$value_email,array('placeholder'=>__('gotin::gotin.email')))}}
	  </div>
	</div>

    <div class="control-group {{ $errors->has('password-register') ? 'error' : '' }}">
	  <div class="controls">
	  	@if($errors->has('password-register'))
			<span class="help-inline">{{$errors->first('password-register')}}</span>
		@endif
		{{Form::password('password-register',array('placeholder'=>__('gotin::gotin.password')))}}
	  </div>
	</div>

    <div class="control-group {{ $errors->has('password-register_confirmation') ? 'error' : '' }}">
	  	<div class="controls">
	  	@if($errors->has('password-register_confirmation'))
			<span class="help-inline">{{$errors->first('password')}}</span>
		@endif
		{{Form::password('password-register_confirmation',array('placeholder'=>__('gotin::gotin.password_confirmation')))}}
		</div>
	</div>

  </div>

  <div class="span6">
    <div class="control-group {{ $errors->has('question') ? 'error' : '' }}" id="questions">
      <p>{{__('gotin::gotin.captcha_question', array('day_number' => $day_number))}}:</p>
      <div class="controls">
        @if($errors->has('question'))
			<span class="help-inline">{{$errors->first('question')}}</span>
		@endif
        {{Form::text('question','',array('class'=>''))}}
      </div>
    </div>
  </div>
</div>

<div class="submit-btn-wrapper">
	<span class="pull-left">
		{{HTML::link('login',__('gotin::gotin.login_text'))}} | 
		{{HTML::link('forgot-password',__('gotin::gotin.forgot_password_link'))}}
	</span>
	<span class="">
		{{Form::button(__('gotin::gotin.send'), array('class' => 'btn btn-large btn-primary','type'=>'submit'))}}
	</span>
</div>

{{Form::close()}}