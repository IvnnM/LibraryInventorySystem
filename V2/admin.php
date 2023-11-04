<?php
session_start();
include './php/connection.php';

if (isset($_SESSION["user_id"])) {
    $sql = "SELECT * FROM users WHERE userID = {$_SESSION["user_id"]}";
    $result = $con->query($sql);
    $user = $result->fetch_assoc();
}

function getRecordCount($con, $table, $condition = "") {
    $query = "SELECT COUNT(*) AS total_records FROM $table $condition";
    $result = mysqli_query($con, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }
    $row = mysqli_fetch_assoc($result);
    return $row['total_records'];
}

$total_books = getRecordCount($con, "books");
$total_outstocks = getRecordCount($con, "books", "WHERE quantity <= 0");
$total_transactions = getRecordCount($con, "book_transactions");

$today = date('Y-m-d');
$total_todaytransactions = getRecordCount($con, "book_transactions", "WHERE DATE(date) = '$today'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ADMIN PAGE</title>
  <!--Bootstrap-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
  <!--Sweetalert-->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!--Icons-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">-->
  <link rel="stylesheet" href="./css/admin.css">
  <script src="./js/admin.js"></script>


</head>
<body>

  <div class="container-fluid">
      <div class="row" >
        <div class="col-md-3 col-lg-2 d-md-block d-flex align-items-center" style="padding: 10px;">
          <h1 class="text-center"><span class="fa fa-desktop">ADMIN</span></h1>
        </div>
        <div class="col-md-9 col-lg-10 d-md-block rounded-top" style="background-color: #eff2e3;">
          <h5 style="margin:0;">Dashboard</h5>
          <span style="font-size: small;">Today is <?php echo $today; ?></span>  
          <div class="col-md-9 col-lg-10 d-md-block rounded-top">
            <?php if (isset($_SESSION['user_id'])): ?>
          </div>    
        </div>
      </div>
  </div>
  <div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar" style="background-color: white;">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item1 text-start">
                        <a class="nav-link sidebar active" href="#dashboard">
                          <i class="fa fa-th-large icon-size"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item1 text-start">
                        <a class="nav-link sidebar" href="#report">
                          <i class="fa fa-folder icon-size"></i>Reports
                        </a>
                    </li>
                    <li class="nav-item1 text-start">
                        <a class="nav-link sidebar" href="#librarianTable">
                          <i class="fa fa-user-circle-o icon-size"></i>Librarian
                        </a>
                    </li>
                    <li class="nav-item1 text-start">
                        <a class="nav-link sidebar" href="#userTable">
                          <i class="fa fa-users icon-size"></i>User Accounts
                        </a>
                    </li>
                    <li class="nav-item1 text-start">
                        <a class="nav-link sidebar" href="./php/logout.php">
                          <i class="fa fa-sign-out icon-size"></i>Log Out
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 overflow-y-scroll p-4">
          <div class="container-fluid dashboard overflow-y-scroll rounded" id="dashboard" style="margin-bottom: 30px;">
            <div class="row dashboard">
              <div class="col" style="padding:50px;">
                <h2>Hi, <span><?= htmlspecialchars($user["fullname"]) ?></span></h2>
                <p>Welcome to the admin platform. This is where you can manage and control various aspects of the system</p>
              </div>
              <div class="col" style="padding:0;">
                <!--<img src="../V2/images/index-bg.jpg" alt="" style="height:200px; width:100%;">-->
              </div>
            </div>
          </div>
          <div class="container-fluid" style="padding: 0;">
            <div class="row">
              <h5>Overview</h5>
            </div>
          </div>
          <div class="container-fluid overview" id="report" style="padding: 0;">
            <ul class="nav">
              <li class="nav-item2">
                <a class="nav-link overview rounded" aria-current="page" href="#bookTable">
                  <span style="font-size: 50px; margin-right:5px;"><?php echo $total_books; ?></span> Total Books 
                </a>
              </li>
              <li class="nav-item2">
                <a class="nav-link overview rounded" aria-current="page" href="#OutStocksTable">
                  <span style="font-size: 50px; margin-right:5px;"><?php echo $total_outstocks; ?></span> Out of Stock Books
                </a>
              </li>
              <li class="nav-item2">
                <a class="nav-link overview rounded" aria-current="page" href="#AllTransactionsTable">
                  <span style="font-size: 50px; margin-right:5px;"><?php echo $total_transactions; ?></span>Total Transactions
                </a>
              </li>
              <li class="nav-item2">
                <a class="nav-link overview rounded" aria-current="page" href="#TodayTransactions">
                  <span style="font-size: 50px; margin-right:5px;"><?php echo $total_todaytransactions; ?></span>Today's Transactions
                </a>
              </li>
            </ul>
          </div>
          <div class="container-fluid table"  id="bookTable" style="padding: 0;">
            <h5>Books Lists</h5>
            <div class="container-fluid overflow-y-scroll rounded bg-dark p-4" style="height: 300px;">
              <?php
                $sql = "SELECT * FROM books WHERE quantity >= 0";
                $result = $con->query($sql);
                if ($result->num_rows > 0) {
                    echo '<table class="table table-dark table-hover">';
                    echo '
                    <thead>
                      <tr>
                        <th scope="col">Book ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Quantity</th>
                      </tr>
                    </thead>';
                  while ($row = $result->fetch_assoc()) {
                      echo '<tbody class="table-group-divider">';
                      echo "<tr>";
                      echo "<td>" . $row["bookID"] . "</td>";
                      echo "<td>" . $row["title"] . "</td>";
                      echo "<td>" . $row["description"] . "</td>";
                      $quantity = $row["quantity"];
                      echo "<td>" . ($quantity == 0 ? "Out of Stock" : $quantity) . "</td>";
                      echo '</tbody>';
                  }
                  echo "</table>";
                } else {
                    echo "No records found";
                }
              ?>
            </div>
          </div>

          <div class="container-fluid table" id="OutStocksTable" style="padding: 0;">
            <h5>Out of Stocks</h5>
            <div class="container-fluid overflow-y-scroll rounded bg-dark p-4" style="height: 300px;">
              <?php
                $sql = "SELECT * FROM books WHERE quantity <= 0"; 
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    echo '<table class="table table-dark table-hover">';
                    echo '
                    <thead>
                      <tr>
                        <th scope="col">Book ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Quantity</th>
                      </tr>
                    </thead>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tbody class="table-group-divider">';
                        echo "<tr>";
                        echo "<td>" . $row["bookID"] . "</td>";
                        echo "<td>" . $row["title"] . "</td>";
                        echo "<td>" . $row["description"] . "</td>";
                        $quantity = $row["quantity"];
                        echo "<td>" . ($quantity == 0 ? "Out of Stock" : $quantity) . "</td>";
                        echo '</tbody>';
                    }
                    echo "</table>";
                } else {
                    echo "No records found";
                }
              ?>
            </div>
          </div>
          <div class="container-fluid table" id="AllTransactionsTable" style="padding: 0;">
            <h5>Transactions</h5>
            <div class="container-fluid overflow-y-scroll rounded bg-dark p-4" style="height: 300px;">
              <?php
                $sql = "SELECT * FROM book_transactions";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    echo '<table class="table table-dark table-hover">';
                    echo '
                    <thead>
                      <tr>
                        <th scope="col">Transaction ID</th>
                        <th scope="col">Librarian ID</th>
                        <th scope="col">Book ID</th>
                        <th scope="col">Transaction Type</th>
                        <th scope="col">Transaction Quantity</th>
                        <th scope="col">Timestamp</th>
                      </tr>
                    </thead>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tbody class="table-group-divider">';
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
                        echo '</tbody>';
                    }
                    echo "</table>";
                } else {
                    echo "No records found";
                }
              ?>
            </div>
          </div>
          <div class="container-fluid table" id="TodayTransactions" style="padding: 0;">
            <h5>Today's Transactions</h5>
            <div class="container-fluid overflow-y-scroll rounded bg-dark p-4" style="height: 300px;">
              <?php
                $today = date('Y-m-d');
                $sql = "SELECT * FROM book_transactions WHERE DATE(date) = '$today'";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    echo '<table class="table table-dark table-hover">';
                    echo '
                    <thead>
                      <tr>
                        <th scope="col">Transaction ID</th>
                        <th scope="col">Librarian ID</th>
                        <th scope="col">Book ID</th>
                        <th scope="col">Transaction Type</th>
                        <th scope="col">Transaction Quantity</th>
                        <th scope="col">Timestamp</th>
                      </tr>
                    </thead>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tbody class="table-group-divider">';
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
                        echo "</tr>";
                        echo '</tbody>';
                    }
                    echo "</table>";
                } else {
                    echo "No records found";
                }
              ?>
            </div>
          </div>
          <div class="container-fluid table" id="librarianTable" style="padding: 0;">
            <h5>Librarian</h5>
            <div class="container-fluid overflow-y-scroll rounded bg-dark p-4" style="height: 300px;">
              <?php
                $sql = "SELECT * FROM users WHERE role = 'Librarian'";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    echo '<table class="table table-dark table-hover">';
                    echo '
                    <thead>
                      <tr>
                        <th scope="col">User ID</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                      </tr>
                    </thead>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tbody class="table-group-divider">';
                        echo "<tr>";
                        echo "<td>" . $row["userID"] . "</td>";
                        echo "<td>" . $row["fullname"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["role"] . "</td>";
                        echo "<td><button class='btn' id='editRoleBtn' onclick='editRole(" . $row["userID"] . ", \"" . $row["email"] . "\")'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button></td>";
                        echo "</tr>";
                        echo '</tbody>';
                    }
                    echo '</table>';
                } else {
                    echo "No records found";
                }
              ?>
            </div>
          </div>
          <div class="container-fluid table" id="userTable" style="padding: 0;">
            <h5>Accounts</h5>
            <div class="container-fluid overflow-y-scroll rounded bg-dark p-4" style="height: 300px;">
              <?php
                $sql = "SELECT * FROM users WHERE role != 'Admin'";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    echo '<table class="table table-dark table-hover">';
                    echo '
                    <thead>
                      <tr>
                        <th scope="col">User ID</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                      </tr>
                    </thead>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tbody class="table-group-divider">';
                        echo "<tr>";
                        echo "<td>" . $row["userID"] . "</td>";
                        echo "<td>" . $row["fullname"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["role"] . "</td>";
                        echo "<td><button class='btn' id='editRoleBtn' onclick='editRole(" . $row["userID"] . ", \"" . $row["email"] . "\")'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button></td>";
                        echo "</tr>";
                        echo '</tbody>';
                    }
                    echo "</table>";
                } else {  
                    echo "No records found";
                }
              ?>
            </div>
          </div>
        </main>
    </div>  
  </div>
<?php else: ?>
  <button id="loginBtn" type="button" class="btn btn-danger" onclick="location.href='adminlogin.html'">
    <i class="fa fa-lock"></i> Login
  </button>
<?php endif; ?>
<script>
  function refreshPage() {
    location.reload();
  }

  $(document).ready(function () {
      $("#Dashboard").click(function () {
        refreshPage();
      });
  });
</script>

<script>
$(document).ready(function() {
    $(".nav-link.sidebar").click(function() {
        $(".nav-link.sidebar").removeClass("active"); // Remove 'active' class from all nav items
        $(this).addClass("active"); // Add 'active' class to the clicked nav item
    });
});
$(document).ready(function() {
    $(".nav-link.overview").click(function() {
        $(".nav-link.overview").removeClass("active"); // Remove 'active' class from all nav items
        $(this).addClass("active"); // Add 'active' class to the clicked nav item
    });
});

</script>
</body>
</html>