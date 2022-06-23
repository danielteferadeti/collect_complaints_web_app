<?php

    require "../private/autoload.php";
    $Error = "";
    $email = "";
    $name  = "";

    if(isset($_SESSION['name']))
    {
        header("Location: login.php");
        die;
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['token']) && $_SESSION['token'] == $_POST['token'])
    {
        //validating user input by white listing: regural expression
        $name = $_POST['full_name'];
        //This should be removed //to avoid XSS attack
        //print_r(htmlspecialchars($name));

        if (!preg_match("/^[a-zA-Z ]+$/", $name))
        {
            $Error = "Please enter a valid Name!";
        }

        //Sanitization
        $name = esc($name);

        $email = $_POST['email'];
        //White listing with regular expression
        if (!preg_match("/^[\w\-]+@[\w\-]+.[\w\-]+$/", $email))
        {
            $Error = "Please enter a valid Email!";
        }
        //Sanitization for Password
        $password = esc($_POST['password']);
        $c_password = esc($_POST['c_password']);

        if(strlen($password) < 4)
        {
            $Error = "Password should be more than 4 characters";
        }

        if($password != $c_password)
        {
            $Error = "Password Doesn't match!";
        }

        //Salting and Hashing the password
        $salt = get_random_string(60);
        $hashed_pass = password_hash($salt.$password, PASSWORD_DEFAULT);

        //to make records different
        $date = date("Y-m-d H:i:s");
        $url_address = get_random_string(60);

        //checking the email is already registered or not
            $arr = false;
            $arr['email'] = $email;

            $query = "select * from users where email = :email limit 1";
            $stm = $connection->prepare($query);
            $check = $stm->execute($arr);

            if($check)
            {
                $data = $stm->fetchAll(PDO::FETCH_OBJ);
                if(is_array($data) && count($data) > 0){

                    $Error = "Email already exists! Try to Login in or use another Email!";
                }
            }

        if($Error == "")
        {
            // ----- by using a prepared statement ------
            // to use this parametrized part uncomment the PDO connection part 
            // in database connection php page

            $arr['url_address'] = $url_address;
            $arr['name'] = $name;
            $arr['password'] = $hashed_pass;
            $arr['email'] = $email;
            $arr['date'] = $date;
            $arr['salt'] = $salt;

            $query = "insert into users (url_address,name,password, email, date, salt) values (:url_address,:name,:password,:email,:date,:salt)";
            $stm = $connection->prepare($query);
            $stm->execute($arr);

            header("Location: login.php");
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
            Signup
        </title>
    </head>

    <body style="font-family: verdana">

        <div id="main-div">
            <form id="form" method="post">
                <div id="title">Signup</div>
                <br>
                <input id="textbox" type="text" name="full_name" placeholder="Full Name" value="<?=$name?>" required><br><br>
                <input id="textbox" type="email" name="email" placeholder="Email" value="<?=$email?>" required><br><br>
                <input id="textbox" type="password" name="password" placeholder="Password" required><br><br>
                <input id="textbox" type="password" name="c_password" placeholder="Confirm Password" required><br><br>
            
                <!-- FOR CSRF TOKEN -->
                <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
            
                <div id="error-text">
                    <?php
                        if(isset($Error) && $Error !="")
                        {
                            echo $Error;
                        }
                    ?>
                </div>
            
                <br>
            
                <input id="submit-button" type="submit" value="Signup">
            
                <br><br>
                <div style="float:left">
                    <a href="login.php">Login instead</a>
                </div><br><br>
            
            </form>
        </div>
    </body>
</html>
</html>
