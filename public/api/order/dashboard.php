<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");
date_default_timezone_set('Asia/Bangkok');
#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$branchID = $json->user->relationships->branch->_id;
$isDirector = $json->user->relationships->position->_id;


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);
#-> Query the data.
if($isDirector == 1){
	$query1 = $db->querydb("SELECT Count(orderID) as delivered, month(orderDate) as Month FROM ".TB_ORDER." WHERE statusID = 1 GROUP BY month(orderDate);");
	$query2 = $db->querydb("SELECT Count(orderID) as pending, month(orderDate) as Month FROM ".TB_ORDER." WHERE statusID = 3 GROUP BY month(orderDate);");
	$query3 = $db->querydb("SELECT Count(orderID) as canceled, month(orderDate) as Month FROM ".TB_ORDER." WHERE statusID = 2 GROUP BY month(orderDate);");
} else {
	$query1 = $db->querydb("SELECT Count(orderID) as delivered, month(orderDate) as Month FROM ".TB_ORDER." WHERE statusID = 1 AND branchID = $branchID GROUP BY month(orderDate);");
	$query2 = $db->querydb("SELECT Count(orderID) as pending, month(orderDate) as Month FROM ".TB_ORDER." WHERE statusID = 3 AND branchID = $branchID GROUP BY month(orderDate);");
	$query3 = $db->querydb("SELECT Count(orderID) as canceled, month(orderDate) as Month FROM ".TB_ORDER." WHERE statusID = 2 AND branchID = $branchID GROUP BY month(orderDate);");
}
$arr = array();
if($query1 && $query2 && $query3)
	{
	$arr["status"] = "success";
	$arr["messages"] = "Yes!";
	$result1 = $db->fetch($query1);

	$result2=$db->fetch($query2);
	$result3=$db->fetch($query3);
	for($monthNo = 1; $monthNo < 13; $monthNo ++)
		{
		if($result1["Month"]==$monthNo)
			{
			$arr["data"]["delivered"][$monthNo-1] = $result1["delivered"];
			$result1 = $db->fetch($query1);	
			}
		else
			{
			$arr["data"]["delivered"][$monthNo-1] = 0;		
			}
		}
		for($monthNo = 1; $monthNo < 13; $monthNo ++)
		{
		if($result2["Month"]==$monthNo)
			{
			$arr["data"]["pending"][$monthNo-1] = $result2["pending"];
			$result2=$db->fetch($query2);		
			}
		else
			{
			$arr["data"]["pending"][$monthNo-1] = 0;		
			}
		}
		for($monthNo = 1; $monthNo < 13; $monthNo ++)
		{
		if($result3["Month"]==$monthNo)
			{
			$arr["data"]["canceled"][$monthNo-1] = $result3["canceled"];
			$result3=$db->fetch($query3);		
			}
		else
			{
			$arr["data"]["canceled"][$monthNo-1] = 0;			
			}
		}
	}
#-> Return json data.
echo json_encode($arr,JSON_NUMERIC_CHECK);

#-> Close database.
$db->closedb();

?>