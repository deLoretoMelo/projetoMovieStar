<?php

    require_once("templates/header.php");


    // Verifica se o usuario está autenticado
    require_once("models/Movie.php");
    require_once("dao/MovieDAO.php");
    require_once("dao/ReviewDAO.php");

    // Pegar o id do filme
    $id = filter_input(INPUT_GET, "id");

    $movie;

    $movieDao = new MovieDAO($conn, $BASE_URL);

    $reviewDao = new ReviewDao($conn, $BASE_URL);

    if(empty($id)){
        $message->setMessage("O filme não foi encontrado", "error", "index.php");
    }
    else{
        $movie = $movieDao->findById($id);

        if(!$movie){
            $message->setMessage("O filme não foi encontrado", "error", "index.php");
        }


    }

    // Checar se o filme é do usuario
    $userOwnsMovie = false;
    if(!empty($userData)){

        if($userData->id === $movie->users_id){
            $userOwnsMovie = true;
        }
    }

    //Resgatar as reviews do filme
    $moviereviews = $reviewDao->getmoviesReviews($id);

    //Checagem de trailer
    if($movie->image == ""){
        $movie->image = "movie_cover.jpg";
    }

    //Resgatar os reviews
    $alreadyReviewed = false;

?>

<div id="main-container" class="container-fluid">
    <div class="row">
        <div class="offset-md-1 col-md-6 movie-container">
            <h1 class="page-title"><?= $movie->title ?></h1>
            <p class="movie-details">
                <span>Duração: <?= $movie->length ?></span>
                <span class="pipe"></span>
                <span><?= $movie->category ?> </span>
                <span class="pipe"></span>
                <span><i class="fas fa-star"></i> 9</span>
            </p>
            <?php if(!($movie->trailer == "")): ?>
                <iframe src="<?= $movie->trailer ?>" width="560px" height="315px"
                frameborder="0" allow="accelerometer; autoplay; clipboard-right;
                encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <?php else: ?>
                <p>O filme não possui Trailer Cadastrado.</p>
            <?php endif; ?>
             <p><?= $movie->description ?></p>
             
        </div>
        <div class="col-md-4">
            <div class="movie-image-container" style="background-image: url(
            '<?= $BASE_URL ?>/img/movies/<?= $movie->image ?>');"></div>
        </div>
        <div id="reviews-container" class="offset-md-1 col-md-10">
            <h3 class="reviews-title">Avaliações</h3>
            <!-- Verifica se habilita a review pro usuario ou nao -->
            <?php if(!empty($userData) && !$userOwnsMovie && !$alreadyReviewed): ?>
             <div class="col-md-12" id="review-form-container">
                <h4>Envie sua avaliação:</h4>
                <p class="page-decription">Preencha o formulário com a nota e o comentário sobre o filme</p>
                <form id="form-review-id" action="<?= $BASE_URL ?>review_process.php" method="POST">
                    <input type="hidden" name="type" value="create">
                    <input type="hidden" name="movies_id" value="<?= $movie->id ?>">
                    <div class="form-group">
                        <label for="rating">Nota fo filme:</label>
                        <select class="form-control" name="rating" id="rating">
                            <option value="">Selecione</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="review">Seu comentário</label>
                        <textarea style="margin-bottom: 10px;" name="review" id="review" rows="3" class="form-control"
                        placeholder="O que você achou do filme?"></textarea>
                    </div>
                    <input type="submit" class="btn card-btn" value="Enviar Comentário">
                </form>
             </div>
            <?php endif; ?>
            <!-- Comentarios -->
            <?php foreach($moviereviews as $review): ?>
                <?php require("templates/user_review.php"); ?>
            <?php endforeach; ?>
            <?php if(count($moviereviews) == 0): ?>
                <p class="empty-list">Não há comentários para este filme ainda.</p>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php

    require_once("templates/footer.php");