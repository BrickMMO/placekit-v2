<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placekit | BrickMMO</title>
    <link href="assets/styles.css" rel="stylesheet">
</head>

<body>
    <main>

        <h1>Placekit</h1>
        <h2>Dynamically Generated Image</h2>
        <p>Here is the dynamically generated image with the dimensions and color:</p>
        <p><strong>How to use:</strong></p>
        <p>
            <span class="url">
                <?=$_SERVER['REQUEST_SCHEME']?>://<?=$_SERVER['HTTP_HOST']?>/image/?
                <span class="attr">width=[VALUE]</span>
                &<span class="attr">height=[VALUE]</span>
                &<span class="attr">color=[VALUE]</span>
            </span>
        </p>

        <img src="/image/?width=600&height=600&colour=ffffff&bg=336699" alt="">

    </main>

    <script src="https://cdn.brickmmo.com/bar@1.0.0/bar.js"></script>

</body>
</html>
