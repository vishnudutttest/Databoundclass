<?php


class DB
{
  private $host;
	private $user;
	private $pass;
	private $result;
	private $query;
	private $db = 'test';
	private $table;
	private $con;

	public function __construct($host=NULL,$user=NULL,$pass=NULL){
		
		if($host==NULL){
			$host = "localhost";
		}
		if($user==NULL){
			$user = "root";
		}
		if($pass==NULL){
			$pass = "";
		}
		
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		// test
		/*echo $this->host;
		echo $this->user;
		echo $this->pass;*/
		$this->con = mysql_connect($this->host,$this->user,$this->pass) or die("Not connecte to local host <br />".mysql_error());

		$this->con = mysql_select_db($this->db,$this->con) or die("Not selected Database <br />".mysql_error());
		
	}

	public function prepare($query){
		$this->query = '';
		$this->query = $query;
	}

	public function execute(){
		if(!empty($this->query))
		$result = mysql_query($this->query) or mysql_error();
		else
			die('Undefined query');

		return $this->result = $result;
	}

	public function fetch(){
		return mysql_fetch_assoc($this->result);
	}

	public function setTable($table){
		$this->table = $table;
	}

	public function getTable(){
		if(!empty($this->table))
		return $this->table;
	}


}
