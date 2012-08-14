<?php

require_once 'Zend/Auth.php';
require_once 'BaseController.php';

class LoginController extends BaseController {
	public function init() {
		$this->view->style = array("screen"=>"login.css");
		$this->_authCheckRequired = false;
		parent::init();
		$this->_generateAuthAdapter();
	}

	public function indexAction() {
		$this->view->layout()->content = $this->render('login', 'login', false);
	}
	
	public function loginAction() {
		
		if (!$this->_getParam('username') || !$this->_getParam('password')) {
			$this->view->loginFailed = true;
		} else {
			$this->_authadapter->setIdentity($this->getRequest()->getParam('username'));
			$this->_authadapter->setCredential($this->getRequest()->getParam('password'));
			$result = $this->_auth->authenticate($this->_authadapter);
			
			/* @var $result Zend_Auth_Result */
			if (!$result->isValid()) {
				$this->view->loginFailed = true;
			} else {
				$redirector = $this->_helper->redirector;
				/* @var $redirector Zend_Controller_Action_Helper_Redirector */
				$redirector->gotoRoute(array('controller' => 'index', 'action' => 'index'), 'default');
			}
		}
	}
	
	public function logoutAction(){
		$this->_auth->clearIdentity();
		$redirector = $this->_helper->redirector;
		$redirector->gotoRoute(array('controller' => 'login', 'action' => 'index'), 'default');
	}
}