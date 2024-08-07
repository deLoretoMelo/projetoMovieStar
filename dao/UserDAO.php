<?php

    require_once("models/User.php");
    require_once("models/Message.php");

    class UserDAO implements UserDAOInterface{

        public $conn;
        public $url;
        private $message;

        public function __construct(PDO $conn, $url)
        {
            $this->conn = $conn;
            $this->url = $url;
            $this->message = new Message($url);
        }

        public function buildUser($data){

            $user = new User();

            $user->id = $data["id"];
            $user->name = $data["name"];
            $user->lastname = $data["lastname"];
            $user->email = $data["email"];
            $user->password = $data["password"];
            $user->image = $data["image"];
            $user->bio = $data["bio"];
            $user->token = $data["token"];

            return $user;

        }

        public function create(User $user, $authUser = false){

            $stmt = $this->conn->prepare("INSERT INTO user(
              name, lastname, email, password, token) VALUES (
              :name, :lastname, :email, :password, :token)");

            $stmt->bindParam(":name", $user->name);
            $stmt->bindParam(":lastname", $user->lastname);
            $stmt->bindParam(":email", $user->email);
            $stmt->bindParam(":password", $user->password);
            $stmt->bindParam(":token", $user->token);

            $stmt->execute();

            // Autenticar caso dê certo
            if($authUser){
                $this->setTokenToSession($user->token);
            }

        }

        public function update(User $user, $redirect = true){

            $stmt = $this->conn->prepare("UPDATE user SET
              name = :name,
              lastname = :lastname,
              email = :email,
              image = :image,
              bio = :bio,
              token = :token WHERE id = :id");

              $stmt->bindParam(":name", $user->name);
              $stmt->bindParam(":lastname", $user->lastname);
              $stmt->bindParam(":email", $user->email);
              $stmt->bindParam(":image", $user->image);
              $stmt->bindParam(":bio", $user->bio);
              $stmt->bindParam(":token", $user->token);
              $stmt->bindParam(":id", $user->id);

              $stmt->execute();

            if($redirect){
                // Redireciona para o perfil do usuario
                $this->message->setMessage("Dados atualizados com sucesso!", "success", "editprofile.php");
            }

        }

        public function verifyToken($protected = false){

            if(!empty($_SESSION["token"])){
                //pega o token da session
                $token = $_SESSION["token"];

                $user = $this->findByTolken($token);

                if($user){

                    return $user;

                }else if($protected){
                    // Redireciona usuario nao autenticado
                    $this->message->setMessage("Faça a autenticacao para acessar essa página", "error", "index.php");
                
                }

            }else if($protected){
                // Redireciona usuario nao autenticado
                $this->message->setMessage("Faça a autenticacao para acessar essa página", "error", "index.php");
            
            }

        }

        public function setTokenToSession($token, $redirect = true){

            // Salvar token na sessão
            $_SESSION["token"] = $token;

            if($redirect){
                // Redireciona para o perfil do usuario
                $this->message->setMessage("Seja bem vindo", "success", "editprofile.php");
            }

        }

        public function authenticationuser($email, $password){

            $user = $this->findByEmail($email);

            if($user){

                //checar se as senhas batem
                if(password_verify($password, $user->password)){

                    //Gerar um token e inserir na sessão
                    $token = $user->generateToken();
                    $this->setTokenToSession($token, false);

                    //Atualizar o token do usuario
                    $user->token = $token;
                    $this->update($user, false);

                    return true;

                } else {
                    return false;
                }

            } else {
                return false;
            }

        }

        public function findByEmail($email){

            if($email != ""){

                $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = :email");

                $stmt->bindParam(":email", $email);

                $stmt->execute();

                if($stmt->rowCount() > 0){

                    $data = $stmt->fetch();
                    $user = $this->buildUser($data);

                    return $user;

                } else{
                    return false;
                }

            } else {
                return false;
            }

        }

        public function findByTolken($token){

            if($token != ""){

                $stmt = $this->conn->prepare("SELECT * FROM user WHERE token = :token");

                $stmt->bindParam(":token", $token);

                $stmt->execute();

                if($stmt->rowCount() > 0){

                    $data = $stmt->fetch();
                    $user = $this->buildUser($data);

                    return $user;

                } else{
                    return false;
                }

            } else {
                return false;
            }

        }

        public function destroyToken(){

            // Remove o token da sessão
            $_SESSION["token"] = "";

            //Redirecionar e apresentar mensagem
            $this->message->setMessage("você fez o logout com sucesso", "success", "index.php");

        }

        public function findById($id){
            
            if($id != ""){

                $stmt = $this->conn->prepare("SELECT * FROM user WHERE id = :id");

                $stmt->bindParam(":id", $id);

                $stmt->execute();

                if($stmt->rowCount() > 0){

                    $data = $stmt->fetch();
                    $user = $this->buildUser($data);

                    return $user;

                } else{
                    return false;
                }

            } else {
                return false;
            }
        }

        public function changePassword(User $user){

            $stmt = $this->conn->prepare("UPDATE user SET 
            password = :password 
            WHERE id = :id");

            $stmt->bindParam(":password", $user->password);
            $stmt->bindParam(":id", $user->id);

            $stmt->execute();

            $this->message->setMessage("Senha alterada com sucesso", "success", "editprofile.php");
        }

    }