Change Log
==========

This document keeps track of important changes between releases of the library.

2.5.0
------------------

* Added logging
* Fixed Dyanmo minimum version dependency

2.4.0
------------------

* Added support for async requests
* Using http_build_query() to build all query strings
* Allowing modification of return data through event

2.3.0
------------------

* Fixed events to dispatch objects that can be modified

2.2.0
------------------

* Allowed body object to be form encoded
* Added support for serializing \JsonSerializable
* Fixed issue with booleans getting cast to ints
* Fixed issue with serializing null body objects
* Improved generated code to exclude serialization contexts if not applicable

2.1.0
------------------

* Added an event dispatcher
* Added a library specific exception

2.0.0
------------------

* Removed composer wrapper
* Removed guzzle dependency (added support for either v5 or v6)
* Abstracted class generation into tebru/dynamo
* Fixed issue with query map
* Fixed issue with interface inheritance 

1.2.1
------------------

* Upgraded twig to resolve security vulnerability

1.2.0
------------------

* Added annotation for JMS serialization contexts


1.1.0
------------------

* Added support for JMS serialization contexts

1.0.0

1.0.0
------------------

* Mirrored functionality of Square's Retrofit library.
* PSR autoloading of generated classes
* Created binary tool for managing cache.
