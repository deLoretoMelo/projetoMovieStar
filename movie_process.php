<?php

require_once("globals.php");
require_once("db.php");
require_once("models/Movie.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

$message = new Message($BASE_URL);

$movieDao = new MovieDAO($conn, $BASE_URL);

$userDAO = new UserDAO($conn, $BASE_URL);

//Resgata dados do usuario
$userData = $userDAO->verifyToken();

$type = filter_input(INPUT_POST, "type");

if($type == "create"){

    //Receber os dados do input
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");

    $movie = new Movie();

    //Validação minima de dados
    if(!empty($title) && !empty($description) && !empty($category)){

        $movie->title = $title;
        $movie->description = $description;
        $movie->trailer = $trailer;
        $movie->category = $category;
        $movie->length = $length;

        //upload de imagem do filme
        if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])){

            $image = $_FILES["image"];
            $imageTypes = ["image/png", "image/jpeg", "image/jpg"];
            $jpgArray = ["image/jpeg", "image/jpg"];

            if(in_array($image["type"], $imageTypes)){
                if(in_array($image["type"], $jpgArray)){

                    $imageFile = imagecreatefromjpeg($image["tmp_name"]);

                } else {

                    $imageFile = imagecreatefrompng($image["tmp_name"]);

                }

                $imageName = $movie->imageGenerateName();

                imagejpeg($imageFile, "./img/movies/" . $imageName, 100);
                $movie->image = $imageName;

            } else {

                $message->setMessage("Tipo de imagem inválido,
                insira png ou jpg/jpeg!", "error", "newmovie.php");
            }

        }

        $movieDao->create($movie);

    } else {
        $message->setMessage("Você precisa adicionar pelo menos:
        título, descrição e categoria!", "error", "newmovie.php");
    }


} else {
    $message->setMessage("Informações Inválidas!", "error", "index.php");
}