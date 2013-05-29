{{Form::open('/','post',array('class'=>'form-signin','id'=>'forgot-form'))}}
<h2 class="form-signin-heading">{{__('gotin::gotin.provide_email')}}</h2>
<div class="alert alert-error error-msg"></div>
<div class="control-group">
  <div class="controls">
	{{Form::email('email-forgot','',array('class'=>'','placeholder'=>__('gotin::gotin.email')))}}
  </div>
</div>
<div class="submit-btn-wrapper">
  {{Form::button(__('gotin::gotin.send'), array('class' => 'btn btn-large btn-primary','type'=>'submit'))}}
</div>
{{Form::close()}}