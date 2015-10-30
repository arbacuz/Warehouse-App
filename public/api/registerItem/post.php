<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Add Data
$i = 0;
while($json[$[i]) {
	// $query = $db->add(TB_BILL,array("timestamp"=>$timestamp,"user_id"=>$user_id,"bill_status"=>$bill_status,"bill_price"=>$bill_price));
	$i ++;
}

$arr = array();
#-> Preparing return data.
// if($query) {
// 	$query = $db->querydb("SELECT bill_id FROM ".TB_BILL." WHERE timestamp='$timestamp'");
// 	$result = $db->fetch($query);
// 	$i = 0;
// 	while($product_id[$i]) {
// 		$query = $db->add(TB_ORDER,array("bill_id"=>$result["bill_id"],"product_id"=>$product_id[$i],"order_amount"=>$order_amount[$i]));
// 		$i++;
// 	}
// 	$arr["status"] = "success";
// 	$arr["messages"] = "Your order was add completely.";
// } else {
// 	$arr["status"] = "error";
// 	$arr["messages"] = "Failed.";
// }

#-> Return json data.
echo json_encode($json);

#-> Close database.
$db->closedb();

?>