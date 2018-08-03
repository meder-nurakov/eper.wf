<?php
/**
* 
*/
class ShopModel {

  // check name
  public static function name($name){
    if (strlen($name) == 0)
      return false;
    $name = trim($name);

    if (strlen($name) > 40) return false;
    else return htmlspecialchars($name);
  }


  /*Проверка на существование пользывотеля*/
  public static function CPhone($phone) {
    require_once(ROOT.'/db.php');
    $db = Db::connect();

    $sql = "SELECT user_id FROM user WHERE phone = :phone;";

    $result = $db->prepare($sql);
    $result->bindParam(":phone", $phone, PDO::PARAM_STR);
    $result->execute();

    $row = $result->fetch();
    if ($row) {
      return $row['user_id'];
    }
    else return false;
  }

  /*проверка телефон*/
  public static function whatsapp($a){
    $a = preg_replace('~[^0-9]+~','',$a);

    return htmlspecialchars(trim($a));
  }

  /*link*/
  public static function link($link) {
    $link = trim($link);

    if (strlen($link) > 140 || strlen($link) == 0) return false;
    else return htmlspecialchars($link);
  }

  /*Check work place*/
  public static function wp($place){
    return htmlspecialchars(trim($place));
  }

  /*Check position*/
  public static function position($position){
    return htmlspecialchars(trim($position));
  }

  /**/
  public static function social_up($social) {

    require(ROOT.'/db.php');
    $db = Db::connect();

    $sql = "UPDATE user SET facebook = :facebook, instagram = :instagram, vk = :vk, ok = :ok WHERE user_id = :uId;";

    $result = $db->prepare($sql);
    $result->bindParam(":facebook", $social['facebook'], PDO::PARAM_STR);
    $result->bindParam(":instagram", $social['instagram'], PDO::PARAM_STR);
    $result->bindParam(":vk", $social['vk'], PDO::PARAM_STR);
    $result->bindParam(":ok", $social['ok'], PDO::PARAM_STR);

    $result->bindParam(":uId", $social['uId'], PDO::PARAM_INT);

    if ($result->execute()){
        return true;
    } else return false;  
  }

  /*Update user avatar*/
  public static function ava_up($uId, $ava) {
      require(ROOT.'/db.php');
      $db = Db::connect();

      $sql = "UPDATE user SET avatar = :ava WHERE user_id = :uId;";

      $result = $db->prepare($sql);
      $result->bindParam(":ava", $ava, PDO::PARAM_STR);
      $result->bindParam(":uId", $uId, PDO::PARAM_INT);

      if ($result->execute()){
          return true;
      } else return false; 
  }

  /*get Id*/
  public static function getId($phone) {
      require_once(ROOT.'/db.php');
      $db = Db::connect();

      $sql = "SELECT user_id FROM user WHERE phone = :phone";

      $result = $db->prepare($sql);
      $result->bindParam(":phone", $phone, PDO::PARAM_STR);

      $result->execute();
      $row = $result->fetch();

      return $row['user_id'];
  }

  public static function getList($sort){
    if (isset($sort['filter'])) {
      $filter = 'WHERE '.$sort['filter'];
    }

    /*user list sort limit*/
    if (isset($sort['limit'])) $limit = ' LIMIT '.$sort['limit'];
    else $limit = " LIMIT 10";

    if (isset($sort['table'])) $sort = $sort['table'];
    else $sort = 'dr DESC';

    require_once(ROOT.'/db.php');
    $db = Db::connect();

    $uList = array();

    $sql = "SELECT user_id, name, fname, gender, avatar, db, city, phone, mail FROM user ";
    
    if (isset($filter)) $sql = $sql.$filter;

    $sql = $sql." ORDER BY ".$sort.$limit;

    $result = $db->prepare($sql);
    $result->execute();

    $i = 0;
    $uList['cman'] = 0;
    $uList['cwomen'] = 0;
    while($row = $result->fetch()){
      /*Данные поста*/
      $uList[$i]['user_id'] = $row['user_id'];
      $uList[$i]['name'] = $row['name'];
      $uList[$i]['fname'] = $row['fname'];

      /*user avatar*/
      if (isset($row['avatar'])) {
          $uList[$i]['avatar'] = $row['user_id'].'.'.$row['avatar'];
      } else {
          if ($row['gender'] == '1')
              $uList[$i]['avatar'] = 'man.png';
              else $uList[$i]['avatar'] = 'women.png';
      }

      /*user gender*/
      if ($row['gender'] == '1') {
        $uList['cman']++;
      } else {
        $uList['cwomen']++;
      }

      $uList[$i]['db'] = $row['db'];
      $uList[$i]['phone'] = $row['phone'];
      $uList[$i]['mail'] = $row['mail'];
      $uList[$i]['city'] = $row['city'];
      
      $uList[$i]['city'] = User::getCity($row['city']);

      $i++;
    }

    $uList['count'] = $i;

    return $uList;
  }

  public static function get($uId) {
    require_once(ROOT.'/db.php');
    $db = Db::connect();

    $uData = array();

    $sql = "SELECT user_id, name, fname, gender, avatar, db, city, phone, mail, facebook, wp, position, dr FROM user WHERE user_id = :user_id";

    $result = $db->prepare($sql);
    $result->bindParam(":user_id", $uId, PDO::PARAM_STR);

    $result->execute();
    $row = $result->fetch();
    
    /*Данные поста*/
    $uData['uId'] = $row['user_id'];
    $uData['name'] = $row['name'];
    $uData['fname'] = $row['fname'];

    if ($row['gender'] == '1') $uData['gender'] = 'Эркек';
    else $uData['gender'] = 'Аял';

    if (isset($row['avatar'])) {
      $uData['avatar'] = $uId.'.'.$row['avatar'];
    } else {
      if ($row['gender'] == '1')
          $uData['avatar'] = 'man.png';
      else $uData['avatar'] = 'women.png';
    }

    if ($row['db'] == '') $uData['db'] = 'Не указан';
    else $uData['db'] = $row['db'];

    /*user city*/
    $uData['city'] = $row['city'];

    $uData['phone'] = $row['phone'];

    /**/
    if ($row['mail'] == '') $uData['mail'] = 'Не указан';
    else $uData['mail'] = $row['mail'];

    $uData['facebook'] = $row['facebook'];
    $uData['wp'] = $row['wp'];
    $uData['position'] = $row['position'];

    return $uData;
  }

  /*get user data on category*/
  public static function getd($uId, $category) {
    require_once(ROOT.'/db.php');
    $db = Db::connect();

    $uData = array();
    switch ($category) {
      case 'base':
        $sql = "SELECT user_id, name, gender, avatar, db, about, phone, mail, city, facebook, instagram, vk, ok FROM user ";
        break;
      case 'contact':
        $sql = "SELECT user_id, name, gender, avatar, phone, phone_add, whatsapp, country, city, life, mail, facebook, instagram, vk, ok, skype FROM user ";
        break;
      default:
        # code...
        break;
    }

    $sort = 'WHERE user_id = :uId';
    $sql.=$sort;

    $result = $db->prepare($sql);
    $result->bindParam(":uId", $uId, PDO::PARAM_STR);

    $result->execute();
    $row = $result->fetch();
    
    /*Данные поста*/
    switch ($category) {
      case 'base':
        $uData['uId'] = $row['user_id'];
        $uData['name'] = $row['name'];

        if ($row['gender'] == '1') $uData['gender'] = 'Эркек';
        else $uData['gender'] = 'Аял';

        if (isset($row['avatar'])) {
          $uData['avatar'] = $uId.'.'.$row['avatar'];
        } else {
          if ($row['gender'] == '1')
              $uData['avatar'] = 'man.png';
              else $uData['avatar'] = 'women.png';
        }

        if ($row['db'] == '') $uData['db'] = 'Не указан';
        else $uData['db'] = $row['db'];

        $uData['about'] = $row['about'];

        /*contact*/
        $uData['city'] = User::getCity($row['city']);
        
        $uData['phone'] = $row['phone'];
        if ($row['mail'] != NULL) $uData['mail'] = $row['mail'];
        else  $uData['mail'] = false;

        /*Соцсети*/
        $uData['facebook'] = $row['facebook'];
        $uData['instagram'] = $row['instagram'];
        $uData['vk'] = $row['vk'];
        $uData['ok'] = $row['ok'];
        break;
      
      case 'contact':
        $uData['uId'] = $row['user_id'];
        $uData['name'] = $row['name'];
        $uData['gender'] = $row['gender'];

        if (isset($row['avatar'])) {
          $uData['avatar'] = $uId.'.'.$row['avatar'];
        } else {
          if ($row['gender'] == '1')
              $uData['avatar'] = 'man.png';
              else $uData['avatar'] = 'women.png';
        }

        $uData['whatsapp'] = $row['whatsapp'];

        /*contact*/
        /*country*/
        switch ($row['country']) {
          case '1':
            $uData['country'] = 'Кыргызстан';
            break;

          case '2':
            $uData['country'] = 'Казахстан';
            break;
          
          default:
            $uData['country'] = 'Башка Мамлекет';
            break;
        }

        /*city*/
        switch ($row['city']) {
          case '1':
            $uData['city'] = 'Бишкек';
            break;

          case '2':
            $uData['city'] = 'Ош';
            break;
          
          default:
            $uData['city'] = 'Башка Шаар';
            break;
        }
        $uData['life'] = $row['life'];
        $uData['phone'] = $row['phone'];
        $uData['phone_add'] = $row['phone_add'];

        /*Соцсети*/
        $uData['facebook'] = $row['facebook'];
        $uData['instagram'] = $row['instagram'];
        $uData['vk'] = $row['vk'];
        $uData['ok'] = $row['ok'];
        break;
    }

    return $uData;
  }

  /*get Mail*/
  public static function getData($uId) {
      require_once(ROOT.'/db.php');
      $db = Db::connect();

      $sql = "SELECT name, fname, mail FROM user WHERE user_id = :uId";

      $dUser = array();
      $result = $db->prepare($sql);
      $result->bindParam(":uId", $uId, PDO::PARAM_STR);

      $result->execute();
      $row = $result->fetch();
      
      /*User Mail*/
      $dUser['name'] = $row['name'];
      $dUser['fname'] = $row['fname'];
      $dUser['mail'] = $row['mail'];

      return $dUser;
  }

  /*Регистрация пользывотелья*/
  public static function subscrip($mail){
      require_once(ROOT.'/db.php');
      $db = Db::connect();

      $sql = "INSERT INTO subscrip (subscrip_id, mail, dr)
      VALUES (NULL, :mail, now());";

      $result = $db->prepare($sql);
      $result->bindParam(":mail", $mail, PDO::PARAM_STR);

      if ($result->execute()){
          return true;
      } else return false;    
  }

  /**/
  public static function PayList($user_id) {
      require_once(ROOT.'/db.php');
      $db = Db::connect();

      $payList = array();

      $sql = "SELECT bay_id, price, dr FROM bay WHERE user_id = :user_id";

      $result = $db->prepare($sql);
      $result->bindParam(":user_id", $user_id, PDO::PARAM_INT);

      $result->execute();
      
      $i = 0;
      while($row = $result->fetch()){
          /*Данные поста*/
          $payList[$i]['bay_id'] = $row['bay_id'];
          $payList[$i]['price'] = $row['price'];
          $payList[$i]['dr'] = $row['dr'];

          $i++;
      }

      return $payList;
  }

  /*Регистрация пользывотелья*/
  public static function reg($dUser){
    require_once(ROOT.'/db.php');
    $db = Db::connect();

    $sql = "INSERT INTO user (user_id, name, fname, gender, country, city, phone, mail, dr) 
    VALUES (NULL, :name, :fname, :gender, '1', :city, :phone, :mail, now());";

    $result = $db->prepare($sql);
    $result->bindParam(":name", $dUser['name'], PDO::PARAM_STR);
    $result->bindParam(":fname", $dUser['fname'], PDO::PARAM_STR);
    $result->bindParam(":gender", $dUser['gender'], PDO::PARAM_STR);
    
    $result->bindParam(":city", $dUser['city'], PDO::PARAM_INT);
    $result->bindParam(":phone", $dUser['phone'], PDO::PARAM_STR);
    $result->bindParam(":mail", $dUser['mail'], PDO::PARAM_STR);

    if ($result->execute()){
      return $db->lastInsertId(); 
    } else return false;
  }
  
  // user login
  public static function user_login($data){
    if (!$data['phone'])
      return false;

    $db = Db::connect();
    
    $sql = "SELECT * FROM `users` WHERE `phone` = :phone";
   
    $result = $db->prepare($sql);
    
    $result->bindParam(":phone", $data['phone']);
    
    if ($result->execute()){
      $row = $result->fetch();

      if ($row['password'] == $data['password'])
      {
        return $row;
      }
      else
        return false;
    }
    else
    {
      return false;
    }
  }

  // user registry
  public static function user_registry($data){
    $db = Db::connect();

    //print_r($data);

    $sql = "INSERT INTO users (`id`, `password`, `name`, `phone`, `mail`, `dr`) 
    VALUES (NULL, :password, :name, :phone, :mail, :dr);";

    $result = $db->prepare($sql);

    $result->bindParam(":password", $data['password'], PDO::PARAM_STR);
    
    $result->bindParam(":name", $data['name'], PDO::PARAM_INT);

    $result->bindParam(":phone", $data['phone'], PDO::PARAM_STR);
    $result->bindParam(":mail", $data['mail'], PDO::PARAM_INT);

    $result->bindParam(":dr", $data['dr'], PDO::PARAM_INT);

    if ($result->execute()){
      return $db->lastInsertId(); 
    } else return false;
  }

  // shop registry
  public static function registry($data){
    $db = Db::connect();

    $sql = "INSERT INTO shop (`id`, `login`, `password`, `name`, `phone`, `mail`, `dr`) 
    VALUES (NULL, :login, :password, :name, :phone, :mail, :dr);";

    $result = $db->prepare($sql);

    $result->bindParam(":login", $data['login'], PDO::PARAM_STR);
    $result->bindParam(":password", $data['password'], PDO::PARAM_STR);
    
    $result->bindParam(":name", $data['name'], PDO::PARAM_INT);

    $result->bindParam(":phone", $data['phone'], PDO::PARAM_STR);
    $result->bindParam(":mail", $data['mail'], PDO::PARAM_INT);

    $result->bindParam(":dr", $data['dr'], PDO::PARAM_INT);

    if ($result->execute()) return $db->lastInsertId(); 
    else return false;
  }

  /**/
  public static function login($phone, $password){
    $db = Db::connect();

    $dUser = array();

    $sql = "SELECT user_id, name FROM user WHERE phone = :phone AND password = :password LIMIT 1";

    $result = $db->prepare($sql);
    $result->bindParam(":phone", $phone, PDO::PARAM_STR);
    $result->bindParam(":password", $password, PDO::PARAM_STR);

    $result->execute();
    $row = $result->fetch();
    if ($row){
      $dUser['name'] = $row['name'];
      $dUser['uId'] = $row['user_id'];

      return $dUser;
    } else return false;
  }

  public static function session(){
      $db = Db::connect();

      $sql = "INSERT INTO user_session (user_id, ip, user_agent, dr) VALUES (:uId, :ip, :uAgent, now());";

      $result = $db->prepare($sql);
      $result->bindParam(":uId", $aId, PDO::PARAM_INT);
      $result->bindParam(":ip", $_SERVER['SERVER_ADDR'], PDO::PARAM_STR);
      $result->bindParam(":uAgent", $_SERVER['HTTP_USER_AGENT'], PDO::PARAM_STR);

      if ($result->execute()) return true;
      else return false;
  }

  /*Add password for user*/
  /*public static function apu() {
      require_once(ROOT.'/db.php');
      require(ROOT.'/function/all/gen.php');
      $db = Db::connect();

      $pData = array();

      $sql = "SELECT user_id, phone FROM user";

      $lUser = $db->prepare($sql);
      $lUser->execute();

      while($row = $lUser->fetch()) {
          $password = code('3cvy7Xx', passgen($row['user_id'], $row['phone']));

          $sql = "UPDATE user SET password = :password WHERE user_id = :uId;";

          $result = $db->prepare($sql);

          $result->bindParam(":uId", $row['user_id'], PDO::PARAM_INT);
          $result->bindParam(":password", $password , PDO::PARAM_INT);

          $result->execute();
      }
  }*/
}
?>