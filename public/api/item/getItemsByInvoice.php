<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$invoiceCode = $json->invoice_code;
$brnachID = $json->branch->_id;
//$orderID = 1;

echo "test1";

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Query the data.
$query = $db->querydb("SELECT * FROM ".TB_ORDER." o INNER JOIN ".TB_STAFF." sf ON o.staffID = sf.staffID INNER JOIN "
					.TB_STATUS." st ON o.statusID=st.statusID INNER JOIN "
					.TB_BRANCH." b ON o.branchID=b.branchID INNER JOIN "
					.TB_ORDERTYPE."ot ON o.orderTypeID=ot.orderTypeID INNER JOIN "
					.TB_COMPANY." c ON o.companyID=c.companyID INNER JOIN "
					.TB_BRANCH." b ON o.toBranchID=b.branchID INNER JOIN "
					.TB_ORDERITEM." oi ON o.orderID=oi.orderID INNER JOIN "
					.TB_ITEM." i ON oi.itemID=i.itemID INNER JOIN "
					.TB_ITEMTYPE." it ON i.typeID=it.typeID WHERE o.invoiceCode = '$invoiceCode' AND o.branchID=$branchID ");

#-> Preparing return data.
$arr = array();
if($query){
	$i = 0;
	$arr["status"] = "success";
	if($result = $db->fetch($query)){
		$arr["data"][$i]["attributes"]["_id"] = $result["itemID"];
		$arr["data"][$i]["attributes"]["code"] = $result["itemCode"];
		$arr["data"][$i]["attributes"]["name"] = $result["itemName"];
		$arr["data"][$i]["attributes"]["cost"] = $result["costPerUnit"];
		$arr["data"][$i]["relationships"]["type"]["name"]=$result["typeName"];
		$arr["data"][$i]["relationships"]["type"]["_id"]=$result["typeID"];
		$arr["data"][$i]["attributes"]["quantity"] = $result["quantity"];
		$arr["data"][$i]["relationships"]["order"]["staffName"] = $result["staffName"];
		$arr["data"][$i]["relationships"]["order"]["statusName"] = $result["statusName"];
		$arr["data"][$i]["relationships"]["order"]["deliveredDate"] = $result["deliveredDate"];
		$arr["data"][$i]["relationships"]["order"]["branchName"] = $result["branchName"];
		$arr["data"][$i]["relationships"]["order"]["orderType"] = $result["orderType"];
		$arr["data"][$i]["relationships"]["order"]["companyName"] = $result["companyName"];
		$arr["data"][$i]["relationships"]["order"]["toBranchID"] = $result["branchName"];
	$i++;
	}
}else{
	$arr["status"] = "error";
	$arr["messages"] = "Error occured when you add the data to $table table.";
	echo json_encode($arr);
	exit();
}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>