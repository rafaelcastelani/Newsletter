<?
require "../application/config/general.php";
require "Zend/Loader/Autoloader.php";
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('ZFDebug');


// load  DB configuration
$config = new Zend_Config_Xml('../application/config/config.xml','staging');
$registry = Zend_Registry::getInstance();
$registry->set('general', $config);

// setup database
$db = Zend_Db::factory($config->db->adapter,$config->db->config->toArray());
Zend_Db_Table::setDefaultAdapter($db);
//Set default encoding for DB use :P
$db->query("SET NAMES UTF8");

// Set the Doctype to XHTML 1.0 TRANSITIONAL for purposes of view helpers
$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
$viewRenderer->initView();
$viewRenderer->view->doctype('XHTML1_TRANSITIONAL');
$viewRenderer->view->setEncoding('UTF-8');

// setup controller
$frontController = Zend_Controller_Front::getInstance();
$frontController->setDefaultControllerName('home');
$frontController->setControllerDirectory("../application/controllers/")->throwExceptions(true);


//setup Zend Debug Bar
$zdBarOptions = array('database_adapter' =>  $db);
$zdBar = new ZFDebug_Controller_Plugin_Debug($zdBarOptions);
//$frontController->registerPlugin($scBar);

// Start your engines!! :P

$frontController->dispatch();

?>
