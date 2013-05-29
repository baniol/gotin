/**
 *
 * Regiser test:
 * 1) register a new user
 * 2) logout
 * 3) try to register with empty fields
 * 4) try to register with:
 *	a) existing credentials
 *	b) not matching password
 *	c) wrong captcha answer
 *
 **/

var casper = require('casper').create({
	clientScripts: [
			'lib/jquery.js'
	]
});

var utils = require('./lib/casperutils'),
	testCount = 0;

// admin login url
var url = "http://cms.dev/";

casper.start(url + 'login');

casper.thenClick('#register-link', function() {
	testCount++;
	this.test.info(testCount + '/ Going to register url by clicking register link in the login view');
	// @todo - wait for...
	this.waitForText('Please register', function() {
		this.test.assertUrlMatch(/register/, 'Register view after clicking register link in the login view');
	});
});

casper.then(function() {
	testCount++;
	this.test.info(testCount + '/ Submit correctly filled register form');

	this.fill('#register-form', {
		'username-register': 'test_1',
		'email-register': 'test_1@test.com',
		'password-register': 'pass123',
		'password-register_confirmation': 'pass123',
		'question': utils.captchaQuestion(this)
	}, true);

	this.waitForSelector('#logout', function() {
		this.test.assertUrlMatch(/dashboard/, 'Logging in after succesfull register');
	});

});

casper.then(function() {
	testCount++;
	casper.test.info(testCount + '/ Log out');
});

casper.thenClick('#logout');

casper.thenOpen(url + 'register');

casper.thenClick('button[type=submit]', function() {
	testCount++;
	this.test.info(testCount + '/ Leave all fields blank & submit register form');

	var u = this.evaluate(function() {
		return $('input[name=username-register]').parent().find('.help-inline').text();
	});
	this.test.assertEquals(u, 'This field cannot be empty!', 'Username field required alert');

	var e = this.evaluate(function() {
		return $('input[name=email-register]').parent().find('.help-inline').text();
	});
	this.test.assertEquals(e, 'This field cannot be empty!', 'Email field required alert');

	var p = this.evaluate(function() {
		return $('input[name=password-register]').parent().find('.help-inline').text();
	});
	this.test.assertEquals(p, 'This field cannot be empty!', 'Password field required alert');

	var q = this.evaluate(function() {
		return $('input[name=question]').parent().find('.help-inline').text();
	});
	this.test.assertEquals(q, 'The question field is required.', 'Question field required alert');

});

casper.thenOpen(url + 'register', function() {
	testCount++;
	this.test.info(testCount + '/ Fill inputs with incorrect values');

	this.fill('#register-form', {
		'username-register': 'test_1',
		'email-register': 'test_1@test.com',
		'password-register': 'pass123',
		'password-register_confirmation': 'pass1234',
		'question': 'wronganswer'
	}, true);

	this.waitForText('This username is already used!', function() {
		var u = this.evaluate(function() {
			return $('input[name=username-register]').parent().find('.help-inline').text();
		});
		this.test.assertEquals(u, 'This username is already used!', 'This username is already used!');

		var e = this.evaluate(function() {
			return $('input[name=email-register]').parent().find('.help-inline').text();
		});
		this.test.assertEquals(e, 'This email is already used!', 'This email is already used!');

		var p = this.evaluate(function() {
			return $('input[name=password-register]').parent().find('.help-inline').text();
		});
		this.test.assertEquals(p, 'The confirmation does not match!', 'The confirmation does not match!');

		var q = this.evaluate(function() {
			return $('input[name=question]').parent().find('.help-inline').text();
		});
		this.test.assertEquals(q, 'The answer is not correct', 'The answer is not correct');
	});

});

/* login in to delete the new user */
utils.loginClassic(casper, url + 'login', 'baniol', 'szapo123', true);

// filter for confirming user delete
casper.thenOpen(url + "admin/users", function() {

	testCount++;
	this.test.info(testCount + '/ Login as admin & remove test user');

	this.waitForText('Actions', function() {
		// console.log(this.getCurrentUrl());
		// this.test.assertTextExists('dasboard', 'xxxxxxxx');
		casper.removeAllFilters('page.confirm');
		casper.setFilter('page.confirm', function(message) {
			return true;
		});
	});
});

casper.thenClick('table.table tr:last-child td .delete_toggler', function() {
	this.test.info('Deleting the test user ...');
	// @todo - check if user has been removed
});


// run tests
casper.run(function() {
	this.test.done(10);
	this.test.renderResults(true);
});