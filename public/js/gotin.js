(function() {
	var Gotin;

	// "use strict";

	Gotin = (function() {
		function Gotin() {
			this.submitVal = null;
			this.test = 'present';
		}

		Gotin.prototype.init = function() {
			this.serveLogin();
			this.serveRegister();
			this.serveForgot();
			$(document).on('click', '#gotinTabs a', function(e) {
				e.preventDefault();
				$(this).tab('show');
			});
			// $('#gotinTabs li:eq(2) a').tab('show');
		};

		Gotin.prototype.serveLogin = function() {
			var self = this;
			$(document).on('submit', '#login-form', function(e) {
				var form = $(this);
				e.preventDefault();
				var data = $(this).serialize();
				self.showLoad(form);
				self.sendLogin(data, form);
			});
		};

		Gotin.prototype.sendLogin = function(data, form) {
			var self = this;
			if (form.attr('id') == "register-form") {
				data = data.replace(/email-register/, "email-login");
				data = data.replace(/password-register/, "password-login");
			}
			self.xhr('/gotin/loginajax', data,

			// success

			function(res) {
				window.location.href = window.location.href;
			},

			// error

			function(err) {
				self.restoreSubmit(form);
				$('#login-form .alert').text('Wrong email / password!').show();
			});
		};

		Gotin.prototype.serveRegister = function() {
			var self = this;
			$(document).on('submit', '#register-form', function(e) {
				var form = $(this);
				e.preventDefault();
				var data = $(this).serialize();
				self.showLoad(form);
				self.xhr('/gotin/registerajax', data,
				// success

				function(res) {
					self.sendLogin(data, form);
				},
				// error

				function(err) {
					var errors = JSON.parse(err.responseText);
					var msgs = errors.errors.messages;
					form.find('.control-group').removeClass('error').find('.help-inline').text('');
					$.each(msgs, function(field, message) {
						var el = form.find('[name=' + field + ']');
						el.closest('.control-group').addClass('error');
						el.prev().text(message);
					});
					self.restoreSubmit(form);
				});
			});
		};

		Gotin.prototype.serveForgot = function() {
			var self = this;
			$(document).on('submit', '#forgot-form', function(e) {
				var form = $(this);
				e.preventDefault();
				var data = $(this).serialize();
				self.showLoad(form);
				self.xhr('/gotin/forgotajax', data,
				// success

				function(res) {
					// change tab to login & display message
					$('#gotinTabs a[href="#login-tab"]').tab('show');
					$('#login-form .alert')
						.removeClass('alert-error')
						.addClass('alert-success')
						.text(res).show();

					form.find('input[name=email-forgot]').val('');
					self.restoreSubmit(form);
				},
				// error

				function(err) {
					self.restoreSubmit(form);
					$('#forgot-form .alert').text("Provide a valid email address!").show();
				});
			});
		};

		Gotin.prototype.showLoad = function(form) {
			var self = this;
			self.submitVal = form.find('button[type=submit]').text();
			form.find('button[type=submit]').text('Sending...').attr('disabled', true);
		};

		Gotin.prototype.restoreSubmit = function(form) {
			form.find('button[type=submit]').text(this.submitVal).attr('disabled', false);
		};

		Gotin.prototype.xhr = function(url, data, fn, error) {
			var self = this;
			$.ajax({
				cache: false,
				url: url,
				data: data,
				type: 'post'
			}).done(function(res) {
				fn(res);
			}).fail(function(jqXHR, textStatus) {
				if (typeof error == 'function') {
					error(jqXHR);
				}
			});
		};

		return Gotin;

	})();

	window.gotin = new Gotin();
	window.gotin.init();

}).call(this);