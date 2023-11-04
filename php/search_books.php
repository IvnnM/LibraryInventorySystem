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
    } else {
        echo "<tr><td colspan='4'>No records found</td></tr>";
    }
} else {
    echo "<tr><td colspan='4'>Enter a search term</td></tr>";
}
?>
