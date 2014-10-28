<?php
include_once '../lib/DbManager.php';


$action = 'modules.php';
if (isset($_SESSION['ORG_SCRIPT_NAME']))
    $action = $_SESSION['ORG_SCRIPT_NAME'];

$mess = null;
if (isset($_REQUEST['login_mess']))
    $mess = $_REQUEST['login_mess'];

$dbs = array();
$i = 1;
while (defined("DBNAME_$i")) {
    $dbs[] = array(constant("DBNAME_$i"));
    $i++;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Login to CMS</title>
        <link rel="stylesheet" href="../common/style.css">
        <!-- Optimize for mobile devices -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>  
    </head>
    <body>

        <!-- HEADER -->
        <div id="header">

            <div class="page-full-width cf">
                <div id="login-intro" class="fl">
                    <h1>Login to</h1>
                    <h5>Enter your credentials below</h5>	
                </div> <!-- login-intro -->

                <!-- Change this image to your own company's logo -->
                <!-- The logo will automatically be resized to 39px height. -->
                <a href="#" id="company-branding" class="fr"><img src="../public/images/CityBank.png" alt="City Bank" /></a>
            </div> <!-- end full-width -->	
        </div> <!-- end header -->



        <!-- MAIN CONTENT -->
        <div id="content">
            <form action="<?php echo $action ?>" method="POST" id="login-form">
                <fieldset>
                    <p>
                        <label for="login-username">user name</label>
                        <input type="text" id="login-username" name='username' class="round full-width-input" autofocus placeholder="User Name" />
                    </p>

                    <p>
                        <label for="login-password">password</label>
                        <input type="password" id="login-password" name='pwd' class="round full-width-input" placeholder="Password"/>
                    </p>


                    <button type='submit' name='login' class="button round blue image-right ic-right-arrow">LOG IN</button>
                </fieldset>
                <br/><div class="information-box round">Just click on the "LOG IN" button to continue</div>
            </form>

        </div> <!-- end content -->

        <!-- FOOTER -->
        <div id="footer">
            <p>&copy; Copyright 2009-2013 <a href="http://www.ics-ss.com/" target="_blank">ICS System Solution</a>. All rights reserved.</p>
        </div> <!-- end footer -->
    </body>
</html>



