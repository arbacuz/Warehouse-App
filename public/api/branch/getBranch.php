<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
// $branchID = $json->branchID;
$branchID = 1;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Query the data.
$query = $db->querydb("SELECT * FROM ".TB_BRANCH." WHERE branchID = '$branchID'");

#-> Preparing return data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	if($result = $db->fetch($query)) {
		$arr["data"]["attributes"]["_id"] = $result["branchID"];
		$arr["data"]["attributes"]["name"] = $result["branchName"];
		$arr["data"]["attributes"]["address"] = $result["branchAddress"];
		$arr["data"]["attributes"]["telephone"] = $result["branchTel"];
		$arr["data"]["attributes"]["capacity"] = $result["capacity"];
	}
} else {
	$arr["status"] = "error";
}


#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>