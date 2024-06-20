<?php

    require_once("templates/header.php");

    //Verifica se o usuario está autenticado
    require_once("models/User.php");
    require_once("dao/UserDAO.php");

    $user = new User();

    $userDao = new UserDAO($conn, $BASE_URL);

    $userData = $userDao->verifyToken(true);

?>
    <!-- Corpo de site -->

    <div id="main-container" class="container-fluid">
        <div class="offset-md-4 col-md-4 new-movie-container">
            <h1 class="page-title">Adicionar Filme</h1>
            <p class="page-description">Adicione sua crítica e compartilhe com o mundo!</p>
            <form action="<?= $BASE_URL ?>movie_process.php" class="add-movie-form" method="POST"
            enctype="multipart/form-data">
                <input type="hidden" name="type" value="create">
                <div class="form-group">
                    <label for="title">Título</label>
                    <input style="margin-bottom: 10px;" class="form-control" type="text" name="title" id="title" 
                    placeholder="Digite o título do seu filme">
                </div>
                <div class="form-group">
                    <label for="image">Imagem:</label>
                    <input style="margin-bottom: 10px;" class="form-control-file" type="file" name="image" id="image">
                </div>
                <div class="form-group">
                    <label for="length">Duração</label>
                    <input style="margin-bottom: 10px;" class="form-control" type="text" name="length" id="length"
                    placeholder="Digite a duração de filme">
                </div>
                <div class="form-group">
                    <label for="category">Categoria</label>
                    <select style="margin-bottom: 10px;" class="form-control" name="category" id="category">
                        <option value="">Selecione</option>
                        <option value="Ação">Ação</option>
                        <option value="Drama">Drama</option>
                        <option value="Comédia">Comédia</option>
                        <option value="Fantasia/Ficção">Fanstasia / Ficção</option>
                        <option value="Romance">Romance</option>
                        <option value="Terror">Terror</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="trailer">Trailer</label>
                    <input style="margin-bottom: 10px;" class="form-control" type="text" name="trailer" id="trailer"
                    placeholder="Insira o link do trailer">
                </div>
                <div class="form-group">
                    <label for="description">Descrição</label>
                    <textarea style="margin-bottom: 10px;" class="form-control" name="description" id="description" rows="5"
                    placeholder="Descreva o filme..."></textarea>
                </div>
                <input type="submit" class="btn card-btn" value="Adicionar Filme">
            </form>
        </div>
    </div>

<?php

require_once("templates/footer.php");

?>