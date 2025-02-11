<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <!-- <meta name="csrf-token" content="content"> -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="<?php echo e(asset('assets/images/logo-icon.png')); ?>" type="image/png" />

    <!-- CSS Links -->
    <link href="<?php echo e(asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/plugins/simplebar/css/simplebar.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/plugins/metismenu/css/metisMenu.min.css')); ?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/jquery-ui.css')); ?>">
    <link href="<?php echo e(asset('assets/css/pace.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/bootstrap-extended.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-select.min.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="<?php echo e(asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/css/app.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/custom.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/icons.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/dark-theme.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/semi-dark.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/header-colors.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/range.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/jquery.dataTables.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/buttons.dataTables.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/responsive.dataTables.min.css')); ?>">
    <!-- Toaster CSS Added by Diwakar Sinha at 20-09-2024 -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <title>e-Dharti | <?php echo $__env->yieldContent('title'); ?></title>

    <!-- Jquery moved to headre by nitin to fix $ is not defined error -->
    <script src="<?php echo e(asset('assets/js/jquery.min.js')); ?>"></script>
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0px !important;
        }
        .backButton{
            position: fixed;
            top: 80px;
            right: 54px;
            z-index: 1030;
        }
    </style>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper toggled">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="<?php echo e(asset('assets/images/logo-icon.png')); ?>" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h4 class="logo-text">e-Dharti</h4>
                </div>
                <div class="toggle-icon mobile-toggle"><i class='bx bx-menu'></i></div>

            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                <!-- <li class="<?php echo e(request()->is('dashboard') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('dashboard')); ?>">
                        <div class="parent-icon"><i class='bx bx-home-circle'></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li> -->

                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-home-circle'></i></div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                    <ul>
                        <li class="<?php echo e(request()->is('dashboard') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('dashboard')); ?>"><i class="bx bx-right-arrow-alt"></i>Dashboard</a>
                        </li>
                        <?php if (\Illuminate\Support\Facades\Blade::check('haspermission', 'main.dashboard')): ?>
                        <li class="<?php echo e(request()->is('dashboard/main') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('dashboard.main')); ?>"><i class="bx bx-right-arrow-alt"></i>Main Dashboard</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php if (\Illuminate\Support\Facades\Blade::check('haspermission', 'viewDetails')): ?>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fa-solid fa-file-pen"></i>
                        </div>
                        <div class="menu-title">MIS</div>
                    </a>
                    <ul>
                        <?php if (\Illuminate\Support\Facades\Blade::check('haspermission', 'add.single.property')): ?>
                            <li class="<?php echo e(request()->is('property-form') ? 'active' : ''); ?>"> <a
                                    href="<?php echo e(route('mis.index')); ?>"><i class="bx bx-right-arrow-alt"></i>Add Single Property</a>
                            </li>
                        <?php endif; ?>
                        <?php if (\Illuminate\Support\Facades\Blade::check('haspermission', 'add.multiple.property')): ?>
                        <li class="<?php echo e(request()->is('property-form-multiple') ? 'active' : ''); ?>"> <a
                                href="<?php echo e(route('mis.form.multiple')); ?>"><i class="bx bx-right-arrow-alt"></i>Add
                                Multiple Property</a>
                        </li>
                        <?php endif; ?>
                        <?php if (\Illuminate\Support\Facades\Blade::check('haspermission', 'create.flat')): ?>
                        <li class="<?php echo e(request()->is('flat-form') ? 'active' : ''); ?>"> <a
                                href="<?php echo e(route('create.flat.form')); ?>"><i class="bx bx-right-arrow-alt"></i>Add
                                Flat</a>
                        </li>
                        <?php endif; ?>
                        <?php if(auth()->user()->hasAnyPermission(['viewDetails', 'view.flat'])): ?>
                            <li> <a href="javascript:;" class="has-arrow submenu-parent"><i class="bx bx-right-arrow-alt"></i>View Details</a>
                                <ul>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('haspermission', 'viewDetails')): ?>
                                    <li class="<?php echo e(request()->is('property-details') ? 'active' : ''); ?>"> <a
                                            href="<?php echo e(route('propertDetails')); ?>"><i class='bx bx-chevron-right'></i>Property Details</a>
                                    </li>
                                    <?php endif; ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('haspermission', 'view.flat')): ?>
                                    <li class="<?php echo e(request()->is('flats') ? 'active' : ''); ?>"> <a
                                            href="<?php echo e(route('flats')); ?>"><i class='bx bx-chevron-right'></i>Flat Details</a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if (\Illuminate\Support\Facades\Blade::check('haspermission', 'apply.application')): ?>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bxs-file'></i></div>
                        <div class="menu-title">Application</div>
                    </a>
                    <ul>
                        <li class="<?php echo e(request()->is('application/new') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('new.application')); ?>"><i class="bx bx-right-arrow-alt"></i>New</a>
                        </li>
                        <li class="<?php echo e(request()->is('applications/draft') ? 'active' : ''); ?>"> <a
                                href="<?php echo e(route('draftApplications')); ?>"><i
                                    class="bx bx-right-arrow-alt"></i>Draft</a>
                        </li>
                        <li>
                            <a href="javascript:;" class="has-arrow submenu-parent"><i class="bx bx-right-arrow-alt"></i> History</a>
                            <ul>
                                <li class="<?php echo e(request()->is('applications/history/details') ? 'active' : ''); ?>"> <a href="<?php echo e(route('applications.history.details')); ?>"><i class='bx bx-chevron-right'></i> Submitted Applications</a></li>
                                <li class="<?php echo e(request()->is('applications/history/withdraw') ? 'active' : ''); ?>"> <a href="<?php echo e(route('applications.history.withdraw.details')); ?>"><i class='bx bx-chevron-right'></i> Withdrawn Applications</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php
                $hasAccess = Auth::user()->can('view.appointment') || Auth::user()->can('view.grievance');
                ?>

                <?php if($hasAccess): ?>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fas fa-users-cog"></i></div>
                        <div class="menu-title">Public Services</div>
                    </a>
                    <ul>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view.appointment')): ?>
                        <li class="<?php echo e(request()->is('appointments*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('appointments.index')); ?>"><i class="bx bx-right-arrow-alt"></i>Appointments</a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view.grievance')): ?>
                        <li class="<?php echo e(request()->is('grievances*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('grievance.index')); ?>"><i class="bx bx-right-arrow-alt"></i>Grievances</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if (\Illuminate\Support\Facades\Blade::check('haspermission', 'view reports')): ?>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="bx bx-message-square-edit"></i>
                        </div>
                        <div class="menu-title">Reports</div>
                    </a>
                    <ul>
                        <li class="<?php echo e(request()->is('reports') ? 'active' : ''); ?>"> <a
                                href="<?php echo e(route('reports.index')); ?>"><i class="bx bx-right-arrow-alt"></i>Filter Report</a>
                        </li>

                        <li class="<?php echo e(request()->is('detailed-report') ? 'active' : ''); ?>"> <a
                                href="<?php echo e(route('detailedReport')); ?>"><i class="bx bx-right-arrow-alt"></i>Detailed
                                Report</a>
                        </li>
                        <li class="<?php echo e(request()->is('customize-report') ? 'active' : ''); ?>"> <a
                                href="<?php echo e(route('customizeReport')); ?>"><i class="bx bx-right-arrow-alt"></i>Customized
                                Report</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fa-solid fa-house-user"></i>
                        </div>
                        <div class="menu-title">Property Details</div>
                    </a>
                    <ul>
                        <!-- <li class="<?php echo e(request()->is('applicant/profile') ? 'active' : ''); ?>"> <a
                                href="<?php echo e(route('applicant.profile')); ?>"><i class="bx bx-right-arrow-alt"></i>Profile
                                <?php echo e(request()->is()); ?></a>
                        </li> -->
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('applicant.view.property.details')): ?>
                        <li class="<?php echo e(request()->is('applicant/property/details') ? 'active' : ''); ?>"> <a
                                href="<?php echo e(route('applicant.properties')); ?>"><i
                                    class="bx bx-right-arrow-alt"></i>Property Details <?php echo e(request()->is()); ?></a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('section.property.mis.update.request')): ?>
                        <li class="<?php echo e(request()->is('mis/update/request/list') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('misUpdateRequestList')); ?>"><i class="bx bx-right-arrow-alt"></i>Mis
                                Update Request <?php echo e(request()->is()); ?></a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create.demand')): ?>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fa-solid fa-coins"></i>
                        </div>
                        <div class="menu-title">Demand</div>
                    </a>
                    <ul>
                        <li class="<?php echo e(request()->is('demand') ? 'active' : ''); ?>"> <a
                                href="<?php echo e(route('createDemandView')); ?>"><i class="bx bx-right-arrow-alt"></i>Create Demand</a>
                        </li>
                        <li class="<?php echo e(request()->is('demandList') ? 'active' : ''); ?>"> <a
                                href="<?php echo e(route('demandList')); ?>"><i class="bx bx-right-arrow-alt"></i>Demand List</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('club.membership')): ?>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fas fa-users-cog"></i></div>
                        <div class="menu-title">Club Membership</div>
                    </a>
                    <ul>
                        <li class="<?php echo e(request()->is('property-form') ? 'active' : ''); ?>"> <a
                                href="<?php echo e(route('club.membership.index')); ?>"><i class="bx bx-right-arrow-alt"></i>View Listing</a>
                        </li>
                        <li class="<?php echo e(request()->is('property-form-multiple') ? 'active' : ''); ?>"> <a
                                href="<?php echo e(route('create.club.membership.form')); ?>"><i class="bx bx-right-arrow-alt"></i>Add
                                Details</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                
            </ul>
            <!--end navigation-->
        </div>
        <!--end sidebar wrapper -->
        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mob-logo">
                        <img src="<?php echo e(asset('assets/images/logo-icon.png')); ?>" class="logo-icon" alt="logo icon">
                    </div>
                    <div class="toggle-icon"><i class='bx bx-menu'></i></div>
                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center">
                            <?php echo $__env->make('layouts.settings', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <li class="d-none nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="alert-count">7</span>
                                    <i class='bx bx-bell'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Notifications</p>
                                            <p class="msg-header-clear ms-auto">Marks all as read</p>
                                        </div>
                                    </a>
                                    <div class="header-notifications-list">
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-primary text-primary"><i
                                                        class="bx bx-group"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Customers<span
                                                            class="msg-time float-end">14 Sec ago</span></h6>
                                                    <p class="msg-info">5 new user registered</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-danger text-danger"><i
                                                        class="bx bx-cart-alt"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Orders <span class="msg-time float-end">2
                                                            min ago</span></h6>
                                                    <p class="msg-info">You have received new orders</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-success text-success"><i
                                                        class="bx bx-file"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">24 PDF File<span
                                                            class="msg-time float-end">19 min ago</span></h6>
                                                    <p class="msg-info">The pdf files generated</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-warning text-warning"><i
                                                        class="bx bx-send"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Time Response <span
                                                            class="msg-time float-end">28 min ago</span></h6>
                                                    <p class="msg-info">5.1 min avarage time response</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-info text-info"><i
                                                        class="bx bx-home-circle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Product Approved <span
                                                            class="msg-time float-end">2 hrs ago</span>
                                                    </h6>
                                                    <p class="msg-info">Your new product has approved</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-danger text-danger"><i
                                                        class="bx bx-message-detail"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Comments <span
                                                            class="msg-time float-end">4
                                                            hrs
                                                            ago</span></h6>
                                                    <p class="msg-info">New customer comments recived</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-success text-success"><i
                                                        class='bx bx-check-square'></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Your item is shipped <span
                                                            class="msg-time float-end">5 hrs
                                                            ago</span></h6>
                                                    <p class="msg-info">Successfully shipped your item</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-primary text-primary"><i
                                                        class='bx bx-user-pin'></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New 24 authors<span
                                                            class="msg-time float-end">1 day
                                                            ago</span></h6>
                                                    <p class="msg-info">24 new authors joined last week</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-warning text-warning"><i
                                                        class='bx bx-door-open'></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Defense Alerts <span
                                                            class="msg-time float-end">2 weeks
                                                            ago</span></h6>
                                                    <p class="msg-info">45% less alerts last 4 weeks</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <a href="javascript:;">
                                        <div class="text-center msg-footer">View All Notifications</div>
                                    </a>
                                </div>
                            </li>
                            <li class="d-none nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="alert-count">8</span>
                                    <i class='bx bx-comment'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Messages</p>
                                            <p class="msg-header-clear ms-auto">Marks all as read</p>
                                        </div>
                                    </a>
                                    <div class="header-message-list">
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="<?php echo e(asset('assets/images/avatars/avatar-1.png')); ?>"
                                                        class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Daisy Anderson <span
                                                            class="msg-time float-end">5 sec
                                                            ago</span></h6>
                                                    <p class="msg-info">The standard chunk of lorem</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="<?php echo e(asset('assets/images/avatars/avatar-2.png')); ?>"
                                                        class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Althea Cabardo <span
                                                            class="msg-time float-end">14
                                                            sec ago</span></h6>
                                                    <p class="msg-info">Many desktop publishing packages</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="<?php echo e(asset('assets/images/avatars/avatar-3.png')); ?>"
                                                        class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Oscar Garner <span
                                                            class="msg-time float-end">8
                                                            min
                                                            ago</span></h6>
                                                    <p class="msg-info">Various versions have evolved over</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="<?php echo e(asset('assets/images/avatars/avatar-4.png')); ?>"
                                                        class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Katherine Pechon <span
                                                            class="msg-time float-end">15
                                                            min ago</span></h6>
                                                    <p class="msg-info">Making this the first true generator</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="<?php echo e(asset('assets/images/avatars/avatar-5.png')); ?>"
                                                        class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Amelia Doe <span
                                                            class="msg-time float-end">22
                                                            min
                                                            ago</span></h6>
                                                    <p class="msg-info">Duis aute irure dolor in reprehenderit</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="<?php echo e(asset('assets/images/avatars/avatar-6.png')); ?>"
                                                        class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Cristina Jhons <span
                                                            class="msg-time float-end">2 hrs
                                                            ago</span></h6>
                                                    <p class="msg-info">The passage is attributed to an unknown</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="<?php echo e(asset('assets/images/avatars/avatar-7.png')); ?>"
                                                        class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">James Caviness <span
                                                            class="msg-time float-end">4 hrs
                                                            ago</span></h6>
                                                    <p class="msg-info">The point of using Lorem</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="<?php echo e(asset('assets/images/avatars/avatar-8.png')); ?>"
                                                        class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Peter Costanzo <span
                                                            class="msg-time float-end">6 hrs
                                                            ago</span></h6>
                                                    <p class="msg-info">It was popularised in the 1960s</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="<?php echo e(asset('assets/images/avatars/avatar-9.png')); ?>"
                                                        class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">David Buckley <span
                                                            class="msg-time float-end">2 hrs
                                                            ago</span></h6>
                                                    <p class="msg-info">Various versions have evolved over</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="<?php echo e(asset('assets/images/avatars/avatar-10.png')); ?>"
                                                        class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Thomas Wheeler <span
                                                            class="msg-time float-end">2 days
                                                            ago</span></h6>
                                                    <p class="msg-info">If you are going to use a passage</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="<?php echo e(asset('assets/images/avatars/avatar-11.png')); ?>"
                                                        class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Johnny Seitz <span
                                                            class="msg-time float-end">5
                                                            days
                                                            ago</span></h6>
                                                    <p class="msg-info">All the Lorem Ipsum generators</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <a href="javascript:;">
                                        <div class="text-center msg-footer">View All Messages</div>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo e(asset('assets/images/avatars/avatar-1.png')); ?>" class="user-img"
                                alt="user avatar">
                            <div class="user-info ps-3">
                                <p class="user-name mb-0"><?php echo e(Auth::user()->name); ?></p>
                                <p class="designattion mb-0"><?php echo e(Auth::user()->email); ?></p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!-- <li><a class="dropdown-item" href="javascript:;"><i
                                        class="bx bx-user"></i><span>Profile</span></a>
                            </li>
                            <li><a class="dropdown-item" href="javascript:;"><i
                                        class="bx bx-cog"></i><span>Settings</span></a>
                            </li>
                            <li><a class="dropdown-item" href="javascript:;"><i
                                        class='bx bx-home-circle'></i><span>Dashboard</span></a>
                            </li>
                            <li><a class="dropdown-item" href="javascript:;"><i
                                        class='bx bx-dollar-circle'></i><span>Earnings</span></a>
                            </li>
                            <li><a class="dropdown-item" href="javascript:;"><i
                                        class='bx bx-download'></i><span>Downloads</span></a>
                            </li>
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li> -->
                            <li><a class="dropdown-item" href="/applicant/profile"><i
                                        class="bx bx-user"></i><span>Profile</span></a>
                            </li>
                            <li><a class="dropdown-item" href="<?php echo e(route('password.reset')); ?>"><i
                                        class="bx bx-user"></i><span>Change Password</span></a>
                            </li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>

                                    <a class="dropdown-item" href="route('logout')"
                                        onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                        <i class='bx bx-log-out-circle'></i> <span><?php echo e(__('Log Out')); ?></span>
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!--end header -->
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <?php if(session('success')): ?>
                <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
                    <div class="text-white"><?php echo e(session('success')); ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <?php if(session('failure')): ?>
                <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                    <div class="text-white"><?php echo e(session('failure')); ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="text-white"><?php echo e($error); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
        <!--end page wrapper -->


        <!-- Global Back Button START - SOURAV CHAUHAN (31/Jan/2025) -->
         <div class="fixed-top">
                <button type="button" onclick="handleBackButtonClick()" class="btn btn-danger px-2 mx-2 backButton">← Back</button>
         </div>



        <!--start overlay-->
        <!-- <div class="overlay toggle-icon"></div> commneted by anil and added new overlay div after footer on 23-01-2025 -->
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright © 2024. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->

    <div class="overlay"></div>

    <!--start switcher-->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['calculate.conversion', 'calculate.landUseChange','calculate.unearnedIncrease'])): ?>
    <div class="switcher-wrapper">
        <div class="switcher-btn"> <!-- <i class="fa-solid fa-screwdriver-wrench bx-spin"></i> commented by anil on 24-01-2025 for hiding screwdriver-wrench icon-->
            <h6 class="charges_title"><i class='bx bx-info-circle'></i> Know the Charges</h6>
        </div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase">Utilities</h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>

            <hr />
            <div class="header-colors-indigators">

                <div class="row row-cols-auto g-3">
                    <div class="col">
                        <h5 class="utilities-title">Calculator <i class='bx bxs-calculator'></i></h5>
                    </div>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('calculate.conversion')): ?>
                    <div class="col">
                        <a href="<?php echo e(route('calculateConversionCharges')); ?>">
                            <span><i class='bx bx-chevron-right'></i> Conversion</span>
                        </a>
                    </div>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('calculate.landUseChange')): ?>
                    <div class="col">
                        <a href="<?php echo e(route('calculateLandUseChangeCharges')); ?>">
                            <span><i class='bx bx-chevron-right'></i> Land Use Change</span>
                        </a>
                    </div>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('calculate.unearnedIncrease')): ?>
                    <div class="col">
                        <a href="<?php echo e(route('calculateUnearnedIncrease')); ?>">
                            <span><i class='bx bx-chevron-right'></i> Unearned Increase</span>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
    <?php endif; ?>
    <!--end switcher-->

    <!-- JavaScript Files -->
    <script src="<?php echo e(asset('assets/js/jquery-3.5.1.js')); ?>"></script> <!--found extra jquery library need to check it---Amita--[07-11-2024]--> 
    <script src="<?php echo e(asset('assets/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/dataTables.buttons.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/buttons.flash.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/jszip.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/buttons.html5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/buttons.print.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/pdfmake.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/vfs_fonts.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/pace.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/simplebar/js/simplebar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/metismenu/js/metisMenu.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')); ?>"></script>
    <!-- Commented By Lalit on 09/18/2024 Duplicate we are already using jquery.dataTables.min.js that's why we commented jquery.dataTables.min.js  -->
    
    <script src="<?php echo e(asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/summernote-lite.min.js')); ?>"></script>
    <!-- Toast JS Added by Diwakar Sinha at 20-09-2024 -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        $(document).ready(function() {
            $('#myDataTable').DataTable();
        });
    </script>

    <script>
        $(document).ready(function() {
            var $alertElement = $('.alert');
            if ($alertElement.length) {
                setTimeout(function() {
                    $alertElement.fadeOut();
                }, 5000);
            }
        });

        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });


        function handleBackButtonClick(){
            window.location.href = "<?php echo e(url()->previous()); ?>";
        }
    </script>
    <!-- <script>
        Toastify({
            text: "Success",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "bottom", // `top` or `bottom`
            position: "right", // `left`, `center` or `right`
            stopOnFocus: true, // Prevents dismissing of toast on hover
            style: {
                background: "linear-gradient(to right, #00b09b, #116d6e)",
            },
            offset: {
                // x: 50, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
            },
            onClick: function toasterDemo() {} // Callback after click
        }).showToast();

        Toastify({
            text: "Failed",
            duration: 5000,
            newWindow: true,
            close: true,
            gravity: "bottom", // `top` or `bottom`
            position: "right", // `left`, `center` or `right`
            stopOnFocus: true, // Prevents dismissing of toast on hover
            style: {
                background: "linear-gradient(to right, #00b09b, #116d6e)",
            },
            offset: {
                // x: 50, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
            },
            onClick: function failedtoasterDemo() {} // Callback after click
        }).showToast();
    </script> -->
    <?php echo $__env->yieldContent('footerScript'); ?>
</body>

</html><?php /**PATH C:\Users\cyber\OneDrive\Desktop\edharti\resources\views/layouts/app.blade.php ENDPATH**/ ?>