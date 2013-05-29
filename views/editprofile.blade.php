@layout('gotin::layouts.main_layout')

@section('content')

{{Form::open(URL::to_action('gotin::users@editprofile'),'post',array('class'=>'form-horizontal well shadowed'))}}

{{Form::token()}}

<fieldset>

	{{$flash}}

	<?php
		// @todo - move to controller; the same in the roles view
		$value_email = !Input::old('email') ? $data->email : Input::old('email');
		$value_username = !Input::old('username') ? $data->username : Input::old('username');
	?>

	<div class="control-group {{ $errors->has('username') ? 'error' : '' }}">
		{{Form::label('email','Username',array('class'=>'control-label'))}}
		<div class="controls">
			{{Form::text('username',$value_username,array('class'=>''))}}
			@if($errors->has('username'))
			<span class="help-inline">{{$errors->first('username')}}</span>
			@endif
		</div>
	</div>

	<div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
		{{Form::label('email','Email',array('class'=>'control-label'))}}
		<div class="controls">
			{{Form::email('email',$value_email,array('class'=>''))}}
			@if($errors->has('email'))
			<span class="help-inline">{{$errors->first('email')}}</span>
			@endif
		</div>
	</div>

	@if(!is_numeric($user_id))
	<div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
		{{Form::label('password','Password',array('class'=>'control-label'))}}
		<div class="controls">
			{{Form::password('password',array('class'=>''))}}
			@if($errors->has('password'))
			<span class="help-inline">{{$errors->first('password')}}</span>
			@endif
		</div>
	</div>

	<div class="control-group">
		{{Form::label('password_confirmation','Password Confirmation',array('class'=>'control-label'))}}
		<div class="controls">
			{{Form::password('password_confirmation',array('class'=>''))}}
		</div>
	</div>
	@endif
</fieldset>

<div class="form-actions">
	{{Form::submit('Save', array('class' => 'btn btn-primary'))}}
	{{HTML::link_to_action('gotin::deleteaccount',__('gotin::gotin.delete_account'),array(),array('class'=>'btn btn-danger pull-right','onclick'=> 'return confirm("'.__('gotin::gotin.delete_account_confirm').'")'))}}
</div>
{{Form::close()}}
@endsection
