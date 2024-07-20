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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include '../../server.php'; // เชื่อมต่อฐานข้อมูล
            $esp_id = $_POST['editEsp32ID'];
            $user_id = $_POST['user_id'];
            
            // ตรวจสอบว่าผู้ใช้ที่เล
            $check_esp_sql = "SELECT * FROM tbl_esp32_status WHERE user_id = '$user_id'";
            $check_esp_result = $conn->query($check_esp_sql);
            $row = $check_esp_result->fetch_assoc();
            if ($check_esp_result->num_rows > 0) {
                echo "<script>";
                    echo "$(document).ready(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'ไม่สามารถมอบบอร์ดให้บุคคลนี้ได้',
                                text: 'เนื่องจากบุคคลนี้เป็นเจ้าของบอร์ดอยู่ด้วย',
                                showConfirmButton: false,
                                timer: 5000
                            }).then((result) => {
                                if (result.isDismissed) {
                                    window.location.href = 'javascript:history.back(1)';
                                }
                            });
                        })";    
                    echo "</script>";
            }else{
                // ปิดการตรวจสอบ foreign key
                $conn->query("SET FOREIGN_KEY_CHECKS=0;");

                // อัพเดทค่า user_id
                $sql_update = "UPDATE tbl_esp32_status 
                            SET user_id = '$user_id'
                            WHERE esp_id = '$esp_id';";
                $result = $conn->query($sql_update);

                // เปิดการตรวจสอบ foreign key ใหม่
                $conn->query("SET FOREIGN_KEY_CHECKS=1;");

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