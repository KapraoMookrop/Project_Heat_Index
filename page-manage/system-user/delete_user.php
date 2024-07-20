<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Project_Journal/css/style.css">
</head>
<body>
    <?php
        session_start();
        include '../../server.php';

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $id = $_GET['id'];

            // ตรวจสอบค่า $id และดำเนินการลบข้อมูล
            $delete_sql = "DELETE FROM tbl_system_user WHERE id = $id";

            if ($conn->query($delete_sql) === TRUE) {
                echo "<script>";
                echo "$(document).ready(function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'ลบข้อมูลเรียบร้อยแล้ว',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            if (result.isDismissed) {
                                window.location.href = 'dashboard.php';
                            }
                        });
                    })";    
                echo "</script>";
            } else {
                $msgErr = json_encode($conn->error);
                echo "<script>";
                echo "$(document).ready(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาดในการลบข้อมูล',
                            text: $msgErr,
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            if (result.isDismissed) {
                                window.location.href = 'dashboard.php';
                            }
                        });
                    })";    
                echo "</script>";
            }
        }
        ?>
</body>
</html>