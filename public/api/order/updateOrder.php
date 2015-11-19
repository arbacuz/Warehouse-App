<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$orderID = $json->order->attributes->_id;
$statusID = $json->order->relationships->status->attributes->_id;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

$arr = array();

//UPDATE COMPANY TABLE 
$table = TB_ORDER;
if($statusID == 1) {
	if($orderID){
		if($orderID < 10){
			$invCode = "INV00".strval($orderID);
		}else if($orderID  < 100){
			$invCode = "INV0".strval($orderID);
		}else{
			$invCode = "INV".strval($orderID);
		}
	}else{
		$invCode = "INV000";
	}

	$deliverdDate = date('Y-m-d H:i:s');
	$data = array(
		"statusID" => $statusID,
		"invoiceCode" => $invCode,
		"deliverdDate" => $deliverdDate
		);
} else {
	$data = array(
		"statusID" => $statusID
		);
}
$where = "orderID = $orderID";

$query = $db->update($table,$data,$where);

if($query) {
	$arr["status"] = "success";
	$arr["messages"] = "Order status have been updated.";
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Cannot update an order!";
}

echo json_encode($arr);
#-> Close database.
$db->closedb();

?>