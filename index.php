<?php

    require_once("templates/header.php");
    require_once("globals.php");
    require_once("dao/MovieDAO.php");

    $movieDao = new MovieDAO($conn, $BASE_URL);

    $latestMovies = $movieDao->getLatestMovies();
    $actionMovies = $movieDao->getMovieByCategory("Ação");
    $comedyMovies = $movieDao->getMovieByCategory("Comédia");

?>
    <!-- Corpo de site -->

    <div id="main-container" class="container-fluid">
        <h2 class="section-title">Filmes Novos</h2>
        <p class="section-description">Vejas as críticas dos últimos filmes adicionados no MovieStar</p>
        <div class="movies-container">
            <?php foreach($latestMovies as $movie): ?>
                <?php require("templates/movie_card.php"); ?>
            <?php endforeach; ?>
            <?php if(count($latestMovies) == 0): ?>
                <p class="empty-list">Ainda não há filmes cadastrados</p>
            <?php endif; ?>
        </div>

        <h2 class="section-title">Ação</h2>
        <p class="section-description">Vejas os melhores filmes de ação</p>
        <div class="movies-container">
            <?php foreach($actionMovies as $movie): ?>
                <?php require("templates/movie_card.php"); ?>
            <?php endforeach; ?>
            <?php if(count($actionMovies) == 0): ?>
                <p class="empty-list">Ainda não há filmes cadastrados</p>
            <?php endif; ?>
        </div>

        <h2 class="section-title">Comédia</h2>
        <p class="section-description">Vejas os melhores filmes de comédia</p>
        <div class="movies-container">
            <?php foreach($comedyMovies as $movie): ?>
                <?php require("templates/movie_card.php"); ?>
            <?php endforeach; ?>
            <?php if(count($comedyMovies) == 0): ?>
                <p class="empty-list">Ainda não há filmes cadastrados</p>
            <?php endif; ?>
        </div>
    </div>

<?php

require_once("templates/footer.php")

?>