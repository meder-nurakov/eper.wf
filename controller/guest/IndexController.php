<?php
class IndexController {
  function IndexAction() {
    Connect::head();

    Connect::view('d', 'header');

    	Connect::view('lp', 'index');
    
    Connect::view('d', 'footer');
  }
}
?>