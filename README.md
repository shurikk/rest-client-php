Basic REST client using pecl http module
========================================

Simple implementation that supports PUT, GET, POST, DELETE and uses pecl
http module functions instead of cURL

Examples
--------

    require 'rest_client.php';
    $c = new RestClient();

GET request

    $res = $c->get('http://www.yahoo.com');

Posting raw POST data

    $res = $c->post(
      'http://api.example.com/create', json_encode(array('name' => 'foobar'))
    );

Sending a form using POST

    $res = $c->post(
      'http://www.example.com/form', array('name' => 'foobar'))
    );

Sending custom HTTP headers

    $res = $c->post(
      'http://www.example.com/form', json_encode(array('name' => 'foobar')),
      array(
        'headers' => array(
          'X-My-App' => 'foobar/1.0',
          'Content-type' => 'application/json'
        )
      )
    );

References
----------

* http://pecl.php.net/package/pecl_http
* http://www.php.net/manual/en/http.request.options.php

License
-------

Released under the MIT license.


Contributors
------------

* [Alexander Kabanov](http://github.com/shurikk)

