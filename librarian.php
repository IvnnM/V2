<?php
  session_start();
  include './php/connection.php';
  if (isset($_SESSION["user_id"])){

    $sql = "SELECT * FROM users
    WHERE userID = {$_SESSION["user_id"]}";

    $result = $con->query($sql);
    $user = $result->fetch_assoc();
  }

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LIBRARIAN PAGE</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="./css/librarian.css">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="./js/librarian.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="container-fluid" id="headerCont">

  <?php if (isset($_SESSION['user_id'])): ?>

  <div class="row">
    <div class="col">
      <h3><?= htmlspecialchars($user["fullname"]) ?></h3>
    </div>
    <div class="col d-flex justify-content-end">
      <button id="logoutBtn" type="button" class="btn btn-danger text-nowrap" onclick="location.href='./php/logout.php'">
        <i class="fa fa-sign-out"></i> Log Out
      </button>
    </div>
  </div>

</div>
  <div class="container-fluid">

    <h4>Inventory</h4>
    <div class="container-fluid overflow-y-scroll rounded bg-dark p-4" id="InventoryTbl">
      <?php
        $sql = "SELECT * FROM books";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {

            echo "<table class='table table-dark table-hover'>";
            echo "<tr><th>Book ID</th><th>Title</th><th>Description</th><th>Quantity</th><th></th><th></th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["bookID"] . "</td>";
                echo "<td>" . $row["title"] . "</td>";
                echo "<td>" . $row["description"] . "</td>";
                $quantity = $row["quantity"];
                echo "<td>" . ($quantity == 0 ? "Out of Stock" : $quantity) . "</td>";
                echo "<td><button class='btn btn-outline-success' id='addBtn' onclick='handleInventory(\"IN\", {$row["bookID"]})'>IN</button></td>";
                
                if ($quantity == 0) {
                  echo "<td><button class='btn btn-outline-danger' onclick='handleInventory(\"OUT\", {$row["bookID"]})' disabled>OUT</button></td>";
              } else {
                  echo "<td><button class='btn btn-outline-danger' id='subtractBtn' onclick='handleInventory(\"OUT\", {$row["bookID"]})'>OUT</button></td>";
              }
              echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No records found";
            
        }
      ?>
    </div>


  </div>
  <?php else: ?>
    <button id="loginBtn" type="button" class="btn btn-danger" onclick="location.href='librarianlogin.html'">
      <i class="fa fa-lock"></i> Login
    </button>
  <?php endif; ?>
</body>
</html>