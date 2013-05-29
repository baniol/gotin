@layout('gotin::layouts.login_layout')

@section('content')

{{Form::open('forgot-password','post',array('class'=>'form-signin classic','id'=>'forgot-form'))}}
<h2 class="form-signin-heading">{{__('gotin::gotin.provide_email')}}</h2>
{{$flash}}
{{Form::token()}}

<div class="control-group {{ $errors->has('email-forgot') ? 'error' : '' }}">
  <div class="controls">
  	@if($errors->has('email-forgot'))
		<span class="help-inline">{{$errors->first('email-forgot')}}</span>
	@endif
	{{Form::email('email-forgot','',array('placeholder'=>__('gotin::gotin.email')))}}
  </div>
</div>

{{Form::button(__('gotin::gotin.send'), array('class' => 'btn btn-large btn-primary pull-right','type'=>'submit'))}}

<br/>

{{HTML::link('register',__('gotin::gotin.register_link'))}} | {{HTML::link('login',__('gotin::gotin.login_text'))}}

{{Form::close()}}

@endsection