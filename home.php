<?php
$servername = "localhost";
$username = "root";
$password = "AbC13579";
$database = "web_project";
// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  echo "$conn->connect_error";
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// load forms from db and display their titles
$data = mysqli_query($conn, "SELECT id,title FROM forms");

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Home</title>
  <link href="./home.css" rel="stylesheet" />
</head>

<body class="page">
  <main>
    <img src="./logo.png" class="logo" />
    <button type="submit" name="submit" value="submit" class="btn">Create new form</button>
    <ol class="gradient-list">
      <?php while ($row = $data->fetch_assoc()) { ?>
        <li>
          <!-- TODO: redirect to form, using form id -->
          <a href="index.php"> <?php echo $row['title']; ?></a>
        </li>
      <?php } ?>
    </ol>
  </main>
</body>

</html>