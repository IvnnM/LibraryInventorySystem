<?php

session_start();
include './connection.php';
if (isset($_SESSION["user_id"])){

  $sql = "SELECT * FROM users
  WHERE userID = {$_SESSION["user_id"]}";

  $result = $con->query($sql);
  $user = $result->fetch_assoc();
}


if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM books WHERE quantity >= 0 AND (title LIKE '%$search%' OR description LIKE '%$search%' OR bookID LIKE '%$search%')";

    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        echo "<tr><th>User ID</th><th>Full Name</th><th>Email</th><th>Role</th><th></th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["bookID"] . "</td>";
            echo "<td>" . $row["title"] . "</td>";
            echo "<td>" . $row["description"] . "</td>";
            $quantity = $row["quantity"];
            echo "<td>" . ($quantity == 0 ? "Out of Stock" : $quantity) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No records found</td></tr>";
    }
} else {
    echo "<tr><td colspan='4'>Enter a search term</td></tr>";
}
?>
