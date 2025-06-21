<?php
$conn = new mysqli("localhost", "root", "", "valarie");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $table = $_POST['table'];
    $description = $_POST['description'];

    $allowedImgTypes = ['image/jpeg', 'image/png', 'image/webp'];
    $allowedVideoTypes = ['video/mp4', 'video/webm'];
    $totalUploaded = 0;

    if (!empty($_FILES['media']['name'][0])) {
        foreach ($_FILES['media']['name'] as $key => $fileName) {
            $tmpName = $_FILES['media']['tmp_name'][$key];
            $fileType = mime_content_type($tmpName);
            $uniqueName = uniqid() . '_' . basename($fileName);

            if (in_array($fileType, $allowedImgTypes)) {
                $targetDir = "images/";
            } elseif (in_array($fileType, $allowedVideoTypes)) {
                $targetDir = "uploads/";
            } else {
                continue; // skip unsupported file types
            }

            $targetPath = $targetDir . $uniqueName;
            if (move_uploaded_file($tmpName, $targetPath)) {
                $stmt = $conn->prepare("INSERT INTO `$table` (name, image, category, price, description) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssis", $name, $uniqueName, $category, $price, $description);
                if ($stmt->execute()) $totalUploaded++;
            }
        }

        $msg = $totalUploaded > 0 ? "✅ Successfully uploaded $totalUploaded file(s) to <b>$table</b>!" : "❌ No files were uploaded.";
    } else {
        $msg = "❌ Please select at least one file.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Gallery Upload | Valarie Fashion</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #1a1a1a, #2c2c2c);
      color: #f9f9f9;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .upload-box {
      background: #111;
      border-radius: 16px;
      padding: 40px;
      max-width: 600px;
      width: 90%;
      box-shadow: 0 0 20px rgba(255, 215, 0, 0.2);
      border: 1px solid gold;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      font-size: 28px;
      color: gold;
    }

    label {
      display: block;
      margin: 15px 0 5px;
      color: #eee;
    }

    input, select, textarea {
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      border: none;
      font-size: 16px;
      margin-bottom: 15px;
    }

    textarea {
      resize: vertical;
      min-height: 80px;
    }

    input[type="submit"] {
      background: gold;
      color: #111;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    input[type="submit"]:hover {
      background: #ffd700;
    }

    .msg {
      text-align: center;
      margin-top: 15px;
      font-size: 16px;
      color: #90ee90;
    }

    .msg.error {
      color: #ff4d4d;
    }
  </style>
</head>
<body>

<div class="upload-box">
  <h2>📸 Upload Products or Videos</h2>

  <form method="POST" enctype="multipart/form-data">
    <label>Product Name:</label>
    <input type="text" name="name" required>

    <label>Price (Ksh):</label>
    <input type="number" name="price" required>

    <label>Category (e.g. maxi, casual, silver):</label>
    <input type="text" name="category" required>

    <label>Description:</label>
    <textarea name="description" placeholder="Write product description..."></textarea>

    <label>Target Table:</label>
    <select name="table" required>
      <option value="">-- Select Table --</option>
      <option value="dresses">Dresses</option>
      <option value="heels">Shoes</option>
      <option value="rings">Rings</option>
      <option value="skirts">Lip Gloss</option>
      <option value="earrings">Earrings</option>
      <option value="shirts">shirts</option>

    </select>

    <label>Upload Images or Videos:</label>
    <input type="file" name="media[]" accept="image/*,video/mp4,video/webm" multiple required>

    <input type="submit" value="Upload">
  </form>

  <?php if ($msg): ?>
    <div class="msg <?php echo strpos($msg, '❌') !== false ? 'error' : ''; ?>">
      <?php echo $msg; ?>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
