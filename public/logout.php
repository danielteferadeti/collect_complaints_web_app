<?php
    require "../private/autoload.php";

    if(isset($_SESSION['email']))
    {
        unset($_SESSION['email']);
    }

    if(isset($_SESSION['name']))
    {
        unset($_SESSION['name']);
    }

    if(isset($_SESSION['url_address']))
    {
        unset($_SESSION['url_address']);
    }

    header("Location: login.php");
    die;