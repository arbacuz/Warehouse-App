<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$orderID = 2; 

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

$query = $db->querydb("SELECT * FROM ".TB_ORDER." WHERE orderID = '$orderID' ");
if($query)
	{
		$arr["status"] = "success";
		$result = $db->fetch($query);
		$arr["data"]["order_list"]["order_id"] = $result["orderID"];
		$arr["data"]["order_list"]["orderDate"] = $result["orderDate"];
		$arr["data"]["order_list"]["branch_id"] = $result["branchID"];
		$arr["data"]["order_list"]["staff_id"] = $result["staffID"];
		$arr["data"]["order_list"]["company_id"] = $result["companyID"];
		$arr["data"]["order_list"]["status_id"] = $result["statusID"];
		$arr["data"]["order_list"]["invoiceCode"] = $result["invoiceCode"];
		$arr["data"]["order_list"]["deliverdDate"] = $result["deliverdDate"];
		$arr["data"]["order_list"]["orderTypeID"] = $result["orderTypeID"];
		$arr["data"]["order_list"]["toBranchID"] = $result["toBranchID"];
	} 
else {
		$arr["status"] = "error";
		$arr["messages"] = "failed to get order information";
	}
echo json_encode($arr);
#-> Close database.
$db->closedb();

?>