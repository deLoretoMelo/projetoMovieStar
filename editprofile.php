<?php

    require_once("templates/header.php");
    require_once("models/User.php");
    require_once("dao/UserDAO.php");

    $user = new User();

    $userDao = new UserDAO($conn, $BASE_URL);

    $userData = $userDao->verifyToken(true);

    $fullName = $user->getFullName($userData);

    if($userData->image == ""){
        $userData->image = "user.png";
    }

?>
    <!-- Corpo de site -->

    <div id="main-container" class="container-fluid">
        <div class="col-md-12">
            <form action="<?= $BASE_URL ?>user_process.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="type" value="update">
                <div class="row">
                    <div class="col-md-4">
                        <h1><?= $fullName ?></h1>
                        <p class="page-description">Altere seus dados no formulario abaixo</p>
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input style="margin-bottom: 10px;" type="text" class="form-control" id="name" name="name" placeholder="Digite seu nome"
                            value="<?= $userData->name ?>">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Sobrenome</label>
                            <input style="margin-bottom: 10px;" type="text" class="form-control" id="lastname" name="lastname" placeholder="Digite seu nome"
                            value="<?= $userData->lastname ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input style="margin-bottom: 10px;" type="text" readonly class="form-control disabled" id="email" name="email" placeholder="Digite seu nome"
                            value="<?= $userData->email ?>">
                        </div>
                        <input type="submit" class="btn form-btn" value="Alterar">
                    </div>
                    <div class="col-md-4">
                        <div id="profile-image-container" style="background-image: url(
                        '<?= $BASE_URL ?>img/users/<?= $userData->image ?>');"></div>
                        <div class="form-group">
                            <label for="image">Foto:</label>
                            <input style="margin-bottom: 10px;" type="file" class="form-control-file" name="image">
                        </div>
                        <div class="form-group">
                            <label for="bio">Sobre você</label>
                            <textarea style="margin-bottom: 10px;" class="form-control" name="bio" id="bio" rows="5" placeholder="Conte sobre sua vida">
                                <?= $userData->bio ?>
                            </textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php

require_once("templates/footer.php")

?>