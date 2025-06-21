<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['video'])) {
    $targetDir = "uploads/";
    $filename = basename($_FILES["video"]["name"]);
    $targetFile = $targetDir . $filename;

    // Check file type
    $videoType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = ['mp4', 'webm', 'ogg'];

    if (in_array($videoType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["video"]["tmp_name"], $targetFile)) {
            $conn = new mysqli("localhost", "root", "", "valarie");
            if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

            $stmt = $conn->prepare("INSERT INTO videos (filename) VALUES (?)");
            $stmt->bind_param("s", $filename);
            $stmt->execute();
            $message = "✅ Video uploaded successfully!";
        } else {
            $message = "❌ Upload failed. Please try again.";
        }
    } else {
        $message = "❌ Invalid file type. Only MP4, WebM, and OGG allowed.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Fashion Video</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      height: 100vh;
      margin: 0;
    }
    h1 {
      margin-bottom: 20px;
      color: #222;
    }
    form {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
    }
    input[type="file"] {
      padding: 10px;
      border: 1px solid #ccc;
      margin-bottom: 20px;
      width: 100%;
    }
    button {
      padding: 10px 25px;
      background: #27ae60;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
    }
    button:hover {
      background: #219150;
    }
    .msg {
      margin-top: 15px;
      font-weight: bold;
      color: #444;
    }
    a.back {
      margin-top: 20px;
      display: inline-block;
      text-decoration: none;
      color: #555;
    }
    a.back:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <h1>Upload Fashion Video</h1>

  <form action="upload_video.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="video" accept="video/*" required><br>
    <button type="submit">Upload Video</button>
  </form>

  <?php if (!empty($message)): ?>
    <div class="msg"><?php echo $message; ?></div>
  <?php endif; ?>

  <a class="back" href="Home.php">← Back to Home</a>

</body>
</html>
