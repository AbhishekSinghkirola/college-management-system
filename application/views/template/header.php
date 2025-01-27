<?php
$user = get_logged_in_user();
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />
    <base href="<?= base_url(); ?>cms-assets/">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />


    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- <link rel="stylesheet" href="//cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css"> -->
    <!-- <script src="//cdn.datatables.net/2.1.7/js/dataTables.min.js"></script> -->
    <link rel="stylesheet" href="assets/vendor/libs/datatables/datatables.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables/responsive.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables/datatables.checkboxes.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables/buttons.bootstrap5.css">

    <script src="assets/vendor/libs/datatables/datatables-bootstrap5.min.js"></script>

    <!-- <script src="assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="assets/vendor/libs/@form-validation/auto-focus.js"></script>

    <script src="assets/js/tables-datatables-basic.js"></script> -->
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="index.html" class="app-brand-link">
                        <img src="<?php base_url() ?>assets/img/logo.png" alt="" class="img-fluid">
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item active">
                        <a href="index.html" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>

                    <?php if ($user['role_type'] !== 'USER'): ?>
                        <!-- Manage Student  -->
                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bxs-user-badge"></i>
                           
                                <div data-i18n="Account Settings">Manage Student</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item">
                                    <a href="<?= base_url() ?>Student" class="menu-link">
                                        <div data-i18n="Notifications">Students</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="<?= base_url() ?>Student/today_attendance" class="menu-link">
                                        <div data-i18n="Connections">Today Attendance</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if ($user['role_type'] !== 'USER'): ?>
                        <!-- Manage Courses -->
                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-book-reader"></i>
                                
                                <div data-i18n="Account Settings">Manage Courses</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item">
                                    <a href="<?= base_url() ?>Courses/category" class="menu-link">
                                        <div data-i18n="Notifications">Course Category</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="<?= base_url() ?>Courses" class="menu-link">
                                        <div data-i18n="Account">Courses</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="<?= base_url() ?>Courses/content" class="menu-link">
                                        <div data-i18n="Notifications">Content</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="<?= base_url() ?>Courses/assign_content" class="menu-link">
                                        <div data-i18n="Notifications">Assign Content</div>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    <?php endif; ?>
                    <?php if($user['role_type'] == 'EMP') : ?>

                                <li class="menu-item">
                                    <a href="<?= base_url() ?>Teachers/recived_salary" class="menu-link">
                                         <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                        <div data-i18n="Notifications">Recived Salary</div>
                                    </a>
                                </li>
                                
                    <?php endif; ?>


                    <?php if ($user['role_type'] === 'USER'): ?>
                        <!-- Courses  -->
                        <li class="menu-item">
                            <a href="<?= base_url() ?>Courses/course_details" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                <div data-i18n="Analytics">Course Details</div>
                            </a>
                        </li>

                        <!-- Manage Attendance -->
                        <li class="menu-item">
                            <a href="<?= base_url() ?>Attendance/student_attendance" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                <div data-i18n="Analytics">Attendance</div>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="<?= base_url() ?>Fees/paid_fees" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                    <div data-i18n="Notifications">Pending Fees</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if($user['role_type'] == 'ADMIN'): ?>
                    <!-- Manage Teachers  -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bxs-user-voice"></i>
                            <div data-i18n="Account Settings">Manage Teachers</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="<?= base_url() ?>Teachers" class="menu-link">
                                    <div data-i18n="Notifications">Teachers</div>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="<?= base_url() ?>Teachers/pending_salary" class="menu-link">
                                    <div data-i18n="Notifications">Pending Salary</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if($user['role_type'] == 'ADMIN'): ?>

                    <!-- manage Fees  -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-money"></i>
                            <div data-i18n="Account Settings">Fees Management</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="<?= base_url() ?>Fees" class="menu-link">
                                    <div data-i18n="Notifications">Pending Fees</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-file-find"></i>
                            <div data-i18n="Account Settings">Reports</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="<?= base_url() ?>Reports" class="menu-link">
                                    <div data-i18n="Notifications">Fees Reports</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= base_url() ?>Reports/salary" class="menu-link">
                                    <div data-i18n="Notifications">Salary Reports</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>


                   

                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <i class="bx bx-search fs-4 lh-0"></i>
                                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." aria-label="Search..." />
                            </div>
                        </div>
                        <!-- /Search -->



                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Place this tag where you want the button to render. -->
                            <li class="nav-item lh-1 me-3">
                                <a class="github-button" href="https://github.com/themeselection/sneat-html-admin-template-free" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star themeselection/sneat-html-admin-template-free on GitHub">Star</a>
                            </li>

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">John Doe</span>
                                                    <small class="text-muted">Admin</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <span class="d-flex align-items-center align-middle">
                                                <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                                                <span class="flex-grow-1 align-middle">Billing</span>
                                                <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= base_url() ?>logout">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>

                </nav>

                <!-- / Navbar -->
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">