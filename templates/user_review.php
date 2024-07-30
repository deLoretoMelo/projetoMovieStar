<?php

require_once("models/User.php");

$userModel = new User();

$fullName = $userModel->getFullName($review->users_id);

if($review->users_id->image == ""){
    $review->users_id->image = "movie_cover.jpg";
}

?>

<div class="col-md-12 review">
    <div class="row">
            <div class="col-md-1">
                <div class="profile-image-container review-image" style="background-image: url(
                '<?= $BASE_URL ?>img/users/<?= $review->users_id->image ?>');"></div>
            </div>
            <div class="col-md-9 author-details-container">
                <h4 class="author-name">
                    <a href="<?= $BASE_URL ?>profile.php?id=<?= $review->users_id->id ?>"><?= $fullName ?></a>
                </h4>
                <p><i class="fas fa-star"></i> <?= $review->rating ?></p>
            </div>
            <div class="col-md-12">
                <p class="comment-title">Comentario:</p>
                <p><?= $review->review ?></p>
            </div>
    </div>
</div>