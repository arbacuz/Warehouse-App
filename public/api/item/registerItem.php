<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$items = $json->items;
$companyID = $json->supplier->attributes->_id;
$staffID = $json->user->attributes->_id;
$branchID = $json->user->relationships->branch->_id;
$registerDate = date('Y-m-d H:i:s');

// var_dump($items);

##### Dummy data #####
// $items[0] = array(
// 	"itemID"=>3,
// 	"name"=>"Banana",
// 	"type"=>2,
// 	"costPerUnit"=>4.00,
// 	"quantity"=>1000
// 	);
// #var_dump($items);
// $companyID = 1;
// $staffID = 1;
// $branchID = 1;
######################


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Add Data
#==============
#Add register
#==============

#Create register code
$sql = "SELECT registerID FROM ".TB_REGISTER." ORDER BY registerID DESC LIMIT 1 "; //query last registerID
$query = $db->querydb($sql);
// #Check if registerID is valid
if($query){
	if($registerData = $db->fetch($query)){
		if($registerData["registerID"]+1 < 10){
			$registerCode = "REG00".strval($registerData["registerID"]+1);
		}else if($registerData["registerID"]+1  < 100){
			$registerCode = "REG0".strval($registerData["registerID"]+1);
		}else{
			$registerCode = "REG".strval($registerData["registerID"]+1);
		}
	}
}
$data = array(
	"registerDate" => $registerDate,
	"staffID"=>$staffID,
	"branchID"=>$branchID,
	"companyID"=>$companyID,
	"registerCode"=>$registerCode
	);
// $query = $db->add(TB_REGISTER,$data);

#Add item to itemBranch and registerItem
$i = 0;
while($items[$i]) { 
	#initialize data
	$itemName = $items[$i]->name;
	$type = $items[$i]->type->attributes->_id;
	$costPerUnit = $items[$i]->cost;
	$qty = $items[$i]->quantity;
	#check if item exist in item table
	$sql = "SELECT * FROM ".TB_ITEM." WHERE itemName=".$itemName.";";
	$query = $db->querydb($sql);
	// var_dump($items[$i]->type);
	if(!$query) {
		#Create Item Code
		$sqlItemType = "SELECT * FROM ".TB_ITEMTYPE." WHERE typeID=".$type.";"; // quey itemType
		$sqlItemID = "SELECT itemID FROM ".TB_ITEM." ORDER BY itemID DESC LIMIT 1"; //query last itemID
		$queryItemType = $db->querydb($sqlItemType);
		$queryItemID = $db->querydb($sqlItemID);
		if(!$queryItemType){
			$arr["status"] = "error";
			$arr["messages"] = "Fail query item type";
			echo json_encode($arr);
			exit();
		}
		if(!$sqlItemID){
			$arr["status"] = "error";
			$arr["messages"] = "Fail query item ID";
			echo json_encode($arr);
			exit();
		}
		#Check if itemType and itemID is valid
		if($queryItemType && $queryItemID){
			if($itemTypeData = $db->fetch($queryItemType)){
				$typeName = $itemTypeData["typeName"];
			}
			if($itemData = $db->fetch($queryItemID)){
				$itemID = $itemData["itemID"]+1;
			}
			if($itemID < 10){
				$str = str_split($typeName);
				$code = $str[0]."00".strval($itemID);
			}else if($itemID < 100){
				$str = str_split($typeName);
				$code = $str[0]."0".strval($itemID);
			}else{
				$str = str_split($typeName);
				$code = $str[0].strval($itemID);
			}
		}else{
			$arr["status"] = "error";
			$arr["messages"] = "Can't Concate Code";
			echo json_encode($arr);
			exit();
		}
		$data = array(
			"itemCode" => $code,
			"itemName"=>$itemName,
			"typeID"=>$type,
			"costPerUnit"=>$costPerUnit
			);
		$query = $db->add(TB_ITEM,$data);
		if(!$query){
			$arr["status"] = "error";
			$arr["messages"] = "Fail to add item in registration";
			echo json_encode($arr);
			exit();
		}
	}
	# fetch itemID
	if($fetchItemID = $db->fetch($query)){
		$itemID = $fetchItemID["itemID"];
	}
	else{
		$arr["status"] = "error";
		$arr["messages"] = "Fail to fetch itemID";
		echo json_encode($arr);
		exit();
	}
	#Check if itemID is already exist in branch or not
	$sql = "SELECT * FROM ".TB_ITEMBRANCH." WHERE itemID=".$itemID." AND branchID=".$branchID.";";
	$query = $db->querydb($sql);
	if($query){
		if($itemBranchData = $db->fetch($query)){
			$qty += $itemBranchData["quantity"];
		}
		$data = array(
			"itemID" => $itemID,
			"branchID"=>$branchID,
			"quantity"=>$qty,
			"lastUpdatedDate"=>$registerDate,
			"staffID"=>$staffID
		);
		$where = "itemID=".$itemID." AND branchID=".$branchID.";";
		$query = $db->update(TB_ITEMBRANCH,$data,$where);
	}else{
		$data = array(
			"itemID" => $itemID,
			"branchID"=>$branchID,
			"quantity"=>$qty,
			"lastUpdatedDate"=>$registerDate,
			"staffID"=>$staffID
		);
		$query = $db->add(TB_ITEMBRANCH,$data);
	}
	#Get registerID
	$sql = "SELECT registerID FROM ".TB_REGISTER." WHERE ORDER BY registerID DESC LIMIT 1";
	$query = $db->querydb($sql);
	if($query){
		if($registerData = $db->fetch($query)){
			$registerID = $itemBranchData["registerID"];
		}
		$data = array(
			"registerID" => $registerID,
			"itemID"=>$itemID,
			"registerQuantity"=>$qty,
		);
		$query = $db->add(TB_REGISTERITEM,$data);
	}
	$i++;#Get next item
}
#Send status
$arr = array();
if($query) {
	$arr["status"] = "success";
	$arr["messages"] = "Complete adding Item to $table table.";
} else {
	$arr["status"] = "error";
	$arr[$j]["messages"] = "Error occure when you add the data to ".$table." table.";
}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>