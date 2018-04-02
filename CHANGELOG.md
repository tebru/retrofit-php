Change Log
==========

This document keeps track of important changes between releases of the library.

3.1.0
-----

* ADDED: Ability to set custom cache adapter
* CHANGED: Default cache to use php file cache over filesystem cache

3.0.3
-----

* FIXED: Issue with default parameters and nulls

3.0.2
-----

* ADDED: Symfony 4 support

3.0.1
-----

* FIXED: Issue with query encoding

3.0.0
-----

* See [upgrade guide](docs/upgrade_2_3.md)

2.9.0
-----

* Dependency updates
* Added request/response to return event


2.8.3
-----

* Fixed issue with AfterSendEvent

2.8.2
-----

* Fixed issue with events

2.8.1
-----

* Updating dependencies


2.8.0
-----

* Added option to return complete response instead of body
* Removed logging of which events occurred
* Dependency updates
* Added multipart support

2.7.0
-----

* Updated dynamo library

2.6.1
-----

* Fixed issue with urlencoding parameters

2.6.0
-----

* Changing client adapter to only accept request interface
* Updating client dependency

2.5.5
-----

* Updating Dynamo library
* Updating minimum dependencies
* Added ability to set a client adapter

2.5.4
-----

* Event updates: adding request

2.5.3
-----

* Fixed bug AfterSendEvent

2.5.2
-----

* Fixed bug in event system

2.5.1
-----

* Fixed issue with urlencode

2.5.0
-----

* Added logging
* Fixed Dyanmo minimum version dependency

2.4.0
-----

* Added support for async requests
* Using http_build_query() to build all query strings
* Allowing modification of return data through event

2.3.0
-----

* Fixed events to dispatch objects that can be modified

2.2.0
-----

* Allowed body object to be form encoded
* Added support for serializing \JsonSerializable
* Fixed issue with booleans getting cast to ints
* Fixed issue with serializing null body objects
* Improved generated code to exclude serialization contexts if not applicable

2.1.0
-----

* Added an event dispatcher
* Added a library specific exception

2.0.0
-----

* Removed composer wrapper
* Removed guzzle dependency (added support for either v5 or v6)
* Abstracted class generation into tebru/dynamo
* Fixed issue with query map
* Fixed issue with interface inheritance 

1.2.1
-----

* Upgraded twig to resolve security vulnerability

1.2.0
-----

* Added annotation for JMS serialization contexts


1.1.0
-----

* Added support for JMS serialization contexts

1.0.0
-----

* Mirrored functionality of Square's Retrofit library.
* PSR autoloading of generated classes
* Created binary tool for managing cache.
