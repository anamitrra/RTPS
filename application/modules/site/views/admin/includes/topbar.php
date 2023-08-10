  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?=base_url("site/admin/dashboard");?>" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <!-- <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form> -->

    

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <li class="nav-item dropdown user user-menu">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <!-- <img class="user-image" src="<?=$this->session->userdata("image");?>" alt="User Image"> -->
            <?=$this->session->userdata("name");?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
         
          <a href="<?=base_url("site/admin/login/logout");?>" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
          
        </div>
      </li>

    </ul>
  </nav>
