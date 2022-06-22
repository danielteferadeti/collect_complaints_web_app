<?php

    require "../private/autoload.php";
    $Error = "";

    //Check first time login and signup the Admin
    if(!check_admin($connection))
    {
        header("Location: moderator_create.php");
        die;
    }
    //for anti-automation as well
    if(!empty( $_POST['honeypot']))
    {
        echo "You just got basted!!!";
        die;
    }
    if(isset($_SESSION['admin']))
    {
        header("Location: moderator.php");
        die;
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] == $_POST['token'])
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
        if($Error == "")
        {
            $arr['id'] = 1;

            $query = "select * from moderator where id = :id limit 1";
            $stm = $connection->prepare($query);
            $check = $stm->execute($arr);

            if($check)
            {
                $moderator = $stm->fetchAll(PDO::FETCH_OBJ);
                $db_email = $moderator[0]->email;
                $db_password = $moderator[0]->password;
                $db_salt = $moderator[0]->salt;

                //Verify the user here
                $user_pass_with_salt = hash('sha512', $db_salt.$password);
                if (hash_equals($db_password, $user_pass_with_salt))
                {
                    $_SESSION['admin'] = $db_email;
                    header("Location: moderator.php");
                    die;
                }
            }
        }

        if($Error=="")
        {
            $Error = "Wrong Email or Password!";
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
            Admin Login Page
        </title>
    </head>

    <body style="font-family: verdana">
    <div id="main-div">
        <form id="form" method="post">
           
            <div id="title">ADMIN LOGIN</div>
            <input id="textbox1" type="text" name="honeypot" class="hidden" value=""><br><br>
            <input id="textbox" type="email" name="email" placeholder="Enter You email" required><br><br>
            <input id="textbox" type="password" name="password" placeholder="Password" required><br><br>

            <!-- FOR CSRF TOKEN -->
            <input type="hidden" name="token" value="<?=$_SESSION['token']?>">

            <div id="error-text"><?php
                if(isset($Error) && $Error !="")
                {
                    echo $Error;
                }
            ?> </div><br>

            <input id="submit-button" type="submit" value="LOGIN">
        </form>
    </div>
    </body>
</html>
</html>
