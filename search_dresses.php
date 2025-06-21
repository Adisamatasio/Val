<?php
$conn = new mysqli("localhost", "root", "", "valarie");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$keyword = isset($_POST['keyword']) ? strtolower(trim($_POST['keyword'])) : '';
$categories = ['maxi', 'casual', 'mini', 'formal'];

$output = '';

foreach ($categories as $cat) {
    if (strpos($keyword, $cat) !== false) {
        $sql = "SELECT * FROM dresses WHERE LOWER(category) LIKE '%$cat%' LIMIT 1";
        $res = $conn->query($sql);

        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $output .= "
              <div class='product-card'>
                <img src='images/{$row['image']}' alt='{$row['name']}'>
                <h4>{$row['name']}</h4>
                <p>Ksh {$row['price']}</p>
              </div>
            ";
        } else {
            $output .= "<div><p>No $cat dresses found.</p></div>";
        }
    }
}

echo $output ?: "<p>No matching category found. Try: maxi, casual, mini, or formal.</p>";
?>
