<!doctype html>
<!-- This tool is pure Shashi Idea and Developed by Philiphacker (www.philiphacker.in) -->
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Shashi Security</title>
    <link rel="icon" type="image/png" href="https://philiphacker.in/api/shashi/logo/shashi-security.png" sizes="32x32">
    <link rel="icon" type="image/png" href="https://philiphacker.in/api/shashi/logo/shashi-security.png" sizes="192x192">
    <title>Encrypt & Decrypt Tool</title>
    <meta name="title" content="Shashi Security">
    <meta name="description" content="A secure, one-time view encryption and decryption tool.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://philiphacker.in/">
    <meta property="og:title" content="Shashi Security">
    <meta property="og:description" content="A secure, one-time view encryption and decryption tool.">
    <meta property="og:image" content="https://philiphacker.in/api/shashi/logo/shashi-security.png">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://philiphacker.in/">
    <meta name="twitter:title" content="Shashi Security">
    <meta name="twitter:description" content="A secure, one-time view encryption and decryption tool.">
    <meta name="twitter:image" content="https://philiphacker.in/api/shashi/logo/shashi-security.png">

  
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <main class="container">
    <h1>Shashi Security</h1>

    <div class="card" id="controls">
      <div class="btn-row">
        <button id="showEncrypt" class="btn">Encrypt</button>
        <button id="showDecrypt" class="btn ghost">Decrypt</button>
      </div>

      <form id="encryptForm" class="panel hidden">
        
        <textarea id="message" rows="6" placeholder="Type message..." required></textarea>
        <div class="row-between">
          <button type="submit" id="b1" class="btn">Encrypt Message</button>
          <button type="button" id="cancelEncrypt" class="btn ghost">Cancel</button>
        </div>
      </form>

      <form id="decryptForm" class="panel hidden">
        <input id="keyInput" type="text" maxlength="6" placeholder="Enter 6‑digit key" required />
        <div class="row-between">
          <button type="submit" id="b2" class="btn">Decrypt Message</button>
          <button type="button" id="cancelDecrypt" class="btn ghost">Cancel</button>
        </div>
      </form>

      <div id="result" class="panel hidden"></div>
    </div>

    <footer class="muted">www.philiphacker.in</footer>
  </main>

  <script src="script.js"></script>
</body>
</html>

