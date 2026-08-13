<?php

/*
|--------------------------------------------------------------------------
| Book SOAP api
|--------------------------------------------------------------------------
|
| Both ends of the book import read this file. `public/soap.php` uses it to
| check the basic auth credentials on an incoming request, and the importers
| use it to build the `BookClient` that calls back into that same service.
|
| Every value is read straight from the environment with no fallback, so a
| key missing from `.env` is a fatal error rather than a silent default.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Base url
    |--------------------------------------------------------------------------
    |
    | Where the importers reach the service, with no trailing slash — the
    | client appends `/soap.php` and `/soap.php?wsdl` itself. The WSDL
    | advertises localhost, which is not reachable from another container, so
    | this is also what overrides the endpoint the client sends to.
    |
    */
    'baseUrl' => $_ENV['SOAP_BASEURL'],

    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    |
    | The single basic auth pair the service accepts and the importers send.
    | Changing either one means changing it for both, as the api and the
    | importer containers read the same variables.
    |
    */
    'username' => $_ENV['SOAP_USERNAME'],
    'password' => $_ENV['SOAP_PASSWORD'],
];
