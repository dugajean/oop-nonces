# OOP WordPress Nonces

Nonces in WordPress are a measure of security to prevent URLs and forms being turned into malicious entities. The functionality of this library is to take WordPress's nonce functionality and implement it in an object oriented way, resulting in a more intuitive and extensible API.

**Note:** This is a standalone package and cannot be used with WordPress without further modification. 

## How it works

In order to create nonce-d entities, such as a URL or a hidden form field, instantiate the respective class:

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

## Design Decisions

###### Usability

* [x] Intuitive API which allows for easy access to needed data. Chaining methods for clarity and expressive code.
* [x] Base class `Nonce` includes all logic necessary to generate nonce hashes.
* [x] Subclasses have access to all protected methods in order to implement further logic specific to the subclasses.

###### Extensibility

* [x] Concrete type implementations for nonces are easy to make by inheriting from `Nonce`.
* [x] Types must implement the `get()` method in order to output their respective nonce attached data.
* [x] The PSR-4 configuration allows for easy access of each component within the package.

This whole implementation results in a package which is open for extension without needing to touch the core.

## Requirements

This package requires the use of PHP 7 and above.

## Tests

There is a `tests/` folder demonstrating the functionality of this library as well as the coverage of some edge cases. 
