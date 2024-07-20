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
            include '../server.php'; // เชื่อมต่อฐานข้อมูล

            $username = $_POST['username'];
            $password = $_POST['password'];
            $cf_password = $_POST['cf_password'];
            $tel = $_POST['tel'];
            $email = $_POST['email'];

            // ตรวจสอบว่ามีชื่อผู้ใช้งานนี้แล้วหรือยัง
            $check_username_sql = "SELECT username FROM tbl_system_user WHERE username = '$username'";
            $check_username_result = $conn->query($check_username_sql);
            
            if ($check_username_result->num_rows > 0) {
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
                if (!empty($username) && !empty($password) && !empty($cf_password) && !empty($tel) && !empty($email)) {
                    if ($password === $cf_password) {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน
                        date_default_timezone_set("Asia/Bangkok");
                        $created_at = date('Y-m-d H:i:s') . " - ";
                        $sql = "INSERT INTO tbl_system_user (username, password, tel, email, created_at) VALUES ('$username', '$hashed_password', '$tel', '$email', '$created_at')";
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
                                            window.location.href = '../index.php';
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
                                            window.location.href = '../index.php';
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
                                    title: 'รหัสผ่านไม่ตรงกัน',
                                    text: 'โปรดตรวจสอบรหัสให้ตรงกัน',
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