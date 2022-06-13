<?php

    require "../private/autoload.php";

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        print_r($_POST);

        $name = $_POST['full_name'];
        $email = $_POST['email'];
        //White listing with regular expresions
        if (!preg_match("/^[\w\-]+@[\w\-]+.[\w\-]+$/", $email))
        {
            $Error = "Please enter a valid Email";
        }

        $password = $_POST['password'];
        $date = date("Y-m-d H:i:s");
        $url_address = get_random_string(60);

        $query = "insert into users (url_address,name,password, email, date) values ('$url_address','$name','$password', '$email', '$date')";
        mysqli_query($connection, $query);
    }



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
            <input id="textbox" type="text" name="full_name" required><br><br>
            <input id="textbox" type="email" name="email" required><br><br>
            <input id="textbox" type="password" name="password" required><br><br>
            
            <input type="submit" value="Signup"><br><br>
        </form>
    </body>
</html>
</html>
