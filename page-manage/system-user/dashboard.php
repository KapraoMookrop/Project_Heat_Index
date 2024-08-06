<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d05e3fe0a9.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/Project_Journal/css/style.css">
    <link rel="icon" type="png" href="../../img/logo.png">
</head>

<body>
    <?php
        include "../../server.php";

        if (empty($_SESSION['username'])){
            echo "<script>";
            echo "$(document).ready(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'คุณไม่ได้รับอนุญาติให้เข้าหน้านี้',
                        text: 'กรุณาล็อคอินก่อน', 
                        showConfirmButton: false,
                        timer: 3000
                    }).then((result) => {
                        if (result.isDismissed) {
                            window.location.href = '../../index.php';
                        }
                    });
                })";
            echo "</script>";
        }else{
        // ตรวจสอบว่าผู้ใช้ได้กรอกคำค้นหาหรือไม่
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // ถ้าผู้ใช้กรอกคำค้นหา ให้สร้างคำสั่ง SQL สำหรับค้นหา
        if (!empty($search)) {
            $sql = "SELECT * FROM tbl_system_user WHERE username LIKE '%$search%'";
        } else {
            $sql = "SELECT * FROM tbl_system_user";
        }

        $result = $conn->query($sql);
    ?>
    <div class="container">
        <?php include "../../page_master/nav.php"; ?>
        <div class="row gx-1 mt-4 mb-2 align-items-center">
            <h2 class="col-md-7">ข้อมูลผู้ดูแลระบบ</h2>
            <div class="col-10 col-md-4">
                <form method="GET" action="">
                    <div class="input-group border border-1 rounded-2">
                        <input id="searchInput" name="search" class="form-control border-0" type="search" placeholder="ค้นหา" value="<?php echo htmlspecialchars($search); ?>">
                        <span class="input-group-append">
                            <button class="btn btn-outline-secondary border-0 bg-light-subtle" type="submit">
                                <i class="fa fa-search text-dark"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="col-2 col-md-1">
                <button class="btn btn-outline-success w-100 p-1 p-lg-2" type="button" data-bs-toggle="modal" data-bs-target="#registerModal">
                    <i class="fa-solid fa-user-plus"></i>
                    <span class="d-none d-lg-inline">เพิ่ม</span>
                </button>
            </div>
        </div>
        <div class="container">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr class="row">
                        <th class="col-2">ชื่อผู้ใช้</th>
                        <th class="col-4">อีเมล</th>
                        <th class="col-3">เบอร์โทร</th>
                        <th class="col-3">เมนู</th>
                    </tr>
                </thead>
                <tbody id="dataTable">
                    <?php
                        while ($row = $result->fetch_assoc()): 
                    ?>
                    <tr class="row">
                        <td class="col-2 text-break"><?php echo $row['username'] ?></td>
                        <td class="col-4 text-break"><?php echo $row['email'] ?></td>
                        <td class="col-3 text-break"><?php echo $row['tel'] ?></td>
                        <td class="col-3">
                            <div class="row gx-2 text-center">
                                <div class="col-lg-6 m-1 m-lg-0">
                                    <button type="button" class="btn btn-outline-warning w-100 edit-btn" data-bs-toggle="modal" data-bs-target="#editModal"
                                            data-id="<?php echo $row['id']; ?>"
                                            data-username="<?php echo $row['username']; ?>"
                                            data-email="<?php echo $row['email']; ?>"
                                            data-tel="<?php echo $row['tel']; ?>">
                                        <i class="fa-solid fa-user-pen"></i>
                                        <span>แก้ไข</span>
                                    </button>
                                </div>
                                <div class="col-lg-6 m-1 m-lg-0">
                                    <button type="submit" class="btn btn-outline-danger btn_d w-100" data-id="<?php echo $row['id']; ?>">
                                        <i class="fa-solid fa-trash"></i>
                                        <span>ลบ</span>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                        if ($result->num_rows == 0){
                            echo "<tr class='row'><td colspan='4' class='text-center'>ไม่พบข้อมูล</td></tr>";
                        }
                    ?>
                    
                </tbody>
            </table>
        </div>
    </div>

    <!-- Start Edit User Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editForm" action="edit_user_process.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">แก้ไขผู้ใช้</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="editUserId">
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">ชื่อผู้ใช้</label>
                            <input type="text" class="form-control" id="editUsername" name="username">
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">อีเมล</label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="editTel" class="form-label">เบอร์โทร</label>
                            <input type="number" class="form-control" id="editTel" name="tel">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Edit User Modal -->
    
    <!--Start Insert User -->
        <div class="modal fade" tabindex="-1" id="registerModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-box-arrow-in-right me-2"></i>Register</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3 needs-validation" action="../../log-reg_process/reg_process.php" method="post" novalidate>
                            <div class="col-12">
                                <label for="registerUsername" class="form-label">Username</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text text-secondary" id="registerGroupPrepend"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" class="form-control" id="registerUsername" aria-describedby="registerGroupPrepend" name="username" required>
                                    <div class="invalid-feedback">
                                        Invalid username.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="registerTel" class="form-label">Tel.</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text text-secondary" id="registerGroupPrepend"><i class="fa-solid fa-phone"></i></span>
                                    <input type="number" class="form-control" id="registerTel" aria-describedby="registerGroupPrepend" name="tel" required>
                                    <div class="invalid-feedback tel-custom">
                                        Invalid phone number.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="registerEmail" class="form-label">Email</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text text-secondary" id="registerGroupPrepend"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="registerEmail" aria-describedby="registerGroupPrepend" name="email" required>
                                    <div class="invalid-feedback">
                                        Invalid email.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="registerPassword" class="form-label">Password</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text text-secondary" id="registerGroupPrepend"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" class="form-control" id="registerPassword" aria-describedby="registerGroupPrepend" name="password" required>
                                    <div class="invalid-feedback">
                                        Invalid password.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="registerConfirmPassword" class="form-label">Confirm Password</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text text-secondary" id="registerGroupPrepend"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" class="form-control" id="registerConfirmPassword" aria-describedby="registerGroupPrepend" name="cf_password" required>
                                    <div class="invalid-feedback">
                                        Password not match
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Register</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <!-- End Insert User -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            //แทนค่าที่จะแก้ไขใน modal
            const editButtons = document.querySelectorAll(".edit-btn");

            editButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const userId = button.getAttribute("data-id");
                    const username = button.getAttribute("data-username");
                    const email = button.getAttribute("data-email");
                    const tel = button.getAttribute("data-tel");

                    document.getElementById("editUserId").value = userId;
                    document.getElementById("editUsername").value = username;
                    document.getElementById("editEmail").value = email;
                    document.getElementById("editTel").value = tel;
                });
            });
            //ยืนยันการลบ
            const deleteButtons = document.querySelectorAll(".btn_d");
            deleteButtons.forEach(function(button) {
                button.addEventListener("click", function(event) {
                    event.preventDefault();

                    const deleteId = button.dataset.id;
                    console.log(button.dataset.id);
                    Swal.fire({
                        title: 'ยืนยันการลบข้อมูลหรือไม่?',
                        showDenyButton: false,
                        showCancelButton: true,
                        confirmButtonText: 'ยืนยัน',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'กรุณายืนยันอีกครั้ง',
                                text: 'เมื่อลบข้อมูลแล้วจะไม่สามารถกู้คืนได้',
                                showCancelButton: true,
                                confirmButtonText: 'ยืนยันการลบ',
                                cancelButtonText: 'ยกเลิก'
                            }).then((confirmResult) => {
                                if (confirmResult.isConfirmed) {
                                    window.location.href = 'delete_user.php?id=' + deleteId; // ทำการลบเมื่อยืนยัน
                                }
                            });
                        }
                    });
                });
            });
        });

        (function () {
                'use strict'

                var forms = document.querySelectorAll('.needs-validation')
                Array.prototype.slice.call(forms).forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        var password = document.getElementById('registerPassword').value;
                        var confirmPassword = document.getElementById('registerConfirmPassword').value;
                        
                        if (password !== confirmPassword) {
                            event.preventDefault();
                            event.stopPropagation();
                            document.getElementById('registerConfirmPassword').setCustomValidity('Passwords do not match');
                        } else {
                            document.getElementById('registerConfirmPassword').setCustomValidity('');
                        }

                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false);

                    // ฟังก์ชันการตรวจสอบขณะพิมพ์
                    var passwordField = document.getElementById('registerPassword');
                    var confirmPasswordField = document.getElementById('registerConfirmPassword');

                    function validatePasswordMatch() {
                        var password = passwordField.value;
                        var confirmPassword = confirmPasswordField.value;

                        if (password !== confirmPassword) {
                            confirmPasswordField.setCustomValidity('Passwords do not match');
                        } else {
                            confirmPasswordField.setCustomValidity('');
                        }
                    }

                    passwordField.addEventListener('input', validatePasswordMatch);
                    confirmPasswordField.addEventListener('input', validatePasswordMatch);
                });
            })();
        
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php } ?>
</body>
</html>