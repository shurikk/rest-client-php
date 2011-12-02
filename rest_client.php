<?php

class HttpServerException extends Exception {
}

class HttpServerException404 extends Exception {
  function __construct($message = 'Not Found') {
    parent::__construct($message, 404);
  }
}

class RestClientException extends Exception {
}

class RestClient {

  const COOKIE_JAR = '/tmp/rest-client-cookie';
  const AGENT = 'rest-client-php/0.0.2';
  
  public $response_info;
  public $response_object;
  public $response_raw;

  public $http_options = array();

  function __construct($http_options = array()) {
    $this->http_options = array_merge(array(
      'cookiestore' => self::COOKIE_JAR,
      'useragent' => self::AGENT,
      'redirect' => 5
    ), $http_options);
  }
  
  function get($url, $http_options = array()) {
    $http_options = array_merge($this->http_options, $http_options);
    $this->http_parse_message(
      http_get($url, $http_options, $this->response_info)
    );
    return $this->response_object->body;
  }

  function post($url, $fields = array(), $http_options = array()) {
    $http_options = array_merge($this->http_options, $http_options);
    $res = is_array($fields) ? 
      http_post_fields($url, $fields, array(), $http_options, $this->response_info) :
      http_post_data($url, $fields, $http_options, $this->response_info);
    $this->http_parse_message($res);
    return $this->response_object->body;
  }
  
  function put($url, $data = '', $http_options = array()) {
    $http_options = array_merge($this->http_options, $http_options);
    $this->http_parse_message(
      http_put_data($url, $data, $http_options, $this->response_info)
    );
    return $this->response_object->body;
  }

  function delete($url, $http_options = array()) {
    $http_options = array_merge($this->http_options, $http_options);
    $this->http_parse_message(
      http_request(HTTP_METH_DELETE, $url, '', $http_options, $this->response_info)
    );
    return $this->response_object->body;
  }

  function http_parse_message($res) {
    $this->response_raw = $res;  
    $this->response_object = http_parse_message($res);

    if($this->response_object->responseCode == 404) {
      throw new HttpServerException404(
        $this->response_object->responseStatus
      );
    }

    if($this->response_object->responseCode >= 400 && $this->response_object->responseCode <=600) {
      throw new HttpServerException(
        $this->response_object->responseStatus,
        $this->response_object->responseCode
      );
    }

    if(!in_array($this->response_object->responseCode, range(200,207))) {
      throw new RestClientException(
        $this->response_object->responseStatus,
        $this->response_object->responseCode
      );
    }
  }
}
