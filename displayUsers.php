<?php
include('session.php');
include('SQLFunctions.php');
$table = 'users_enc';
$title = "SVBX - Display Users";
include('filestart.php');
$adminID = $_SESSION['userID'];
$Role = $_SESSION['role'];

$link = f_sqlConnect();
    if(!f_tableExists($link, $table, DB_NAME)) {
        die('<br>Destination table does not exist: '.$table);
    }

    $sql = "SELECT UserID, Username, firstname, lastname, Role, LastUpdated, updated_By, DateAdded, Created_by, Email, Company, LastLogin FROM $table ORDER BY lastname";
    $sql1 = "SELECT COUNT(*) FROM $table";

    if($result = mysqli_query($link,$sql1)) {
        echo "
                <header class='container page-header'>
                    <h1 class='page-title'>Users</h1>
                    <h6 class='text-center'>Users in Database: ";
            while ($row = mysqli_fetch_array($result)) {
                    echo $row[0];
            }
            echo "
                    </h6>
                </header>";
}
    if($result = mysqli_query($link,$sql)) {
        echo"
            <div class='main-content'>
                <div class='container text-right item-margin-bottom'>
                    <a href='/newUser.php' class='btn btn-primary'>Add new user</a>
                </div>
                <table class='table table-responsive'>
                    <tr class='usertr'>
                        <th class='userth'>First name</th>
                        <th class='userth'>Last name</th>
                        <th class='userth'>Email</th>";
                        if(!isset($_SESSION['userID'])) {
                        echo "</tr>";
                        } else {
                        echo "
                            <th class='userth'>User ID</th>
                            <th class='userth'>Username</th>
                            <th class='userth'>Role</th>
                            <th class='userth'>Company</th>";
                            }
                        if ($Role >= 30) {
                        echo "
                            <th class='userth'>Last Login</th>
                            <th class='userth'>Date Created</th>
                            <th class='userth'>Created By</th></th>
                            <th class='userth'>Last Updated</th>
                            <th class='userth'>Updated By</th>
                            <th class='userth'>Edit</th>";
                            if ($Role >= 40) {
                                echo "
                            <th class='userth'>Delete</th>";
                            }
                            echo "
                            </tr>";
                        }
            while ($row = mysqli_fetch_array($result)) {
        echo"       <tr class='usertr'>

                        <td class='usertd'>{$row[2]}</td>
                        <td class='usertd'>{$row[3]}</td>
                        <td class='usertd'><a href='mailto:{$row[9]}' style='color:black'>{$row[9]}</a></td>";
                        if(!isset($_SESSION['userID']))
                        {
                            echo "</tr>";
                        } else {
                        echo "
                        <td style='text-align:center' class='usertd'>{$row[0]}</td>
                        <td class='usertd'>{$row[1]}</td>
                        <td style='text-align:center' class='usertd'>{$row[4]}</td>
                        <td class='usertd'>{$row[10]}</td>";
                        }
                        if ($Role >= 30)
                        {
                            if($row[11] == '0000-00-00 00:00:00') {
                                echo "
                                <td class='usertd'>Never</td>";
                            } else {
                                echo "
                                <td class='usertd'>{$row[11]}</td>";
                            }
                    echo "
                        <td class='usertd'>{$row[7]}</td>
                        <td class='usertd'>{$row[8]}</td>
                        <td class='usertd'>{$row[5]}</td>
                        <td class='usertd'>{$row[6]}</td>
                        <td class='usertd'>
                            <a class='btn btn-outline' href='/UpdateUser.php?userID={$row[0]}'>
                                <i class='typcn typcn-edit'></i>
                            </a>
                        </td>";
                            if($Role >= 40) {
                                echo "
                        <td class='usertd'>
                            <form action='DeleteUser.php' method='POST' onsubmit='' onsubmit='' onclick='return confirm(`do you want to delete user {$row[2]} {$row[3]}`)'>
                                <button type='submit' name='q' value='{$row[0]}' class='btn btn-outline'>
                                    <i class='typcn typcn-times'></i>
                                </button>
                            </form>
                        </td>";
                    }
                    echo "</tr>";
                }
            }
            echo "</table></div>";
    }
    mysqli_free_result($result);

    if(mysqli_error($link)) {
        echo '<br>Error: ' .mysqli_error($link);
    } else //echo '<br>Success';

    mysqli_close($link);

?>
<?php include 'fileend.php';?>
</Body>
</HTML>
