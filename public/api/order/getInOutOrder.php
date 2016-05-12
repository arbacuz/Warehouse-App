<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");
date_default_timezone_set('Asia/Bangkok');
#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$branchID = $json->branch->_id;


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);
#-> Query the data.

$queryREG = $db->querydb("SELECT DATE(r.registerDate) AS regDate, r.registerID FROM   ".TB_BRANCH." b
						INNER JOIN ".TB_REGISTER." r ON r.branchID=b.branchID 
						WHERE b.branchID=$branchID 
						ORDER BY r.registerDate DESC;");
$queryOrder = $db->querydb("SELECT DATE(o.orderDate) AS orderDate , o.orderID , s.statusName , o.deliverdDate ,o.invoiceCode  FROM ".TB_BRANCH." b  
						INNER JOIN ".TB_ORDER." o ON o.branchID=b.branchID 
						INNER JOIN ".TB_STATUS." s ON s.statusID=o.statusID
						WHERE b.branchID=$branchID
						ORDER BY o.orderDate DESC,s.statusID;");

$arr = array();
// var_dump($queryREG && $queryOrder);
if($queryREG && $queryOrder) 
	{
	################ Initialize date ###################
	$arr["status"] = "success";
	$i = 0;
	$getNextReg = 0;
	$getNextOrder = 0;
	################ Fetch first date of each transaction ###################
	$resultREG=$db->fetch($queryREG);
	if($resultREG["regDate"]!= NULL)
		{
		$strREG = str_split($resultREG["regDate"]);
		$regDate = $strREG[8].$strREG[9];
		}	
	$resultOrder=$db->fetch($queryOrder);
	if($resultOrder["orderDate"]!= NULL)
		{
		$strOrder = str_split($resultOrder["orderDate"]);
		$orderDate = $strOrder[8].$strOrder[9];
		}
	################ Loop all month transaction ###################
	while($resultREG["regDate"]!=NULL || $resultOrder["orderDate"]!=NULL)
		{
			// echo $i;
		if($regDate!=NULL && $orderDate!=NULL)
			{
			if($regDate < $orderDate)
				{
				################ Store data in array ###################
				$arr["data"][$i]["in"]["attributes"]["date"] = $resultREG["regDate"];
				$arr["data"][$i]["in"]["attributes"]["code"] = $resultREG["registerCode"];
				$arr["data"][$i]["out"]["attributes"]["date"] = "";
				$arr["data"][$i]["out"]["attributes"]["orderID"] = "";
				$arr["data"][$i]["out"]["attributes"]["status"] = "";
				$arr["data"][$i]["out"]["attributes"]["deliveredDate"] = "";
				$arr["data"][$i]["out"]["attributes"]["invoiceCode"] = "";
				################ set fetch next data ###################
				$getNextReg = 1;
				################ get next array ###################
				$i++;
				}
			else if($regDate == $orderDate)
				{
				################### 
				## Calculate REG ##
				###################
				################ Store data in array ###################
				$arr["data"][$i]["in"]["attributes"]["date"] = $resultREG["regDate"];
				$arr["data"][$i]["in"]["attributes"]["code"] = $resultREG["registerCode"];
				$arr["data"][$i]["out"]["attributes"]["date"] = "";
				$arr["data"][$i]["out"]["attributes"]["orderID"] = "";
				$arr["data"][$i]["out"]["attributes"]["status"] = "";
				$arr["data"][$i]["out"]["attributes"]["deliveredDate"] = "";
				$arr["data"][$i]["out"]["attributes"]["invoiceCode"] = "";
				################ set fetch next data ###################
				$getNextReg = 1;
				################ get next array ###################
				$i++;
				################### 
				## Calculate INV ##
				###################
				################ Store data in array ###################
				if($resultOrder["invoiceCode"]!=NULL)
					{
					$arr["data"][$i]["in"]["attributes"]["date"] = "";
					$arr["data"][$i]["in"]["attributes"]["code"] = "";
					$arr["data"][$i]["out"]["attributes"]["date"] = $resultOrder["orderDate"];
					$arr["data"][$i]["out"]["attributes"]["orderID"] = $resultOrder["orderID"];
					$arr["data"][$i]["out"]["attributes"]["status"] = $resultOrder["statusName"];
					$arr["data"][$i]["out"]["attributes"]["deliveredDate"] = $resultOrder["deliveredDate"];
					$arr["data"][$i]["out"]["attributes"]["invoiceCode"] = $resultOrder["invoiceCode"];
					}
				else
					{
					$arr["data"][$i]["in"]["attributes"]["date"] = "";
					$arr["data"][$i]["in"]["attributes"]["code"] = "";
					$arr["data"][$i]["out"]["attributes"]["date"] = $resultOrder["orderDate"];
					$arr["data"][$i]["out"]["attributes"]["orderID"] = $resultOrder["orderID"];
					$arr["data"][$i]["out"]["attributes"]["status"] = $resultOrder["statusName"];
					$arr["data"][$i]["out"]["attributes"]["deliveredDate"] = "";
					$arr["data"][$i]["out"]["attributes"]["invoiceCode"] = "";
					}
				
				################ set fetch next data ###################
				$getNextOrder = 1;
				################ get next array ###################
				$i++;
				}
			else if($regDate > $orderDate)
				{
				################### 
				## Calculate INV ##
				###################
				################ Store data in array ###################
				if($resultOrder["invoiceCode"]!=NULL)
					{
					$arr["data"][$i]["in"]["attributes"]["date"] = "";
					$arr["data"][$i]["in"]["attributes"]["code"] = "";
					$arr["data"][$i]["out"]["attributes"]["date"] = $resultOrder["orderDate"];
					$arr["data"][$i]["out"]["attributes"]["orderID"] = $resultOrder["orderID"];
					$arr["data"][$i]["out"]["attributes"]["status"] = $resultOrder["statusName"];
					$arr["data"][$i]["out"]["attributes"]["deliveredDate"] = $resultOrder["deliveredDate"];
					$arr["data"][$i]["out"]["attributes"]["invoiceCode"] = $resultOrder["invoiceCode"];
					}
				else
					{
					$arr["data"][$i]["in"]["attributes"]["date"] = "";
					$arr["data"][$i]["in"]["attributes"]["code"] = "";
					$arr["data"][$i]["out"]["attributes"]["date"] = $resultOrder["orderDate"];
					$arr["data"][$i]["out"]["attributes"]["orderID"] = $resultOrder["orderID"];
					$arr["data"][$i]["out"]["attributes"]["status"] = $resultOrder["statusName"];
					$arr["data"][$i]["out"]["attributes"]["deliveredDate"] = "";
					$arr["data"][$i]["out"]["attributes"]["invoiceCode"] = "";
					}
				################ set fetch next data ###################
				$getNextOrder = 1;
				################ get next array ###################
				$i++;
				}
			}
		else if($regDate==NULL && $orderDate!=NULL)
			{	
			################### 
			## Calculate INV ##
			###################
			################ Store data in array ###################
			if($resultOrder["invoiceCode"]!=NULL)
				{
				$arr["data"][$i]["in"]["attributes"]["date"] = "";
				$arr["data"][$i]["in"]["attributes"]["code"] = "";
				$arr["data"][$i]["out"]["attributes"]["date"] = $resultOrder["orderDate"];
				$arr["data"][$i]["out"]["attributes"]["orderID"] = $resultOrder["orderID"];
				$arr["data"][$i]["out"]["attributes"]["status"] = $resultOrder["statusName"];
				$arr["data"][$i]["out"]["attributes"]["deliveredDate"] = $resultOrder["deliveredDate"];
				$arr["data"][$i]["out"]["attributes"]["invoiceCode"] = $resultOrder["invoiceCode"];
				}
			else
				{
				$arr["data"][$i]["in"]["attributes"]["date"] = "";
				$arr["data"][$i]["in"]["attributes"]["code"] = "";
				$arr["data"][$i]["out"]["attributes"]["date"] = $resultOrder["orderDate"];
				$arr["data"][$i]["out"]["attributes"]["orderID"] = $resultOrder["orderID"];
				$arr["data"][$i]["out"]["attributes"]["status"] = $resultOrder["statusName"];
				$arr["data"][$i]["out"]["attributes"]["deliveredDate"] = "";
				$arr["data"][$i]["out"]["attributes"]["invoiceCode"] = "";
				}
			################ set fetch next data ###################
			$getNextOrder = 1;
			################ get next array ###################
			$i++;
			}
		else if($regDate!=NULL && $orderDate==NULL)
			{
			################### 
			## Calculate REG ##
			###################
			################ Store data in array ###################
				$arr["data"][$i]["in"]["attributes"]["date"] = $resultREG["regDate"];
				$arr["data"][$i]["in"]["attributes"]["code"] = $resultREG["registerCode"];
				$arr["data"][$i]["out"]["attributes"]["date"] = "";
				$arr["data"][$i]["out"]["attributes"]["orderID"] = "";
				$arr["data"][$i]["out"]["attributes"]["status"] = "";
				$arr["data"][$i]["out"]["attributes"]["deliveredDate"] = "";
				$arr["data"][$i]["out"]["attributes"]["invoiceCode"] = "";
			################ set fetch next data ###################
			$getNextReg = 1;
			################ get next array ###################
			$i++;
			}

		################ Fetch Next data ###################
		if($getNextReg == 1)
			{
			$resultREG=$db->fetch($queryREG);
			if($resultREG["regDate"]!= NULL)
				{
				$strREG = str_split($resultREG["regDate"]);
				$regDate = $strREG[8].$strREG[9];
				}
			$getNextReg = 0;	
			}
		if($getNextOrder == 1)
			{
			$resultOrder=$db->fetch($queryINV);
			if($resultOrder["orderDate"]!= NULL)
				{
				$strOrder = str_split($resultOrder["orderDate"]);
				$orderDate = $strOrder[8].$strOrder[9];
				}
			$getNextOrder = 0;	
			}
		}
	}
#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>