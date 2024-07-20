<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warning Heat Index</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/d05e3fe0a9.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <?php 
        $current_page = 'status';
        include 'server.php';
        include 'nav.php';

        // SQL query to join tbl_user and tbl_esp32_status
        $sql = "
            SELECT 
                u.name, 
                e.temp, 
                e.humidity, 
                e.heat_index, 
                e.status_name
            FROM 
                tbl_user u
            JOIN 
                tbl_esp32_status  e
            ON 
                u.id = e.user_id
        ";

        $result = $conn->query($sql);
    ?>

    <div class="content mt-5">
        <div class="table">
            <table class="menu-table">
                <thead>
                    <th>ชื่อผู้ใช้</th>
                    <th>อุณหภูมิ</th>
                    <th>ความชื้นสัมพัทธ์</th>
                    <th>ค่าดัชนิความร้อนในตัว</th>
                    <th>สถานะ</th>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['temp']); ?></td>
                            <td><?php echo htmlspecialchars($row['humidity']); ?></td>
                            <td><?php echo htmlspecialchars($row['heat_index']); ?></td>
                            <td><?php echo htmlspecialchars($row['status_name']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="main.js"></script>
</body>
</html>