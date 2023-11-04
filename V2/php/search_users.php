<?php
session_start();
include './connection.php';

if (isset($_SESSION["user_id"])) {
  $user_id = $_SESSION["user_id"];

  if (isset($_GET['search'])) {
    $search = $_GET['search'];

    // Build the SQL query to search for users based on name or email
    $sql = "SELECT * FROM users WHERE role != 'Admin' AND (fullname LIKE '%$search%' OR email LIKE '%$search%' OR userID LIKE '%$search%')";

    $result = $con->query($sql);

    if ($result->num_rows > 0) {
      echo "<tr><th>User ID</th><th>Full Name</th><th>Email</th><th>Role</th><th></th></tr>";
      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["userID"] . "</td>";
        echo "<td>" . $row["fullname"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["role"] . "</td>";
        echo "<td><button class='btn btn-outline-success' id='editRoleBtn' onclick='editRole(" . $row["userID"] . ", \"" . $row["email"] . "\")'><i class='fa fa-pencil' aria-hidden='true'></i></button></td>";
        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='4'>No records found</td></tr>";
    }
  } else {
    // Handle the case where no search term is provided
    echo "<tr><td colspan='4'>Enter a search term</td></tr>";
  }
}
?>
