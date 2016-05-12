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

#dummy data
$itemID[0] = 1;
$orderID = 1; //order to delete 
$delete = false;
$orderTypeID = 2;

$arr = array();
//DELETE ORDER ITEM 
$i = 0;

while(isset($itemID[$i])) { 
$table = TB_ORDERITEM;
$where = "itemID = $itemID[$i]" AND "orderID = $orderID";
$query = $db->delete($table,$where);
if($query)
	{
		$arr["status"] = "success";
		$delete = true;
	}
else {
		$arr["status"] = "error";
		$arr["messages"] = "Failed to delete order item";
	}
$i++;
}
if($delete){
	$query = $db->querydb("SELECT * FROM ".TB_ORDER." INNER JOIN ".TB_ORDERITEM." ON ".TB_ORDER.".orderID = ".TB_ORDERITEM.".orderID");
	$i = 0;
	while($result = $db->fetch($query)) {
		$arr["data"][$i]["attributes"]["orderID"] = $result["orderID"];
		$arr["data"][$i]["attributes"]["itemID"] = $result["itemID"];
		$arr["data"][$i]["attributes"]["quantity"] = $result["orderQuantity"];
		
		$arr["data"][$i]["relationships"]["order_list"]["order_id"] = $result["orderID"];
		$arr["data"][$i]["relationships"]["order_list"]["orderDate"] = $result["orderDate"];
		$arr["data"][$i]["relationships"]["order_list"]["branch_id"] = $result["branchID"];
		$arr["data"][$i]["relationships"]["order_list"]["staff_id"] = $result["staffID"];
		$arr["data"][$i]["relationships"]["order_list"]["company_id"] = $result["companyID"];
		$arr["data"][$i]["relationships"]["order_list"]["status_id"] = $result["statusID"];
		$arr["data"][$i]["relationships"]["order_list"]["invoiceCode"] = $result["invoiceCode"];
		$arr["data"][$i]["relationships"]["order_list"]["deliverdDate"] = $result["deliverdDate"];
		$arr["data"][$i]["relationships"]["order_list"]["orderTypeID"] = $result["orderTypeID"];
		$arr["data"][$i]["relationships"]["order_list"]["toBranchID"] = $result["toBranchID"];
	}
}
//DELETE ORDER 
$table = TB_ORDER;
$where = "orderID = $orderID" AND "orderTypeID = orderTypeID";
$query = $db->delete($table,$where);
if($query)
	{
		$arr["status"] = "success";
		$query = $db->querydb("SELECT * FROM ".TB_ORDER." INNER JOIN ".TB_ORDERITEM." ON ".TB_ORDER.".orderID = ".TB_ORDERITEM.".orderID");
		$i = 0;
		while($result = $db->fetch($query)) {
			$arr["data"][$i]["relationships"]["attributes"]["orderID"] = $result["orderID"];
			$arr["data"][$i]["relationships"]["attributes"]["itemID"] = $result["itemID"];
			$arr["data"][$i]["relationships"]["attributes"]["quantity"] = $result["orderQuantity"];
			
			$arr["data"][$i]["order_list"]["order_id"] = $result["orderID"];
			$arr["data"][$i]["order_list"]["orderDate"] = $result["orderDate"];
			$arr["data"][$i]["order_list"]["branch_id"] = $result["branchID"];
			$arr["data"][$i]["order_list"]["staff_id"] = $result["staffID"];
			$arr["data"][$i]["order_list"]["company_id"] = $result["companyID"];
			$arr["data"][$i]["order_list"]["status_id"] = $result["statusID"];
			$arr["data"][$i]["order_list"]["invoiceCode"] = $result["invoiceCode"];
			$arr["data"][$i]["order_list"]["deliverdDate"] = $result["deliverdDate"];
			$arr["data"][$i]["order_list"]["orderTypeID"] = $result["orderTypeID"];
			$arr["data"][$i]["order_list"]["toBranchID"] = $result["toBranchID"];
			$i++;
		}
	}
else {
		$arr["status"] = "error";
		$arr["messages"] = "Failed to delete order";
	}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>