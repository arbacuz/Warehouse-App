<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);

##### Dummy data #####
$item[0] = array(
	"id"=>1,
	"quantity"=>00 
	);
#var_dump($items);
$orderDate= "2015-11-06 00:00:00";
$deliveredDate= "2015-12-06 00:00:00";
$companyID = 1;
$staffID = 1;
$branchID = 1;
$statusID = 1;
$toBranchID = 2;
#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> GET,ADD,DEL,EDIT
//$db->......

#-> Preparing data.
$arr = array();
#Get next orderID
$sql = "SELECT orderID FROM ".TB_ORDER." 
ORDER BY orderID DESC LIMIT 1 "; //get next orderID
$query = $db->querydb($sql);
if($query){
	if($result = $db->fetch($query)){
		$orderID = $result["orderID"]+1;
		echo "$orderID";
	}
}
$i = 0;
while(isset($item[$i])) { 
	#Check if Item is available in stock
	$sql = "SELECT * FROM ".TB_ITEMBRANCH."
	WHERE itemID = ".$item[$i]["id"]."
	AND branchID = ".$branchID."  
	AND quantity >= ".$item[$i]["quantity"]." ";
	$query = $db->querydb($sql);
	if($result = $db->fetch($query)) {
		$arr["status"] = "success";
		echo json_encode($arr);
		#create the next invoice code
		$sql = "SELECT orderID FROM ".TB_ORDER." 
		ORDER BY orderID DESC LIMIT 1 "; //query last orderID 
		$query = $db->querydb($sql);
		if($query){
			if($result = $db->fetch($query)){
				$invoiceCode = "I".strval($result["orderID"]+1);
				echo "$invoiceCode";
				$arr["status"] = "successfully created next invoice code";
				echo json_encode($arr);
				#Add new order into order table 
				$data = array(
					"orderDate"=>$orderDate,
					"branchID"=>$branchID,
					"staffID"=>$staffID,
					"companyID"=>$companyID,
					"statusID"=>$statusID,
					"invoiceCode"=>$invoiceCode,
					"deliverdDate"=>$deliveredDate,
					"orderTypeID"=>1,
					"toBranchID"=>$toBranchID
					);
				$query = $db->add(TB_ORDER,$data);
				// var_dump((bool) "$query"); 
				if($query){
				$arr["status"] = "success";
				$arr["data"]["attributes"]["order_id"] = $orderID;
				$arr["data"]["attributes"]["orderDate"] =$orderDate;
				$arr["data"]["attributes"]["branch_id"] = $branchID;
				$arr["data"]["attributes"]["staff_id"] =$staffID;
				$arr["data"]["attributes"]["company_id"] = $companyID;
				$arr["data"]["attributes"]["status_id"] = $statusID;
				$arr["data"]["attributes"]["invoiceCode"] = $invoiceCode;
				$arr["data"]["attributes"]["deliveredDate"] = $deliveredDate;
				$arr["data"]["attributes"]["orderTypeID"] = 1;
				$arr["data"]["attributes"]["toBranchID"] = $toBranchID;

				$arr["data"][$i]["relationships"]["orderType"]["_id"] = 1;
				$arr["data"][$i]["relationships"]["orderType"]["type"] = "Order";
				$arr["data"][$i]["relationships"]["orderItem"]["orderID"] = $orderID;
				$arr["data"][$i]["relationships"]["orderItem"]["itemID"] = $item[$i]["id"];
				$arr["data"][$i]["relationships"]["orderItem"]["quantity"] = $item[$i]["quantity"];
				echo json_encode($arr);
				}
				else{
					$arr["status"] = "error";
					$arr["message"] = "failed to add new order";
					echo json_encode($arr);
				}
				#Add new order item into orderItem 
				$data = array(
					"orderID"=>$orderID,
					"itemID"=>1,
					"orderQuantity"=>$item[$i]["quantity"] 
					);
				$query = $db->add(TB_ORDERITEM,$data);
				if($query){
				$arr["status"] = "success";
				$arr["data"]["attributes"]["order_id"] = $orderID;
				$arr["data"]["attributes"]["itemID"] = $item[$i]["id"];
				$arr["data"]["attributes"]["quantity"] = $item[$i]["quantity"];

				$arr["data"][$i]["relationships"]["orderList"]["_id"] = $orderID;
				$arr["data"][$i]["relationships"]["item"]["_id"] = $item[$i]["id"];
				echo json_encode($arr);
				}
				else{
					$arr["status"] = "error";
					$arr["message"] = "failed to add new order item";
				}
			}
		}
	}
	else{
		$arr["status"] = "error";
		$arr["messages"] = "Item is not available in stock, failed to add order";
		echo json_encode($arr);
		 exit();
	}
	$i++; //next item 
}

#-> Close database.
$db->closedb();

?>