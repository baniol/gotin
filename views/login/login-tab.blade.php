<form class="form-signin" method="post" id="login-form">
  <h2 class="form-signin-heading">{{__('gotin::gotin.sign_in_header')}}</h2>
  <div class="alert alert-error error-msg"></div>
  <input type="text" name="email-login" id="email-login" class="input-block-level" placeholder="{{__('gotin::gotin.email_placeholder')}}">
  <input type="password" name="password-login" id="password-login" class="input-block-level" placeholder="{{__('gotin::gotin.password')}}">
  <label class="checkbox">
    <input type="checkbox" name="remember-login" value="remember"> {{__('gotin::gotin.remember_me')}}
  </label>
  <button class="btn btn-large btn-primary" type="submit">{{__('gotin::gotin.login_button')}}</button>
</form>