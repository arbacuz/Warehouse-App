<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$companyTypeID = 1;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Query the data.
$query = $db->querydb("SELECT * FROM ".TB_COMPANYTYPE." WHERE companyTypeID = $companyTypeID");

#-> Preparing the data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$result = $db->fetch($query);
	$arr["data"]["attributes"]["_id"] = $result["companyTypeID"];
	$arr["data"]["attributes"]["name"] = $result["companyTypeName"];
} else {
	$arr["status"] = "error";
	$arr["messages"] = "failed to query";
}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>