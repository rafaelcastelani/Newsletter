<?php
require 'BaseController.php';
class NewsLetterController extends BaseController {
	public function indexAction(){

	}

	public function generatehtmlAction(){
		$plain = $this->_getParam("plain");
		$this->_helper->viewRenderer->setNoRender();
		if($plain != null) {
			header("Content-type:text/plain");
		}
		
		$data = new DateTime($this->_getParam("date"));

		$nl_date = new DateTime();
		$nl_date->setTime(0,0,0);
		$nl_date->setDate($nl_date->format("Y"), $nl_date->format("m"), $nl_date->format("d")+1);
		Zend_Loader::loadClass("wwnlDB");
		$date = new DateTime();
		$wdb = new wwnlDB();

		$banner 	= 	$wdb->getBanner($data);
		$novas 		= 	$wdb->getNovas($data);
		$andamento 	= 	false;//$wdb->getAndamento($data);
		$ultimas 	= 	$wdb->getUltimas($data);
		$proxima 	= 	$wdb->getProxima($data);
		$revista 	= 	$wdb->getRevista($data);

		$count = 1;
		$header_date = $data; ##new DateTime( ($block['start_date']),  new DateTimeZone('America/Sao_Paulo'));

		echo ($wdb->generateHtmlHeader($header_date));
		if(count($banner)){
			//echo ($wdb->generateHtmlSectionHeader("Novas", "Campanhas"));
			foreach($banner as $block){
				$count++;
				$start_date = new DateTime( ($block['start_date']),  new DateTimeZone('America/Sao_Paulo'));
				echo $wdb->generateHtmlSection(
				'banner.php',//$template,
				"",//$title = null,
				$wdb->getURL($block['url_campanha'],/*$block['type_code']*/"", $start_date,"IMG",$count),//$link1 = null,
				$wdb->getURL($block['url_campanha'],/*$block['type_code']*/"", $start_date,"BT",$count),//$link2 = null,
				"",//$alt = null,
				"http://n.westwing.com.br/images/4370/".trim($block['url_proxima']),//$src = null,
				$block['name_campanha'],//$name = null,
				$block['subline'],//$desc = null,
				"Início: ",//$startend = null,
				$start_date->format("d/m"),//$date = null,
				$start_date->format("h:i a"),//$time = null,
				"");//$altura = null){
			}
		}
		$count++;
		if(count($novas)){
			if(count($novas)%2){
				$qtdDestaque = 1;
			}else {
				$qtdDestaque = 2;
			}
			$i = 0;
			$j = 0;
			
			echo ($wdb->generateHtmlSectionHeader("Novas", "Campanhas"));
			
			foreach($novas as $k=>$block){
				if($i < $qtdDestaque){
					$count++;
					$start_date = new DateTime( ($block['start_date']),  new DateTimeZone('America/Sao_Paulo'));
					echo $wdb->generateHtmlSection(
					'novas-ofertas.php',//$template,
					"",//$title = null,
					$wdb->getURL($block['url_campanha'],/*$block['type_code']*/"", $data,"IMG",$count),//$link1 = null,
					$wdb->getURL($block['url_campanha'],/*$block['type_code']*/"", $data,"BT",$count),//$link2 = null,
					"",//$alt = null,
					"http://n.westwing.com.br/images/4370/".trim($block['url_nova_oferta']),//$src = null,
					$block['name_campanha'],//$name = null,
					$block['subline'],//$desc = null,
					"Início: ",//$startend = null,
					$start_date->format("d/m"),//$date = null,
					$start_date->format("h:i a"),//$time = null,
					"");//$altura = null){
				}
				else {
					$count++;
					$start_date = new DateTime( ($block['start_date']),  new DateTimeZone('America/Sao_Paulo'));
					if($j%2 == 0){
						echo file_get_contents("template/HTML/novas-ofertas-head.php");
					}else {
						echo '<td width="6">&nbsp;</td>';
					}
					echo $wdb->generateHtmlSection(
					'novas-ofertas-body.php',//$template,
					"",//$title = null,
					$wdb->getURL($block['url_campanha'],"", $data,"IMG",$count),//$link1 = null,
					$wdb->getURL($block['url_campanha'],"", $data,"BT",$count),//$link2 = null,
					"",//$alt = null,
					"http://n.westwing.com.br/images/4370/".trim($block['url_proxima']),//$src = null,
					$block['name_campanha'],//$name = null,
					$block['subline'],//$desc = null,
					"Início: ",//$startend = null,
					$start_date->format("d/m"),//$date = null,
					$start_date->format("h:i a"),//$time = null,
					"");//$altura = null){
					if(count($novas) == $j+1 && $j%2 == 0){
						echo '<td width="6">&nbsp;</td><td>
								<table width="301" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse: collapse;"></table></td>';
						echo file_get_contents("template/HTML/novas-ofertas-foot.php");

					}
					if($j%2 !== 0){
						echo file_get_contents("template/HTML/novas-ofertas-foot.php");
					}
					$j++;
				}
				$i++;
			}
		}
		
		echo ($wdb->generateHtmlBannerShop($count,$start_date));
		$count++;
		if(count($ultimas)){
			echo ($wdb->generateHtmlSectionHeader("&uacute;ltimos", "Dias"));
			$i = 0;
			foreach($ultimas as $block){
				$count++;
				$end_date = new DateTime( ($block['end_date']),  new DateTimeZone('America/Sao_Paulo'));

				if($i%2 == 0){
					echo file_get_contents("template/HTML/ultimos-dias-head.php");
				}else {
					echo "<td width=\"6\">&nbsp;</td>";
				}

				echo $wdb->generateHtmlSection(
					'ultimos-dias.php',//$template,
					"",//$title = null,
					$wdb->getURL($block['url_campanha'],/*$block['type_code']*/"", $start_date,"IMG",$count),//$link1 = null,
					$wdb->getURL($block['url_campanha'],/*$block['type_code']*/"", $start_date,"BT",$count),//$link2 = null,
					"",//$alt = null,
					"http://n.westwing.com.br/images/4370/".trim($block['url_ultimos_dias']),//$src = null,
					$block['name_campanha'],//$name = null,
					$block['subline'],//$desc = null,
					"Término: ",//$startend = null,
					$end_date->format("d/m"),//$date = null,
					$end_date->format("h:i a"),//$time = null,
					""
				);//$altura = null){
				if(count($ultimas) == $i+1 && $i%2 == 0){
					echo '<td width=\"6\">&nbsp;</td><td>
													<table width="301" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse: collapse;"></table></td>';
					echo file_get_contents("template/HTML/ultimos-dias-foot.php");
				}
				if($i%2 !== 0){
					echo file_get_contents("template/HTML/ultimos-dias-foot.php");
				}
				$i++;
			}
		}

		/*if(count($andamento)){
			echo ($wdb->generateHtmlSectionHeader("Em", "Andamento"));
		foreach($andamento as $block){
		$count++;
		$end_date = new DateTime( ($block['end_date']),  new DateTimeZone('America/Sao_Paulo'));
		echo $wdb->generateHtmlSection(
		'em-andamento.php',//$template,
		"",//$title = null,
		$wdb->getURL($block['url_campanha'],"", $nl_date,"IMG",$count),//$link1 = null,
		$wdb->getURL($block['url_campanha'],"", $nl_date,"BT",$count),//$link2 = null,
		"",//$alt = null,
		"http://n.westwing.com.br/images/4370/".trim($block['url_em_andamento']),//$src = null,
		$block['name_campanha'],//$name = null,
		$block['subline'],//$desc = null,
		"Término: ",//$startend = null,
		$end_date->format("d/m"),//$date = null,
		$end_date->format("h:i a"),//$time = null,
		"");//$altura = null){
		}
		}*/


		if(count($proxima)){
			foreach($proxima as $block){
				$count++;
				$end_date = new DateTime( ($block['end_date']),  new DateTimeZone('America/Sao_Paulo'));
				echo $wdb->generateHtmlSection(
				'proximas.php',//$template,
				"",//$title = null,
				$wdb->getURL($block['url_campanha'],/*$block['type_code']*/"", $start_date,"IMG",$count),//$link1 = null,
				$wdb->getURL($block['url_campanha'],/*$block['type_code']*/"", $start_date,"BT",$count),//$link2 = null,
				"",//$alt = null,
				"http://n.westwing.com.br/images/4370/".trim($block['url_proxima']),//$src = null,
				$block['name_campanha'],//$name = null,
				$block['subline'],//$desc = null,
				"Início: ",//$startend = null,
				"em breve",//$end_date->format("d/m"),//$date = null,
				"",//$end_date->format("h:i a"),//$time = null,
				($this->_getParam("p"))?"<tr><td height=\"".$this->_getParam("p")."\">&nbsp;</td></tr>":"");//$altura = null){
			}
		}
		if(count($revista)){
			foreach($revista as $block){
				$count++;
				$end_date = new DateTime( ($block['end_date']),  new DateTimeZone('America/Sao_Paulo'));
				echo $wdb->generateHtmlSection(
				'revista.php',//$template,
				"",//$title = null,
				$wdb->getURL($block['url_campanha'],"", $start_date,"IMG",$count),//$link1 = null,
				$wdb->getURL($block['url_campanha'],"", $start_date,"TXT",$count),//$link2 = null,
				"",//$alt = null,
				"http://n.westwing.com.br/images/4370/".trim($block['url_proxima']),//$src = null,
				$block['name_campanha'],//$name = null,
				$block['subline'],//$desc = null,
				"Término: ",//$startend = null,
				$end_date->format("d/m"),//$date = null,
				$end_date->format("h:i a"),//$time = null,
				($this->_getParam("r"))?"<tr><td height=\"".$this->_getParam("r")."\">&nbsp;</td></tr>":"");//$altura = null){
			}
		}
		echo $wdb->generateHtmlInviteFooter($start_date);

	}

	public function generateplainAction(){
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader("Content-Type", "text/plain");

		$nl_date = new DateTime();
		$nl_date->setTime(0,0,0);
		$nl_date->setDate($nl_date->format("Y"), $nl_date->format("m"), $nl_date->format("d")+1);

		Zend_Loader::loadClass("wwnlDB");
		$wdb = new wwnlDB();

		$data = new DateTime($this->_getParam("date"));

		$novas = $wdb->getNovas($data);
		$ultimas = $wdb->getUltimas($data);
		$andamento = $wdb->getAndamento($data);
		$proxima = $wdb->getProxima($data);
		$revista = $wdb->getRevista($data);


		echo $wdb->generatePlainHeader();
		if(count($novas)){
			echo $wdb->generatePlainSectionHeader("NOVAS","OFERTAS");
			foreach($novas as $block){
				$start_date = new DateTime( ($block['start_date']),  new DateTimeZone('America/Sao_Paulo'));
				echo $wdb->generatePlainSection ( $block['url_campanha'],
				$block['name_campanha'],
				$block['subline'],
			"Início",
				$start_date->format("d/m"),
				$start_date->format("h:i a"));
			}
		}

		if(count($ultimas)){
			echo $wdb->generatePlainSectionHeader("ULTIMOS","DIAS");
			foreach($ultimas as $block){
				$end_date = new DateTime( ($block['end_date']),  new DateTimeZone('America/Sao_Paulo'));
				echo $wdb->generatePlainSection ( $block['url_campanha'],
				$block['name_campanha'],
				$block['subline'],
			"Término",
				$end_date->format("d/m"),
				$end_date->format("h:i a"));
			}
		}
		if(count($andamento)){
			echo $wdb->generatePlainSectionHeader("EM","ANDAMENTO");
			foreach($andamento as $block){
				$end_date = new DateTime( ($block['end_date']),  new DateTimeZone('America/Sao_Paulo'));
				echo $wdb->generatePlainSection ( $block['url_campanha'],
				$block['name_campanha'],
				$block['subline'],
			"Término",
				$end_date->format("d/m"),
				$end_date->format("h:i a"));
			}
		}

		if(count($proxima)){
			echo $wdb->generatePlainSectionHeader("PRÓXIMAS","CAMPANHAS");
			foreach($proxima as $block){
				$start_date = new DateTime( ($block['start_date']),  new DateTimeZone('America/Sao_Paulo'));
				echo $wdb->generatePlainSection ( $block['url_campanha'],
				$block['name_campanha'],
				$block['subline'],
			"Início",
			"em breve",
			"");
			}
		}

		if(count($revista)){
			echo $wdb->generatePlainSectionHeader("NOVIDADES NA","REVISTA");
			foreach($revista as $block){
				$start_date = new DateTime( ($block['start_date']),  new DateTimeZone('America/Sao_Paulo'));
				echo $wdb->generatePlainSection ( $block['url_campanha'],
				$block['name_campanha'],
				$block['subline'],
			"",
			"",
			"");
			}
		}

		echo $wdb->generatePlainFooter();
		/*
		 $header = $wdb->generatePlainHeader();
		$db = new sqlconnect();
		$db->openConnection();
		$blocks = $db->getAssocBlocks($nl_id,'1');
		$db->closeConnection();
		$novas = $novasHeader ="";
		if(count($blocks)){
		$novasHeader = $wdb->generatePlainSectionHeader("NOVAS","OFERTAS");
		foreach($blocks as $block){
		$novas .= $wdb->generatePlainSection ( $block['short_link_url'],
		$block['text_main'],
		$block['text_sub'],
		$block['date_type'],
		$block['bl_date'],
		$block['bl_time']);
		}
		}



		$db = new sqlconnect();
		$db->openConnection();
		$blocks = $db->getAssocBlocks($nl_id,'2');
		$db->closeConnection();
		$ultimos = $ultimosHeader ="";

		if(count($blocks)){
		$ultimosHeader = $wdb->generatePlainSectionHeader("ÚLTIMOS","DIAS");

		foreach($blocks as $block){
		$ultimos .= $wdb->generatePlainSection ( $block['short_link_url'],
		$block['text_main'],
		$block['text_sub'],
		$block['date_type'],
		$block['bl_date'],
		$block['bl_time']);
		}
		}



		$db = new sqlconnect();
		$db->openConnection();
		$blocks = $db->getAssocBlocks($nl_id,'3');
		$db->closeConnection();
		$andamento = $andamentoHeader = "";
		if(count($blocks)){
		$andamentoHeader = $wdb->generatePlainSectionHeader("EM","ANDAMENTO");
		foreach($blocks as $block){
		$andamento .= $wdb->generatePlainSection ( $block['short_link_url'],
		$block['text_main'],
		$block['text_sub'],
		$block['date_type'],
		$block['bl_date'],
		$block['bl_time']);
		}
		}



		$db = new sqlconnect();
		$db->openConnection();
		$blocks = $db->getAssocBlocks($nl_id,'4');
		$db->closeConnection();
		$proximas = $proximasHeader =  "";

		if(count($blocks)){
		$proximasHeader = $wdb->generatePlainSectionHeader("PRÓXIMAS","CAMPANHAS");
		foreach($blocks as $block){
		$proximas .= $wdb->generatePlainSection ( $block['short_link_url'],
		$block['text_main'],
		$block['text_sub'],
		$block['date_type'],
		$block['bl_date'],
		$block['bl_time']);
		}
		}



		$db = new sqlconnect();
		$db->openConnection();
		$blocks = $db->getAssocBlocks($nl_id,'5');
		$db->closeConnection();
		$revista = $revistaHeader= "";

		if(count($blocks)){
		$revistaHeader = $wdb->generatePlainSectionHeader("NOVIDADES NA","REVISTA");
		foreach($blocks as $block){
		$revista .= $wdb->generatePlainSection ( $block['short_link_url'],
		$block['text_main'],
		$block['text_sub'],
		$block['date_type'],
		$block['bl_date'],
		$block['bl_time']);
		}
		}

		$footer = $this->generatePlainFooter();




		$nl = "";

		$nl .= $header;
		$nl .= $novasHeader.$novas;
		$nl .= $ultimosHeader.$ultimos;
		$nl .= $andamentoHeader.$andamento;
		$nl .= $proximasHeader.$proximas;
		$nl .= $revistaHeader.$revista;
		$nl .= $footer;

		return $nl;*/

	}

}
?>
