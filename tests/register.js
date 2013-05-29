var casper = require('casper').create({
    clientScripts: [
            'lib/jquery.js'
    ]
});
var utils = require('./casperutils');

var url = "http://cms.dev/admin";

var registerEmail = 'test@test.te',
    registerUsername = 'test_john',
    registerPassword = 'test123';

// user login
casper.start(url, function() {
    this.test.info('Right after login...');
    var regVisible = casper.evaluate(function() {
        return __utils__.visible('#register-tab');
    });
    this.test.assertEquals(regVisible, false, 'Register tab should be invisible');
    // this.test.assertVisible('#register-tab');
});


// @todo - does not work
// casper.thenClick('#gotinTabs li:nth-child(2) a', function () {
//     this.test.info('Click register tab -> register form should be visible');
//     this.wait(500,function(){
//         this.test.assertVisible('#register-tab');
//     });
// });

casper.thenClick('#register-form button[type=submit]', function() {
    this.test.info('Don`t fill the register form -> Expect error messages in the body...');
    this.wait(1000, function() {
        // @todo - specify dom containers for each message
        this.test.assertTextExists('This field cannot be empty!', 'Username field required alert');
        // this.test.assertTextExists('The email-register field is required.', 'Email field required alert');
        // this.test.assertTextExists('The password-register field is required.', 'Password field required alert');
        // this.test.assertTextExists('The question field is required', 'Question field required alert');
    });
});

casper.then(function() {
    this.test.info('If you fill the password field but leave confirmation empty -> expect error...');
    this.fill('#register-form', {
        'password-register': 'test123'
    }, false);
});

casper.thenClick('#register-form button[type=submit]', function() {
    this.wait(1000, function() {
        this.test.assertTextExists('The confirmation does not match!', 'Confirmation error');
    });
});

casper.then(function() {
    this.test.info('If you fill the password field and confirm with not matching string -> expect error...');
    this.fill('#register-form', {
        'password-register': 'test123',
        'password-register_confirmation': 'test1234'
    }, false);
});

casper.thenClick('#register-form button[type=submit]', function() {
    this.wait(1000, function() {
        this.test.assertTextExists('The confirmation does not match!', 'Confirmation error');
    });
});

casper.then(function() {
    this.test.info('Fill the registration form properly ...');
    var t = this.fetchText('#questions p'),
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

    this.fill('#register-form', {
        'username-register': registerUsername,
        'email-register': registerEmail,
        'password-register': registerPassword,
        'password-register_confirmation': registerPassword,
        'question': s
    }, false);

});

casper.thenClick('#register-form button[type=submit]', function() {
    this.wait(1000, function() {
        this.test.assertUrlMatch(/users/, 'Redirected to package after successful register & login');
        // this.test.assertTextExists("You don't have any bundle yet. Click Add Bundle to add one", 'Info of not having bundles.');
    });
});

// logout 
casper.thenOpen(url + "/logout");

// *********************

// user login with email
casper.thenOpen(url, function() {
    this.test.info('Loggin in with correct credentials - using email');
    this.test.assertExist("#login-form", 'Login form exists');
    this.fill('#login-form', {
        'email-login': registerEmail,
        'password-login': registerPassword
    }, false);

    this.test.assertField('email-login', registerEmail,'email field is filled correctly');
    this.test.assertField('password-login', registerPassword,'password field is filled correctly');
});

casper.thenClick('#login-form button[type=submit]', function() {
    this.wait(1000, function() {
        this.test.assertUrlMatch(/users/, 'Redirected to index page after login');
    });
});

casper.then(function() {
    var t = this.fetchText('#top-uname');
    this.test.assertEquals(t, registerUsername, 'Check username after logging in');
    this.capture('screen.png', {
        top: 0,
        left: 0,
        width: 1300,
        height: 800
    });
});

casper.thenClick('#logout', function() {
    var check = utils.matchUrl(this,url);
    this.test.assertTruthy(check,"Redirected to start page after logout");
});

//login with username
// user login with email
casper.thenOpen(url, function() {
    this.test.info('Loggin in with correct credentials using username');
    this.fill('#login-form', {
        'email-login': registerUsername,
        'password-login': registerPassword
    }, false); // false means don't autosubmit the form
});

casper.thenClick('#login-form button[type=submit]', function() {
    this.wait(1000, function() {
        this.test.assertUrlMatch(/users/, 'Redirected to index page after login');
    });
});

casper.then(function() {
    // this.test.info('Sprawdź czy istnieje na liście online zalogowany user.');    
    var t = this.fetchText('#top-uname');
    this.test.assertEquals(t, registerUsername, 'Check username after logging in');
});

casper.thenClick('#logout', function() {
    var check = utils.matchUrl(this,url);
    this.test.assertTruthy(check,"Start url after logout");
});

// login with wrong credentials

casper.thenOpen(url, function() {
    this.test.info('Loggin in with wrong credentials');
    this.fill('#login-form', {
        'email-login': 'wrong@wrong.er',
        'password-login': 'wrrrpasss'
    }, false);
});

casper.thenClick('#login-form button[type=submit]', function() {
    this.wait(1000, function() {
        var t = this.fetchText('#login-form .alert-error');
        this.test.assertEquals(t, 'Wrong email / password!', 'Check error message');
    });
});

// *****************

/* login in to delete the new user */
utils.login(casper, url, registerUsername, registerPassword, true);

// filter for confirming user delete
casper.thenOpen(url + "/users", function() {
    this.wait(1000, function() {
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
    this.test.done(16);
    this.test.renderResults(true);
});