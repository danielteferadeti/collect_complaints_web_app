<?php

    require "../private/autoload.php";
    $Error = "";

    if(isset($_SESSION['name']))
    {
        header("Location: index.php");
        die;
    }

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        //This part should be removed!
        print_r($_POST);

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
            $arr['password'] = $password;
            $arr['email'] = $email;

            $query = "select * from users where email = :email && password = :password limit 1";
            $stm = $connection->prepare($query);
            $check = $stm->execute($arr);

            if($check)
            {
                $data = $stm->fetchAll(PDO::FETCH_OBJ);
                if(is_array($data) && count($data) > 0){
                    $data = $data[0];
                    $_SESSION['url_address'] = $data->url_address;
                    $_SESSION['name'] = $data->name;
                    $_SESSION['email'] = $data->email;
                    header("Location: index.php");
                    die;
                }
            }
        }

        $Error = "Wrong Email or Password!";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Login
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
            <div id="title">Login</div>
            <input id="textbox" type="email" name="email" required><br><br>
            <input id="textbox" type="password" name="password" required><br><br>
            
            <input type="submit" value="Login">

            <div style="float:right">
                <a href="signup.php">Signup first</a>
            </div><br><br>
            
        </form>
    </body>
</html>
</html>
