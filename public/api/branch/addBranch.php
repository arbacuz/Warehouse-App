<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

// IF ADD: 		$query = $db->add($table,$data)
// IF UPDATE:   $query = $db->update($table,$data,$where)
// IF DELETE:	$query = $db->delete($table,$where)
// IF QUERY: 	$query = $db->querydb("QUERY STATEMENT");
//
// SEE MORE ./includes/class_mysql.php

$arr = array();

#-> Preparing return data.
/*************** JSON SHOULD BE *******************
**
** {
**	 status: "success or error",
**   messages: "error messages",
**   data: {
**     attributes: {
**        columns1: data1,
**        columns2: data2,
**		  ..
**	   }
**	   relations: {
**		  tables1: {
**			columns1: data1,
**			columns2: data2,
**			..
**		  },
**		  tables2: {
**			columns1: data1,
**			columns2: data2,
**			..
**		  }
**	   }
**   }	
** }
**
***************************************************/
if($query) {
	$result = $db->fetch($query);
	// ASSIGN DATA TO ARRAY
} else {
	// IF NO RESULT
}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>