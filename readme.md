# Laravel 3 Gotin Bundle

---

Authentification & authorisation bundle

---

## Installation steps

``application\bundles.php`` :

```php
return array(

	...
	'gotin' =>array('location'=>'gotin','handles' => 'admin','auto'=>true),
	...
);
```

``application/config/auth.php``:
change driver to gotinauth:
```php
'driver' => 'gotinauth',
```
