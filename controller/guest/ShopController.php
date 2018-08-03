<?php
class ShopController {
  function RegistryAction() {
    Connect::head();

    Connect::view('d', 'header');
    Connect::view('', 'shop/registry');
    Connect::view('d', 'footer');
  }

  // registry handler
  function RegAction() {
    if (isset($_POST['reg'])) {
      Connect::model('shop');
      Connect::model('text');

      $data = array();
      $keys = array("login", 'password', 'name', "phone", "mail");

      // check
      foreach ($keys as $key) {

        switch ($key) {
          case 'name': $data[$key] = ShopModel::name($_POST[$key]); break;
          case 'login': $data[$key] = TextModel::login($_POST[$key]); break;
          case 'phone': $data[$key] = TextModel::phone($_POST[$key]); break;
          case 'mail': $data[$key] = TextModel::mail($_POST[$key]); break;
          case 'password': $data[$key] = TextModel::password($_POST[$key]); break;
        }

        if (!$data[$key]) {
          $_SESSION['sreg'][$key] = 'error';
          header("Location: ".$_SERVER['HTTP_REFERER']);
        }
      }

      $data['dr'] = time();

      $shop_id = ShopModel::registry($data);
      if ($shop_id) {

        // sessions
        $_SESSION['user'] = 'shop';
        
        // redirect
        header("Location: /");
      } else {

        // redirect
        header("Location: ".$_SERVER['HTTP_REFERER']);
      }
    }
  }

  // result
 function SregAction() {
    Connect::head();

    Connect::view('d', 'header');
    Connect::view('', 'shop/sreg');
    Connect::view('d', 'footer');
  }
}
?>