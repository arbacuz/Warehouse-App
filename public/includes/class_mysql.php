<?php
// if(eregi("class_mysql.php",$_SERVER['PHP_SELF'])){
// 	header("Location: ../index.php");
// }

class Database {
	var $host = DB_HOST;

	#-> Connect to Database
	function connectdb($db_name,$user,$pass) {
		#-> Set the variables
		$this->database = $db_name;
		$this->username = $user;
		$this->password = $pass;
		#-> Connect to DB
		$this->conndb = mysql_connect($this->host,$this->username,$this->password) or die (mysql_error());
		$this->db = mysql_select_db($this->database,$this->conndb) or die (mysql_error());
		mysql_query("SET NAMES UTF8");
	}

	#-> Close Database
	function closedb() {
		// mysql_close($this->conndb) or die (mysql_error());
		mysql_close($this->conndb) or die (mysql_error());

	}

	#-> Query data from database
	function querydb($sql) {
		if($result = mysql_query($sql)) {
			return $result;
		} else {
			mysql_error();
			return false;
		}
	}

	#-> Fetch
	function fetch($query) {
		if($result = mysql_fetch_array($query)) {
			return $result;
		} else {
			mysql_error();
			return false;
		}
	}

	#-> Add
	function add($table,$data) {
		$key = array_keys($data);
		$value = array_values($data);
		$sum = count($key);
		for($i=0;$i<$sum;$i++) {
			if(empty($add)) {
				$add = "(";
			} else {
				$add = $add.",";
			}
			if(empty($val)) {
				$val = "(";
			} else {
				$val = $val.",";
			}
			$add = $add.$key[$i];
			$val = $val."'".$value[$i]."'";
		}

		$add = $add.")";
		$val = $val.")";
		// $table = table name (config.php)
		// $data = ["name":"Company1"]
		#-> INSERT INTO tbl (data1,data2) VALUES ('val1','val2')
		$sql = "INSERT INTO ".$table." ".$add."VALUES ".$val;
		if(mysql_query($sql)) {
			return true;
		} else {
			mysql_error();
			return false;
		}
	}
	
	#-> Update
	function update($table,$data,$where) {
		$key = array_keys($data);
		$value = array_values($data);
		$sum = count($key);
		$set = "";
		for($i=0;$i<$sum;$i++) {
			if(!empty($set)){
				$set = $set.",";
			}
			$set = $set.$key[$i]."='".$value[$i]."'";
		}
		
		#-> UPDATE tbl SET username='$name',password='$pass' WHERE .....=....
		$sql = "UPDATE ".$table." SET ".$set." WHERE ".$where;
		if(mysql_query($sql)) {
			return true;
		} else {
			mysql_error();
			return false;
		}
	}

	#-> Delete
	function delete($table,$where) {
		$sql = "DELETE FROM ".$table." WHERE ".$where;
		if(mysql_query($sql)) {
			return true;
		} else {
			mysql_error();
			return false;
		}
	}
	
	#-> Num Row
	function num($query) {
		if($result = mysql_num_rows($query)) {
			return $result;
		} else {
			mysql_error();
			return false;
		}
	}
}
?>