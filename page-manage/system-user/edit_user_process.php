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
            $new_username = $_POST['username'];
            $new_email = $_POST['email'];
            $new_tel= $_POST['tel'];

            // ตรวจสอบว่ามีชื่อผู้ใช้งานนี้แล้วหรือยัง
            $check_username_sql = "SELECT username FROM tbl_system_user WHERE id = '$user_id'";
            $check_username_result = $conn->query($check_username_sql);
            $row = $check_username_result->fetch_assoc();
            if ($check_username_result->num_rows > 0 && $new_username != $row['username']) {
                echo "<script>";
                    echo "$(document).ready(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'มีชื่อผู้ใช้งานนี้แล้ว',
                                text: 'โปรดใช้ชื่ออื่น',
                                showConfirmButton: false,
                                timer: 2000
                            }).then((result) => {
                                if (result.isDismissed) {
                                    window.location.href = 'javascript:history.back(1)';
                                }
                            });
                        })";    
                    echo "</script>";
            }else{
                $sql = "UPDATE tbl_system_user SET 
                        username = '$new_username',
                        email = '$new_email',
                        tel = '$new_tel'
                    WHERE id = '$user_id'";
                $result = $conn->query($sql);

                if ($result === True) {
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
            }
        }else{
            echo "ไม่มีการส่งข้้อมูลมา";
        }
        
    ?>
</body>
</html>