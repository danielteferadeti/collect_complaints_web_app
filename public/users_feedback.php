<?php
    require "../private/autoload.php";
    $feedbacks = get_feedbacks_m($connection);

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] == $_POST['token'])
    {
        
    }
    $_SESSION['token'] = get_random_string(60);
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css">
        <title>
            A Users Review Feedback Page
        </title>
    </head>

    <body>
        <div id="header">
            <div style="float:right">
                <a href="moderator.php">To Home Page</a>
            </div><br><br>
            <div style="float:right">
                <a href="mod_logout.php">Logout</a>
            </div><br>

            <label id="review-feedback-sub-head" for="story">This User's feedbacks are below.</label><br>

            <?php
                foreach($feedbacks as $feedback):
                ?>
                    <div id="review-container">
                        <div class="review">
                            <div class="course-preview">
                                <h3><?= htmlspecialchars(esc($feedback->feedback))?> </h3>
                                    <h6>Date: <?php echo htmlspecialchars(esc($feedback->date)); ?></h6>
                                    <h6>ID: <?php echo $feedback->id; ?></h6>
                                    <h6>Uploaded file: <?php echo htmlspecialchars(esc(end(explode('/', $feedback->filepath)))); ?></h6>
                            </div>
                        </div>
                    </div><br><br>
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