<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);

#Dummy Data
$statusName = "Cancelled";

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

//ADD STATUS TABLE 
if(strlen($statusName) == 0) {
	$query = false;
} else {
	$table = TB_STATUS;
	$data = array("statusName"=>$statusName);
	$query = $db->add($table,$data);
}

if($query)
	{
		$arr["status"] = "success";
		$query = $db->querydb("SELECT * FROM ".TB_STATUS." INNER JOIN ".TB_ORDER." ON ".TB_STATUS.".statusID = ".TB_ORDER.".statusID");
		$i = 0;
		while($result = $db->fetch($query)) {
			$arr["data"][$i]["attributes"]["id"] = $result["statusID"];
			$arr["data"][$i]["attributes"]["name"] = $result["statusName"];
			
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
			$i++;
		}
	} else {
		$arr["status"] = "error";
		$arr["messages"] = "Some missing values, failed to add";
	}
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>