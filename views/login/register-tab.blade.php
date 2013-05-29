{{Form::open('/','post',array('class'=>'form-signin register','id'=>'register-form'))}}
<h2 class="form-signin-heading">{{__('gotin::gotin.register_header')}}</h2>
<div class="row-fluid">
  <div class="span6">
    <div class="control-group">
      <div class="controls">
      <span class="help-inline"></span>
      {{Form::text('username-register','',array('class'=>'','placeholder'=>__('gotin::gotin.username')))}}
      </div>
    </div>

    <div class="control-group">
      <div class="controls">
    	<span class="help-inline"></span>
      {{Form::email('email-register','',array('class'=>'','placeholder'=>__('gotin::gotin.email')))}}
      </div>
    </div>

    <div class="control-group">
      <div class="controls">
        <span class="help-inline"></span>
      {{Form::password('password-register',array('class'=>'','placeholder'=>__('gotin::gotin.password')))}}
      </div>
    </div>

    <div class="control-group">
      	<div class="controls">
    	   <span class="help-inline"></span>
         {{Form::password('password-register_confirmation',array('class'=>'','placeholder'=>__('gotin::gotin.password_confirmation')))}}
    	</div>
    </div>
  </div>

  <div class="span6">
    <div class="control-group" id="questions">
      <p>{{__('gotin::gotin.captcha_question', array('day_number' => $day_number))}}:</p>
      <div class="controls">
        <span class="help-inline"></span>
        {{Form::text('question','',array('class'=>''))}}
      </div>
    </div>
  </div>
</div>
<div class="submit-btn-wrapper">
  {{Form::button(__('gotin::gotin.send'), array('class' => 'btn btn-large btn-primary','type'=>'submit'))}}
</div>
{{Form::close()}}