<?php
function admin_get($request) {
  $params = [
    0 => ['Колонка 1', 'Колонка 2'],
    1 => ['Колонка 1', 'Колонка 2'],
    2 => ['Колонка 1', 'Колонка 2']];
  return theme('admin', ['admin' => $params]);
}

function admin_post($request, $url_param_1) {
  $id = intval($url_param_1);
  
  return redirect('admin');
}
