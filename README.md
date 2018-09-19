# OOP Wordpress Nonces

Nonces in Wordpress are a measure of security to prevent URLs and forms being turned into malicious entities. The functionality of this library is to take Wordpress's nonce functionality and implement it in an object oriented way, resulting in a more intuitive and extensible API.

**Note:** This is a standalone package and cannot be used with Wordpress without further modification. 

## How it works

In order to create Nonced elements, such as a URL or a hidden form field, you need to know what you need exactly and then instantiate the class, like so:

```php
$nonceUrl = new NonceUrl()->url('http://example.com')->get(); 
// Will output: http://example.com?_wpnonce=somerandomstring

$nonceField = new NonceField()->get(); 
// Will output: <input type="hidden" id="_wpnonce" name="_wpnonce" value="somerandomstring"/>
```

You're then able to pass these variables into your HTML and use them accordingly. The example above assumes the use of the default values; You are able to set `action` and `name` in the constructor or using setters in order to make the nonce specific to an action.

When you need to verify a nonce that has been sent to the backend, you can do so by using the verify method, like so:

```php
$nonceValid = new NonceField($_REQUEST['_wpnonce'])->verify();

if ($nonceValid) {
    // do something
}
```

## Requirements

This package requires the use of PHP 7 and above.

## Tests

There is a `tests/` folder demonstrating the functionality of this library as well as the coverage of some edge cases. 

## Changelog

* Fixed bug in testing platform where individual NonceTypes tests weren't passing due to session not being started.
* Basic refactoring and removal of static methods.

-------------------------

* Ran PHPCS and fixed all errors and the majority of the warnings (some type hints were not possible due to multiple types being needed).
* Overall refactoring and fixing of small issues.

-------------------------

* Major refactor by separating Nonce "types" (url and field) into their own specific classes, holding their own logic.
* Added unit tests to cover the changes.

-------------------------

* Verifying a nonce requires passing a Nonce object. Nonce key, action and name can be set in the constructor.
* Forming nonce URLs and fields is now separated into its own class to maintain single responsibility for the Nonce class
  * This will allow for easier testing as well.
  
  -------------------------

* Create base class with methods.
* Replicate the methods in an OOP fashion, while maintaining the same functionality.
  * Use of functions like `session_id()` until it's clarified if I can use Wordpress functions.
* Fluid API to work with nonces (mostly used within the class itself).


