<?php
require_once ("lib/simple_html_dom.php");

if($_REQUEST){
	if(isset($_POST['operation']) && !empty($_POST['operation'])) {
		$operation = $_POST['operation'];
		if ($operation == "exchange") {

			$params = array('Amount', 'From', 'To');
			foreach ($params as $i => $value) {
			   if (!isset($_POST[$value]) && empty($_POST[$value])) 
			   	exit(json_encode(array('operation' => $operation, 'succeed' => 'false', 'result' => 'missing parameter(s).')));
			}

			$Amount = $_POST['Amount'];
			$From =  $_POST['From'];
			$To =  $_POST['To'];

		 	$url = "http://www.xe.com/currencyconverter/convert/?Amount=".$Amount."&From=".$From."&To=".$To;
			$html = new simple_html_dom();
			$html->load_file($url);
			$uccRes = $html->find('table[class=ucc-result-table]', 0)->children(0)->children(0);
			$leftCol = $uccRes->find('td[class=leftCol]', 0);
			$rightCol = $uccRes->find('td[class=rightCol]', 0);

			$leftCol_text = preg_replace("/&#?[a-z0-9]+;/i", "", $leftCol->find('text', 0));
			$leftCol_uccResCde = $leftCol->children(0)->plaintext;

			$rightCol_text = preg_replace("/&#?[a-z0-9]+;/i", "", $rightCol->find('text', 0));
			$rightCol_uccResCde = $rightCol->children(0)->plaintext;
		 
		 	echo json_encode(array(
		 			'operation' => $operation,
		 			'succeed' => 'true',
					'result' => array(
									'from' => array('amount' => $leftCol_text, 'currency' => $leftCol_uccResCde), 
									'to' => array('amount' => $rightCol_text, 'currency' => $rightCol_uccResCde)
									)));

			
		}
	}
}
?>
