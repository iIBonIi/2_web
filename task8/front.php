<?php

function front_get($request) {
  return array('headers' => array('Content-Type' => 'application/xml'), 'entity' => '<document />');
  return '123';
  return access_denied();
  return not_found();
}

function front_post($request) {
  return redirect('new-location');
}