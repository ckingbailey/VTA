<?PHP
    include('SQLFunctions.php');
    session_start();
    $table = 'system';

    echo '<br>display full contents of the _POST: <br>';
    var_dump($_POST);
    
    $link = f_sqlConnect();
    // $check = "SELECT * FROM $table WHERE SystemName = '".$_POST['Status']."'";
    $UserID = $_SESSION['userID'];
    $Username = $_SESSION['username'];
    
    $keys = implode(", ", (array_keys($_POST)));
    echo '<br>Parsed Key: ' .$keys;
    $values = implode("', '", (array_values($_POST)));
    echo '<br>Parsed Values: ' .$values;
    
    // if(!f_tableExists($link, $table, DB_NAME)) {
    //     die('<br>Destination table does not exist:'.$table);
    // }
    
    // $result = mysqli_query($link,$check);
    // $num_rows = mysqli_num_rows($result);

    // if ($num_rows > 0) {
    //   header("location: $duplicate?msg=1");
    // }
    // else {
    $sql = "INSERT INTO $table($keys, lastUpdated, updatedBy) VALUES ('$values', NOW(), '$UserID')";
    //echo '<br>sql: ' .$sql;
    //echo '<br>Num_rows: ' .$num_rows;
    
    if (!mysqli_query($link,$sql)) {
		echo '<br>Error: ' .mysqli_error($link);
		if(!empty($rejectredirecturl)) {
	    	//header("location: $rejectredirecturl?msg=1");
	    	echo $sql;
        }
    } else {
        header("Location: DisplaySystems.php");
        //echo "SQL: ".$sql;
        //echo "Success";
    }
    
	mysqli_close($link);
?>