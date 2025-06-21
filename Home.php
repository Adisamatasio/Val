<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "valarie");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch videos
$videos = $conn->query("SELECT * FROM videos ORDER BY uploaded_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Valarie</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f9f9f9;
      color: #333;
    }
    header {
      background: #222;
      color: #fff;
      padding: 20px;
      text-align: center;
    }
    .category-section {
      padding: 40px 20px;
      text-align: center;
    }
    .category-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
      gap: 20px;
      max-width: 1000px;
      margin: auto;
    }
    .category-grid a {
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      text-decoration: none;
      color: #222;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transition: 0.3s;
    }
    .category-grid a:hover {
      background: #e0ffe0;
    }

    .videos-section {
      background: #fff;
      padding: 50px 20px;
      text-align: center;
    }

    .videos-section h2 {
      font-size: 2.2em;
      margin-bottom: 30px;
      color: #222;
    }

    .video-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
    }

    .video-box {
      position: relative;
    }

    .video-box video {
      width: 280px;
      height: 160px;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .video-box video:hover {
      transform: scale(1.05);
    }

    .watermark {
      position: absolute;
      top: 8px;
      right: 12px;
      font-size: 12px;
      font-weight: bold;
      background: rgba(255, 255, 255, 0.7);
      color: #222;
      padding: 2px 6px;
      border-radius: 6px;
      font-family: 'Segoe UI', sans-serif;
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.9);
      justify-content: center;
      align-items: center;
    }

    .modal video {
      max-width: 90%;
      max-height: 80%;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(255,255,255,0.1);
    }

    .close {
      position: absolute;
      top: 30px;
      right: 50px;
      color: #fff;
      font-size: 40px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover {
      color: #ccc;
    }

    footer {
      background: #222;
      color: #ccc;
      text-align: center;
      padding: 20px 10px;
      font-size: 14px;
    }

    .social-icons {
      margin-top: 10px;
    }

    .social-icons a {
      margin: 0 10px;
      color: #ccc;
      text-decoration: none;
      font-size: 20px;
      transition: color 0.3s ease;
    }

    .social-icons a:hover {
      color: #e0ffe0;
    }

    @media (max-width: 600px) {
      .video-box video {
        width: 90vw;
        height: auto;
      }

      .category-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
  </style>
</head>
<body>

<header>
  <h1>Valarie's trendy Fashion Store</h1>
  <p>Trendy clothes, classy jewelry, stunning shoes</p>
</header>

<section class="category-section">
  <h2>Shop by Category</h2>
  <div class="category-grid">
    <a href="dresses.php">Dresses</a>
    <a href="skirts.php">Skirts</a>
    <a href="shirts.php">Shirts</a>
    <a href="tshirts.php">T-Shirts</a>
    <a href="bracelets.php">Bracelets</a>
    <a href="earrings.php">Earrings</a>
    <a href="rings.php">Rings</a>
    <a href="heels.php">Heels</a>
    <a href="upload_video.php">Upload</a>
    <a href="gallery.php">gallery</a>
  </div>
</section>

<section class="videos-section">
  <h2>In store  𝜗𝜚 ࣪˖ ִ𐙚 📸<br>Click video for easier viewing</h2>
  <div class="video-container">
    <?php while ($row = $videos->fetch_assoc()): ?>
      <div class="video-box">
        <video autoplay muted loop onclick="openModal('uploads/<?php echo htmlspecialchars($row['filename']); ?>')">
          <source src="uploads/<?php echo htmlspecialchars($row['filename']); ?>" type="video/mp4">
        </video>
        <div class="watermark">Valarie</div>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<!-- Modal Viewer -->
<div id="videoModal" class="modal">
  <span class="close" onclick="closeModal()">&times;</span>
  <video id="modalVideo" controls autoplay></video>
</div>

<script>
  function openModal(videoSrc) {
    const modal = document.getElementById("videoModal");
    const modalVideo = document.getElementById("modalVideo");
    modal.style.display = "flex";
    modalVideo.src = videoSrc;
  }

  function closeModal() {
    const modal = document.getElementById("videoModal");
    const modalVideo = document.getElementById("modalVideo");
    modalVideo.pause();
    modalVideo.src = "";
    modal.style.display = "none";
  }
</script>

<footer>
  &copy; 2025 Valarie Fashion Store. All rights reserved.
  <div class="social-icons">
    <a href="https://facebook.com" target="_blank">📘</a>
    <a href="https://instagram.com" target="_blank">📸</a>
    <a href="https://tiktok.com" target="_blank">🎵</a>
    <a href="https://twitter.com" target="_blank">🐦</a>
  </div>
</footer>

</body>
</html>
