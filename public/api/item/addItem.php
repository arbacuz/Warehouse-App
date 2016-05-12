<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$name = $json->item->name;
$typeID = $json->item->type->attributes->_id;
$typeName = $json->item->type->attributes->name;
$cost = $json->item->cost;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#Create Item Code
$sql = "SELECT itemID FROM ".TB_ITEM." ORDER BY itemID DESC LIMIT 1;";
$query = $db->querydb($sql);
#Check if itemType and itemID is valid
if($sql){
	if($result = $db->fetch($query)){
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
	$db->closedb();
	exit();
}

#Check if Type is valid or not
$checkItem = $db->querydb("SELECT itemName FROM ".TB_ITEM." WHERE itemName ='$name' AND typeID = $type;");
if(!$db->fetch($checkItem)){
	$data = array("itemCode" => $code,
				"itemName"=> $name,
				"typeID"=> $typeID,
				"costPerUnit"=> $cost);

	$addItem = $db->add(TB_ITEM,$data);
}

$arr = array();
if($addItem) {
	$arr["status"] = "success";
	$arr["messages"] = "Complete adding Item to item table.";
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Error occure when you add the data to ".TB_ITEM." table.";
}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>