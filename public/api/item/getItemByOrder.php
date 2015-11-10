<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$orderID = $json->order->_id;
$orderID = 1;


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Query the data.
$query = $db->querydb("SELECT * FROM ".TB_ORDER. " WHERE orderID =".$orderID);

#-> Preparing return data.
$arr = array();
if($query){
	$i = 0;
	if($orderData = $db->fetch($query)){
		#Query item name from itemID
		$sql = "SELECT * FROM ".TB_ORDERITEM." WHERE orderID =".$orderID.";";
		$subquery = $db->querydb($sql);
		if($subquery){
			while($itemOrderData = $db->fetch($subquery)){
				$queryItem = $db->querydb("SELECT * FROM ".TB_ITEM." WHERE itemID=".$itemOrderData["itemID"]);
				if($queryItem){
					if($itemData = $db->fetch($queryItem)){
						$arr["data"][$i]["attributes"]["itemID"] = $itemData["itemID"];
						$arr["data"][$i]["attributes"]["itemCode"] = $itemData["itemCode"];
						$arr["data"][$i]["attributes"]["itemName"] = $itemData["itemName"];
						$arr["data"][$i]["attributes"]["costPerUnit"] = $itemData["costPerUnit"];
						$sql = "SELECT typeName FROM ".TB_ITEMTYPE." WHERE typeID =".$itemData["typeID"].";";
						$checkQuery = $db->querydb($sql);
						if($checkQuery){
							if($itemTypeData = $db->fetch($checkQuery)){
								$arr["data"][$i]["attributes"]["type"]=$itemTypeData["typeName"];
							}
						}else{
							$arr["status"] = "error";
							$arr["messages"] = "Fail query item type";
							echo json_encode($arr);
							exit();
						}
					}
				}
				#Query staff name from staffID
				$sql = "SELECT * FROM ".TB_STAFF." WHERE staffID =".$orderData["staffID"].";";
				$checkQuery = $db->querydb($sql);
				if($checkQuery){
					if($staffData = $db->fetch($checkQuery)){
						$arr["data"][$i]["attributes"]["staffName"] = $staffData["staffName"];
					}
				}else{
					$arr["status"] = "error";
					$arr["messages"] = "Fail to query staff";
					echo json_encode($arr);
					exit();
				}
				#get status name
				$sql = "SELECT * FROM ".TB_STATUS." WHERE statusID =".$orderData["statusID"].";";
				$checkQuery = $db->querydb($sql);
				if($checkQuery){
					if($statusData = $db->fetch($checkQuery)){
						$arr["data"][$i]["attributes"]["statusName"] = $statusData["statusName"];
					}
				}else{
					$arr["status"] = "error";
					$arr["messages"] = "Fail to query status";
					echo json_encode($arr);
					exit();
				}
				#get deliveredDate if it deliver
				if($result["statusID"] == 1){
					$arr["data"][$i]["attributes"]["deliveredDate"] = $orderData["deliveredDate"];
				}
				#get branch name
				$sql = "SELECT * FROM ".TB_BRANCH." WHERE branchID =".$orderData["branchID"].";";
				$checkQuery = $db->querydb($sql);
				if($checkQuery){
					if($branchData = $db->fetch($checkQuery)){
						$arr["data"][$i]["attributes"]["branchName"] = $branchData["branchName"];
					}
				}else{
					$arr["status"] = "error";
					$arr["messages"] = "Fail to query branch";
					echo json_encode($arr);
					exit();
				}
				#get order type
				$sql = "SELECT * FROM ".TB_ORDERTYPE." WHERE orderTypeID =".$orderData["orderTypeID"].";";
				$checkQuery = $db->querydb($sql);
				if($checkQuery){
					if($orderTypeData = $db->fetch($checkQuery)){
						$arr["data"][$i]["attributes"]["orderType"] = $orderTypeData["orderType"];
					}
				}else{
					$arr["status"] = "error";
					$arr["messages"] = "Fail to query order type";
					echo json_encode($arr);
					exit();
				}
				if($orderTypeData["orderTypeID"]== 1){
					#Query company
					$sql = "SELECT * FROM ".TB_COMPANY." WHERE companyID =".$orderData["companyID"].";";
					$checkQuery = $db->querydb($sql);
					if($checkQuery){
						if($companyData = $db->fetch($checkQuery)){
							$arr["data"][$i]["attributes"]["companyName"] = $companyData["companyName"];
						}
					}else{
						$arr["status"] = "error";
						$arr["messages"] = "Fail to query company";
						echo json_encode($arr);
						exit();
					}
				}else{
					#get toBranchID
					$sql = "SELECT * FROM ".TB_BRANCH." WHERE branchID =".$orderData["toBranchID"].";";
					$checkQuery = $db->querydb($sql);
					if($checkQuery){
						if($toBranchData = $db->fetch($checkQuery)){
							$arr["data"][$i]["attributes"]["toBranchID"] = $toBranchData["branchName"];
						}
					}else{
						$arr["status"] = "error";
						$arr["messages"] = "Fail to query toBranchID";
						echo json_encode($arr);
						exit();
					}
				}
			$arr["data"][$i]["attributes"]["quantity"] = $itemOrderData["orderQuantity"];
			$i++;
			}
		}else{
			$arr["status"] = "error";
			$arr["messages"] = "Fail to query orderID";
			echo json_encode($arr);
			exit();
		}
	}
}else{
	$arr["status"] = "error";
	$arr["messages"] = "Error occure when you add the data to .$table. table.";
	echo json_encode($arr);
	exit();
}
#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>