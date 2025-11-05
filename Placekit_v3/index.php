<?php
  
  $scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
  $base     = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
  $baseUrl  = $scheme.'://'.$host.$base;

  $imageEndpoint = $baseUrl . '/image.php';

  
  $imagesDir = __DIR__ . '/images';
  $count = 0;
  if (is_dir($imagesDir)) {
    $files = glob($imagesDir . '/*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);
    if ($files) $count = count($files);
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PlaceKit | BrickMMO </title>
  <link rel="stylesheet" href="<?=$baseUrl?>/assets/styles.css">
</head>
<body>
  <main>
    <section class="hero">
      <h1>Create resized placeholder photos</h1>
      <p>Pick an image by number (1..<?=$count ?: 8?>) or use <strong>random</strong>. Set width & height, and we’ll return a center-cropped, cover-fit photo.</p>
    </section>

    <section class="card">
      <form id="generatorForm" novalidate>
        <div class="grid">
          <div>
            <label for="width">Width (px)</label>
            <input id="width" name="width" type="number" min="1" placeholder="e.g., 1200" required />
          </div>
          <div>
            <label for="height">Height (px)</label>
            <input id="height" name="height" type="number" min="1" placeholder="e.g., 628" required />
          </div>

          <div>
            <label for="imageSel">Image index (1..<?=$count ?: 8?>) or "random"</label>
            <input id="imageSel" name="image" type="text" placeholder='e.g., 1 or random' />
           <div class="hint">We have <?=$count?> image<?=($count===1?'':'s')?> in /images.“For best results, use images 1–5 for wide (horizontal) layouts, and 6–8 for tall (vertical) ones.  </div>
          </div>
          <div>
            <label for="overlay">Overlay bar (shows text)</label>
            <select id="overlay" name="overlay">
              <option value="0" selected>No</option>
              <option value="1">Yes</option>
            </select>
          </div>

          <div class="full">
            <label for="text">Overlay text (optional)</label>
            <input id="text" name="text" type="text" placeholder='Defaults to "WIDTHxHEIGHT" if empty' />
          </div>

          <div class="full actions">
            <button id="generateBtn" class="btn-primary" type="submit">Get Download Link</button>
            <span class="hint">Preview uses the same URL.</span>
          </div>
        </div>
        <p id="error" class="error" hidden></p>
      </form>
    </section>

    <section id="result" class="result center" hidden>
      <a id="downloadBtn" class="btn-primary" href="#" download>Download</a>
      <p class="note" id="linkDetails"></p>
      <p class="small">
        Direct link:
        <a id="directLink" href="#" target="_blank" rel="noopener">open in new tab</a>
      </p>
      <img id="previewImg" class="preview-thumb" alt="Preview" hidden />
      <p class="small muted" id="previewHint" hidden>(Preview uses the exact same URL.)</p>
    </section>
  </main>

  <script>
    const IMAGE_ENDPOINT = '<?=$imageEndpoint?>';

    const form        = document.getElementById('generatorForm');
    const widthEl     = document.getElementById('width');
    const heightEl    = document.getElementById('height');
    const imageSelEl  = document.getElementById('imageSel');
    const overlayEl   = document.getElementById('overlay');
    const textEl      = document.getElementById('text');

    const errorEl     = document.getElementById('error');
    const result      = document.getElementById('result');
    const downloadBtn = document.getElementById('downloadBtn');
    const directLink  = document.getElementById('directLink');
    const linkDetails = document.getElementById('linkDetails');
    const previewImg  = document.getElementById('previewImg');
    const previewHint = document.getElementById('previewHint');

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

    form.addEventListener('submit', (e) => {
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

      result.hidden = false;
      result.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  </script>
  <script src="https://cdn.brickmmo.com/bar@1.0.0/bar.js"></script>
</body>
</html>
