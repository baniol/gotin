var utils = {

	login: function(casper, url, user, password, cont) {

		if (cont === undefined) {
			casper.start(url, function() {
				this.test.info('Logging in user: ' + user);
				this.fill('#login-form', {
					'email-login': user,
					'password-login': password
				}, false);
			});
		}
		else {
			casper.thenOpen(url, function() {
				this.test.info('Logging in user: ' + user);
				this.fill('#login-form', {
					'email-login': user,
					'password-login': password
				}, false);
			});
		}

		// 	casper.thenClick('#login-form button[type=submit]', function() {
		// 	this.test.assertUrlMatch(/users/, 'Redirected to package after successful register & login');
		// });
	},

	loginClassic: function(casper, url, user, password) {
		casper.thenOpen(url, function() {
			this.test.info('Logging in user: ' + user);
			this.fill('#login-form', {
				'email-login': user,
				'password-login': password
			}, true);
		});
	},

	// @todo admin credentials to config
	loginAsAdmin: function(casper, url) {
		utils.login(casper, url, 'baniol', 'szapo123', true);
	},

	/**
	 *
	 * Checks if the inpurl matches the current url
	 * @param {Object} casper object
	 * @url {String} input string
	 * @return {Boolean}
	 **/
	matchUrl: function(casper, url) {
		var currUrl = casper.getCurrentUrl(),
			filter = new RegExp(url + "$"),
			c = currUrl.match(filter);
		return !!c;
	},

	/**
	 *
	 * Return the right answer to the captcha question
	 * @return {String}
	 **/
	captchaQuestion: function(casper) {
		var t = casper.fetchText('#questions p'),
			s;

		if (t.match(/first/))
			s = "monday";
		else if (t.match(/second/))
			s = "tuesday";
		else if (t.match(/third/))
			s = "wednesday";
		else if (t.match(/fourth/))
			s = "thursday";
		else if (t.match(/fifth/))
			s = "friday";
		else if (t.match(/sixth/))
			s = "saturday";
		else if (t.match(/seventh/))
			s = "sunday";

		return s;
	}
};
module.exports = utils;