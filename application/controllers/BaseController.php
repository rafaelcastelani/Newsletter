<?php

require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Storage/Session.php';
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Auth/Storage/Session.php';
require_once 'Zend/Db/Table.php';

abstract class BaseController extends Zend_Controller_Action {	
	/**
	 * Authentication object
	 * 
     * @var Zend_Auth
     */
	protected $_auth = null;
	
	/**
	 * Authentication adapter
	 * 
     * @var Zend_Auth_Adapter_DbTable
     */
	protected $_authadapter = null;

	/**
	 * @var boolean
	 * 
	 * Define if this controller requires authentication or not
	 */
	protected $_authCheckRequired = false;
	
	/**
	 * Sets authentication up (used for auth changes such as the login)
	 * 
	 * @return void
	 */
	protected function _generateAuthAdapter() {
		$this->_auth = Zend_Auth::getInstance(); 
		$this->_auth->setStorage(new Zend_Auth_Storage_Session('RssProxy_Auth'));
		$this->_authadapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter(), 'users', 'user_name', 'user_pass', 'SHA1(?)');
	}
	
	
	/**
	 * Initialize the controller
	 * sets basic info such as baseUrl
	 * 
	 * @return void
	 */
	public function init() {
		parent::init();
		$this->view->baseUrl = $this->_request->getBaseUrl();
		if ($this->_authCheckRequired == true) {
			$this->_generateAuthAdapter();
			if (!$this->_auth->hasIdentity()) {
				$this->_helper->redirector->gotoRoute(array('controller' => 'login', 'action' => 'index'));
			}
		}
		$this->view->styles = array('reset.css','main.css','jquery-ui.css');
		$this->view->scripts = array('jquery.js','jquery-ui.js');
	}
}