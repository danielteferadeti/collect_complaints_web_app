<?php
    require "../private/autoload.php";
    $user_data = check_login($connection);
    $feedbacks = get_feedbacks($connection);
    $name = $user_data->name;
    $email = $user_data->email;

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] == $_POST['token'])
    {
        $_SESSION['feedback_id'] = $_POST['id'];
        echo $_SESSION['feedback_id'];
        header("Location: edit_feedback.php");
        die;
    }

    $_SESSION['token'] = get_random_string(60);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Review Feedback Page
        </title>
    </head>

    <body>
        <div id="header">
            <div style="float:right">
                <a href="logout.php">Logout</a>
            </div>

            <?php if($name != ""):?>
                <div>Welcome to Review feedback page. you are loged in as <?=$email ?></div>
            <?php endif; ?><br><br>

            <label for="story">Your feedbacks are below.</label><br>

            <?php
                foreach($feedbacks as $feedback):
                ?>
                    <div class="courses-container">
                        <div class="course">
                            <div class="course-preview">
                                <h3><?= $feedback->feedback?> </h3>
                                    <h6>Date: <?php echo $feedback->date; ?></h6>
                                    <h6>ID: <?php echo $feedback->id; ?></h6>
                                    <h6>Uploaded file: <?php echo end(explode('/', $feedback->filepath)); ?></h6>
                            </div>
                            <div class="course-info">
                                <div class="action-btns">
                                    <form method="post">
                                        <input id="feedback_id" type="hidden" name="id" value=<?= $feedback->id?>>
                                        <input type="submit" value="Edit">
                                        <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;
            ?>

            <!-- If there is nay error display it below -->
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