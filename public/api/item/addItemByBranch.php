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
		$type = $items[$i]->attributes->type->_id;
		$costPerUnit = $items[$i]->cost;
		$qty = $items[$i]->quantity;
		$typeName = $item[$i]->attributes->type->name;
		#check if item exist in item table
		$sql = "SELECT * FROM ".TB_ITEM." WHERE itemName='".$itemName."';";
		$checkItem = $db->querydb($sql);
		if(!$checkItem){
			#Create Item Code
			$queryItemID = $db->querydb("SELECT itemID FROM ".TB_ITEM." ORDER BY itemID DESC LIMIT 1");
			#Check if itemType and itemID is valid
			if($queryItemID){
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
			}
			$data = array(
				"itemCode" => $code,
				"itemName"=>$itemName,
				"itemType"=>$type,
				"costPerUnit"=>$costPerUnit
				);
			$query = $db->add(TB_ITEM,$data);
		}
		# fetch itemID
		$sql = "SELECT * FROM ".TB_ITEM." WHERE itemName='".$itemName."';";
		$checkItem = $db->querydb($sql);
		if($fetchItemID = $db->fetch($checkItem)){
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
		$checkItemBranch = $db->querydb($sql);
		if($checkItemBranch){
			if($result = $db->fetch($checkItemBranch)){
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
}
$arr = array();
#return status
if($query) {
	$arr["status"] = "success";
	$arr["messages"] = "Complete adding Item to itemBranch table.";
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Error occure when you add the data to .$table. table.";
}
#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>