<?php
class IndexController {
  function IndexAction() {
    Connect::head();

    Connect::view('d', 'sidebar');

    echo '<div id="page__layout">';

    Connect::view('d', 'header');
    
    echo '<div>';
  }
}
?>