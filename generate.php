<?php
require __DIR__ . '/vendor/autoload.php';
use chillerlan\QRCode\{QRCode, QROptions};

if(isset($_POST['link']) && filter_var($_POST['link'], FILTER_VALIDATE_URL)){
    $options = new QROptions([
        'outputType' => QRCode::OUTPUT_MARKUP_SVG,
        'eccLevel'   => QRCode::ECC_L,
        'scale'      => 5,
        'imageBase64'=> false,
    ]);
    $qrcode = new QRCode($options);
    echo $qrcode->render($_POST['link']);
}
?>
