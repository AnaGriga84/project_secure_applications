<?php
    require_once("pageLogic/logs.php");
?>

<html>
<head>
    <title>Logs</title>
    <link rel="stylesheet" href="static/css/logs.css">
</head>
<body>
    <table class = "styled-table">
        <thead>
            <tr>
                <th>ID</td>
                <th>Action</td>
                <th>IP Address</td>
                <th>DateTime</td>
                <th>Outcome</td>
            </tr>
        </thead>
        <tbody>
            <tr class = "active-row">
            <?php
                while($row = $results->fetch_assoc())
                {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['action'] . "</td>";
                    echo "<td>" . $row['ipAddress'] . "</td>";
                    echo "<td>" . $row['datetime'] . "</td>";
                    echo "<td>" . $row['outcome'] . "</td>";
                    echo "</tr>";
                }
            ?>
            </tr>
        </tbody>
    </table>
</body>
</html>