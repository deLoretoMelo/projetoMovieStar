<?php

    class Movie{

        public $id;
        public $title;
        public $description;
        public $image;
        public $trailer;
        public $category;
        public $length;
        public $users_id;

        public function imageGenerateName(){
            return bin2hex(random_bytes(60)) . ".jpg";
        }
    }

    interface MovieDAOInterface{

        public function buildMovie($data);
        public function fildAll();
        public function getLatestMovies();
        public function getMovieByCategory($category);
        public function getMovieByUsersId($id);
        public function findById($id);
        public function findByTilte($title);
        public function create(Movie $movie);
        public function update(Movie $movie);
        public function destroy($id);

    }