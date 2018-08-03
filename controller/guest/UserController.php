<?php
class UserController 
{
  function RegistryAction() {
    Connect::head();

    Connect::view('d', 'header');
    Connect::view('', 'user/registry');
    Connect::view('d', 'footer');
  }

  // user login
  function LoginAction() {
    Connect::head();
    
    Connect::view('d', 'header');
    if (isset($_COOKIE['user_is']) && $_COOKIE['user_is']) {
      Connect::view('', 'user/log_out');
    }
    else {
      Connect::view('', 'user/login');
      Connect::view('', 'user/registry');
    }
    Connect::view('d', 'footer');
  }


  // logout
  function LogoutAction() {
    if (isset($_POST['logout']))
    {
      setcookie("user_is", 0, time() + (86400 * 99), "/");
      setcookie("user_id", "", time() + (86400 * 99), "/");
      setcookie("user_name", "", time() + (86400 * 99), "/");
      setcookie("user_phone", "", time() + (86400 * 99), "/");
      setcookie("user_mail", "", time() + (86400 * 99), "/");
      
      header("Location: http://eper.com");
    }
  }

  //product delete and buy action
  function DoAction() {
    $db = db::connect();

    $id = $_GET['id'];

    if (isset($_POST['is_del']))
    {
      if (isset($_COOKIE['user_is']) && $_COOKIE['user_is'])
      {
        $sql = "DELETE FROM want_buy WHERE product_id = $id";
        $result = $db->prepare($sql);
        $result -> execute();
      }
      else
      {
        for($i = 0; $i < sizeof($_SESSION['box_product']); $i++)
        {
          if ($_SESSION['box_product'][$i] == $id)
          {
            $_SESSION['box_was_deleted'][$i] = 1;
          }
        }
      }
    }
    if (isset($_POST['is_buy']))
    {
      $s = $_POST['number'];
      $num = 0;

      for ($i = 0; $i < strlen($s) - 6; $i++)
      {
        $num *= 10;
        $num += ($s[$i]);
      }

      $t = time();
      
      if (isset($_COOKIE['user_is']) && $_COOKIE['user_is'])
      {
        $uid = $_COOKIE['user_id'];
        $time = time();
        $sql = "INSERT INTO `eper`.`buy` (`id`, `product_id`, `number`, `user_id`, `time`, `shop_id`, `dr`) VALUES (NULL, $id, $num, $uid, $t, 1, $time);";

        $result = $db -> prepare($sql);
        $result -> execute();
      }
      else
      {
        $_SESSION['is_buy'] = $id;
        header("Location: http://eper.com/login");
        exit();
      }
    }

    header("Location: ".$_SERVER['HTTP_REFERER']);
  }

  // shop profile
  function ShopAction() {
    Connect::head();
    Connect::view('d', 'header');
    Connect::view('', 'user/product');
    Connect::view('d', 'footer');

  }

  // user check login
  function SloginAction() {
    if (isset($_POST['login']))
    {
        Connect::model('shop');

        $data = array();

        $data['phone'] = ShopModel::phone($_POST['phone']);
        $data['password'] = ShopModel::password($_POST['password']);

        $user = ShopModel::user_login($data);

        if (!$user)
          header('Location:'.$_SERVER['HTTP_REFERER']);
        else
        {
          setcookie("user_is", 1, time() + (86400 * 99), "/");
          setcookie("user_id", $user['id'], time() + (86400 * 99), "/");
          setcookie("user_name", $user['name'], time() + (86400 * 99), "/");
          setcookie("user_phone", $user['phone'], time() + (86400 * 99), "/");
          setcookie("user_mail", $user['mail'], time() + (86400 * 99), "/");

          if (isset($_SESSION['is_buy']) && $_SESSION['is_buy'])
          {
            $db = Db::connect();

            for ($i = 0; $i < sizeof($_SESSION['box_product']); $i++)
            {
              if ($_SESSION['box_was_deleted'][$i])
                continue;

              $pid = $_SESSION['box_product'][$i];
              $uid = $user['id'];
              $t = $_SESSION['box_time'][$i];
              $number = $_SESSION['box_number'][$i];

              if ($_SESSION['is_buy'] == $pid)
              {
                $time = time();

                $sql = "INSERT INTO `eper`.`buy` (`id`, `product_id`, `number`, `user_id`, `time`, `shop_id`, `dr`) VALUES (NULL, $pid, $number, $uid, $t, 1, $time);"; 

                $result = $db->prepare($sql);
                $result -> execute();
              }
              else
              {
                $sql = "INSERT INTO `eper`.`want_buy` (`id`, `product_id`, `user_id`, `number`, `dr`) VALUES (NULL, $pid, $uid, $number, $t);";
                $result = $db->prepare($sql);
                $result -> execute();
              }
            }

            header("Location: http://eper.com/box"); 
            exit();
          }
        }

      header("Location: http://eper.com");
    }
  }

  // box

  function BoxAction(){
    Connect::head();

    Connect::view('d', 'header');
    Connect::view('', 'user/box_product');
    Connect::view('d', 'footer'); 
  }

  // add box

  function Add_boxAction()
  {
    if (!isset($_GET['id']) || !isset($_POST['add_box']))
    {
      return;
    }

    $id = $_GET['id'];
    $s = $_POST['number'];
    $num = 0;

    for ($i = 0; $i < strlen($s) - 6; $i++)
    {
        $num *= 10;
        $num += ($s[$i]);
    }

    $_POST['number'] = $num;

    if (isset($_COOKIE['user_is']) && $_COOKIE['user_is'])
    {
      $db = Db::connect();
      $number = $_POST['number'];

      $uid = $_COOKIE['user_id'];

      $t = time();

      $sql = "INSERT INTO `want_buy` (`id`, `product_id`, `user_id`, `dr`, `number`) VALUES (NULL, $id, $uid, $t, $number);";
      
      $result = $db->prepare($sql);
      $result->execute();
    }
    else
    {
      if (!isset($_SESSION['box_product']) || !isset($_SESSION['box_time']) || !isset($_SESSION['box_number']) || !isset($_SESSION['box_was_deleted']))
      {
        $_SESSION['box_product'] = $_SESSION['box_time'] = $_SESSION['box_number'] = $_SESSION['box_was_deleted'] = array();
      }

      $bad = 0;

      for ($i = 0; $i < sizeof($_SESSION['box_product']); $i++)
      {
        if ($_SESSION['box_product'][$i] == $id && !$_SESSION['box_was_deleted'][$i])
        {
          $bad = 1;
          $_SESSION['box_number'][$i] += $_POST['number'];
          
          if ($_SESSION['box_number'][$i] > 100)
            $_SESSION['box_number'][$i] = 100;
        }
      }

      if (!$bad)
      {
        array_push($_SESSION['box_product'], $id);
        array_push($_SESSION['box_time'], time()); 
        array_push($_SESSION['box_number'], $_POST['number']);
        array_push($_SESSION['box_was_deleted'], 0);
      }
    }
    if (isset($_SERVER['HTTP_REFERER']))
      header("Location: ".$_SERVER['HTTP_REFERER']);
  }

  // Product Profile

  function ProductAction(){
    Connect::head();
     Connect::view('d', 'header');
    
    if (!isset($_SESSION['box_product']))
    {
      $_SESSION['box_product'] = $_SESSION['box_time'] = $_SESSION['box_number'] = array();
    }

    if (isset($_GET['id']))
    {
      $_SESSION['product_id'] = $_GET['id'];
       Connect::view('', 'user/product_profile');
    }
    Connect::view('d', 'footer');
  }

  // user registry
  function SregAction() 
  {
    if (isset($_POST['reg']))
    {
      Connect::model('shop');
        
      $data = array();
      $keys = array("name", "phone", "mail", "password");

      foreach ($keys as $key)
      {
        switch ($key)
        {
          case 'name': $data[$key] = ShopModel::name($_POST[$key]); break;
          case 'phone': $data[$key] = ShopModel::phone($_POST[$key]); break;
          case 'mail': $data[$key] = ShopModel::mail($_POST[$key]); break;
          case 'password': $data[$key] = ShopModel::password($_POST[$key]); break;
        }
          
        if (!$data[$key]) 
        {
            $_SESSION['sreg'][$key] = 'error';
            header("Location: ".$_SERVER['HTTP_REFERER']);
        }
      }

      $data['dr'] = time();

      $user_id = ShopModel::user_registry($data);

      if (!$user_id)
        header("Location: ".$_SERVER['HTTP_REFERER']);
      else 
      {
        setcookie("user_is", 1, time() + (86400 * 99), "/");
        setcookie("user_id", $user_id, time() + (86400 * 99), "/");
        setcookie("user_name", $data['name'], time() + (86400 * 99), "/");
        setcookie("user_phone", $data['phone'], time() + (86400 * 99), "/");
        setcookie("user_mail", $data['mail'], time() + (86400 * 99), "/");

        if (isset($_SESSION['is_buy']) && $_SESSION['is_buy'])
        {
          $db = Db::connect();

          for ($i = 0; $i < sizeof($_SESSION['box_product']); $i++)
          {
            if ($_SESSION['box_was_deleted'][$i])
                  continue;

              $pid = $_SESSION['box_product'][$i];
            $uid = $user_id;
            $t = $_SESSION['box_time'][$i];
            $number = $_SESSION['box_number'][$i];

            if ($_SESSION['is_buy'] == $pid)
            {
              $time = time();
              $sql = "INSERT INTO `eper`.`buy` (`id`, `product_id`, `number`, `user_id`, `time`, `shop_id`, `dr`) VALUES (NULL, $pid, $number, $uid, $t, 1, $time);";
              $result = $db->prepare($sql);
              $result -> execute();
            }
            else
            {
              $sql = "INSERT INTO `eper`.`want_buy` (`id`, `product_id`, `user_id`, `number`, `dr`) VALUES (NULL, $pid, $uid, $number, $t);";
              $result = $db->prepare($sql);
              $result -> execute();
            }
          }

          header("Location: http://eper.com/box"); 
          exit();
        }
      }

      header("Location: http://eper.com");
    }
  }
}
?>