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