<?php

    require_once("templates/header.php");

    // Verifica se o usuario está autenticado
    require_once("models/Movie.php");
    require_once("dao/MovieDAO.php");

    // Pegar o id do filme
    $id = filter_input(INPUT_GET, "id");

    $movie;

    $movieDao = new MovieDAO($conn, $BASE_URL);

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
                    <input type="hidden" name="id" value="<?= $movie->id ?>">
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
            <div class="col-md-12 review">
                <div class="row">
                    <div class="col-md-1">
                        <div class="profile-image-container review-image" style="background-image: url(
                        '<?= $BASE_URL ?>img/users/<?= $userData->image ?>');"></div>
                    </div>
                    <div class="col-md-9 author-details-container">
                        <h4 class="author-name"><a href="#">Teste</a></h4>
                        <p><i class="fas fa-star"></i> 9</p>
                    </div>
                    <div class="col-md-12">
                        <p class="comment-title">Comentario:</p>
                        <p>Este é o comentario do usuario</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php

    require_once("templates/footer.php");