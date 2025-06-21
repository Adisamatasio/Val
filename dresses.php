<?php
$conn = new mysqli("localhost", "root", "", "valarie");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch all dresses by default
$result = $conn->query("SELECT * FROM dresses ORDER BY id DESC");
$dresses = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dresses | Valarie Fashion</title>
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
      padding: 30px 20px;
      text-align: center;
    }
    header h1 {
      margin: 0;
    }
    .filter-bar {
      margin-top: 15px;
    }
    select, button {
      padding: 10px;
      font-size: 16px;
      border-radius: 6px;
      border: none;
    }
    button {
      background: #27ae60;
      color: #fff;
      cursor: pointer;
      margin-left: 10px;
    }

    .results-section {
      padding: 30px 20px;
      text-align: center;
    }

    .results-grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
    }

    .product-card {
      background: #fff;
      border-radius: 10px;
      padding: 15px;
      width: 240px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }

    .product-card img {
      width: 100%;
      height: 230px;
      object-fit: cover;
      border-radius: 8px;
    }

    .product-card h4 {
      margin: 10px 0 5px;
    }

    .product-card p {
      margin: 0;
      font-weight: bold;
      color: #27ae60;
    }

    footer {
      background: #222;
      color: #ccc;
      text-align: center;
      padding: 20px 10px;
      font-size: 14px;
      margin-top: 40px;
    }

    @media (max-width: 600px) {
      .results-grid {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<body>

<header>
  <h1>Dresses Collection</h1>
  <div class="filter-bar">
    <select id="categorySelect">
      <option value="">-- search Category --</option>
      <option value="maxi">Maxi Dresses</option>
      <option value="casual">Casual Fits</option>
      <option value="mini">Mini Dress</option>
      <option value="formal">Formal Fits</option>
    </select>
    <button onclick="filterDresses()">Search</button>
  </div>
</header>

<section class="results-section">
  <h2>Available Dresses</h2>
  <div class="results-grid" id="results">
    <?php foreach ($dresses as $row): ?>
      <div class="product-card">
        <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
        <h4><?php echo htmlspecialchars($row['name']); ?></h4>
        <p>Ksh <?php echo htmlspecialchars($row['price']); ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<footer>
  &copy; 2025 Valarie Fashion. All rights reserved.
</footer>

<script>
function filterDresses() {
  const category = document.getElementById('categorySelect').value;
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "filter_dresses.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function() {
    document.getElementById("results").innerHTML = this.responseText;
  };
  xhr.send("category=" + encodeURIComponent(category));
}
</script>

</body>
</html>
