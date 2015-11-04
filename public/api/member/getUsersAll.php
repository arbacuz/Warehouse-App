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
$query = $db->querydb("SELECT ".TB_BRANCH.".branchName,".TB_BRANCH.".branchAddress, ".TB_STAFF.".staffID, ".TB_STAFF.".staffName, ".TB_STAFF.".email, ".TB_POSITION.".positionName FROM ".TB_STAFF." INNER JOIN ".TB_BRANCH." ON ".TB_STAFF.".branchID = ".TB_BRANCH.".branchID INNER JOIN ".TB_POSITION." ON ".TB_STAFF.".positionID = ".TB_POSITION.".positionID");

#-> Preparing return data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$i = 0;
	while($result = $db->fetch($query)) {	
		$arr["data"]["attributes"]["_id"] = $result["staffID"];
		$arr["data"][$i]["attributes"]["name"] = $result["staffName"];
		$arr["data"][$i]["attributes"]["email"] = $result["email"];
		$arr["data"][$i]["relationships"]["position"]["name"] = $result["positionName"];
		$arr["data"][$i]["relationships"]["branch"]["name"] = $result["branchName"];
		$arr["data"][$i]["relationships"]["branch"]["address"] = $result["branchAddress"];
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