<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$registerID = $json->register->_id;
$branchID = $json->branch->_id;
$branchName = $json->branch->name;


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

$sql = "SELECT * FROM ".TB_TB_REGISTER." WHERE registerID=".$registerID." AND branchID=".$branchID.";";
$query = $db->querydb($sql);
if($query){
	$i=0;
	#get register data
	$registerData = $db->fetch($query);
	#query item in registerItem
	$sql = "SELECT * FROM ".TB_REGISTERITEM." WHERE registerID=".$registerID.";";
	$subquery = $db->querydb($sql);
	//query register
	while($itemData = $db->fetch($subquery)){
		$arr["data"][$i]["attributes"]["_id"] = $registerData["registerID"];
		$arr["data"][$i]["attributes"]["code"] = $registerData["registerCode"];
		$arr["data"][$i]["attributes"]["date"] = $registerData["registerDate"];

		#Query staff name from staffID
		$sql = "SELECT * FROM ".TB_STAFF." WHERE staffID =".$registerData["staffID"].";";
		$checkQuery = $db->querydb($sql);
		if($checkQuery){
			if($fetchResult = $db->fetch($checkQuery)){
				$arr["data"][$i]["attributes"]["staffName"] = $fetchResult["staffName"];
			}
		}
		#get branch name
		$sql = "SELECT * FROM ".TB_BRANCH." WHERE branchID =".$registerData["branchID"].";";
		$checkQuery = $db->querydb($sql);
		if($checkQuery){
			if($fetchResult = $db->fetch($checkQuery)){
				$arr["data"][$i]["attributes"]["branchName"] = $fetchResult["branchName"];
			}
		}
		#Query company
		$sql = "SELECT * FROM ".TB_COMPANY." WHERE companyID =".$registerData["companyID"].";";
		$checkQuery = $db->querydb($sql);
		if($checkQuery){
			if($fetchResult = $db->fetch($checkQuery)){
				$arr["data"][$i]["attributes"]["companyName"] = $fetchResult["companyName"];
			}
		}
		//query item in register form
		$arr["data"][$i]["attributes"]["itemID"] = $itemData["itemID"];
		$arr["data"][$i]["attributes"]["itemCode"] = $itemData["itemCode"];
		$arr["data"][$i]["attributes"]["itemName"] = $itemData["itemName"];
		$arr["data"][$i]["attributes"]["costPerUnit"] = $itemData["costPerUnit"];
		$sql = "SELECT typeName FROM ".TB_ITEMTYPE." WHERE typeID =".$itemData["typeID"].";";
		$checkQuery = $db->querydb($sql);
		if($checkQuery){
			if($fetchResult = $db->fetch($checkQuery)){
				$arr["data"][$i]["attributes"]["type"]=$fetchResult["typeName"];
			}
		}
		$i++;
	}
}else{
	$arr["status"] = "error";
	$arr["messages"] = "Fail query register";
	echo json_encode($arr);
	exit();
}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>