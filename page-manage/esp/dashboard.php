<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/d05e3fe0a9.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="/Project_Journal/css/style.css">
</head>

<body>
    <?php
        include "../../server.php";
        // ตรวจสอบว่าผู้ใช้ได้กรอกคำค้นหาหรือไม่
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // ถ้าผู้ใช้กรอกคำค้นหา ให้สร้างคำสั่ง SQL สำหรับค้นหา
        if (!empty($search)) {
            $sql = "SELECT 
                        u.name,
                        e.esp_id, 
                        e.temp, 
                        e.humidity, 
                        e.heat_index, 
                        e.status_name,
                        e.last_updated
                    FROM 
                        tbl_esp32_status e
                    LEFT JOIN 
                        tbl_user u
                    ON 
                        u.id = e.user_id
                    WHERE
                        u.name = '$search'
                    ORDER BY
                        e.user_id";
        } else {
            $sql = "SELECT 
                        u.name,
                        e.esp_id, 
                        e.temp, 
                        e.humidity, 
                        e.heat_index, 
                        e.status_name,
                        e.last_updated
                    FROM 
                        tbl_esp32_status e
                    LEFT JOIN 
                        tbl_user u
                    ON 
                        u.id = e.user_id
                    ORDER BY
                        e.user_id ASC, e.heat_index DESC";

        }

        $result = $conn->query($sql);
    ?>

    <div class="container">
        <?php include "../../page_master/nav.php"; ?>
        <div class="row gx-1 mt-4 mb-2 align-items-center">
            <h2 class="col-md-9">ข้อมูลบอร์ด ESP</h2>
            <div class="col-md-3">
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
            <!-- <div class="col-2 col-md-1">
                <button class="btn btn-outline-success w-100 p-1 p-lg-2" type="button" data-bs-toggle="modal" data-bs-target="#registerModal">
                    <i class="fa-solid fa-user-plus"></i>
                    <span class="d-none d-lg-inline">เพิ่ม</span>
                </button>
            </div> -->
        </div>
        <div class="container">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr class="row">
                        <th class="col-2 col-lg-3 text-break">ชื่อผู้ใส่</th>
                        <th class="col-1 col-lg-1 text-break">อุณหภูมิ</th>
                        <th class="col-1 col-lg-1 text-break">ความชื้นสัมพัทธ์</th>
                        <th class="col-2 col-lg-1 text-break">ค่าดัชนิความร้อน</th>
                        <th class="col-2 col-lg-1 text-break">สถานะ</th>
                        <th class="col-2 col-lg-2 text-break">อัพเดทล่าสุด</th>
                        <th class="col-2 col-lg-3 text-break">เมนู</th>
                    </tr>
                </thead>
                <tbody id="dataTable">
                    <?php
                        while ($row = $result->fetch_assoc()): 
                    ?>
                    <tr class="row">
                        <td class="col-2 col-lg-3 text-break"><?php echo $row['name'] ?></td>
                        <td class="col-1 col-lg-1 text-break"><?php echo $row['temp'] ?></td>
                        <td class="col-1 col-lg-1 text-break"><?php echo $row['humidity'] ?></td>
                        <td class="col-2 col-lg-1 text-break"><?php echo $row['heat_index'] ?></td>
                        <td class="col-2 col-lg-1 text-break"><?php echo $row['status_name'] ?></td>
                        <td class="col-2 col-lg-2 text-break"><?php echo $row['last_updated'] ?></td>
                        <td class="col-2 col-lg-3">
                            <div class="row gx-2 text-center">
                                <div class="col-lg-6 m-1 m-lg-0">
                                    <button type="button" class="btn btn-outline-warning w-100 edit-btn" data-bs-toggle="modal" data-bs-target="#editModal"
                                            data-esp32ID="<?php echo $row['esp_id']; ?>"
                                            data-username="<?php echo $row['name']; ?>">
                                        <i class="fa-solid fa-user-pen"></i>
                                        <span>แก้ไข</span>
                                    </button>
                                </div>
                                <div class="col-lg-6 m-1 m-lg-0">
                                    <button type="submit" class="btn btn-outline-danger btn_d w-100" data-id="<?php echo $row['esp_id']; ?>">
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

    <!-- Start Edit ESP Modal -->
     <?php
        $sql_list_user = "SELECT * FROM tbl_user";
        $list_user = $conn->query($sql_list_user);
     ?>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editForm" action="edit_esp_process.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">แก้ไขเจ้าของ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editEsp32ID" class="form-label">ESP32 ID</label>
                            <input type="hidden" class="form-control" id="editEsp32ID" name="editEsp32ID" >
                            <input type="text" class="form-control" id="displayEsp32ID" name="displayEsp32ID" disabled>
                        </div>
                        <div class="">
                            <label class="form-label">เลือกเจ้าของ</label>
                            <select class="form-select w-100" name="user_id" required>
                                <option selected disabled value="">Choose...</option>
                                <option value="0">ล้างชื่อเจ้าของ</option>
                                <?php while ($user = $list_user->fetch_assoc()): ?>
                                <option value="<?php echo $user['id'] ?>">
                                    <?php echo $user['name'] ?>
                                </option>
                              <?php endwhile; ?>
                            </select>
                        </div>
                        <!-- <div class="mb-3">
                            <label for="editUsername" class="form-label">ชื่อผู้ใช้</label>
                            <input type="text" class="form-control" id="editUsername" name="username">
                        </div> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Edit ESP Modal -->
    
    <!--Start Insert ESP -->
        <!-- <div class="modal fade" tabindex="-1" id="registerModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-box-arrow-in-right me-2"></i>ลงทะเบียนผู้ใช้</h5>
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
        </div> -->
    <!-- End Insert ESP -->
     
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            //แทนค่าที่จะแก้ไขใน modal
            const editButtons = document.querySelectorAll(".edit-btn");

            editButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const esp32ID = button.getAttribute("data-esp32ID");
                    const username = button.getAttribute("data-username");

                    document.getElementById("editEsp32ID").value = esp32ID;
                    document.getElementById("displayEsp32ID").value = esp32ID;
                    document.getElementById("editUsername").value = username;
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
                                    window.location.href = 'delete_esp.php?id=' + deleteId; // ทำการลบเมื่อยืนยัน
                                }
                            });
                        }
                    });
                });
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>


</html>