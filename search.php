<?php
//fetch.php
include("functions.php");
$request = mysqli_real_escape_string($link, $_POST["query"]);
$query = "
 SELECT * FROM users WHERE email LIKE '%".$request."%'
";

$result = mysqli_query($link, $query);

$data = array();

if(mysqli_num_rows($result) > 0)
{
 while($row = mysqli_fetch_assoc($result))
 {
  $data[] = $row["email"];
 }
 echo json_encode($data);
}

?>
