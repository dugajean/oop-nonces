## Wordpress Nonces for Inpsyde

* Create base class with methods.
* Replicate the methods in an OOP fashion, while maintaining the same functionality.
  * Use of functions like `session_id()` until it's clarified if I can use Wordpress functions.
* Fluid API to work with nonces (mostly used within the class itself).
* Verifying a nonce requires passing a Nonce object. Nonce key and action can be set in the constructor.