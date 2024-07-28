<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/d05e3fe0a9.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="/Project_Heat_Index/css/style.css">
  <link rel="icon" type="png" href="img/logo.png">
</head>
<body>
    <?php 
        session_start(); 
        if (!empty($_SESSION['username'])){
            $username = $_SESSION['username'];
        }else{
            $username = 'none';
        }
    ?>
    <div class="container p-0">
        <nav class="navbar navbar-expand-lg border-dark border-2 border-bottom align-items-end">
            <a class="navbar-brand logo m-0 p-0" href="/Project_Heat_Index">
                <img src="/Project_Heat_Index/img/logo.png" alt="Logo" width="75px" class="d-inline-block align-text-center"></img>
            </a>
            <span class="logo m-0 p-0 mx-2">
                <span class="fs-3">Heat Index Warning</span>
            </span>
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse justify-content-end collapse" id="navbarSupportedContent">
                <ul class="navbar-nav align-items-lg-center align-items-end">
                    <?php if($username != "none"): ?>
                        <li class="nav-item">
                            <p class="fs-4 m-0 me-1 mt-1"><?php echo  "สวัสดีคุณ " . $username; ?></p>
                        </li>
                        <li class="nav-item dropdown">
                            <a type="button" class="btn btn-success nav-btn dropdown-toggle" href='#'  data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-list-check me-3"></i> 
                                <p>จัดการข้อมูล</p> 
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/Project_Heat_Index">หน้าหลัก</a></li>
                                <li><a class="dropdown-item" href="/Project_Heat_Index/page-manage/system-user/dashboard.php">ข้อมูลผู้ดูแลระบบ</a></li>
                                <li><a class="dropdown-item" href="/Project_Heat_Index/page-manage/user/dashboard.php">ข้อมูลผู้ลงทะเบียน</a></li>
                                <li><a class="dropdown-item" href="/Project_Heat_Index/page-manage/esp/dashboard.php">ข้อมูลบอร์ด ESP</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a type="button" class="btn btn-danger nav-btn" href='/Project_Heat_Index/logout.php'>
                                <i class="fa-solid fa-circle-xmark me-3"></i> 
                                <p>ออกจากระบบ</p> 
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if($username == "none"): ?>
                        <li class="nav-item">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#loginModal" class="btn btn-outline-success nav-btn">
                                <i class="fa-solid fa-right-to-bracket me-3" aria-hidden="true"></i> 
                                <p>เข้าสู่ระบบ</p> 
                            </button>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </div>
        <!-- modal start -->
    <!-- LOGIN -->
    <div class="modal fade" tabindex="-1" id="loginModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-box-arrow-in-right me-2"></i>Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" class="row g-3 needs-validation" novalidate action="log-reg_process/login_process.php" method="post">
                        <div class="col-md-12">
                            <label for="validationUsername" class="form-label">Username</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text" id="inputGroupPrepend" style="color: #a0a0a0;"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control" id="validationUsername" aria-describedby="inputGroupPrepend" name="username" required>
                                <div class="invalid-feedback">
                                    Please enter a username.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="validationPassword" class="form-label">Password</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text" id="inputGroupPrepend" style="color: #a0a0a0;"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" class="form-control" id="validationPassword" aria-describedby="inputGroupPrepend" name="password" required>
                                <div class="invalid-feedback">
                                    Please enter a password.
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Sign in</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script>
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
              .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                  if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                  }
                  form.classList.add('was-validated')
                }, false)
              })
        })()
    </script>
</body>

</html>