<?php
namespace DB; 
class MySQL
{
    protected $host = 'localhost'; 	
    protected $database = 'rms'; 	
    protected $username = 'root'; 	
    protected $password = ''; 	
    public $connection = NULL; 

    function __construct($settings = array())
    {
        $this->host          = $settings['host'];
		$this->database 	= $settings['database'];
		$this->username 	= $settings['username'];
		$this->password 	= $settings['password'];

        if( is_null($this->connection) ) {
			$this->getConnection();
		}

		return $this->connection;
    }

    function __destruct()
    {
        mysqli_close($this->connection);
    }


	public function getConnection(){
        $this->connection = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        if(!$this->connection) {
            die("Unable to create connection, Server might be too busy or check your credentials!");			
        }

        return $this;
    }

}

?>