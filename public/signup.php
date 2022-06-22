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
            $arr['password'] = $password;
            $arr['email'] = $email;
            $arr['date'] = $date;

            $query = "insert into users (url_address,name,password, email, date) values (:url_address,:name,:password,:email,:date)";
            $stm = $connection->prepare($query);
            $stm->execute($arr);

            // ----------- // ------------ // -----------
            // --- without prepared statement
            // -- before using the following code uncomment the connection to 
            // database at database.php page and comment the PDO section

            // $query = "insert into users (url_address,name,password, email, date) values ('$url_address','$name','$password', '$email', '$date')";
            // mysqli_query($connection, $query);

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
        <title>
            Signup
        </title>
    </head>

    <body style="font-family: verdana">
        <style type="text/css">
            form{
                margin: auto;
                border: solid thin #aaa;
                padding: 6px;
                max-width: 200px;
            }

            #title{
                background-color: #39b799;
                padding: 1em;
                text-align: center;
                color: white;
            }

            #textbox{
                border: solid thin #aaa;
                margin-top: 6px;
                width: 98%;
            }
        </style>

        <form method="post">
            <div><?php
                if(isset($Error) && $Error !="")
                {
                    echo $Error;
                }
            ?> </div>
            <div id="title">Signup</div>
            <input id="textbox" type="text" name="full_name" placeholder="Full Name" value="<?=$name?>" required><br><br>
            <input id="textbox" type="email" name="email" placeholder="Email" value="<?=$email?>" required><br><br>
            <input id="textbox" type="password" name="password" placeholder="Password" required><br><br>

            <!-- FOR CSRF TOKEN -->
            <input type="hidden" name="token" value="<?=$_SESSION['token']?>">

            <input type="submit" value="Signup">
            <div style="float:right">
                <a href="login.php">Login instead</a>
            </div><br><br>
            
        </form>
    </body>
</html>
</html>
