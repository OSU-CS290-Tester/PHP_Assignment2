<?php
ini_set('display_errors','On');
include 'storedInfo.php';

$conn = new mysqli("oniddb.cws.oregonstate.edu","whitegr-db",$mypass,"whitegr-db");

// Check connection
if (mysqli_connect_errno())
 {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit(0);
 } 

if ($_GET["request"] == "addVideo") {

	$name = $_GET["name"];
	$category = $_GET["category"];
	$length = $_GET["length"];
	if (empty($length)) {
		$length = "0";
	}

	if ($result = mysqli_query($conn,"INSERT INTO aVideoStore (name,category,length,rented) 
	VALUES ('$name','$category',$length,0)")){
		echo json_encode(array('Response'=>'addVideo','isSuccess' =>'True','name' => $name,'category' => $category,'length' => $length));
	} else {
		echo json_encode(array('Response'=>'addVideo','isSuccess' => 'False','error' => mysqli_error($conn)));
	}
	mysqli_close($conn);
}

if ($_GET["request"] == "deleteAll") {
	//delete from tableName;
	mysqli_query($conn,"delete FROM aVideoStore");
	echo json_encode(array('Response'=>'deleteAll','values' => "All Videos Removed"));
}

if ($_GET["request"] == "rent") {
	//delete from tableName;
	mysqli_query($conn,"delete FROM aVideoStore");
	echo json_encode(array('Response'=>'deleteAll','values' => "All Videos Removed"));
}

if ($_GET["request"] == "getVideoCatalog") {
	$filter = $_GET["filter"];
	$query = "";
	if ($filter == 'Any') {
		$query = "Select * FROM aVideoStore";
	} else {
		$query = "Select * FROM aVideoStore WHERE category = '$filter'";
	}
	
	if ($result = mysqli_query($conn,$query)){
		$return_vidoes = array();
		$i = 0;
		while ($obj = $result->fetch_object()) {
			$return_vidoes[$i]['name'] = $obj->name;
			$return_vidoes[$i]['category'] = $obj->category;
			$return_vidoes[$i]['length'] = $obj->length;
			$return_vidoes[$i]['rented'] = $obj->rented;
			$i = $i + 1;
		}
		echo json_encode(array('Response'=>'getVideoCatalog','values' => $return_vidoes));
		$result->close();
	}
	mysqli_close($conn);
}

if ($_GET["request"] == "setIn") {

	$movie = $_GET["movie"];
	mysqli_query($conn,"UPDATE aVideoStore SET rented=0 WHERE name='$movie'");
	echo json_encode(array('Response'=>'rentStatus','movie' => $_GET["movie"],'status' => 'Check Out'));
						
}

if ($_GET["request"] == "setOut") {
		$movie = $_GET["movie"];
		mysqli_query($conn,"UPDATE aVideoStore SET rented=1 WHERE name='$movie'");
		echo json_encode(array('Response'=>'rentStatus','movie' => $_GET["movie"],'status' => 'Checked Out'));
		
}

if ($_GET["request"] == "deleteVideo") {
		$movie = $_GET["movie"];
		mysqli_query($conn,"DELETE FROM aVideoStore WHERE name = '$movie'");
		echo json_encode(array('Response'=>'deleteVideo','movie' => $_GET["movie"]));
}

if ($_GET["request"] == "getCategories") {
		$result = mysqli_query($conn,"SELECT DISTINCT category FROM aVideoStore");
		$return_categories = array();
		$i = 0;
		while ($obj = $result->fetch_object()) {
			$return_categories[$i]['category'] = $obj->category;
			$i = $i + 1;
		}
		echo json_encode(array('Response'=>'getCategories','values' => $return_categories));
}
 
 ?> 