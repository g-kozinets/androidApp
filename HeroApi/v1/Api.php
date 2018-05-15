<?php 
 
	function isTheseParametersAvailable($params){
		 
		$available = true; 
		$missingparams = ""; 
		
		foreach($params as $param){
			if(!isset($_POST[$param]) || strlen($_POST[$param])<=0){
				$available = false; 
				$missingparams = $missingparams . ", " . $param; 
			}
		}
		
		//���� ��������� �� ������ 
		if(!$available){
			$response = array(); 
			$response['error'] = true; 
			$response['message'] = 'Parameters ' . substr($missingparams, 1, strlen($missingparams)) . ' missing';
			
			
			echo json_encode($response);
			
			die();
		}
	}
	
	//������ ��� ������ ������
	$response = array();
	
	if(isset($_GET['apicall'])){
		
		switch($_GET['apicall']){
			
			case 'createhero':
				//first check the parameters required for this request are available or not 
				isTheseParametersAvailable(array('name','realname','rating','teamaffiliation'));
				
		
				$db = new DbOperation();
				
			
				$result = $db->createHero(
					$_POST['name'],
					$_POST['realname'],
					$_POST['rating'],
					$_POST['teamaffiliation']
				);
				

				//���� ������ ������� ������� �����
				if($result){
					
					$response['error'] = false; 

					
					$response['message'] = 'Hero addedd successfully';

					$response['heroes'] = $db->getHeroes();
				}else{

					$response['error'] = true; 

					$response['message'] = 'Some error occurred please try again';
				}
				
			break; 
			
			//�������� ������
			case 'getheroes':
				$db = new DbOperation();
				$response['error'] = false; 
				$response['message'] = 'Request successfully completed';
				$response['heroes'] = $db->getHeroes();
			break; 
			
			
			//�������� ����������
			case 'updatehero':
				isTheseParametersAvailable(array('id','name','realname','rating','teamaffiliation'));
				$db = new DbOperation();
				$result = $db->updateHero(
					$_POST['id'],
					$_POST['name'],
					$_POST['realname'],
					$_POST['rating'],
					$_POST['teamaffiliation']
				);
				
				if($result){
					$response['error'] = false; 
					$response['message'] = 'Hero updated successfully';
					$response['heroes'] = $db->getHeroes();
				}else{
					$response['error'] = true; 
					$response['message'] = 'Some error occurred please try again';
				}
			break; 
			
			//��������
			case 'deletehero':

				//for the delete operation we are getting a GET parameter from the url having the id of the record to be deleted
				if(isset($_GET['id'])){
					$db = new DbOperation();
					if($db->deleteHero($_GET['id'])){
						$response['error'] = false; 
						$response['message'] = 'Hero deleted successfully';
						$response['heroes'] = $db->getHeroes();
					}else{
						$response['error'] = true; 
						$response['message'] = 'Some error occurred please try again';
					}
				}else{
					$response['error'] = true; 
					$response['message'] = 'Nothing to delete, provide an id please';
				}
			break; 
		}
		
	}else{
		//���� ������ �� �������� api, ������� ������ 
		$response['error'] = true; 
		$response['message'] = 'Invalid API Call';
	}
	
	//displaying the response in json structure 
	echo json_encode($response);
	
	
