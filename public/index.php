<?php

    require "../private/autoload.php";
    $user_data = check_login($connection);

    //only set the variable name if they are logged in!
    $name = "";
    $url_address = "";
    if($user_data->is_locked==1)
    {
        header("Location: logout.php");
        die;
    }
    if(isset($_SESSION['name']))
    {
        $name = $_SESSION['name'];
        $url_address = $_SESSION['url_address'];
    }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>
        Home Page
    </title>
</head>

<body>
    <div id="main-div">
        <div id="title">
            Home Page
        </div>
        <br>
        <?php if($name != ""):?>
            <div id="header-1" >Hello <?=$_SESSION['name']?></div>
        <?php endif; ?><br>

        <?php if($name != ""):?>
            <div id="header-1">email: <?=$user_data->email?></div>
        <?php endif; ?>

        <br>

        <div id="index-navigation">
            <div id="a-tag">
                <a style="text-decoration: none; color: #1d5548;" href="logout.php">Logout</a>
            </div> 
            
            <div id="a-tag">
                <a style="text-decoration: none; color: #1d5548;" href="feedback.php">Give Feedback</a>
            </div> 
            
            <div id="a-tag" >
                <a style="text-decoration: none; color: #1d5548;" href="review_feedback.php">Review Feedbacks</a>
            </div> 
        </div>

    </div>

    
</body>
</html>
</html>