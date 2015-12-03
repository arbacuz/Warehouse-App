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
$query = $db->querydb("SELECT * FROM ".TB_BRANCH);

#-> Preparing return data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$i = 0;
	while($result = $db->fetch($query)) {
		$arr["data"][$i]["attributes"]["_id"] = $result["branchID"];
		$arr["data"][$i]["attributes"]["name"] = $result["branchName"];
		$arr["data"][$i]["attributes"]["address"] = $result["branchAddress"];
		$arr["data"][$i]["attributes"]["telephone"] = $result["branchTel"];
		$arr["data"][$i]["attributes"]["capacity"] = $result["capacity"];
		$arr["data"][$i]["update"] = false;
		$i ++;
	}
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Cannot get the branch.";

}

#-> Return json data.
echo json_encode($arr,JSON_NUMERIC_CHECK);

#-> Close database.
$db->closedb();

?>