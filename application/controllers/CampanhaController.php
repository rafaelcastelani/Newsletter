<?php
require 'BaseController.php';
class CampanhaController extends BaseController {
	function addAction(){
		$this->view->styles[] = 'timepicker.css';
		$this->view->scripts[] = 'jquery.timepicker.js';
		$this->view->scripts[] = 'campanha/add.js';
		$this->view->categorias = explode("\n",file_get_contents("db/categorias.csv"));
	}
	
	public function saveAction(){
		$this->_helper->viewRenderer->setNoRender();
		Zend_Loader::loadClass("wwnlDB");
		$wdb = new wwnlDB();
		$data = array(
			"name_campanha"		=>	$this->_getParam("name_campanha"),
			"subline" 			=>	$this->_getParam("subline"),
			"url_campanha"		=>	$this->_getParam("url_campanha"),
			"start_date"		=>	$this->_getParam("start_date"),
			"end_date"			=>	$this->_getParam("end_date"),
			"url_proxima"		=>	trim($this->_getParam("url_proxima")),
			"url_nova_oferta"	=>	trim($this->_getParam("url_nova_oferta")),
			/*"url_em_andamento"	=>	trim($this->_getParam("url_em_andamento")),*/
			"url_ultimos_dias"	=>	trim($this->_getParam("url_ultimos_dias")),
			"is_magazine"		=>	($this->_getParam("is_magazine")=="on")?1:0,
			"is_banner"			=>	($this->_getParam("is_banner")=="on")?1:0,
			"is_active"			=>	($this->_getParam("is_active")=="on")?1:0
			
		);
		if($wdb->addCampaign($data)){
			$this->_helper->redirector->gotoRoute(array('controller' => 'campanha', 'action' => 'list'));
		}else {
			$this->_helper->redirector->gotoRoute(array('controller' => 'campanha', 'action' => 'add', "error" =>"1"));
		}
	}
	
	public function updateAction(){
		//update_value=Candella%20Sentido
		//&element_id=9-name_campanha
		//&original_html=Candella%20Sentidos
		//&original_value=Candella%20Sentidos
		$this->_helper->viewRenderer->setNoRender();
		Zend_Loader::loadClass("wwnlDB");
		$wdb = new wwnlDB();
		$id_field = explode('-',$this->_getParam("element_id"));
		$wdb->updateCampanha($id_field[0],$id_field[1],$this->_getParam("update_value"));
		echo $this->_getParam("update_value");
	}
	
	public function deleteAction(){
		$this->_helper->viewRenderer->setNoRender();
		Zend_Loader::loadClass("wwnlDB");
		$wdb = new wwnlDB();
		$id = $this->_getParam("id");
		if($id != null){
			if($wdb->deleteCampanha($id)){
				echo "deletado";
			}else {
				echo "não achei esse id";
			}
		}else {
			echo "/id/numero<br/> não tem volta";
		}
		
		
	}
	
	
	function listAction(){
		$this->view->styles[] = 'campanha/list.css';
		$this->view->styles[] = 'timepicker.css';
		$this->view->scripts[] = 'jquery.timepicker.js';
		$this->view->scripts[] = 'campanha/add.js';
		$this->view->scripts[] = 'jquery.editinplace.js';
		$this->view->scripts[] = 'campanha/list.js.php';
		$id_campanha = $this->_getParam('id');
		$all = $this->_getParam('all');
		Zend_Loader::loadClass("wwnlDB");
		$wdb = new wwnlDB();
		$this->view->campaignList = $wdb->getCampaign($id_campanha,$all);
	}
		
}
