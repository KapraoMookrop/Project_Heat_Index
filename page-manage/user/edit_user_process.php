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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include '../../server.php'; // เชื่อมต่อฐานข้อมูล
            $user_id = $_POST['user_id'];
            $new_Name = $_POST['name'];
            $new_Tel = $_POST['tel'];

            $sql_update_user = "UPDATE tbl_user 
                                SET name = '$new_Name',
                                    tel = '$new_Tel'
                                WHERE id = '$user_id';";
            $result = $conn->query($sql_update_user);

            if ($sql_update_user == True) {
                echo "<script>";
                echo "$(document).ready(function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'แก้ไขข้อมูลสำเร็จ',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            if (result.isDismissed) {
                                window.location.href = 'dashboard.php';
                            }
                        });
                    })";    
                echo "</script>";
            }else{
                $msgErr = json_encode($conn->error);
                echo "<script>";
                echo "$(document).ready(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล',
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
        $conn->close();
        }else{
            echo "ไม่มีการส่งข้้อมูลมา";
        }
        
    ?>
</body>
</html>