<head>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400&display=swap" rel="stylesheet">
    <style>
        *{
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>

<?php
session_start();

$user_logout = $_SESSION['username'];

echo "<script>";
echo "$(document).ready(function() {
    Swal.fire({
        icon: 'success',
        title: 'ออกกจากระบบแล้ว',
        showConfirmButton: false,
        timer: 2000
    }).then((result) => {
        if (result.isDismissed) {
            window.location.href = 'index.php';
        }
    });
})";
echo "</script>";
echo "</script>";

session_destroy();
exit();
?>
