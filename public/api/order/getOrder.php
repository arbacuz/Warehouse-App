<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$orderID = $json->order->_id; 

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

$query = $db->querydb("SELECT * FROM ".TB_ORDER." o INNER JOIN ".TB_ORDERTYPE." ot ON ot.orderTypeID = o.orderTypeID INNER JOIN ".TB_STAFF." s ON s.staffID = o.staffID INNER JOIN ".TB_COMPANY." c ON c.companyID = o.companyID INNER JOIN ".TB_BRANCH." b ON b.branchID = o.branchID WHERE o.orderID = '$orderID' "); 
if($query)
	{
		$arr["status"] = "success";
		if($result = $db->fetch($query)){
			$arr["data"]["attributes"]["_id"] = $result["orderID"];
			$arr["data"]["attributes"]["date"] = date('d M Y',strtotime($result["orderDate"]));
			$arr["data"]["relationships"]["branch"]["_id"] = $result["branchID"];
			$arr["data"]["relationships"]["branch"]["name"] = $result["branchName"];
			$arr["data"]["relationships"]["branch"]["address"] = $result["branchAddress"];
			$arr["data"]["relationships"]["branch"]["telephone"] = $result["branchTel"];
			$arr["data"]["relationships"]["staff"]["_id"] = $result["staffID"];
			$arr["data"]["relationships"]["staff"]["name"] = $result["staffName"];
			$arr["data"]["relationships"]["staff"]["email"] = $result["staffEmail"];
			$arr["data"]["relationships"]["staff"]["telephone"] = $result["staffTel"];
			$arr["data"]["relationships"]["company"]["_id"] = $result["companyID"];
			$arr["data"]["relationships"]["company"]["name"] = $result["companyName"];
			$arr["data"]["relationships"]["status"]["attributes"]["_id"] = $result["statusID"];
			$arr["data"]["relationships"]["status"]["attributes"]["name"] = $result["statusName"];
			$arr["data"]["attributes"]["invoiceCode"] = $result["invoiceCode"];
			$arr["data"]["attributes"]["deliverdDate"] = $result["deliverdDate"];
			$arr["data"]["relationships"]["orderType"]["_id"] = $result["orderTypeID"];
			$arr["data"]["relationships"]["orderType"]["name"] = $result["orderTypeName"];
			$arr["data"]["attributes"]["toBranchID"] = $result["toBranchID"];
			$arr["data"]["update"] = false;
			$i = 0;
			$query = $db->querydb("SELECT * FROM ".TB_ORDER." o INNER JOIN ".TB_ORDERITEM." oi ON o.orderID = oi.orderID INNER JOIN ".TB_ITEMBRANCH." ib ON ib.itemID = oi.itemID INNER JOIN ".TB_ITEM." it ON it.itemID = ib.itemID INNER JOIN ".TB_ITEMTYPE." ity ON it.typeID = ity.typeID WHERE o.orderID = $orderID");
			while($result = $db->fetch($query)) {
				$arr["data"]["items"][$i]["attributes"]["_id"] = $result["itemID"];
				$arr["data"]["items"][$i]["attributes"]["name"] = $result["itemName"];
				$arr["data"]["items"][$i]["attributes"]["code"] = $result["itemCode"];
				$arr["data"]["items"][$i]["attributes"]["cost"] = $result["costPerUnit"];
				$arr["data"]["items"][$i]["attributes"]["quantity"] = $result["quantity"];
				$arr["data"]["items"][$i]["relationships"]["type"]["_id"] = $result["typeID"];
				$arr["data"]["items"][$i]["relationships"]["type"]["name"] = $result["typeName"];
				$i ++;
			}
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