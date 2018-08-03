<?php
class ProductController {
  function ListAction() {
    Connect::head();

    Connect::view('d', 'sidebar');

    echo '<div id="page__layout">';

    Connect::view('d', 'header');

    Connect::view('', 'products');
    
    echo '<div>';

  }

  
  function PageAction() {
    
  }
}
?>