<?php
$conn = new mysqli("localhost", "root", "", "valarie");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$category = isset($_POST['category']) ? strtolower(trim($_POST['category'])) : '';

if ($category) {
  $stmt = $conn->prepare("SELECT * FROM dresses WHERE LOWER(category) LIKE ? LIMIT 1");
  $like = "%$category%";
  $stmt->bind_param("s", $like);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  $result = $conn->query("SELECT * FROM dresses ORDER BY id DESC");
}

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<div class='product-card'>
            <img src='images/{$row['image']}' alt='{$row['name']}'>
            <h4>{$row['name']}</h4>
            <p>Ksh {$row['price']}</p>
          </div>";
  }
} else {
  echo "<p>No dresses found for this category.</p>";
}
?>
