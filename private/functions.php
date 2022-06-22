<?php

//function to generate random string
function get_random_string($length)
{
    $array = array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $text = "";

    $length = rand(4, $length);

    for($i=0;$i<$length;$i++){
        $random = rand(0,61);

        $text .= $array[$random];
    }

    return $text;
}

// Function to scape characters and more...
function esc($word)
{
    return addslashes($word);
}


//checking whether a user is logged in or not
function check_login($connection)
{
    if(isset($_SESSION['email']))
    {
        $arr['email'] = $_SESSION['email'];

        $query = "select * from users where email = :email limit 1";
        $stm = $connection->prepare($query);
        $check = $stm->execute($arr);

        if($check)
        {
            $data = $stm->fetchAll(PDO::FETCH_OBJ);
            if(is_array($data) && count($data) > 0){
                return $data[0];
            }
        }
    }

    header("Location: login.php");
    die;
}

//Get all feedbacks from a user
function get_feedbacks($connection)
{
    if(isset($_SESSION['email']))
    {
        $arr['email'] = $_SESSION['email'];

        $query = "select * from feedbacks where email = :email";
        $stm = $connection->prepare($query);
        $check = $stm->execute($arr);

        if($check)
        {
            $feedback_datas = $stm->fetchAll(PDO::FETCH_OBJ);
            if(is_array($feedback_datas) && count($feedback_datas) > 0){
                return $feedback_datas;
            }else{
                return array();
            }
            return array();
        }
    }

    header("Location: login.php");
    die;
}

//Get all feedbacks from a user for Admin
function get_feedbacks_m($connection)
{
    if(isset($_SESSION['email_m']))
    {
        $arr['email'] = $_SESSION['email_m'];

        $query = "select * from feedbacks where email = :email";
        $stm = $connection->prepare($query);
        $check = $stm->execute($arr);

        if($check)
        {
            $feedback_datas = $stm->fetchAll(PDO::FETCH_OBJ);
            if(is_array($feedback_datas) && count($feedback_datas) > 0){
                return $feedback_datas;
            }else{
                return array();
            }
            return array();
        }
    }

    header("Location: login.php");
    die;
}

//get all info on users given feedback
function get_single_feedback($connection)
{
    if(isset($_SESSION['feedback_id']))
    {
        $arr['id'] = $_SESSION['feedback_id'];

        $query = "select * from feedbacks where id = :id limit 1";
        $stm = $connection->prepare($query);
        $check = $stm->execute($arr);

        if($check)
        {
            $feedback_info = $stm->fetchAll(PDO::FETCH_OBJ);
            if(is_array($feedback_info) && count($feedback_info) > 0){
                return $feedback_info[0];
            }else{
                return array();
            }
            return array();
        }
    }else{
        header("Location: review_feedback.php");
        die;
    }
}

//Check if admin is created or not

function check_admin($connection)
{
    $arr['id'] = 1;

    $query = "select * from moderator where id = :id limit 1";
    $stm = $connection->prepare($query);
    $check = $stm->execute($arr);

    if($check)
    {
        $moderator = $stm->fetchAll(PDO::FETCH_OBJ);
        if(is_array($moderator) && count($moderator) > 0){
            return true;
        }else{
            return false;
        }
    }else
    {
        echo "There is error finding the admin";
    }
}

//Get all users
function get_users($connection){
    if(isset($_SESSION['admin']))
    {
        $query = "select * from users";
        $stm = $connection->prepare($query);
        $check = $stm->execute();

        if($check)
        {
            $users_datas = $stm->fetchAll(PDO::FETCH_OBJ);
            if(is_array($users_datas) && count($users_datas) > 0){
                return $users_datas;
            }else{
                return array();
            }
            return array();
        }
    }
}

//Block unblock User
function block_unblock($email, $connection)
{
    $arr['email'] = $email;

    $query = "select * from users where email = :email limit 1";
    $stm = $connection->prepare($query);
    $check = $stm->execute($arr);

    if($check)
    {
        $user = $stm->fetchAll(PDO::FETCH_OBJ);
        if(is_array($user) && count($user) > 0){
            $is_locked = $user[0]->is_locked;
            if($is_locked==0)
            {
                $is_locked = 1;
            }else{
                $is_locked = 0;
            }

            $arr2['is_locked'] = $is_locked;
            $arr2['email'] = $email;

            $query = "update users set is_locked=:is_locked where email=:email";
            $stm = $connection->prepare($query);
            $check = $stm->execute($arr2);

            header("Location: moderator.php");
            die;
        }
    }else
    {
        echo "There is error finding the User";
    }
}