<?php
    require "../private/autoload.php";
    $user_data = check_login($connection);
    $feedbacks = get_feedbacks($connection);
    $name = htmlspecialchars(esc($user_data->name));
    $email = htmlspecialchars(esc($user_data->email));

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
        <link rel="stylesheet" href="styles.css">
        <title>
            Review Feedback Page
        </title>
    </head>

    <body>
        <div id="review-feedback-div">
            <div style="float:right">
                <a href="logout.php">Logout</a>
            </div><br>
            <div style="float:right">
                <a href="index.php">Home</a>
            </div><br>

            <?php if($name != ""):?>
                <div id="review-feedback-head">Welcome to Review feedback page. you are loged in as <?=$email ?></div>
            <?php endif; ?><br><br>

            <!-- <br> -->

            <label id="review-feedback-sub-head" for="story">Your feedbacks are below.</label><br>

            <br><br>

            <!-- If there is an error display it below -->
            <div id="error-text">
                <?php
                            if(isset($Error) && $Error !="")
                            {
                                echo $message;
                                echo $Error;
                            }else
                            {
                                echo $comment_msg;
                            }
                        ?>
            </div>

            <br><br>

            <?php
                foreach($feedbacks as $feedback):
                ?>
                    <div id="review-container">
                        <div id="review">
                            <div class="course-preview">
                                <h3><?= $feedback->feedback?> </h3>
                                <h6>Date: <?php echo htmlspecialchars($feedback->date); ?></h6>
                                <h6>ID: <?php echo htmlspecialchars($feedback->id); ?></h6>
                                <h6>Uploaded file: <?php echo htmlspecialchars(end(explode('/', $feedback->filepath))); ?></h6>
                            </div>
                            <div class="course-info">
                                <div class="action-btns">
                                    <form method="post">
                                        <input id="feedback_id" type="hidden" name="id" value=<?= $feedback->id?>>
                                        <input id="submit-button" type="submit" value="Edit">
                                        <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div><br><br>
                <?php endforeach;
            ?>

            <br>

        </div>
    </body>
</html>
</html>