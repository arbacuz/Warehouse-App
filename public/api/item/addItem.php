<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
//$items = $json->items;
//$typeName = $json->attributes->type->name;
//var_dump($items);

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);


##### Dummy data #####
$items[0] = array(
	"itemID"=>3,
	"name"=>"Onlo",
	"type"=>2,
	"costPerUnit"=>4.00,
	);
#var_dump($items);
$companyID = 1;
$staffID = 1;
$branchID = 1;
$typeName = "Material";
######################


#-> Add Data
$i = 0;
while($items[$i]) { 
	// $name = $items[$i]->name;
	// $type = $items[$i]->type->attributes->_id;
	// $cost = $items[$i]->cost;
	#Dummy
	$name = "Onlo";
	$type = 1;
	$cost = 10;

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
		exit();
	}
	#Check if Type is valid or not
	$checkItem = $db->querydb("SELECT itemName FROM ".TB_ITEM." WHERE itemName ='".$name."' AND typeID = $type;");
	if(!$checkItem){
		$data = array("itemCode" => $code,"itemName"=>$name,"itemType"=>$type,"costPerUnit"=>$cost);
		$addItem = $db->add(TB_ITEM,$data);
	}
	#get next value
	$i++;
}

$arr = array();
if($query) {
	$arr["status"] = "success";
	$arr["messages"] = "Complete adding Item to item table.";
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Error occure when you add the data to .$table. table.";
}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>