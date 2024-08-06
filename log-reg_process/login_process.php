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
        
            if (!empty($username) && !empty($password)) {
                $sql = "SELECT password, username FROM tbl_system_user WHERE username = '$username'";
                $result = $conn->query($sql);
                if ($result === false) {
                    echo "Error in query: " . $conn->error;
                }
                if ($result->num_rows === 1) {
                    $row = $result->fetch_assoc();
                    $hashed_password = $row['password'];
        
                    if (password_verify($password, $hashed_password)) { // เปรียบเทียบรหัสผ่านที่ถูกเข้ารหัส
                        $_SESSION['username'] = $username;
                        echo "<script>";
                        echo "$(document).ready(function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'เข้าสู่ระบบสำเร็จ',
                                    text: 'ยินดีต้อนรับคุณ $username',
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
                                    title: 'รหัสผ่านไม่ถูกต้อง',
                                    text: 'โปรดป้อนรหัสให้ถูกต้องด้วยคุณ $username',
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
                                title: 'ไม่พบผู้ใช้งานที่ระบุ',
                                text: 'โปรดป้อนชื่อผู้ใช้งานให้ถูกต้อง',
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
                            title: 'กรอกข้อมูลไม่ครบ',
                            text: 'โปรดป้อข้อมูลให้ครบถ้วน',
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
        
            $conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล
        }else{
            echo "ไม่มีการส่งข้้อมูลมา";
        }
    ?>
</body>
</html>
