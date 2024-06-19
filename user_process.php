<?php

require_once("globals.php");
require_once("db.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");


$message = new Message($BASE_URL);

$userDao = new UserDAO($conn, $BASE_URL);

// Resgata o tipo do domulario
$type = filter_input(INPUT_POST, "type");

// Atualiza o usuario
if($type === "update"){
    
    //Resgata dados do usuario
    $userData = $userDao->verifyToken();

    //Recebe os dados do post
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $bio = filter_input(INPUT_POST, "bio");

    $user = new User();

    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->email = $email;
    $userData->bio = $bio;


    //Upload da imagem
    if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])){

        $image = $_FILES["image"];

        $imageTypes = ["image/png", "image/jpeg", "image/jpg"];
        $jpgArray = ["image/jpeg", "image/jpg"];

        //checagem de tipo de imagem
        if(in_array($image["type"], $imageTypes)){
            

            //checar se é jpg
            if(in_array($image, $jpgArray)){
                $imageFile = imagecreatefromjpeg($image["tmp_name"]);

            } else {
                $imageFile = imagecreatefrompng($image["tmp_name"]);
            }

            $imageName = $user->imageGenerateName();

            imagejpeg($imageFile, "./img/users/" . $imageName, 100);

            $userData->image = $imageName;

        } else {
            $message->setMessage("Tipo de imagem inválido!", "error", "editprofile.php");
        }
    }

    $userDao->update($userData);

    //Atualizar senha do usuario
} else if($type === "changepassword"){



} else{

    $message->setMessage("Informações Inválidas!", "error", "index.php");

}