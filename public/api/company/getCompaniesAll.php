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

// Query the data.
$query = $db->querydb("SELECT * FROM ".TB_COMPANY." INNER JOIN ".TB_COMPANYTYPE." ON ".TB_COMPANY.".companyTypeID = ".TB_COMPANYTYPE.".companyTypeID");

#-> Preparing the data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$i = 0;
	while($result = $db->fetch($query)) {
		$arr["data"][$i]["attributes"]["_id"] = $result["companyID"];
		$arr["data"][$i]["attributes"]["name"] = $result["companyName"];
		$arr["data"][$i]["attributes"]["address"] = $result["companyAddress"];
		$arr["data"][$i]["attributes"]["telephone"] = $result["companyTel"];
		$arr["data"][$i]["update"] = false;
		$arr["data"][$i]["relationships"]["companyType"]["attributes"]["_id"] = $result["companyTypeID"];
		$arr["data"][$i]["relationships"]["companyType"]["attributes"]["name"] = $result["companyTypeName"];
		$i++;
	}
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Some missing values, failed to get the information";
}

#-> Return the data.
echo json_encode($arr,JSON_NUMERIC_CHECK);

#-> Close database.
$db->closedb();

?>