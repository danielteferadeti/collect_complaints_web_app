<?php

    require "../private/autoload.php";
    $user_data = check_login($connection);

    //only set the variable name if they are logged in!
    $name = "";
    $url_address = "";
    if(isset($_SESSION['name']))
    {
        $name = $_SESSION['name'];
        $url_address = $_SESSION['url_address'];
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
            <?php endif; ?><br><br>

            <?php if($name != ""):?>
                <div>email: <?=$user_data->email?></div>
            <?php endif; ?>

            <div style="float:right">
                <a href="logout.php">Logout</a>
            </div> <br><br>

            <div style="float:left">
                <a href="feedback.php">Give Feedback</a>
            </div> <br><br>

            <div style="float:left">
                <a href="review_feedback.php">Review Feedbacks</a>
            </div> <br><br>

            <!-- To avoid javascript files to run in our system(unwanted) -->
            <!-- Use the following: if the data is prone to being tempered with
                when storing the file.

                This is HTML : Javascript won't run here!
            -->
            <?= htmlspecialchars($user_data->password)?>

        </div>

        This is the home page!
    </body>
</html>
</html>