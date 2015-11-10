<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$items = $json->items;
$branchID = $json->branch->_id;
$stuffID = $json->stuffID->_id;
$lastUpdatedDate = date('Y-m-d H:i:s');

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);


# Query Branch
$sql = "SELECT branchID FROM ".TB_BRANCH." WHERE branchID = ".$branchID.";";
$checkBranch = $db->querydb($sql)
# If Branch found
if($checkBranch){
	#Add item to itemBranch and registerItem
$i = 0;
while($items[$i]) { 
	$itemName = $items[$i]->name;
	$type = $items[$i]->type;
	$costPerUnit = $items[$i]->cost;
	$qty = $items[$i]->quantity;
	#check if item exist in item table
	$sql = "SELECT * FROM ".TB_ITEM." WHERE itemName=".$itemName.";";
	$query = $db->querydb($sql);
	if(!$query){
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
			if($result = $db->fetch($queryItemType)){
				$typeName = $result["typeName"];
			}
			if($result = $db->fetch($queryItemID)){
				$itemID = $result["itemID"]+1;
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
			"itemType"=>$type,
			"costPerUnit"=>$costPerUnit
			);
		$query = $db->add(TB_ITEM,$data);
		if(!$query){
			$arr["status"] = "error";
			$arr["messages"] = "Fail to add item in item";
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
		if($result = $db->fetch($query)){
			 $qty += $result["quantity"];
		}
		$data = array(
			"itemID" => $itemID,
			"branchID"=>$branchID,
			"quantity"=>$qty,
			"lastUpdatedDate"=>$lastUpdatedDate,
			"staffID"=>$staffID
		);
		$where = "itemID=".$itemID." AND branchID=".$branchID.";";
		$query = $db->update(TB_ITEMBRANCH,$data,$where);
	}
	else{
		$data = array(
			"itemID" => $itemID,
			"branchID"=>$branchID,
			"quantity"=>$quantity,
			"lastUpdatedDate"=>$lastUpdatedDate,
			"staffID"=>$staffID
		);
		$query = $db->add(TB_ITEMBRANCH,$data);
	}
	$i++;
}
$arr = array();
#return status
if($query) {
	$arr["status"] = "success";
	$arr["messages"] = "Complete adding Item to .$table. table.";
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Error occure when you add the data to .$table. table.";
}
#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>