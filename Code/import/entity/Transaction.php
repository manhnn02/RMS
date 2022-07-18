<?php
namespace Entity;
class Transaction
{
	public $ID;
    public $WALLET_ID;
	public $NAME;
    public $TYPE;
	public $AMOUNT;
    public $REFERENCE;
    public $TIMESTAMP;
    public $HASH_CHECK;

	function __construct(){

	}
}
?>