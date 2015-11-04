<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);

$companyName = "aaa";
$companyType = 1;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

//ADD COMPANY TABLE 
if((strlen($companyName) == 0)||($companyType==null)) {
	$query = false;
} else {
	$table = TB_COMPANY;
	$data = array("companyName"=>$companyName,
			"companyAddress"=>"123 Casandra Rd. Spain 90588",
			"companyTel"=>"022-688-51",
			"companyTypeID"=>$companyType
			);
	$query = $db->add($table,$data);
}

#-> Prepaing data for return.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$query = $db->querydb("SELECT * FROM ".TB_COMPANY." INNER JOIN ".TB_COMPANYTYPE." ON ".TB_COMPANY.".companyTypeID = ".TB_COMPANYTYPE.".companyTypeID");
	$i = 0;
	while($result = $db->fetch($query)) {
		$arr["data"][$i]["attributes"]["id"] = $result["companyID"];
		$arr["data"][$i]["attributes"]["name"] = $result["companyName"];
		$arr["data"][$i]["attributes"]["address"] = $result["companyAddress"];
		$arr["data"][$i]["attributes"]["telephone"] = $result["companyTel"];
		
		$arr["data"][$i]["relationships"]["companyType"]["name"] = $result["companyTypeName"];
		$arr["data"][$i]["relationships"]["companyType"]["_id"] = $result["companyTypeID"];
		$i++;
	}
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Some missing values, failed to add";
}

#-> Return the data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>