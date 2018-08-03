<?php
/**
 * 
 */
class HeadModel {
  
  public static function get($url) {
    $filter = ' WHERE url = "'.$url.'"';
    $limit = ' LIMIT 1';

    $db = Db::connect();

    $sql = "SELECT `title`, `descrip`, `keywords`, `index` FROM head";

    $sql = $sql.$filter.$limit;

    $result = $db->prepare($sql);
    // check
    if ($result->execute()){
      $row = $result->fetch();

      $dHead = array();

      $dHead['title'] = $row['title'];
      $dHead['descrip'] = $row['descrip'];
      $dHead['keywords'] = $row['keywords'];
      $dHead['index'] = $row['index'];

      return $dHead;

    } else return false;
  }
}
?>