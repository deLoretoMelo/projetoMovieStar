<?php

    require_once("models/Movie.php");
    require_once("models/Message.php");

    //ReviewDAO

    class MovieDAO implements MovieDAOInterface{
        private $conn;
        private $url;
        private $message;

        public function __construct(PDO $conn, $url)
        {
            $this->conn = $conn;
            $this->url = $url;
            $this->message = new Message($url);
        }

        public function buildMovie($data){

            $movie = new Movie();

            $movie->id = $data["id"];
            $movie->title = $data["title"];
            $movie->description = $data["description"];
            $movie->image = $data["image"];
            $movie->trailer = $data["trailer"];
            $movie->category = $data["category"];
            $movie->length = $data["length"];
            $movie->users_id = $data["users_id"];

            return $movie;

        }

        public function fildAll(){

        }
        public function getLatestMovies(){

        }
        public function getMovieByCategory(){

        }
        public function getMovieById(){

        }
        public function findById($id){

        }
        public function findByTilte($title){

        }
        public function create(Movie $movie){

        }
        public function update(Movie $movie){

        }
        public function destroy($id){

        }
    }