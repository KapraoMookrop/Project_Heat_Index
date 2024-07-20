<button class="btn menu-toggle" id="menuToggle">
    <i class="fa-solid fa-bars"></i>
</button>
<div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark nav-slidebar open" id="sidebar">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none">
      <span class="web-name">Heat Index Warning</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="index.php" class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>">
            <i class="fa-solid fa-house icon-bar"></i>
            <span class="name-menu">หน้าหลัก</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="PageStatus.php" class="nav-link <?php echo ($current_page == 'status') ? 'active' : ''; ?>">
            <i class="fa-solid fa-user icon-bar"></i>
            <span class="name-menu">สถานะผู้ใช้</span>
        </a>
    </li>
</ul>

    <hr>
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
        <strong>mdo</strong>
      </a>
      <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
        <li><a class="dropdown-item" href="#">New project...</a></li>
        <li><a class="dropdown-item" href="#">Settings</a></li>
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#">Sign out</a></li>
      </ul>
    </div>
  </div>