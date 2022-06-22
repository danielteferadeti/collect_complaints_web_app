<?php

    require "../private/autoload.php";
    $user_data = check_login($connection);
    $feedback_info = get_single_feedback($connection);

    $id = $_SESSION['feedback_id'];
    $name = $feedback_info->name;
    $email = $feedback_info->email;
    $feedback = $feedback_info->feedback;
    $date = $feedback_info->date;
    $filepath = $feedback_info->filepath;
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
                $arr['id'] = $id;
                $arr['name'] = $name;
                $arr['email'] = $email;
                $arr['feedback'] = $feedback;
                $arr['date'] = $date;
                $arr['filepath'] = $filepath;

                echo "I ws herere";
                $query = "update feedbacks set name=:name,email=:email,feedback=:feedback, date=:date, filepath=:filepath where id=:id";
                $stm = $connection->prepare($query);
                $check = $stm->execute($arr);

                $comment_msg = "Your Compliant is successfully Updated!";
                header("Location: review_feedback.php");
                die;
            }
        }
    }

    $_SESSION['token'] = get_random_string(60);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Edit Feedback Page
        </title>
    </head>

    <body>
        <div id="header">
            <div style="float:right">
                <a href="logout.php">Logout</a>
            </div>

            <?php if($name != ""):?>
                <div>Welcome to Edit feedback page. you are loged in as <?=$email ?></div>
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
                        rows="10" cols="65"><?= $feedback?></textarea> <br>

                <label for="story">Choose a file if you wish to change the uploaded file</label><br>
                <input type="file" name="upload"><br><br>

                <!-- FOR CSRF TOKEN -->
                <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
                <!-- Then Submit -->
                <input type="submit" value="Save edit">
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