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
        $movie->users_id = $userData->id;

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

} else if($type === "delete") {

    // Recebendo os dados do formulario
    $id = filter_input(INPUT_POST, "id");

    $movie = $movieDao->findById($id);

    if($movie){

        // verificar se o filme é do usuario
        if($movie->users_id === $userData->id){

            $movieDao->destroy($movie->id);

        } else {
            $message->setMessage("Informações Inválidas!", "error", "index.php");
        }

    } else {
        $message->setMessage("Informações Inválidas!", "error", "index.php");
    }
 
} else if($type === "update"){ 

    //Receber os dados do input
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");
    $id = filter_input(INPUT_POST, "id");

    $movieData = $movieDao->findById($id);

    // Verifica se encontrou o filme
    if($movieData){
        if($movieData->users_id === $userData->id){
            if(!empty($title) && !empty($description) && !empty($category)){
                $movieData->title = $title;
                $movieData->description = $description;
                $movieData->trailer = $trailer;
                $movieData->category = $category;
                $movieData->length = $length;
                $movieData->users_id = $userData->id;

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

                    $imageName = $movieData->imageGenerateName();

                    imagejpeg($imageFile, "./img/movies/" . $imageName, 100);
                    $movieData->image = $imageName;

                } else {

                $message->setMessage("Tipo de imagem inválido,
                insira png ou jpg/jpeg!", "error", "newmovie.php");
                }

                }
                
                $movieDao->update($movieData);

            }   else{
                $message->setMessage("Você precisa adicionar pelo menos:
                título, descrição e categoria!", "error", "back");
            }   
        } else {
            $message->setMessage("Informações Inválidas!", "error", "index.php");
        }

    } else{
        $message->setMessage("Informações Inválidas!", "error", "index.php");
    }

} else {
    $message->setMessage("Informações Inválidas!", "error", "index.php");
}