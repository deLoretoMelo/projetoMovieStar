<?php

    class Message{
        private $url;

        public function __construct($url)
        {
            $this->url = $url;
        }

        public function setMessage($msg, $type, $redirect = "index.php"){
            $_SESSION["msg"] = $msg;
            $_SESSION["type"] = $type;

            if($redirect != "back"){
                header("Location: $this->url" . $redirect);
            } else{
                header("Location: auth.php");
            }
        }

        public function getMessage(){

        }

        public function clearMessage() {
            
        }

    }