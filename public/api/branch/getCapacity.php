<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$branchID = $json->branch->_id;
$branchName = $json->branch->name;


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Query the data.
$query = $db->querydb("SELECT count(ib.itemID),b.branchName,b.branchID,b.branchAddress,b.branchTel,b.capacity FROM ".TB_BRANCH." b INNER JOIN ".TB_ITEMBRANCH." ib ON b.branchID=ib.branchID WHERE b.branchID=$branchID;");
#-> Preparing return data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	if($result = $db->fetch($query)) {
		$arr["data"]["attributes"]["name"]=$result["branchName"];
		$arr["data"]["attributes"]["address"] = $result["branchAddress"];
		$arr["data"]["attributes"]["telephone"] = $result["branchTel"];
		$arr["data"]["attributes"]["capacity"] = $result["capacity"];
		$capacity = $result["capacity"];
		$count = $result["count(ib.itemID)"];
		$usage = $count*100/$capacity;
		$free = 100 - $usage;
		$arr["data"]["attributes"]["usage"] = $usage;
		$arr["data"]["attributes"]["free"] = $free;
	}
} 
else {
	$arr["status"] = "error";
}


#-> Return json data.
echo json_encode($arr,JSON_NUMERIC_CHECK);

#-> Close database.
$db->closedb();

?>