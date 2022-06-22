<?php
    require "../private/autoload.php";
    //Authentication
    $email = "";
    if(isset($_SESSION['admin']))
    {
        $email = $_SESSION['admin'];
    }else{
        header("Location: moderator_login.php");
        die;
    }

    $users = get_users($connection);
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] == $_POST['token'])
    {
        if(isset($_POST['user_block']))
        {
            $email = $_POST['email'];
            block_unblock($email, $connection);
        }

        if(isset($_POST['user_feedback']))
        {
            $_SESSION['email_m'] = $_POST['email'];
            header("Location: users_feedback.php");
            die;
        }
    }
    $_SESSION['token'] = get_random_string(60);

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css">
        <title>
            Admin Home Page
        </title>
    </head>

    <body>
        
        <div id="header">
            <div style="float:right">
                <a href="mod_logout.php">Logout</a>
            </div> <br><br>
            <div id="title">
                Admin Home Page.
            </div>

            <label id="review-feedback-sub-head" for="story">All Users in the System.</label><br><br><br>

                <?php
                    foreach($users as $user):
                    ?>
                        <div id="review-container">
                            <div class="review">
                                <div class="course-preview">
                                    <h3>Email: <?= htmlspecialchars(esc($user->email))?> </h3>
                                        <h6>Name: <?php echo htmlspecialchars(esc($user->name)); ?></h6>
                                        <h6>ID: <?php echo $user->id; ?></h6>
                                        <h6>Blocked: <?php echo htmlspecialchars(esc($user->is_locked)); ?></h6>
                                </div>
                                <div class="course-info">
                                    <div class="action-btns">
                                        <form method="post">
                                            <input id="feedback_id" type="hidden" name="email" value=<?= htmlspecialchars(esc($user->email))?>>
                                            <input id="submit-button" type="submit" value="View Feedbacks" name="user_feedback"><br><br>
                                            <input id="submit-button" type="submit" value="Block/Unblock" name="user_block">
                                            <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                ?>
        </div>

    </body>
</html>
</html>