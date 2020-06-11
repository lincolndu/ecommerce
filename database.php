<?php 
/*
 * Databas Connection setting
*/

 class databaseConnection{
 	private $host = 'localhost'; //Host Name
    private $db_name = 'brainstation'; //Database Name
    private $db_username = 'root'; //Database Username
    private $db_password = ''; //Database Password
    public $conn=false; //Database Connection

    public function __construct()  
    {  
       try{
        $this->conn = new PDO('mysql:host='. $this->host .';dbname='.$this->db_name, $this->db_username, $this->db_password);
	    } catch (PDOException $e) {
	        exit('Error Connecting To DataBase');
	    }
    }
	public function __destruct(){
		 $this->conn=null;
	}
 }
$connection= new databaseConnection();

?>
