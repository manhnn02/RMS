<?php
namespace DAO;
use DB\MySQL\Query as query; 
use Entity\Wallet; 

class WalletDAO {
	private $table_name = 'wallets';

	function __construct(){

	}

	function __destruct(){

	}

	private function CheckExists($obj){
		try{
			$query = new query("SELECT * FROM $this->table_name WHERE name='$obj->NAME' AND hash_key='$obj->HASH_KEY'");
			$results = $query->fetch();
			$ID = 0;
			foreach ($results as $result):
				$ID = $result['id'];
				break;
	   		endforeach;

			return intval($ID);
		}
		catch(\Throwable $e){
			return 0;
		}
	}

	function GetWallet(){
		try{
			$wallets = new query("SELECT * FROM $this->table_name");
			$all_wallets = $wallets->fetch();

			$resultSet = array();
			foreach ($all_wallets as $wallet):
				$newObj = new Wallet(); 	
				$newObj->ID = $wallet['id'];
				$newObj->NAME = $wallet['name'];
				$newObj->HASH_KEY = $wallet['hash_key'];
				array_push($resultSet, $newObj);
	   		endforeach;
			return array("statusCode" => 200, "data" => $resultSet);
		}
		catch(\Throwable $e){
			return array("statusCode" => 403, "data" => null);
		}
	}

	function GetWalletByID($id){
		try{
			$sql = new query("SELECT * FROM $this->table_name WHERE id=$id");
			$results = $sql->fetch();
			if(count($results) > 0){
				$resultSet = array();
				foreach ($results as $wallet):
					$newObj = new Wallet(); 	
					$newObj->ID = $wallet['id'];
					$newObj->NAME = $wallet['name'];
					$newObj->HASH_KEY = $wallet['hash_key'];
					$resultSet = $newObj;
					break;
				endforeach;
				
				return array("statusCode" => 200, "data" => $resultSet);
			}
			else{
				return array("statusCode" => 403, "data" => null);
			}
		}
		catch(\Throwable $e){
			return array("statusCode" => 403, "data" => null);
		}
	}

	function GetWalletByName($name){
		try{
			$sql = new query("SELECT * FROM $this->table_name WHERE name=$name");
			$results = $sql->fetch();
			if(count($results) > 0){
				$resultSet = array();
				foreach ($results as $wallet):
					$newObj = new Wallet(); 	
					$newObj->ID = $wallet['id'];
					$newObj->NAME = $wallet['name'];
					$newObj->HASH_KEY = $wallet['hash_key'];
					$resultSet = $newObj;
					break;
				endforeach;
				
				return array("statusCode" => 200, "data" => $resultSet);
			}
			else{
				return array("statusCode" => 403, "data" => null);
			}
		}
		catch(\Throwable $e){
			return array("statusCode" => 403, "data" => null);
		}
	}

	function CreateWallet($obj){
		try{
			$checkExists = $this->CheckExists($obj);
			if($checkExists > 0)
			{
				//already existed
				return array("statusCode" => 403, "data" => null);
			}
			$sql = new query("INSERT INTO $this->table_name (name, hash_key) VALUES ('$obj->NAME', '$obj->HASH_KEY')");
			// get auto-generated id
			$insertedID = $sql->inserted_id();
			
			return $this->GetWalletByID($insertedID);
		}
		catch(\Throwable $e){
			return array("statusCode" => 403, "data" => null);
		}
	}

	function DeleteWalletByID($id){
		try{	
			$sql = new query("DELETE FROM $this->table_name WHERE id=$id");
			if(!$sql->resource_id)
				return array("statusCode" => 403, "data" => null);
			else
				return array("statusCode" => 200, "data" => ["id" => $id]);
		}
		catch(\Throwable $e){
			return array("statusCode" => 403, "data" => null);
		}
	}

	function DeleteWallet($obj){
		try{
			$checkExists = $this->CheckExists($obj);
			if($checkExists > 0)
			{
				//already existed
				return $this->DeleteWalletByID($checkExists);
			}
			else{
				return array("statusCode" => 403, "data" => null);
			}
		}
		catch(\Throwable $e){
			return array("statusCode" => 403, "data" => null);
		}
	}
}

?>
