<?php
require 'BaseController.php';
class HomeController extends BaseController {
	public function indexAction(){
		$this->_helper->redirector->gotoRoute(array('controller' => 'campanha', 'action' => 'list'));
		
		

	}
}
?>