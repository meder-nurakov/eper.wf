<?php
class TextModel {

  // check id
  public static function id($id) {
    if ($id > 10000 || $id < 0) return false;
    else return $id;
  }

  // check mail
  public static function mail($mail){
    $mail = trim($mail);
    if (strlen($mail) == 0) return false;

    if (preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $mail)) return htmlspecialchars($mail);
    else return false;
  }


  // check phone
  public static function phone($a){
    if (strlen($a) == 0) return false;
    $a = preg_replace('~[^0-9]+~','',$a);

    // check
    if ((strlen($a) > 20) || (strlen($a) < 7)) return false;
    else {
      $a = '+996 ('.substr($a, strlen($a)-9, 3).') '.substr($a, strlen($a)-6, 3).' - '.substr($a, strlen($a)-3, 3);
      return htmlspecialchars($a);
    }
  }

  // check login
  public static function login($login){
    $login = trim($login);

    if ((strlen($login) < 7) || (strlen($login) > 25) || (!preg_match('/^[a-zA-Z0-9_.]{7,25}$/',$login)) || (substr($login,0,1) == '_') || (substr($login,0,1) == '.')) return false;
    else return htmlspecialchars(trim($login));
  }

  // check password
  public static function password($password){
    if (strlen($password) < 7 || strlen($password) > 32) return false;
    else return md5($password);
  }
}
?>