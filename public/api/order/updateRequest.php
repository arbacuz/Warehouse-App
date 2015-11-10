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

// IF ADD: 		$query = $db->add($table,$data)
// IF UPDATE:   $query = $db->update($table,$data,$where)
// IF DELETE:	$query = $db->delete($table,$where)
// IF QUERY: 	$query = $db->querydb("QUERY STATEMENT");
//
// SEE MORE ./includes/class_mysql.php

$arr = array();
$orderID = 2;
$orderDate= "2015-11-06 00:00:00";
$deliveredDate= "2016-01-06 00:00:00";
$companyID = 1;
$staffID = 1;
$branchID = 1;
$statusID = 1;
$toBranchID = 3;
$invoiceCode = "I17";

//UPDATE COMPANY TABLE 
$table = TB_ORDER;
$data = array("orderDate"=>$orderDate,
			"branchID"=>$branchID,
			"staffID"=>$staffID,
			"companyID"=>$companyID,
			"statusID"=>$statusID,
			"invoiceCode"=>$invoiceCode,
			"deliverdDate"=>$deliveredDate,
			"orderTypeID"=>1,
			"toBranchID"=>$toBranchID
			);
$where = "orderID=$orderID";
$query = $db->update($table,$data,$where);
if($query)
	{
		$arr["status"] = "success";
		$query = $db->querydb("SELECT * FROM ".TB_ORDER." INNER JOIN ".TB_ORDERITEM." ON ".TB_ORDER.".orderID = ".TB_ORDERITEM.".orderID");
		$i = 0;
		while($result = $db->fetch($query)) {
			$arr["status"] = "success";
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

			$arr["data"][$i]["relationships"]["orderType"]["_id"] = 2;
			$arr["data"][$i]["relationships"]["orderType"]["type"] = "Request";
			$arr["data"][$i]["relationships"]["orderItem"]["orderID"] = $result["orderID"];
			$arr["data"][$i]["relationships"]["orderItem"]["itemID"] = $result["itemID"];		
			$arr["data"][$i]["relationships"]["orderItem"]["quantity"] = $result["orderQuantity"];
			$i++;
		}
	}
else {
		$arr["status"] = "error";
		$arr["messages"] = "Failed to update";
	}

 echo json_encode($arr);
#-> Close database.
$db->closedb();

?>