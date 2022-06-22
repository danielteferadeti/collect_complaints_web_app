<?php

    require "../private/autoload.php";
    $user_data = check_login($connection);

    //only set the variable name if they are logged in!
    $url_address = "";
    $email = "";
    $name = "";
    $date = "";
    $filepath = "";

    $Error = "";
    $message = "";
    $comment_msg = "";
    if(isset($_SESSION['name']))
    {
        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        $url_address = $_SESSION['url_address'];
        $date = date("Y-m-d H:i:s");
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] == $_POST['token'])
    {
        //Sanitization(escaping) for feedback(SQL injection) and considering every script tags as normal text: for XSS attack
        $feedback = htmlspecialchars(esc($_POST['feedback']));

        if($feedback=="")
        {
            $Error = "Your are trying to submit empty comment!";
        }

        if($Error == "")
        {
            //Uploaded Files
            if(isset($_FILES['upload']) && $_FILES['upload']['error'] === UPLOAD_ERR_OK)
            {
                $exts_allowed = array('pdf');
                //Details about the file
                $fileTmpPath = $_FILES['upload']['tmp_name'];
                $fileName = $_FILES['upload']['name'];
                $fileSize = $_FILES['upload']['size'];
                $fileType = $_FILES['upload']['type'];
                $ext = end(explode('.', $fileName));

                $newFileName = md5(time() . $fileName) . '.' . $ext;

                //Now to the uploading part
                if (in_array($ext, $exts_allowed))
                {
                    echo "I was HERE";
                    $uploadFileDir = '../private/uploaded_files/';
                    $dest_path = $uploadFileDir . $newFileName;

                    if(move_uploaded_file($fileTmpPath, $dest_path))
                    {
                        $filepath = $dest_path;
                    }
                    else
                    {
                        $message = 'File upload was unsuccessful!';
                        $Error = 'Compliant is not recorded! pls try again.';
                    }

                }else
                {
                  $message = 'Upload failed. Allowed file types: ' . implode(',', $exts_allowed);
                  $Error = "Compliant is not recorded.";
                }
            }
            if($Error == "")
            {
                $arr['name'] = $name;
                $arr['email'] = $email;
                $arr['feedback'] = $feedback;
                $arr['date'] = $date;
                $arr['filepath'] = $filepath;

                $query = "insert into feedbacks (name,email, feedback, date, filepath) values (:name,:email,:feedback, :date, :filepath)";
                $stm = $connection->prepare($query);
                $check = $stm->execute($arr);

                $comment_msg = "Your Compliant is successfully recorded!";
                // header("Location: feedback.php");
                // die;
            }
        }
    }

    $_SESSION['token'] = get_random_string(60);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Feedback Page
        </title>
    </head>

    <body>
        <div id="header">
            <div style="float:right">
                <a href="logout.php">Logout</a>
            </div>

            <?php if($name != ""):?>
                <div>Welcome to feedback page. you are loged in as <?=$email ?></div>
            <?php endif; ?><br><br>

            <?php if($name != ""):?>
                <div>name: <?=$user_data->name?></div>
            <?php endif; ?> <br>

            <?php if($email != ""):?>
                <div>email: <?=$user_data->email?></div>
            <?php endif; ?> <br>

            <label for="story">Write your feedback below:</label><br>

            <form action="" method="post" enctype="multipart/form-data">

                <textarea id="feedback_textarea" type="text" name="feedback"
                        rows="10" cols="65"></textarea> <br>

                <!-- FOR CSRF TOKEN -->
                <input type="hidden" name="token" value="<?=$_SESSION['token']?>">

                <input type="file" name="upload"><br><br>

                <input type="submit" value="Submit Feedback">
            </form><br>


            <!-- If the user tries to send empty comment this error will be displayed -->
            <div><?php
                if(isset($Error) && $Error !="")
                {
                    echo $message;
                    echo $Error;
                }else
                {
                    echo $comment_msg;
                }
            ?> </div>

        </div>
    </body>
</html>
</html>