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
  <title>ADMIN PAGE</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <!--<link rel="stylesheet" href="">-->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="./js/admin.js"></script>
</head>
<body>

  <div class="containerfluid">
  <h1>ADMIN FORM</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
      <p> Welcome <?= htmlspecialchars($user["fullname"]) ?></p>
      <a href="./php/logout.php">Logout</a>
  
      <br><br>
      <button id="ButtonHome">Home</button>
      <button id="ButtonOutStock">Out of Stocks</button>
      <button id="ButtonTodayTransac">Today's Transaction</button>
      <button id="ButtonLibrarianAcc">Librarian Accounts</button>



      <div class="containerfluid" id="Home">
        <div class="containerfluid" id="Inventory"> <!--style="display: none;"-->
          <h1>Books List</h1> <!--style="text-align: right;"-->
          <?php
            $sql = "SELECT * FROM books WHERE quantity >= 0";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Book ID</th><th>Title</th><th>Description</th><th>Quantity</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["bookID"] . "</td>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                    $quantity = $row["quantity"];
                    echo "<td>" . ($quantity == 0 ? "Out of Stock" : $quantity) . "</td>";
                }
                echo "</table>";
            } else {
                echo "No records found";
            }
          ?>
        </div>

        <div class="containerfluid" id="Accounts">
          <h1>Accounts List</h1> 
          <?php
            $sql = "SELECT * FROM users WHERE role != 'Admin'";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>User ID</th><th>Full Name</th><th>Email</th><th>Role</th><th>Change Role</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["userID"] . "</td>";
                    echo "<td>" . $row["fullname"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["role"] . "</td>";
                    echo "<td><button onclick='editRole(" . $row["userID"] . ", \"" . $row["email"] . "\")'>Edit</button></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No records found";
            }
          ?>
        </div>

        <div class="containerfluid" id="Transactions">
          <h1>Transactions</h1>
          <?php
        
          $sql = "SELECT * FROM book_transactions";
          $result = $con->query($sql);

          if ($result->num_rows > 0) {
              echo "<table>";
              echo "<tr><th>Transaction ID</th><th>Librarian ID</th><th>Book ID</th><th>Transaction Type</th><th>Transaction Quantity</th><th> Timestamp</th></tr>";
              while ($row = $result->fetch_assoc()) {
                
                  echo "<tr>";
                  echo "<td>" . $row["transactionID"] . "</td>";
                  echo "<td>" . $row["userID"] . "</td>";
                  echo "<td>" . $row["bookID"] . "</td>";
                  $transactionType = ($row["inQuantity"] > 0) ? "In" : "Out";
                  echo "<td>" . $transactionType . "</td>";

                  if ($transactionType === "In") {
                    echo "<td>" . $row["inQuantity"] . "</td>";
                  } else {
                    echo "<td>" . $row["outQuantity"] . "</td>";
                  }

                  echo "<td>" . $row["date"] . "</td>";
              }
              echo "</table>";
          } else {
              echo "No records found";
          }
          ?>
        </div>

      </div>



      <div class="containerfluid" id="Sort">
        <div class="containerfluid" id="OutStocks" style="display: none;">
          <h1>Out of Stocks</h1> <!--style="text-align: right;"-->
          <?php
            $sql = "SELECT * FROM books WHERE quantity <= 0";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Book ID</th><th>Title</th><th>Description</th><th>Quantity</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["bookID"] . "</td>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                    $quantity = $row["quantity"];
                    echo "<td>" . ($quantity == 0 ? "Out of Stock" : $quantity) . "</td>";
                }
                echo "</table>";
            } else {
                echo "No records found";
            }
          ?>
        </div>

        <div class="containerfluid" id="TodayTransactions" style="display: none;">
          <h1>Today's Transactions</h1>
          <?php
          
          $today = date('Y-m-d');
          $sql = "SELECT * FROM book_transactions WHERE DATE(date) = '$today'";
          $result = $con->query($sql);

          if ($result->num_rows > 0) {
              echo "<table>";
              echo "<tr><th>Transaction ID</th><th>Librarian ID</th><th>Book ID</th><th>Transaction Type</th><th>Transaction Quantity</th><th> Timestamp</th></tr>";
              while ($row = $result->fetch_assoc()) {
                
                  echo "<tr>";
                  echo "<td>" . $row["transactionID"] . "</td>";
                  echo "<td>" . $row["userID"] . "</td>";
                  echo "<td>" . $row["bookID"] . "</td>";
                  $transactionType = ($row["inQuantity"] > 0) ? "In" : "Out";
                  echo "<td>" . $transactionType . "</td>";

                  if ($transactionType === "In") {
                    echo "<td>" . $row["inQuantity"] . "</td>";
                  } else {
                    echo "<td>" . $row["outQuantity"] . "</td>";
                  }

                  echo "<td>" . $row["date"] . "</td>";
              }
              echo "</table>";
          } else {
              echo "No records found";
          }
          ?>
        </div>

        <div class="containerfluid" id="LibrarianAcc" style="display: none;">
          <h1>Librarian Accounts</h1> 
          <?php
            $sql = "SELECT * FROM users WHERE role = 'Librarian'";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>User ID</th><th>Full Name</th><th>Email</th><th>Role</th><th>Change Role</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["userID"] . "</td>";
                    echo "<td>" . $row["fullname"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["role"] . "</td>";
                    echo "<td><button onclick='editRole(" . $row["userID"] . ", \"" . $row["email"] . "\")'>Edit</button></td>";
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
      <a href="adminlogin.html">Log In Account</a>
    <?php endif; ?>

  </div>




  <script>
    function refreshPage() {
      location.reload();
    }

    $(document).ready(function () {
        $("#ButtonHome").click(function () {
          refreshPage();


        });

        $("#ButtonOutStock").click(function () {
          $("#OutStocks").show();


          $("#Home").hide();
          $("#TodayTransactions").hide();
          $("#LibrarianAcc").hide();

        });

        $("#ButtonTodayTransac").click(function () {
          $("#TodayTransactions").show();
          
          $("#Home").hide();
          $("#LibrarianAcc").hide();
          $("#OutStocks").hide();
        });

        $("#ButtonLibrarianAcc").click(function () {
          $("#LibrarianAcc").show();


          $("#Home").hide();
          $("#TodayTransactions").hide();
          $("#OutStocks").hide();
        });

    });
  </script>

</body>
</html>