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
        }else{
            echo "I was Here!!!";
        }
    }else{
        header("Location: review_feedback.php");
        die;
    }
}