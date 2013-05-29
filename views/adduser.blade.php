@layout('gotin::layouts.main_layout')

@section('content')

@if($user_id != false && is_numeric($user_id))
{{Form::open(URL::to_action('gotin::users@edit', array($user_id)),'post',array('class'=>'form-horizontal well shadowed'))}}
@else
{{Form::open(URL::to_action('gotin::users@new'),'post',array('class'=>'form-horizontal well shadowed'))}}
@endif

{{Form::token()}}

<fieldset>

	{{$flash}}

	<div class="control-group {{ $errors->has('username') ? 'error' : '' }}">
		{{Form::label('email','Username',array('class'=>'control-label'))}}
		<div class="controls">
			{{Form::text('username',$values['username'],array('class'=>''))}}
			@if($errors->has('username'))
			<span class="help-inline">{{$errors->first('username')}}</span>
			@endif
		</div>
	</div>

	<div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
		{{Form::label('email','Email',array('class'=>'control-label'))}}
		<div class="controls">
			{{Form::email('email',$values['email'],array('class'=>''))}}
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

@if(Auth::is('Admin'))
<fieldset><legend>Roles</legend>
	@foreach($roles as $r)
	<div class="controls">
		<label class="checkbox">
			@if($user_id == 1)
				{{Form::checkbox('roles[]',$r['id'],$r['checked'],array('disabled'=>'disabled'))}}
			@else
				{{Form::checkbox('roles[]',$r['id'],$r['checked'])}}
			@endif
			{{ $r['name'] }}
		</label>
	</div>
	@endforeach
</fieldset>
@endif

<div class="form-actions">
	{{HTML::link_to_action('gotin::users','Go Back',array(),array('class'=>'btn'))}}
	<?php $stext = $user_id != false ? 'Edit user' : 'Add user' ?>
	{{Form::submit($stext, array('class' => 'btn btn-primary'))}}
</div>
{{Form::close()}}

@endsection
