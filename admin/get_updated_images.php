<?php
$imageFolder = 'onboard_img/';
$images = glob($imageFolder . '*.{jpg,png,gif}', GLOB_BRACE);

$html = '';
foreach ($images as $image) {
    $html .= '<div class="image-item">';
    $html .= '<img src="' . $image . '" alt="Image">';
    $html .= '<button type="button" class="btn btn-success img-select-btn" data-src="' . $image . '" >Select</button>';
    $html .= '</div>';   
}

echo $html;
?>
