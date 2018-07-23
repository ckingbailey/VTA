<?php
require_once 'sql_functions/sqlFunctions.php';
session_start();


$title = "SVBX - Help";
include('filestart.php');
?>

<main role="main">

        <header class="container page-header">
            <h1 class="display-3">Help</h1>
            <p>If you are having difficulties with the database please contact one of the people below who have administrator rights,<br>otherwise email <a href="mailto:robert.burns@vta.org?subject=SVBX Deficiency Database Issue"  style='color:black'>Robert Burns</a> for assistance.</p>
        </header>
        <div class="container main-content">        
<?php
    $link = f_sqlConnect();
    $table = 'users_enc';
    
    // if(!f_tableExists($link, $table, DB_Name)) {
    //     die('<br>Destination table does not exist: '.$table);
    // }
    
    $sql = "SELECT firstname, lastname, Email FROM $table WHERE role > 30 ORDER BY lastname";
   
    
    if($result = $link->query($sql)) {
        echo"   <table class='table svbx-table'>
                    <tr class='svbx-tr'>
                        <th class='svbx-th'>First name</th>
                        <th class='svbx-th'>Last name</th>
                        <th class='svbx-th'>Email</th>
                    </tr>"; 
                    while ($row = $result->fetch_array()) {
                    echo "       
                    <tr class='svbx-tr'>
                        <td class='svbx-td'>{$row[0]}</td>
                        <td class='svbx-td'>{$row[1]}</td>
                        <td class='svbx-td'><a href='mailto:{$row[2]}' style='color:black'>{$row[2]}</a></td>
                    </tr>";
                    }
    }
            echo "</table><br></div>";
    $result->close();
    
    if($link->error) {
        echo '<br>Error: ' . $link->error;
    } else //echo '<br>Success';
    
    $link->close();
    include('fileend.php') 
?>