<?php
namespace DAO;
use DB\MySQL\Query as query; 
use Entity\Transaction; 

class TransactionDAO {
	var $table_name = 'transactions';

	function __construct(){

	}

	function __destruct(){

	}

	function GetTransactions(){
		try{
			$sql = new query("SELECT * FROM $this->table_name");
			$all_trans = $sql->fetch();

			$resultSet = array();
			foreach ($all_trans as $tran):
				$newObj = new Transaction();
				$newObj->ID = $tran['id'];
				$newObj->WALLET_ID = $tran['wallet_id'];
				$newObj->TYPE = $tran['type'];
				$newObj->AMOUNT = $tran['amount'];
				$newObj->REFERENCE = $tran['reference'];
				$newObj->TIMESTAMP = $tran['timestamp'];
				array_push($resultSet, $newObj);
	   		endforeach;
			return array("statusCode" => 200, "data" => $resultSet);
		}
		catch(Exception $e){
			return array("statusCode" => 403, "data" => null);
		}
	}

	function CreateTransaction($obj){
		try{
			$sql = new query("INSERT INTO $this->table_name (`wallet_id`, `type`, `amount`, `reference`, `timestamp`) 
			VALUES ($obj->WALLET_ID, '$obj->TYPE', $obj->AMOUNT, '$obj->REFERENCE', CURRENT_TIMESTAMP())");
			
			// get auto-generated id
			$insertedID = $sql->inserted_id();			
			
			return $this->GetTransactionByID($insertedID);
		}
		catch(Exception $e){
			return array("statusCode" => 403, "data" => null);
		}
	}

	function GetTransactionByID($id){
		try{
			$sql = new query("SELECT * FROM $this->table_name WHERE id=$id");
			$results = $sql->fetch();
			if(count($results) > 0){
				$resultSet = array();
				foreach ($results as $tran):
					$newObj = new Transaction();
					$newObj->ID = $tran['id'];
					$newObj->WALLET_ID = $tran['wallet_id'];
					$newObj->TYPE = $tran['type'];
					$newObj->AMOUNT = $tran['amount'];
					$newObj->REFERENCE = $tran['reference'];
					$newObj->TIMESTAMP = $tran['timestamp'];
					$resultSet = $newObj;
					break;
				endforeach;
				
				return array("statusCode" => 200, "data" => $resultSet);
			}
			else{
				return array("statusCode" => 403, "data" => null);
			}
		}
		catch(Exception $e){
			return array("statusCode" => 403, "data" => null);
		}
	}
}

?>
