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

#-> Query the data.
$query = $db->querydb("SELECT * FROM ".TB_COMPANYTYPE);

#-> Preparing the data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$i = 0;
	while($result = $db->fetch($query)) {
		$arr["data"][$i]["attributes"]["_id"] = $result["companyTypeID"];
		$arr["data"][$i]["attributes"]["name"] = $result["companyTypeName"];
		$i ++;
	}
} else {
	$arr["status"] = "error";
	$arr["messages"] = "failed to query";
}

#-> Return json data.
echo json_encode($arr,JSON_NUMERIC_CHECK);

#-> Close database.
$db->closedb();

?>