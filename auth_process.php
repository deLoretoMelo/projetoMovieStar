<?php

    require_once("globals.php");
    require_once("db.php");
    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");
    

    $message = new Message($BASE_URL);

    $userDao = new UserDAO($conn, $BASE_URL);

    // Resgata o tipo do formulario
    $type = filter_input(INPUT_POST, "type");


    // Verificacao do tipo de formulario
    if($type == "register"){
        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

        //verificacao de dados minimos
        if($name && $lastname && $email && $password){

            //Verificar se as senhas batem
            if($password === $confirmpassword){

                // Veririfcar se o email ja está cadastrado no sistema
                if($userDao->findByEmail($email) === false){

                    $user = new User();

                    // Criação de token e senha
                    $userToken = $user->generateToken();
                    $finalPassword = $user->generatePassword($password);

                    $user->name = $name;
                    $user->lastname = $lastname;
                    $user->email = $email;
                    $user->password = $finalPassword;
                    $user->token = $userToken;

                    $auth = true;

                    $userDao->create($user, $auth);

                } else {

                    // Enviar mensagem de erro pois usuario ja existe
                    $message->setMessage("Usuário ja cadastrado, tente outro email", "error", "back");
                }

            } else {

                // Enviar mensagem de erro pela diferença de senhas
                $message->setMessage("As senhas não são iguais", "error", "back");
            }


        } else{
            // Enviar mensagem de erro, de dados faltantes
            $message->setMessage("Por favor, preencha todos os campos", "error", "back");
        }
     } 
    else if($type == "login"){

     }