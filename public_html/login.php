<?php
$base = $_SERVER['DOCUMENT_ROOT'] . '/..';
require "$base/inc/session.php";
$title = PROJECT_NAME . ' - Login';
include('filestart.php');
?>
    <header class="container page-header masthead">
      <img class="masthead-logo" src="assets/img/brand_logo.jpg" alt="brand logo">
      <h1 class="page-title"><?php echo PROJECT_NAME ?></h1>
      <p><a href="" target="_blank" class="btn btn-primary btn-xs">Learn more &raquo;</a></p>
    </header>
    <main role="main" class="container main-content">
      <div class="container login-container">
        <?php
            if (isset($_SESSION['errorMsg'])) {
                echo "
                    <div class='thin-grey-border bg-yellow pad'>
                        <p class='mt-0 mb-0'>{$_SESSION['errorMsg']}</p>
                    </div>";
                unset($_SESSION['errorMsg']);
            }
        ?>
        <form action="loginSubmit.php" method="post">
          <div class="row">
            <div class="col-md-4 offset-md-4">
              <h2>Username</h2>
              <input type="text" name="username" value="" maxlength="20" class="login-field" />
              <h2>Password</h2>
              <input type="password"  name="password" value="" maxlength="20" class="login-field" />
              <input type="submit" name="submit" value="Login" class="btn btn-primary btn-lg login-btn"/>
            </div>
          </div>
        </form>
      </div>
    </main>
<?php include('fileend.php'); ?>
