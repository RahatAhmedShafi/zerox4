<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your MySQL password
$database = "projmr1"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all services with their department names
$sql = "SELECT 
            s.service_id, 
            s.service_name, 
            s.description, 
            s.cost, 
            d.department_name 
        FROM services s
        LEFT JOIN departments d ON s.department_id = d.department_id";

$result = $conn->query($sql);

// Fetch department-wise service count for stats
$stats_sql = "SELECT 
                d.department_name, 
                COUNT(s.service_id) as service_count 
              FROM services s
              LEFT JOIN departments d ON s.department_id = d.department_id
              GROUP BY d.department_name";
$stats_result = $conn->query($stats_sql);

// Prepare department statistics
$department_stats = [];
while ($row = $stats_result->fetch_assoc()) {
    $department_stats[] = $row;
}

$total_services = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Management Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .card {
            border-radius: 10px;
            background: #ffffff;
        }
        .table {
            background-color: #ffffff;
        }
        .container {
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <div class="container">

        <!-- Header -->
        <div class="row bg-primary text-white py-3">
            <div class="col-md-6">
                <h1 class="ms-3">Service Dashboard</h1>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="row mt-4 text-center">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Total Services</h5>
                        <h2><?= $total_services ?></h2>
                    </div>
                </div>
            </div>

            <!-- <?php foreach ($department_stats as $stat): ?>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($stat['department_name']) ?></h5>
                        <h2><?= $stat['service_count'] ?></h2>
                    </div>
                </div>
            </div>
            <?php endforeach; ?> -->
        </div>

        <!-- Service List Table -->
        <div class="row mt-4">
            <div class="col-12">
                <h3>Service List</h3>
                <table class="table table-striped table-bordered shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Service ID</th>
                            <th>Service Name</th>
                            <th>Description</th>
                            <th>Cost</th>
                            <th>Chack Out</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['service_id']) ?></td>
                                    <td><?= htmlspecialchars($row['service_name']) ?></td>
                                    <td><?= htmlspecialchars($row['description']) ?></td>
                                    <td>$<?= number_format($row['cost'], 2) ?></td>
                                    <td><a href="customer_input.php?service_id=<?= $row['service_id'] ?>" class="btn btn-success">Pay</a></td>

                                
                                
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No services found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
