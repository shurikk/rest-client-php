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
  const AGENT = 'rest-client-php/0.0.1';
  
  public $response_info;
  public $resonse_object;

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
    return $this->resonse_object->body;
  }

  function post($url, $fields = array(), $http_options = array()) {
    $http_options = array_merge($this->http_options, $http_options);
    $res = is_array($fields) ? 
      http_post_fields($url, $fields, array(), $http_options, $this->response_info) :
      http_post_data($url, $fields, $http_options, $this->response_info);
    $this->http_parse_message($res);
    return $this->resonse_object->body;
  }
  
  function put($url, $data = '', $http_options = array()) {
    $http_options = array_merge($this->http_options, $http_options);
    $this->http_parse_message(
      http_put_data($url, $data, $http_options, $this->response_info)
    );
    return $this->resonse_object->body;
  }

  function delete($url, $http_options = array()) {
    $http_options = array_merge($this->http_options, $http_options);
    $this->http_parse_message(
      http_request(HTTP_METH_DELETE, $url, '', $http_options, $this->response_info)
    );
    return $this->resonse_object->body;
  }

  function http_parse_message($res) {
    
    $this->resonse_object = http_parse_message($res);

    if($this->resonse_object->responseCode == 404) {
      throw new HttpServerException404(
        $this->resonse_object->responseStatus
      );
    }

    if($this->resonse_object->responseCode >= 400 && $this->resonse_object->responseCode <=600) {
      throw new HttpServerException(
        $this->resonse_object->responseStatus,
        $this->resonse_object->responseCode
      );
    }

    if(!in_array($this->resonse_object->responseCode, range(200,207))) {
      throw new RestClientException(
        $this->resonse_object->responseStatus,
        $this->resonse_object->responseCode
      );
    }
  }
}
