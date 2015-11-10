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

$query = $db->querydb("SELECT * FROM ".TB_ORDER." ");
if($query)
	{
		$arr["status"] = "success";
		$i = 0;
		while($result = $db->fetch($query)) {
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
		$arr["messages"] = "failed to get order information";
	}
echo json_encode($arr);
#-> Close database.
$db->closedb();

?>