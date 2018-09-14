## Wordpress Nonces for Inpsyde

* Create base class with methods.
* Replicate the methods in an OOP fashion, while maintaining the same functionality.
  * Use of functions like `session_id()` until it's clarified if I can use Wordpress functions.
* Fluid API to work with nonces (mostly used within the class itself).
* Verifying a nonce requires passing a Nonce object. Nonce key, action and name can be set in the constructor.
* Forming nonce URLs and fields is now separated into its own class to maintain single responsibility for the Nonce class
  * This will allow for easier testing as well.