var casper = require('casper').create({
	clientScripts: [
			'lib/jquery.js'
	]
});

var utils = require('./lib/casperutils');

// admin login url
var url = "http://cms.dev/login",

	loginEmail = 'marcin@baniowski.pl',
	loginUsername = 'baniol',
	loginPassword = 'szapo123';

//  *************** user login with email
// correct crecentials
casper.start(url, function() {
	this.test.info('Loggin in with correct credentials - using email');
	this.test.assertExist("#login-form", 'Login form exists');
	this.fill('#login-form', {
		'email-login': loginEmail,
		'password-login': loginPassword
	}, true);

	this.wait(1000, function() {
		this.test.assertUrlMatch(/dashboard/, 'Redirected to index page after login');
	});
});

casper.thenClick('#logout', function() {
	this.wait(1000, function() {
		this.test.assertUrlMatch(/login/, 'Redirected to login page after logout');
	});
});

//  *************** user login with username
// correct crecentials
casper.then(function() {
	this.test.info('Loggin in with correct credentials - using email');
	this.test.assertExist("#login-form", 'Login form exists');
	this.fill('#login-form', {
		'email-login': loginUsername,
		'password-login': loginPassword
	}, true);

	this.wait(1000, function() {
		this.test.assertUrlMatch(/dashboard/, 'Redirected to index page after login');
	});
});

casper.thenClick('#logout', function() {
	this.wait(1000, function() {
		this.test.assertUrlMatch(/login/, 'Redirected to login page after logout');
	});
});

// ************** wrong credentials
casper.then(function() {
	this.test.info('Loggin in with wrong email / username');
	this.fill('#login-form', {
		'email-login': 'wrongwrong',
		'password-login': loginPassword
	}, true);
	this.wait(1000, function() {
		var t = this.fetchText('#login-form .alert-error p');
		this.test.assertEquals(t, 'Wrong email / password!', 'Check error message');
	});
});

casper.then(function() {
	this.test.info('Loggin in with wrong password');
	this.fill('#login-form', {
		'email-login': loginUsername,
		'password-login': 'wrongpassword'
	}, true);
	this.wait(1000, function() {
		var t = this.fetchText('#login-form .alert-error p');
		this.test.assertEquals(t, 'Wrong email / password!', 'Check error message');
	});
});

// run tests
casper.run(function() {
	this.test.done(8);
	this.test.renderResults(true);
});