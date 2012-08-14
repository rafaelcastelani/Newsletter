<?php
require 'BaseController.php';
class TestController extends BaseController {
	
	public function indexAction(){
		$this->_helper->viewRenderer->setNoRender();
		Zend_Loader::loadClass("wwnlDB");
		$data = new DateTime($this->_getParam("date"));
		$wdb = new wwnlDB();
		echo "oi";
		print_r($wdb->getNovas($data));
		
	}

}
?>
