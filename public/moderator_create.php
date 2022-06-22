<?php

    require "../private/autoload.php";
    $Error = "";
    $email = "";
    $name  = "";


    if(isset($_SESSION['admin']))
     {
        header("Location: moderator.php");
        die;
    }

    if(check_admin($connection)){
        header("Location: moderator_login.php");
        die;
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['token']) && $_SESSION['token'] == $_POST['token'])
    {
        $email = $_POST['email'];
        //White listing with regular expression
        if (!preg_match("/^[\w\-]+@[\w\-]+.[\w\-]+$/", $email))
        {
            $Error = "Please enter a valid Email!";
        }
        //Sanitization for Password
        $password = esc($_POST['password']);

        //Salting and Hashing the password
        $salt = get_random_string(60);
        $salted_pass = $salt.$password;

        $hashed_pass = hash('sha512', $salted_pass);

        if($Error == "")
        {
            // ----- by using a prepared statement ------
            // to use this parametrized part uncomment the PDO connection part 
            // in database connection php page

            $arr['password'] = $hashed_pass;
            $arr['email'] = $email;
            $arr['salt'] = $salt;

            $query = "insert into moderator (email, password, salt) values (:email,:password, :salt)";
            $stm = $connection->prepare($query);
            $stm->execute($arr);

            header("Location: moderator_login.php");
            die;
        }
    }
    //This generates a random token to be checked with the one in the form!
    $_SESSION['token'] = get_random_string(60);
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css">
        <title>
            Create Admin Page
        </title>
    </head>

    <body style="font-family: verdana">
    <div id="main-div">
        <form id="form" method="post">
            <div><?php
                if(isset($Error) && $Error !="")
                {
                    echo $Error;
                }
            ?> </div>
            <div id="title">Welcome to the Compliant Recorder Platform. Create Admin of your System.</div>
            <input id="textbox" type="email" name="email" placeholder="Email" value="<?=$email?>" required><br><br>
            <input id="textbox" type="password" name="password" placeholder="Password" required><br><br>

            <!-- FOR CSRF TOKEN -->
            <input type="hidden" name="token" value="<?=$_SESSION['token']?>">

            <input type="submit-button" value="Create Admin">
        </form>
    </div>
    </body>
</html>
</html>
