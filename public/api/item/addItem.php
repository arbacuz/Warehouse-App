<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$items = $json->items;
//var_dump($items);

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);


##### Dummy data #####
$items[0] = array(
	"itemID"=>3,
	"name"=>"Banana",
	"type"=>2,
	"costPerUnit"=>4.00,
	"quantity"=>1000
	);
#var_dump($items);
$companyID = 1;
$staffID = 1;
$branchID = 1;
######################


#-> Add Data
$i = 0;
$table = TB_ITEM;

while($items[$i]) { 
	$name = $items[$i]->name;
	$type = $items[$i]->type->attributes->_id;
	$cost = $items[$i]->cost;

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
	#Check if Type is valid or not
	$sql = "SELECT itemName FROM ".$table." WHERE itemName =".$name.";";
	$query = $db->querydb($sql);
	if(!$query){
		$data = array("itemCode" => $code,"itemName"=>$name,"itemType"=>$type,"costPerUnit"=>$cost);
		$query = $db->add(TB_ITEM,$data);
	}
	#get next value
	$i++;
}

$arr = array();
if($query && $queryItemID && $queryItemType) {
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