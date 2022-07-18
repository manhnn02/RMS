<?php
define("NAMESPACE_DIR",'import'); 

// function __autoload($class) {     
//     // var_dump($class); 
//     // die();
//     // $parts = explode("\\", $class);     
//     // $filepath = implode(DIRECTORY_SEPARATOR,$parts);     
//     // $filepath = strtolower(NAMESPACE_DIR.DIRECTORY_SEPARATOR.$filepath.'.php');   
//     // include $filepath; 
// } 


include('./import/db/mysql.php');
include('./import/db/mysql/query.php');
include('./import/entity/Wallet.php');
include('./import/entity/Transaction.php');
include('./import/dao/WalletDAO.php');
include('./import/dao/TransactionDAO.php');

$DB_SETTINGS = array( 	
	'host' => 'localhost',
	'database' => 'rms',
	'username' => 'root',
	'password' => ''
);

global $DB_SETTINGS;
?>