<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$branchID = $json->user->relationships->branch->_id;
$staffID = $json->user->attributes->_id;
$companyID = $json->company->attributes->_id;
$statusID = 3; // pending
$orderTypeID = 1; // ORDER
$orderDate = date('Y-m-d H:i:s');
$items = $json->items;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

$data = array(
	"branchID" => $branchID,
	"staffID" => $staffID,
	"companyID" => $companyID,
	"statusID" => $statusID,
	"orderTypeID" => $orderTypeID,
	"orderDate" => $orderDate
	);
$table = TB_ORDER;
$query = $db->add($table,$data);
$arr = array();
if($query) {
	$query = $db->querydb("SELECT orderID FROM $table ORDER BY orderID DESC LIMIT 1");
	$result = $db->fetch($query);
	$orderID = $result["orderID"];
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Something wrong, when we add an order.";
}

#-> Preparing data.
$i = 0;
while(isset($items[$i])) {
	$itemID = $items[$i]->_id;
	$qty = $items[$i]->quantity;
	
	$data = array(
		"orderID" => $orderID,
		"itemID" => $itemID,
		"orderQuantity" => $qty
		);
	$table = TB_ORDERITEM;
	$query = $db->add($table,$data);

	$i++;
}
if($query) {
	$arr["status"] = "success";
	$arr["messages"] = "Order have been added.";
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Something wrong, when we add item to an order.";
}

#-> Return
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>