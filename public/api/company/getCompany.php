<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$companyID = 1;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Query the data.
$query = $db->querydb("SELECT * FROM ".TB_COMPANY." WHERE companyID = $companyID");

#-> Preparing the data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$result = $db->fetch($query);
	$arr["data"]["attributes"]["id"] = $result["companyID"];
	$arr["data"]["attributes"]["name"] = $result["companyName"];
	$arr["data"]["attributes"]["address"] = $result["companyAddress"];
	$arr["data"]["attributes"]["telephone"] = $result["companyTel"];
	// echo json_encode($arr);
} else {
	$arr["status"] = "error";
	$arr["messages"] = "failed to get company information";
}

#-> Return data
echo json_encode($arr,JSON_NUMERIC_CHECK);

#-> Close database.
$db->closedb();

?>