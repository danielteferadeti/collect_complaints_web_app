<?php

    require "../private/autoload.php";
    $user_data = check_login($connection);

    //only set the variable name if they are logged in!
    $name = "";
    if(isset($_SESSION['name']))
    {
        $name = $_SESSION['name'];
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Home Page
        </title>
    </head>

    <body>
        <div id="header">
            <?php if($name != ""):?>
                <div>Hello <?=$_SESSION['name']?></div>
            <?php endif; ?>

            <div style="float:right">
                <a href="logout.php">Logout</a>
            </div>

            <!-- To avoid javascript files to run in our system(unwanted) -->
            <!-- Use the following: if the data is prone to being tempered with
                when storing the file.

                This is HTML ESCAPING
            -->
            <!--- <?= htmlspecialchars($user_data->name)?>  --->

        </div>

        This is the home page!
    </body>
</html>
</html>


