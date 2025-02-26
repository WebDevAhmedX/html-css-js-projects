<?php
// Database Details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zone";
// connecttion setup
$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $city = $_POST['city'];
    $zone = $_POST['zone'];
    $area = $_POST['area'];

    $stmt = $conn->prepare("INSERT INTO zones (city, zone, area) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $city, $zone, $area);
    $stmt->execute();
    $stmt->close();
}

// Update Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $city = $_POST['city'];
    $zone = $_POST['zone'];
    $area = $_POST['area'];

    $stmt = $conn->prepare("UPDATE zones SET city=?, zone=?, area=? WHERE id=?");
    $stmt->bind_param("sssi", $city, $zone, $area, $id);
    $stmt->execute();
    $stmt->close();
}

// Delete Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM zones WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Zone Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-container {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }
        button {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Zone Setup</h2>
        <form method="POST" id="zoneForm">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <label>City:</label>
                <input type="text" name="city" id="city" required>
            </div>
            <div class="form-group">
                <label>Zone:</label>
                <input type="text" name="zone" id="zone" required>
            </div>
            <div class="form-group">
                <label>Area:</label>
                <input type="text" name="area" id="area" required>
            </div>
            <button type="submit" name="add">Add Zone</button>
            <button type="submit" name="update">Update</button>
        </form>
    </div>

    <h3>Existing Zones</h3>
    <table>
        <tr>
            <th>City</th>
            <th>Zone</th>
            <th>Area</th>
            <th>Actions</th>
        </tr>
        <?php
        // Fetch Data
        $result = $conn->query("SELECT * FROM zones");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["city"]."</td>
                        <td>".$row["zone"]."</td>
                        <td>".$row["area"]."</td>
                        <td>
                            <button onclick='editZone(".$row["id"].", \"".$row["city"]."\", \"".$row["zone"]."\", \"".$row["area"]."\")'>Edit</button>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='".$row["id"]."'>
                                <button type='submit' name='delete' style='background:red;'>Delete</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No zones found</td></tr>";
        }
        ?>
    </table>

    <script>
        function editZone(id, city, zone, area) {
            document.getElementById('id').value = id;
            document.getElementById('city').value = city;
            document.getElementById('zone').value = zone;
            document.getElementById('area').value = area;
        }
    </script>
</body>
</html>
