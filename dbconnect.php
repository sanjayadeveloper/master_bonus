<?php

class dbFunction {
	public $mysqli='';

	function __construct(){
		// require_once("../../config.php");
		$localhost = 'localhost';
		$dbname = 'erp';
		$username = 'root';
		$pass = '';
		$this->mysqli = new mysqli($localhost,$username,$pass,$dbname);
		// $this->mysqli = $con;
	}


	public function dataInsert($table, $values=[]){

		$keys='';
		$counter=0;
		foreach ($values as $key => $value) {
			$counter++;
			if(count($values)==$counter){
				$keys.=$key;
			}else{
				$keys.=$key.',';
			}
		}

		$valuess='';
		$counters=0;
		foreach ($values as $key => $value) {
			$counters++;
			if(count($values)==$counters){
				$valuess.="'".$value."'";
			}else{
				$valuess.="'".$value."',";
			}
		}
		$insertQry = $this->mysqli->query("INSERT INTO $table($keys) VALUES($valuess)");
		$this->insertId = mysqli_insert_id($this->mysqli);
		return $insertQry;
		// return "INSERT INTO $table($keys) VALUES($valuess)";
	}
	public function getDataFromDB($vls,$tables,$whereData='',$orderby='',$limit=''){
		if($whereData!=''){
			$where = "WHERE ".$whereData;
		}else{
			$where = "";
		}

		if($orderby!=''){
			$orderby = "ORDER BY ".$orderby;
		}else{
			$orderby = "";
		}

		if($limit!=''){
			$limit = "LIMIT ".$limit;
		}else{
			$limit = "";
		}

		$selectQry = $this->mysqli->query("SELECT $vls FROM $tables $where $orderby $limit");
		return $selectQry;
		// return "SELECT $vls FROM $tables $where $orderby $limit";
		
	}

	public function getNumRows($vls,$tables,$whereData=''){
		if($whereData!=''){
			$where = "WHERE ".$whereData;
		}else{
			$where = "";
		}

		$selectQry = $this->mysqli->query("SELECT $vls FROM $tables $where");
		$num_rows_res = $selectQry->num_rows;
		return $num_rows_res;
		// return "SELECT $vls FROM $tables $where";
		
	}
	

	public function fetchAllData($qryData){
		return $qryData->fetch_assoc();
	}

	public function getLastID(){
		return $this->insertId;
	}

	public function dataUpdate($table, $values=[], $where=''){

		$keysVls='';
		$counter=0;
		foreach ($values as $key => $value) {
			$counter++;
			if(count($values)==$counter){
				$keysVls.=$key."="."'".$value."'";
			}else{
				$keysVls.=$key."="."'".$value."',";
			}
		}

		if($where!=''){
			$whereVls = "WHERE ".$where;
		}else{
			$whereVls = "";
		}

		$insertQry = $this->mysqli->query("UPDATE $table SET $keysVls $whereVls");
		return $insertQry;
		// return "UPDATE $table SET $keysVls $whereVls";
	}

	public function deleteDataFromDB($table, $where=''){

		if($where!=''){
			$whereVls = "WHERE ".$where;
		}else{
			$whereVls = "";
		}

		$insertQry = $this->mysqli->query("DELETE FROM $table $whereVls");
		return $insertQry;
		// return "UPDATE $table SET $keysVls $whereVls";
	}

}

$conn_obj = new dbFunction();


?>