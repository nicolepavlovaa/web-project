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

<script type="text/javascript">
  function openForm(id) {
    location.href = `form.php?form_id=${id}`;
  }
</script>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Home</title>
  <link href="./home.css" rel="stylesheet" />
</head>

<body class="page">
  <main>
    <a href="home.php" class="logo">
      <img src="./logo.png" />
    </a>
    <a href="index.php" class="link">
      <button type="submit" class="btn">Create new form</button>
    </a>
    <ol id="forms" class="gradient-list">
      <?php
      $i = 0;
      while ($row = $data->fetch_assoc()) {
        $title = $row['title'];
        $id = $row['id'];
        echo "<li onclick='openForm($id);' id=$i>$title</li>";
        $i++;
      } ?>
    </ol>
  </main>
</body>

</html>