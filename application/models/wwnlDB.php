<?php
class wwnlDB extends Zend_Db_Table {
	protected $_campanha = 'campanha' ;
	
	public function addNewsLetter($nl_name,$nl_submit_date){
		$data = array(	"nl_name" => $nl_name,
						"nl_submit_date" => ($nl_submit_date) );
		return $this->_db->insert($this->_newsletter, $data);
	}
	
	public function getCampaign($id_campanha = null,$all=null){
		$select = new Zend_Db_Select ( $this->getDefaultAdapter () ) ;
		$select->from (array ('c' => $this->_campanha));
		if($id_campanha){
			$select->where('id_campanha = ?', $id_campanha);
		}
		$select->order("start_date DESC");
		$select->order("end_date DESC");
		if($all == null){
			$select->limit(75);
		}

		return $this->_db->fetchAssoc ( $select ) ;
	}
	
	public function addCampaign($data = null){
		if($data){
			return $this->_db->insert($this->_campanha, $data);
		}else {
			return false;
		}
	}
	
	public function updateCampanha($id_campanha = null, $field = null, $data = null){
		if($id_campanha != null && $field != null && $data != null){
			$bind = array($field => $data);
			return $this->_db->update($this->_campanha, $bind, "id_campanha = ".$id_campanha);
		}
	}
	
	public function deleteCampanha($id_campanha = null){
		if($id_campanha != null){
			return $this->_db->delete($this->_campanha,"id_campanha = ".$id_campanha);
		}
	}
	
	public function getNovas($newsDate = null){
		$start_date = new DateTime();
		$start_date->setTime(20,0,0);
		$start_date->setDate($newsDate->format("Y"), $newsDate->format("m"), $newsDate->format("d")-1);
		
		$end_date = new DateTime();
		$end_date->setTime(10,0,0);
		$end_date->setDate($newsDate->format("Y"), $newsDate->format("m"), $newsDate->format("d"));
		
		$select = new Zend_Db_Select($this->getDefaultAdapter());
		$select->from(array ('c' => $this->_campanha) );
		$select->where('start_date > ?', $start_date->format("Y-m-d G:i:s"));
		$select->where('start_date < ?',$end_date->format("Y-m-d G:i:s"));
		$select->where('start_date <> end_date');
		$select->where('is_active = ?','1');
		$select->where('is_banner = ?','0');
		$select->where('is_magazine = ?','0');
		$select->order("end_date ASC");
		$select->order("priority DESC");
		return $this->_db->fetchAssoc ( $select ) ;
	}
	
	public function getUltimas($newsDate = null){
		$start_date = new DateTime();
		$start_date->setTime(0,15,0);
		$start_date->setDate($newsDate->format("Y"), $newsDate->format("m"), $newsDate->format("d"));
		
		$end_date = new DateTime();
		$end_date->setTime(23,59,59);
		$end_date->setDate($newsDate->format("Y"), $newsDate->format("m"), $newsDate->format("d")+1);
		/*print_r($start_date);
		echo "<br/>";
		print_r($end_date);
		die();*/
		$select = new Zend_Db_Select($this->getDefaultAdapter());
		$select->from(array ('c' => $this->_campanha) );
		$select->where('end_date > ?', $start_date->format("Y-m-d G:i:s"));
		$select->where('end_date < ?',$end_date->format("Y-m-d G:i:s"));
		$select->where('start_date <> end_date');
		$select->where('is_active = ?','1');
		$select->where('is_banner = ?','0');
		$select->where('is_magazine = ?','0');
		$select->order("end_date ASC");
		$select->order("priority DESC");
		$result =  $this->_db->fetchAssoc ( $select );
		
		while(!count($result)){
                        $end_date->setDate($end_date->format("Y"), $end_date->format("m"), $end_date->format("d")+1);
			$select = new Zend_Db_Select($this->getDefaultAdapter());
                	$select->from(array ('c' => $this->_campanha) );
           		$select->where('end_date > ?', $start_date->format("Y-m-d G:i:s"));
                	$select->where('end_date < ?',$end_date->format("Y-m-d G:i:s"));
               		$select->where('start_date <> end_date');
                	$select->where('is_active = ?','1');
               		$select->where('is_banner = ?','0');
                	$select->where('is_magazine = ?','0');
                	$select->order("end_date ASC");
                	$select->order("priority DESC");

                        $extraResult = $this->_db->fetchAssoc ( $select ) ;
                        foreach($extraResult as $res) {
                                $result[] = $res;
                        }

                }
		if(count($result)%2){
			$newExtraResult = Array();
			while(!count($newExtraResult)){
				$difference = 4-count($result);
			
				$end_date->setDate($end_date->format("Y"), $end_date->format("m"), $end_date->format("d")+1);
				$start_date->setDate($start_date->format("Y"), $start_date->format("m"), $start_date->format("d")+1);
       		                $select = new Zend_Db_Select($this->getDefaultAdapter());
                	        $select->from(array ('c' => $this->_campanha) );
                       		$select->where('end_date > ?', $start_date->format("Y-m-d G:i:s"));
                        	$select->where('end_date < ?',$end_date->format("Y-m-d G:i:s"));
                        	$select->where('start_date <> end_date');
                        	$select->where('is_active = ?','1');
                        	$select->where('is_banner = ?','0');
                        	$select->where('is_magazine = ?','0');
                        	$select->order("end_date ASC");
                        	$select->order("priority DESC");
				$select->limit($difference);
			
				$newExtraResult = $this->_db->fetchAssoc ( $select ) ;
				foreach($newExtraResult as $res) {
                                	$result[] = $res;
                        	}
			}
		}
		return $result;	

	}
	
	public function getAndamento($newsDate = null){
		$start_date = new DateTime();
		$start_date->setTime(20,0,0);
		$start_date->setDate($newsDate->format("Y"), $newsDate->format("m"), $newsDate->format("d")-1);
		
		$end_date = new DateTime();
		$end_date->setTime(23,59,59);
		$end_date->setDate($newsDate->format("Y"), $newsDate->format("m"), $newsDate->format("d")+1);
		
		$select = new Zend_Db_Select($this->getDefaultAdapter());
		$select->from(array ('c' => $this->_campanha) );
		$select->where('end_date > ?', $end_date->format("Y-m-d G:i:s"));
		$select->where('start_date < ?',$start_date->format("Y-m-d G:i:s"));
		$select->where('start_date <> end_date');
		$select->where('start_date <> end_date');
		$select->where('is_magazine = ?','0');
		$select->where('is_banner = ?','0');
		$select->where('is_active = ?','1');
		$select->order("end_date DESC");
		$select->order("priority DESC");
		
		return $this->_db->fetchAssoc ( $select ) ;
	}
	
	public function getProxima($newsDate = null){
		$start_date = new DateTime();
		$start_date->setTime(20,0,0);
		$start_date->setDate($newsDate->format("Y"), $newsDate->format("m"), $newsDate->format("d"));
	
		$select = new Zend_Db_Select($this->getDefaultAdapter());
		$select->from(array ('c' => $this->_campanha) );
		$select->where('start_date > ?',$start_date->format("Y-m-d G:i:s"));
		$select->where('start_date <> end_date');
		$select->where('is_magazine = ?','0');
		$select->where('is_banner = ?','0');
		$select->where('is_active = ?','1');
		$select->where('start_date <> end_date');
		$select->order("end_date ASC");
		$select->order("priority DESC");
		$select->limit(1);
		return $this->_db->fetchAssoc ( $select ) ;
	}
	
	public function getRevista($newsDate = null){
		$start_date = new DateTime();
		$start_date->setTime(0,0,0);
		$start_date->setDate($newsDate->format("Y"), $newsDate->format("m"), $newsDate->format("d"));
		
		$select = new Zend_Db_Select($this->getDefaultAdapter());
		$select->from(array ('c' => $this->_campanha) );
		$select->where('start_date = ?',$start_date->format("Y-m-d H:i:s"));
		$select->where('start_date = end_date');
		$select->where('is_banner = ?','0');
		$select->where('is_magazine = ?','1');
		$select->where('is_active = ?','1');
		$select->order("priority DESC");
		$select->order("end_date ASC");
		$select->limit(1);
		
		return ($this->_db->fetchAssoc ( $select )) ;
	}
	
	public function getBanner($newsDate = null){
		$start_date = new DateTime();
		$start_date->setTime(0,0,0);
		$start_date->setDate($newsDate->format("Y"), $newsDate->format("m"), $newsDate->format("d")+5);
	
		$select = new Zend_Db_Select($this->getDefaultAdapter());
		$select->from(array ('c' => $this->_campanha) );
		$select->where('start_date = ?',$start_date->format("Y-m-d H:i:s"));
		$select->where('start_date = end_date');
		$select->where('is_magazine = ?','0');
		$select->where('is_banner = ?','1');
		$select->where('is_active = ?','1');
		$select->order("priority DESC");
		$select->order("end_date ASC");
		$select->limit(1);
	
		return ($this->_db->fetchAssoc ( $select )) ;
	}
	
	public function generateHtmlSectionHeader($mainName,$secondName){
		$string = file_get_contents("template/HTML/section-head.php");
		$string = str_replace("###SECTION-MAIN-NAME###", trim($mainName), $string);
		$string = str_replace("###SECTION-SECOND-NAME###", trim($secondName), $string);
		return $string;
	}
	
   	 public function generateBamarangHtmlHeader($header_date){
                $string = file_get_contents("template/HTML/news-header-bamarang.php");

                $date_string= $header_date->format("Ymd");

                $string = str_replace("###NEWS-DATE###",$date_string,$string);
                return $string;
         }


	public function generateHtmlHeader($header_date){
		$string = file_get_contents("template/HTML/news-header.php");
		
		$date_string= $header_date->format("Ymd");
              		
                $string = str_replace("###NEWS-DATE###",$date_string,$string);
		return $string;
	}
	
		public function generateHtmlInviteFooter($date){
		$string = file_get_contents("template/HTML/invite_footer.php");;
 	
 	        $date_string= $date->format("Ymd");
       
            $string = str_replace("###NEWS-DATE###",$date_string,$string);
 
 		return $string; 
 	}
	
	
	public function generateHtmlInvite($count,$date){
		$string = file_get_contents("template/HTML/invite.php");
		$string = str_replace("###COUNT###", trim($count), $string);
	
	        $date_string= $date->format("Ymd");
                $string = str_replace("###NEWS-DATE###",$date_string,$string);

		return $string; 
	}
	
	public function generateHtmlFooter(){
		$string = file_get_contents("template/HTML/facebookfooter.php");
		return $string;
	}
	
		public function generateHtmlBannerShop($count,$date){
		
	       	$date_string_compare= $date->format("D");
		
			if( $date_string_compare == 'Thu' xor $date_string_compare == 'Tue'){
					   $string = file_get_contents("template/HTML/banner_shop.php");
			                }else{
				
						$string = file_get_contents("template/HTML/banner_bestsellers.php");
				                }
				
						$string = str_replace("###COUNT###", trim($count), $string);
						$date_string= $date->format("Ymd");
				
				                $string = str_replace("###NEWS-DATE###",$date_string,$string);
				
				
				               return $string;
		        }
	
				
				
	public function getURL($simpleUrl,$type_code,$date,$target,$pos){
		
		$url_key=$simpleUrl;
		$url_key=preg_replace('/^(?:.*)?\.br\//', '', $url_key);
		$url_key=preg_replace('/\//','',$url_key);
		#$url = $simpleUrl."?email=##Field_email##&utm_source=sale-newsletter&utm_medium=ww-newsletter&utm_campaign=sale-nl-".$date->format("Ymd")."&utm_campaign_name=".$url_key;
		$url = $simpleUrl."?utm_source=newsletter&utm_medium=".$url_key."&utm_content=".$date->format("Ymd")."&utm_campaign=".$url_key."&utm_term=news_diaria";

		return $url;
	}

        public function getURLBAMA($simpleUrl,$type_code,$date,$target,$pos){
	
		  $url = "https://www.westwing.com.br/campaign/landingbamarang/email/##Field_email##";

		return $url;
	
	}
	
	public function generateHtmlSection(	$template,
										$title = null,
										$link1 = null,
										$link2 = null,
										$alt = null,
										$src = null,
										$name = null,
										$desc = null,
										$startend = null,
										$date = null,
										$time = null,
										$altura = null){
		if($title == ""){
			$title = $name." - ".$desc;
		}
		if($alt == "") {
			$alt = $name." - ".$desc;
		}
		$string = file_get_contents("template/HTML/".$template);
		$string = str_replace("###TITLE###", trim($title), $string);
		$string = str_replace("###LINK1###", trim($link1), $string);
		$string = str_replace("###LINK2###", trim($link2), $string);
		$string = str_replace("###ALT###", trim($alt), $string);
		$string = str_replace("###SRC###", trim($src), $string);
		$string = str_replace("###NOMELOJA###", trim($name), $string);
		$string = str_replace("###DESCRICAO###", trim($desc), $string);
		if(trim($startend) != ""){
			$string = str_replace("###INICIOTERMINO###", trim($startend), $string);
		}else {
			$string = str_replace("###INICIOTERMINO###", trim($startend), $string);
		}
		$string = str_replace("###DATA###", trim($date), $string);
		$string = str_replace("###ALTURA###", trim($altura), $string);
	
		if($time != ""){
			$as = "às:";
		}else {
			$as = "";
		}
		$string = str_replace("###AS###", trim($as), $string);
		$string = str_replace("###HORA###", trim($time), $string);
	
		return $string;
	}
	
	
	/*PLAIN*/
	
	public function generatePlainHeader(){
		$string = file_get_contents("template/PLAIN/header.txt");
		return $string;
	}
	
	public function generatePlainFooter(){
		$footer = file_get_contents("template/PLAIN/footer.txt");
		return $footer;
	}
	
	public function generatePlainSectionHeader($mainName = null,$secondName = null){
		$head = file_get_contents("template/PLAIN/section-head.txt");
		$head = str_replace("###SECTION-MAIN-NAME###", trim($mainName), $head);
		$head = str_replace("###SECTION-SECOND-NAME###", trim($secondName), $head);
	
		return $head;
	}
	
	public function getProduct($newsDate = null){
		$start_date = new DateTime();
		$start_date->setTime(20,0,0);
		$start_date->setDate($newsDate->format("Y"), $newsDate->format("m"), $newsDate->format("d")-1);
	
		$end_date = new DateTime();
		$end_date->setTime(10,0,0);
		$end_date->setDate($newsDate->format("Y"), $newsDate->format("m"), $newsDate->format("d"));
	
	
		$select = new Zend_Db_Select($this->getDefaultAdapter());
		$select->from(array ('c' => $this->_campanha) );
		$select->where('start_date = ?',$start_date->format("Y-m-d H:i:s"));
		$select->where('start_date = end_date');
		$select->where('is_banner = ?','0');
		$select->where('is_magazine = ?','0');
		$select->where('is_product = ?','1');
		$select->where('is_active = ?','1');
		$select->order("priority DESC");
		$select->order("end_date ASC");
		$select->limit(1);
	
		return ($this->_db->fetchAssoc ( $select )) ;
	}
	
	public function generatePlainSection($link = null, $name = null,$desc = null,$startend = null,$date = null,$time = null){
		$section = file_get_contents("template/PLAIN/section.txt");
		$section = str_replace("###NOMELOJA###", trim(mb_convert_case($name, MB_CASE_UPPER, "utf-8")), $section);
		$section = str_replace("###DESCRICAO###", trim($desc), $section);
		$section = str_replace("###DATA###", trim($date), $section);
		if($startend != ""){
			$section = str_replace(" ###INICIOTERMINO###", "\n".trim($startend).":", $section);
		}else {
			$section = str_replace(" ###INICIOTERMINO###", "", $section);
		}
		if($time != ""){
			$as = "às:";
			$section = str_replace(" ###AS###", "\n".trim($as), $section);
			$section = str_replace("###HORA###", trim($time), $section);
		}else {
			$as = "";
			$section = str_replace("###AS###", trim($as), $section);
			$section = str_replace("###HORA###", trim($time), $section);
		}
		$section = str_replace("###LINK###", trim($link), $section);
	
		return $section;
	}
	
}
?>
