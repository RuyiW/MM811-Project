<?php session_start(); 
  $host="localhost";
  $db_user="root";
  $db_pass="";
  $db_name="db_relations_short";
  // Create connection
  $conn = mysqli_connect($host, $db_user, $db_pass, $db_name);
  // Check connection
  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }
?>

<?php

// $get_data_sql1 = "SELECT bylaw_neighbourhood,complaint,bylaw_status FROM Bylaw";
// $result1 = $conn->query($get_data_sql1);
// $rows1 = array();
// if ($result1->num_rows > 0) {
//   $index = 1;
//   while($rr = $result1->fetch_assoc()) {
//       if(($rr["complaint"] != null) && ($rr["bylaw_status"] == 'Under Investigation')){
//          $rows1[] = $rr["bylaw_neighbourhood"];
//       }
//     }
// }
$index = 0;
$get_data_sql1 = "SELECT DISTINCT bylaw_neighbourhood,bylaw_status FROM Bylaw";
$result1 = $conn->query($get_data_sql1);
$rows1 = array();
if ($result1->num_rows > 0) {
  $index = 0;
  while($rr = $result1->fetch_assoc()) {
    $CompareReq_Status = strcasecmp($rr["bylaw_status"]  , 'Under Investigation');
      if(($CompareReq_Status == 0)){
         $rows1[$index] = $rr["bylaw_neighbourhood"];
         $index++;
      }
    }
}
//print $index;
// $get_data_sql = "SELECT bylaw_status,complaint,bylaw_count FROM Bylaw";
// $result = $conn->query($get_data_sql);
// $rows = array();
// if ($result->num_rows > 0) {
//   $index = 1;
//   while($r = $result->fetch_assoc()) {
//       if(($r["complaint"] != null) && ($r["bylaw_status"] == 'Under Investigation')){
//          $rows[] = (int)($r["bylaw_count"]);
//       }
//     }
// }

$get_data_sql = "SELECT bylaw_neighbourhood,bylaw_status,complaint,bylaw_count FROM Bylaw";
$result = $conn->query($get_data_sql);
$rows = array();
if ($result->num_rows > 0) {
  $i = 0;
  while($r = $result->fetch_assoc()) {
    $CompareReq_Status = strcasecmp($r["bylaw_status"]  , 'Under Investigation');
      if(($r["complaint"] != null) && ($CompareReq_Status == 0)){
         $rowsneighbour[$i] = ($r["bylaw_neighbourhood"]);
         $rowsbylawcount[$i] = ($r["bylaw_count"]);
         $i++;
      }
    }
}

for($ii = 0; $ii < $index; $ii++){
    $neighbour = $rows1[$ii];
    $count = 0;
   for($jj = 0; $jj < $i; $jj++){
    $neighbour1 = $rowsneighbour[$jj];
    $compare_bylawstatus = strcasecmp($neighbour, $neighbour1);
    if($compare_bylawstatus == 0)
        $count +=  $rowsbylawcount[$jj];
        $rows[$ii] = (int)($count);
   }
}
$result2 = array();
array_push($result2,$rows1);
array_push($result2,$rows);

print json_encode($result2);

?>