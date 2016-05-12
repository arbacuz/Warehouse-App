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
$capacity = $json->user->relationships->branch->capacity;
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

$amountItemQ = $db->querydb("SELECT COUNT(*) FROM ".TB_ITEMBRANCH." WHERE branchID = $branchID");
$amountItem = $db->fetch($amountItemQ);
$arr = array();
if($amountItem > $capacity) {
	// $arr = array();
	$arr["status"] = "error";
	$arr["messages"] = "Items capacity of this branch is exceed!";
} else {



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
	}else{
		$registerCode = "REG000";
	}
}
$data = array(
	"registerDate" => $registerDate,
	"staffID"=>$staffID,
	"branchID"=>$branchID,
	"companyID"=>$companyID,
	"registerCode"=>$registerCode
	);
$checkaddquery = $db->add(TB_REGISTER,$data);
$getRegisterID = $db->querydb("SELECT registerID FROM ".TB_REGISTER." ORDER BY registerID DESC LIMIT 1");
if($getRegisterID && $result=$db->fetch($getRegisterID)){
	$registerID = $result["registerID"];
}
#Add item to itemBranch and registerItem
$i = 0;
while($items[$i]) {
	#initialize data
	$itemName = $items[$i]->name;
	$type = $items[$i]->type->attributes->_id;
	$typeName = $items[$i]->type->attributes->name;
	$costPerUnit = $items[$i]->cost;
	$qty = $items[$i]->quantity;
	#check if item exist in item table
	$sql = "SELECT * FROM ".TB_ITEM." WHERE itemName='".$itemName."';";
	// var_dump($items[$i]->type);
	if(!$db->fetch($db->querydb($sql))) {
		#Create Item Code
		$sqlItemID = "SELECT itemID FROM ".TB_ITEM." ORDER BY itemID DESC LIMIT 1"; //query last itemID
		$queryItemID = $db->querydb($sqlItemID);
		#Check if itemType and itemID is valid
		if($queryItemID){
			if($itemData = $db->fetch($queryItemID)){
				$itemID = $itemData["itemID"]+1;
				$str = str_split($typeName);
				if($itemID < 10){
					$code = $str[0]."00".strval($itemID);
				}else if($itemID < 100){
					$code = $str[0]."0".strval($itemID);
				}else{
					$code = $str[0].strval($itemID);
				}
			}
			else{
				$str = str_split($typeName);
				$code = $str[0]."000";
			}
			
		}
		$data = array(
			"itemCode" => $code,
			"itemName"=>$itemName,
			"typeID"=>$type,
			"costPerUnit"=>$costPerUnit
			);
		$checkaddquery = $db->add(TB_ITEM,$data);
	}
	$queryItem = $db->querydb("SELECT * FROM ".TB_ITEM." WHERE itemName='".$itemName."';");
	# fetch itemID
	if($fetchItemID = $db->fetch($queryItem)){
		$itemID = $fetchItemID["itemID"];
	}
	#Check if itemID is already exist in branch or not
	$queryItemBranch = $db->querydb("SELECT * FROM ".TB_ITEMBRANCH." WHERE itemID=".$itemID." AND branchID=".$branchID.";");
	if($itemBranchData = $db->fetch($queryItemBranch)){
		$qty += $itemBranchData["quantity"];
		$data = array(
			"itemID" => $itemID,
			"branchID"=>$branchID,
			"quantity"=>$qty,
			"lastUpdatedDate"=>$registerDate,
			"staffID"=>$staffID
		);
		$where = "itemID=".$itemID." AND branchID=".$branchID.";";
		$checkAddItemBranch = $db->update(TB_ITEMBRANCH,$data,$where);
	}
	else{
		$data = array(
			"itemID" => $itemID,
			"branchID"=>$branchID,
			"quantity"=>$qty,
			"lastUpdatedDate"=>$registerDate,
			"staffID"=>$staffID
		);
		$checkAddItemBranch = $db->add(TB_ITEMBRANCH,$data);	
	}
	#Get registerID
	$data = array(
		"registerID" => $registerID,
		"itemID"=>$itemID,
		"registerQuantity"=>$qty,
	);
	$checkAddRegisterItem = $db->add(TB_REGISTERITEM,$data);
	$i++;#Get next item
}

}
#Send status

if($query) {
	$arr["status"] = "success";
	$arr["messages"] = "Complete adding Item to $table table.";
} else {
	$arr["status"] = "error";
	$arr[$j]["messages"] = "Error occure when you add the data to registerItem table.";
}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>