<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$typeName = $json->typeName;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

// IF ADD: 		$query = $db->add($table,$data)
// IF UPDATE:   $query = $db->update($table,$data,$where)
// IF DELETE:	$query = $db->delete($table,$where)
// IF QUERY: 	$query = $db->querydb("QUERY STATEMENT");
//
// SEE MORE ./includes/class_mysql.php

$query = $db->querydb("SELECT companyID,companyName,companyAddress,companyTel,companyTypeName,".TB_COMPANY.".companyTypeID FROM ".TB_COMPANY." INNER JOIN ".TB_COMPANYTYPE." ON ".TB_COMPANY.".companyTypeID = ".TB_COMPANYTYPE.".companyTypeID WHERE companyTypeName='$typeName'");
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
	$arr["status"] = "success";
	$i = 0;
	while($result = $db->fetch($query)) {
		$arr["data"][$i]["attributes"]["_id"] = $result["companyID"];
		$arr["data"][$i]["attributes"]["name"] = $result["companyName"];
		$arr["data"][$i]["attributes"]["address"] = $result["companyAddress"];
		$arr["data"][$i]["attributes"]["telephone"] = $result["companyTel"];

		$arr["data"][$i]["relationships"]["companyType"]["_id"] = $result["companyTypeID"];
		$arr["data"][$i]["relationships"]["companyType"]["name"] = $result["companyTypeName"];
		$i ++;
	}
} else {
	$arr["status"] = "error";
}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>