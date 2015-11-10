<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$branchID = $json->branch->_id;
$branchName = $json->branch->_id;
var_dump($json);

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Query the data.
$query = $db->querydb("SELECT * FROM ".TB_ITEMBRANCH. "WHERE branchID =".$branchID);

#-> Preparing return data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$i = 0;


	while($result = $db->fetch($query)) {
		#Query item name from itemID
		$sql = "SELECT * FROM ".TB_ITEM." WHERE itemID =".$result["itemID"].";";
		$subquery = $db->querydb($sql);
		if($subquery){
			if($subresult = $db->fetch($subquery)){
				$arr["data"][$i]["attributes"]["_id"] = $subresult["itemID"];
				$arr["data"][$i]["attributes"]["code"] = $subresult["itemCode"];
				$arr["data"][$i]["attributes"]["name"] = $subresult["itemName"];
				$arr["data"][$i]["attributes"]["cost"] = $subresult["costPerUnit"];
				$sql = "SELECT typeName FROM ".TB_ITEMTYPE." WHERE typeID =".$subresult["typeID"].";";
				$subquery = $db->querydb($sql);
				if($subquery){
					if($subresult = $db->fetch($subquery)){
						$arr["data"][$i]["relationships"]["type"]["name"]=$subresult["typeName"];
					}
				}
				
			}
		}
		#Query staff name from staffID
		$sql = "SELECT * FROM ".TB_STAFF." WHERE staffID =".$result["staffID"].";";
		$subquery = $db->querydb($sql);
		if($subquery){
			if($subresult = $db->fetch($subquery)){
				$arr["data"][$i]["relationships"]["staff"]["name"] = $subresult["staffName"];
			}
		}

		$arr["data"][$i]["relationships"]["branch"]["name"]=$branchName;
		$arr["data"][$i]["attributes"]["lastUpdatedDate"] = $result["lastUpdatedDate"];
		$arr["data"][$i]["attributes"]["quantity"] = $result["quantity"];
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