<?php
switch ($_SESSION['user']) {
  case 'guest':
    return array(
      'do' => 'user/do',
      'user/add_box' => 'user/add_box',
      'box' => 'user/box',
      
      'kalem' => 'user/shop',
      'user/product' => 'user/product',
      'user/logout' => 'user/logout',   
      'user/sreg' => 'user/sreg',
      'user/slogin' => 'user/slogin',

      'contact' => 'contact/page',
      'shop/reg' => 'shop/reg',
      'shop/sreg' => 'shop/sreg',
      'shop/registry' => 'shop/registry',
      
      'login' => 'user/login',
      'registry' => 'user/registry',
      
      '' => 'index/index',
    );
    break;

  case 'shop':
    return array(
      
      'products' => 'product/list',
      'product' => 'product/page',

      '' => 'index/index',
    );
    break;
}
?>