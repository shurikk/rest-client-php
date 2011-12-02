Basic REST client using pecl http module
========================================

Simple implementation that supports PUT, GET, POST, DELETE and uses pecl
http module functions instead of cURL. Inspired by ruby rest client https://github.com/archiloque/rest-client

Supports GET, POST, PUT, DELETE HTTP methods. Basic HTTP authentication is done via
pecl http module settings, see examples below. Constructor accepts all *http_options*
from http://us.php.net/manual/en/http.request.options.php . Easy to extend.

Detailed response information and [response object](http://us.php.net/manual/en/class.httpresponse.php)
are respectively in *$client->response_info* and *$client->response_object*.
Raw response data is available in *$client->response_raw*

Examples
--------

    require 'rest_client.php';
    $c = new RestClient();

*GET request*

    $res = $c->get('http://www.yahoo.com');

*Posting raw POST data*

    $res = $c->post(
      'http://api.example.com/create', json_encode(array('name' => 'foobar'))
    );

*Sending a form using POST*

    $res = $c->post(
      'http://www.example.com/form', array('name' => 'foobar'))
    );

*Sending custom HTTP headers*

    $res = $c->post(
      'http://www.example.com/form', json_encode(array('name' => 'foobar')),
      array(
        'headers' => array(
          'X-My-App' => 'foobar/1.0',
          'Content-type' => 'application/json'
        )
      )
    );

*Basic HTTP authentication*

    $res = $c->post(
      'http://www.example.com/form', json_encode(array('name' => 'foobar')),
      array(
        'httpauth' => 'username:password'
      )
    );

*PUT request*

    $res = $c->put(
      'http://api.example.com/create', 'PUT request data'
    );
    
*DELETE request*

    $res = $c->delete(
      'http://api.example.com/remove', 'PUT request data'
    );

*RAW response data*

    $res = $c->get(
      'http://www.example.com/upload.txt'
    );

    echo $c->response_raw;

References
----------

* http://pecl.php.net/package/pecl_http
* http://www.php.net/manual/en/http.request.options.php
* https://github.com/archiloque/rest-client

License
-------

Released under the MIT license.


Contributors
------------

* [Alexander Kabanov](http://github.com/shurikk)

