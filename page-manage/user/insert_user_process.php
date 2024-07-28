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

            $name = $_POST['name'];
            $tel = $_POST['tel'];

            // ตรวจสอบว่ามีชื่อผู้ใช้งานนี้แล้วหรือยัง
            $check_name_sql = "SELECT name FROM tbl_user WHERE name = '$name'";
            $check_name_result = $conn->query($check_name_sql);
            
            if ($check_name_result->num_rows > 0) {
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
            } else {
                if (!empty($username) && !empty($tel)) {
                    date_default_timezone_set("Asia/Bangkok");
                    $created_at = date('Y-m-d H:i:s') . " - ";
                    $sql = "INSERT INTO tbl_user (name, tel) VALUES ('$name', '$tel')";
                    if ($conn->query($sql) === TRUE) {
                        echo "<script>";
                        echo "$(document).ready(function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'สมัครสมาชิกเรียบร้อย',
                                    text: 'บันทึกข้อมูลของคุณลงในระบบแล้ว',
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
                        echo "<script>";
                        echo "$(document).ready(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ดูเหมือนจะมีบางอย่างผิดพลาด',
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
                } else {
                    echo "<script>";
                    echo "$(document).ready(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'ข้อมูลไม่ครบถ้วน',
                                text: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                                showConfirmButton: false,
                                timer: 2000
                            }).then((result) => {
                                if (result.isDismissed) {
                                    window.location.href = 'javascript:history.back(1)';
                                }
                            });
                        })";    
                    echo "</script>";
                }
            }

            $conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล
        }
    ?>
</body>