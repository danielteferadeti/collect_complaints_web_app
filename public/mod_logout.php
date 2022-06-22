<?php
    require "../private/autoload.php";

    if(isset($_SESSION['admin']))
    {
        unset($_SESSION['admin']);
    }

    if(isset($_SESSION['email_m']))
    {
        unset($_SESSION['email_m']);
    }

    header("Location: moderator_login.php");
    die;