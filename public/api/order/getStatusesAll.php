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

$query = $db->querydb("SELECT * FROM ".TB_STATUS." ");
if($query)
	{
		$arr["status"] = "success";
		$i = 0;
		while($result = $db->fetch($query)) {
			$arr["data"][$i]["attributes"]["_id"] = $result["statusID"];
			$arr["data"][$i]["attributes"]["name"] = $result["statusName"];
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