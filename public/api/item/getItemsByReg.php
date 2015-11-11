<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$registerCode = $json->register_code;
$branchID = $json->branch->_id;
$branchName = $json->branch->name;


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Query the data.
$query = $db->querydb("SELECT * FROM ".TB_REGISTER." r INNER JOIN ".TB_STAFF." sf ON r.staffID = sf.staffID INNER JOIN "
					.TB_BRANCH." b ON r.branchID=b.branchID INNER JOIN "
					.TB_COMPANY." c ON r.companyID=c.companyID INNER JOIN "
					.TB_REGISTERITEM." ri ON r.registerID=ri.registerID INNER JOIN "
					.TB_ITEM." i ON ri.itemID=i.itemID INNER JOIN "
					.TB_ITEMBRANCH." ib ON i.itemID = ib.itemID INNER JOIN "
					.TB_ITEMTYPE." it ON i.typeID=it.typeID WHERE r.registerCode = '$registerCode' AND r.branchID=$branchID;");

#-> Preparing return data.
$arr = array();
if($query){
	$i = 0;
	$arr["status"] = "success";
	while($result = $db->fetch($query)){
		$arr["data"][$i]["relationships"]["register"]["staff"]["name"] = $result["staffName"];
		$arr["data"][$i]["relationships"]["register"]["registerdate"] = $result["registerDate"];
		$arr["data"][$i]["relationships"]["register"]["branchName"] = $result["branchName"];
		$arr["data"][$i]["relationships"]["register"]["companyName"] = $result["companyName"];
		$arr["data"][$i]["attributes"]["_id"] = $result["itemID"];
		$arr["data"][$i]["attributes"]["code"] = $result["itemCode"];
		$arr["data"][$i]["attributes"]["name"] = $result["itemName"];
		$arr["data"][$i]["attributes"]["cost"] = $result["costPerUnit"];
		$arr["data"][$i]["relationships"]["type"]["name"]=$result["typeName"];
		$arr["data"][$i]["relationships"]["type"]["_id"]=$result["typeID"];
		$arr["data"][$i]["attributes"]["quantity"] = $result["quantity"];
		$i++;
	}
}
else{
	$arr["status"] = "error";
	$arr["messages"] = "Error occure when you query the data to $table table.";
	echo json_encode($arr);
	exit();
}
#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>