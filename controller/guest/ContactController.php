<?
	class ContactController
	{
		function PageAction()
		{
			Connect::head();

  			Connect::view('d', 'header');
  			Connect::view('', 'contact');
    		Connect::view('d', 'footer');
		}
	}
?>