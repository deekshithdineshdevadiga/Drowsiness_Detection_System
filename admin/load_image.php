      
<?php
$imageFolder = 'onboard_img/';
$images = glob($imageFolder . '*.{jpg,png,gif}', GLOB_BRACE);

foreach ($images as $image) {
    echo '<div class="image-item">';
    echo '<img src="' . $image . '" alt="Image">';
    echo '</div>';
}

?>
