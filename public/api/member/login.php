<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);

$email = $json->email;
$password = $json->password;
$email = stripslashes($email);
$password = stripslashes($password);
$email = mysql_real_escape_string($email);
$password = mysql_real_escape_string($password);

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

$query = $db->querydb("SELECT * FROM ".TB_STAFF." INNER JOIN ".TB_BRANCH." ON ".TB_STAFF.".branchID = ".TB_BRANCH.".branchID INNER JOIN ".TB_POSITION." ON ".TB_STAFF.".positionID = ".TB_POSITION.".positionID WHERE email='$email' AND password='$password'");

$arr = array();
#-> Preparing return data.
if($query) {
	$result = $db->fetch($query);
	if($result["staffID"]) {
		$arr["status"] = "success";
		$arr["data"]["attributes"]["_id"]=$result["staffID"];
		$arr["data"]["attributes"]["name"]=$result["staffName"];
		$arr["data"]["attributes"]["email"]=$result["email"];

		$arr["data"]["relationships"]["position"]["_id"] = $result["positionID"];
		$arr["data"]["relationships"]["position"]["name"]=$result["positionName"];

		$arr["data"]["relationships"]["branch"]["_id"] = $result["branchID"];
		$arr["data"]["relationships"]["branch"]["name"]=$result["branchName"];
		$arr["data"]["relationships"]["branch"]["address"]=$result["branchAddress"];
	} else {
		$arr["status"] = "error";
		if(isset($username)) $messages = "Please enter your username.";
		else if(isset($password)) $messages = "Please enter your password.";
		else $messages = "Some error occured, Please check your data.";
		$arr["messages"] = $messages;
	}
} else {
	$arr["status"] = "error";
	if(isset($email)) $messages = "Please enter your username.";
	else if(isset($password)) $messages = "Please enter your password.";
	else $messages = "Some error occured, Please check your data.";
	$arr["messages"] = $messages;
}

#-> Return json data.
echo json_encode($arr);
// echo $query;


#-> Close database.
$db->closedb();

?>