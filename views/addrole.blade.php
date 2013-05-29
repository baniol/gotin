@layout('gotin::layouts.main_layout')

@section('content')

@if($role_id != false)
	{{Form::open(URL::to_action('gotin::roles@edit', array($role_id)),'post',array('class'=>'form-horizontal well shadowed'))}}
@else
	{{Form::open(URL::to_action('gotin::roles@new'),'post',array('class'=>'form-horizontal well shadowed'))}}
@endif

{{Form::token()}}

<fieldset>

{{$flash}}

<?php
// @todo - move to controller
	$value_name = !Input::old('name') ? $role->name : Input::old('name');
	$value_description = !Input::old('description') ? $role->description : Input::old('description');
?>

<div class="control-group {{ $errors->has('name') ? 'error' : '' }}">
  {{Form::label('name','Name',array('class'=>'control-label'))}}
  <div class="controls">
	{{Form::text('name',$value_name,array('class'=>''))}}
	@if($errors->has('name'))
	<span class="help-inline">{{$errors->first('name')}}</span>
	@endif
  </div>
</div>

<div class="control-group {{ $errors->has('description') ? 'error' : '' }}">
  {{Form::label('description','Description',array('class'=>'control-label'))}}
  <div class="controls">
	{{Form::textarea('description',$value_description,array('class'=>''))}}
	@if($errors->has('description'))
	<span class="help-inline">{{$errors->first('description')}}</span>
	@endif
  </div>
</div>

<div class="form-actions">
{{HTML::link_to_action('gotin::roles','Go Back',array(),array('class'=>'btn'))}}
{{Form::submit(__('gotin::gotin.save'), array('class' => 'btn btn-primary'))}}
</div>
{{Form::close()}}

@endsection