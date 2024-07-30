<?php

    require_once("templates/header.php");
    require_once("globals.php");
    require_once("dao/MovieDAO.php");

    $movieDao = new MovieDAO($conn, $BASE_URL);

    //resgata busca do usuario
    $q = filter_input(INPUT_GET, "q");

    $movies = $movieDao->findByTilte($q);
?>
    <!-- Corpo de site -->

    <div id="main-container" class="container-fluid">
        <h2 id="search-title" class="section-title">Você está buscando por: <span id="search-result"><?= $q ?></span></h2>
        <p class="section-description">resultados de busca retornados.</p>
        <div class="movies-container">
            <?php foreach($movies as $movie): ?>
                <?php require("templates/movie_card.php"); ?>
            <?php endforeach; ?>
            <?php if(count($movies) == 0): ?>
                <p class="empty-list">Não há filmes para esta busca, <a class="back-link" href="<?= $BASE_URL?>index.php">Voltar</a></p>
            <?php endif; ?>
        </div>
    </div>

<?php

require_once("templates/footer.php")

?>