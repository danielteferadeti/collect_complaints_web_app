<?php

    require "../private/autoload.php";
    $Error = "";

    //for anti-automation as well
    if(!empty( $_POST['honeypot']))
    {
        echo "You just got basted!!!";
        die;
    }
    if(isset($_SESSION['name']))
    {
        header("Location: index.php");
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

        if($Error == "")
        {
            $arr['email'] = $email;

            $query = "select * from users where email = :email limit 1";
            $stm = $connection->prepare($query);
            $check = $stm->execute($arr);

            if($check)
            {
                $data = $stm->fetchAll(PDO::FETCH_OBJ);
                if(is_array($data) && count($data) > 0){
                    $data = $data[0];
                    //check if user is locked or not
                    if(!$data->is_locked){
                        $db_password = $data->password;
                        $db_salt = $data->salt;
                        //Verify the user here
                        $user_pass_with_salt = $db_salt.$password;
                        if (password_verify($user_pass_with_salt, $db_password))
                        {
                            $_SESSION['url_address'] = $data->url_address;
                            $_SESSION['name'] = $data->name;
                            $_SESSION['email'] = $data->email;
                            header("Location: index.php");
                            die;
                        }
                    }else
                    {
                        $Error = "This account is locked!";
                    }
                }
            }else{
                //Handle the brute force attack here!!!!
                $Error = "Wrong Email or Password!";
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
<html lang="en">

<head>
    <link rel="stylesheet" href="styles.css">
    <title>
        Login
    </title>
</head>

<body style="font-family: verdana">
    <div id="main-div">
        <form id="form" method="post">
        
            <div id="title">LOGIN</div>
            <br>
            <input id="textbox1" type="text" name="honeypot" class="hidden" value=""><br><br>
            <input id="textbox" type="email" name="email" placeholder="Enter You email" required><br><br>
            <input id="textbox" type="password" name="password" placeholder="Password" required><br><br>
        
            <!-- FOR CSRF TOKEN -->
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
        
            <div id="error-text"><?php
                if(isset($Error) && $Error !="")
                {
                    echo $Error;
                }
            ?> </div>

            <br>
        
            <input id="submit-button" type="submit" value="Login">
        
            <br><br>
        
            <div style="float:left">
                <a href="signup.php">Signup first</a>
            </div><br><br>
        
        </form>
    </div>
</body>

</html>

</html>
