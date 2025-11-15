<?php
  
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$baseUrl = $scheme.'://'.$host.$base;

$image_endpoint = $baseUrl . '/image.php';

$images_dir = __DIR__ . '/images';

$count = 0;
if (is_dir($images_dir)) 
{
  $files = glob($images_dir . '/*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);
  if ($files) $count = count($files);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PlaceKit | BrickMMO</title>
        
    <!-- W3 School CSS -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css" />

    <!-- BrickMMO Exceptions -->
    <link rel="stylesheet" href="https://cdn.brickmmo.com/exceptions@1.0.0/w3.css" />
    <link rel="stylesheet" href="https://cdn.brickmmo.com/exceptions@1.0.0/fontawesome.css" />

    <!-- BrickMMO Icons -->
    <link rel="stylesheet" href="https://cdn.brickmmo.com/glyphs@1.0.0/icons.css" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Script JavaScript File -->
    <!--<script src="/script.js"></script>-->

</head>
<body>


<main class="w3-flex" style="height:100vh;">
  
    <div class="w3-center w3-display-container" style="width:50%; max-width:600px; height:100%; background:#f9f9f9;">


    <div class="w3-display-middle w3-padding w3-left-align" style="width: 100%;">

      <div class="w3-margin">
        <h1>BrickMMO PlaceKit</h1>
      </div>

      <div class="w3-margin">
        <label for="width">Width (px)</label>
        <input id="width" name="width" type="number" min="1" max="1200" value="800" required />
      </div>
      <div class="w3-margin">
        <label for="height">Height (px)</label>
        <input id="height" name="height" type="number" min="1" max="1200" value="450" required />
      </div>
      <div class="w3-margin">
        <input id="imageSel" name="image" type="hidden" placeholder='e.g., 1 or random' />
        <div class="hint">
          We have <?=$count?> image<?=($count===1?'':'s')?> in /images.“For best results, use images 1-4 for wide (horizontal) 
          layouts, and 7–8 for tall (vertical) ones.  
        </div>
      </div>

      <div class="w3-margin w3-flex w3-wrap">

        <div class="w3-quarter w3-flex w3-center" style="justify-content:center; align-items:center;">
          <img src="images/1.png" width="100" onclick="selectImage(1);" id="image-1" style="border: 3px solid #848484;">
        </div>
        <div class="w3-quarter w3-flex w3-center" style="justify-content:center; align-items:center;">
          <img src="images/2.png" width="100" onclick="selectImage(2);" id="image-2" style="border: 3px solid #848484;">
        </div>
        <div class="w3-quarter w3-flex w3-center" style="justify-content:center; align-items:center;">
          <img src="images/3.png" width="100" onclick="selectImage(3);" id="image-3" style="border: 3px solid #848484;">
        </div>
        <div class="w3-quarter w3-flex w3-center" style="justify-content:center; align-items:center;">
          <img src="images/4.png" width="100" onclick="selectImage(4);" id="image-4" style="border: 3px solid #848484;">
        </div>

      </div>

      <div class="w3-margin w3-flex w3-wrap">

        <div class="w3-quarter w3-flex w3-center" style="justify-content:center; align-items:center;">
          <img src="images/5.png" width="100" onclick="selectImage(5);" id="image-5" style="border: 3px solid #848484;">
        </div>

        <div class="w3-quarter w3-flex w3-center" style="justify-content:center; align-items:center;">
          <img src="images/6.png" width="100" onclick="selectImage(6);" id="image-6" style="border: 3px solid #848484;">
        </div>

        <div class="w3-quarter w3-flex w3-center" style="justify-content:center; align-items:center;">
          <img src="images/7.png" width="100" onclick="selectImage(7);" id="image-7" style="border: 3px solid #848484;">
        </div>

        <div class="w3-quarter w3-flex w3-center" style="justify-content:center; align-items:center;">
          <img src="images/8.png" width="100" onclick="selectImage(8);" id="image-8" style="border: 3px solid #848484;">
        </div>

      </div>
      
      <div class="w3-margin">
        <label for="overlay">Overlay bar (shows text)</label>
        <select id="overlay" name="overlay">
          <option value="0" selected>No</option>
          <option value="1">Yes</option>
        </select>
      </div>

      <div class="w3-margin">
        <label for="text">Overlay text (optional)</label>
        <br>
        <input id="text" name="text" type="text" placeholder='Defaults to "WIDTHxHEIGHT" if empty' style="width: 100%;" />
      </div>

      <div class="w3-margin">
        <button id="generateBtn" class="btn-primary" type="button">Preview</button>
      </div>
    
      <p id="error" class="error" hidden></p>

    </div>
  </div>

  <div class="w3-center w3-display-container" style="flex:1; height:100%; background:#fff;">

    <div class="w3-display-middle w3-padding" style="width: 100%;">

      <div class="w3-margin">
        <img id="previewImg" class="preview-thumb" alt="Preview" hidden style="max-width:100%; max-height: 500px;" />
      </div>

      <div class="w3-margin">

        <a id="downloadBtn" class="btn-primary" href="#" download>Download</a>
        <p class="note" id="linkDetails"></p>
        <p class="small">
          Direct link:
          <a id="directLink" href="#" target="_blank" rel="noopener">open in new tab</a>
        </p>
      
        <p class="small muted" id="previewHint" hidden>(Preview uses the exact same URL)</p>

      </div>

    </div>
  </div>
</main>


  <script>

    const IMAGE_ENDPOINT = '<?=$image_endpoint?>';

    const form        = document.getElementById('generatorForm');
    const widthEl     = document.getElementById('width');
    const heightEl    = document.getElementById('height');
    const imageSelEl  = document.getElementById('imageSel');
    const overlayEl   = document.getElementById('overlay');
    const textEl      = document.getElementById('text');

    const errorEl     = document.getElementById('error');
    // const result      = document.getElementById('result');
    const downloadBtn = document.getElementById('downloadBtn');
    const directLink  = document.getElementById('directLink');
    const linkDetails = document.getElementById('linkDetails');
    const previewImg  = document.getElementById('previewImg');
    const previewHint = document.getElementById('previewHint');

    function selectImage(image)
    {

      for(let i = 1; i <= 8; i ++)
      {
        document.getElementById("image-" + i).style.border = "3px solid #848484";
      }

      imageSelEl.value = image;
      document.getElementById("image-"+image).style.border = "3px solid red";
      
    }

    function buildUrl() {
      const w = parseInt(widthEl.value, 10);
      const h = parseInt(heightEl.value, 10);
      const img = (imageSelEl.value || 'random').trim();
      const ov  = overlayEl.value;
      const t   = (textEl.value || '').trim();

      const params = new URLSearchParams({ width: w, height: h, image: img, overlay: ov });
      if (t) params.set('text', t);
      return IMAGE_ENDPOINT + '?' + params.toString();
    }

    const generateBtn = document.getElementById("generateBtn");
    generateBtn.addEventListener('click', (e) => {
      e.preventDefault();
      errorEl.hidden = true;

      const w = parseInt(widthEl.value, 10);
      const h = parseInt(heightEl.value, 10);
      if (!w || !h || w <= 0 || h <= 0) {
        errorEl.textContent = 'Please enter valid numbers for width and height.';
        errorEl.hidden = false;
        return;
      }

      const url = buildUrl();

      downloadBtn.href = url;
      downloadBtn.setAttribute('download', `placekit-${w}x${h}.jpg`);
      downloadBtn.textContent = `Download ${w}×${h} JPG`;

      directLink.href = url;
      directLink.textContent = url;

      const t = (textEl.value || '').trim();
      const img = (imageSelEl.value || 'random').trim();
      linkDetails.textContent = `Generated ${w} × ${h} from image: ${img}${t ? ` — text: “${t}”` : ''}`;

      previewImg.hidden = true;
      previewHint.hidden = true;
      previewImg.onload = () => { previewImg.hidden = false; previewHint.hidden = false; };
      previewImg.onerror = () => {
        previewImg.hidden = true;
        previewHint.hidden = false;
        previewHint.textContent = 'Could not load preview — check GD and /image.php.';
      };
      previewImg.src = url;

      // result.hidden = false;
      // result.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    selectImage(1);

    generateBtn.click();


  </script>

  <script src="https://cdn.brickmmo.com/bar@1.1.0/bar.js" 
    data-console="false" 
    data-menu="false" 
    data-admin="false" 
    data-local="false" 
    data-https="true"></script>

</body>
</html>
