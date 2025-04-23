<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placekit-v2</title>
    <link href="assets/styles.css" rel="stylesheet">
</head>

<body>
    <main>
        <h1>Placekit-v2: Generated Image</h1>
        <p>Here is the dynamically generated image with the dimensions and color:</p>
        <p><strong>How to use:</strong> <span class="url">http://localhost/placekit-v2/?<span class="attr">width=[VALUE]</span>&<span class="attr">height=[VALUE]</span>&<span class="attr">color=[VALUE]</span></span></p>
        
        <?php
            $width  = isset($_GET['width']) ? (int) $_GET['width'] : 300;
            $height = isset($_GET['height']) ? (int) $_GET['height'] : 200;
            $color  = isset($_GET['color']) ? $_GET['color'] : 'lightgray';
        ?>
        <img src="image_generator.php?width=<?=$width ?>&height=<?=$height ?>&color=<?=$color ?>" alt="Generated Image">
    </main>
</body>

</html>