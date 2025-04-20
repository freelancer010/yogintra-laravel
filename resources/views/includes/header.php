        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #ffffff!important; color:red">
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?php if ($_SESSION['profile_image'] != '') {
                                        echo (PANELURL . $_SESSION['profile_image']);
                                    } else {
                                        echo (PANELURL . 'assets/dist/img/default-profile.png');
                                    } ?>" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">
                            <?= $_SESSION['fullName'] ?>
                        </a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <?php if ($this->uri->segment(1) == '' || $this->uri->segment(1) == 'dashboard') {
                                $openingClass = 'active';
                            } ?>
                            <a href="<?= PANELURL ?>dashboard" class="nav-link <?= @$openingClass ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <?php if ($_SESSION['admin_role_id'] == 1 || $_SESSION['admin_role_id'] == 2 || $_SESSION['admin_role_id'] == 3) { ?>
                            <li class="nav-item">
                                <?php if ($this->uri->segment(1) == 'allData') {
                                    $allData = 'active';
                                } ?>
                                <a href="<?= PANELURL ?>allData" class="nav-link <?= @$allData ?>">
                                    <i class="nav-icon fas fa-plus"></i>
                                    <p>
                                        All Data
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">

                                    <li class="nav-item">
                                        <?php if ($this->uri->segment(1) == 'allData') {
                                            $allData = 'active';
                                        } ?>
                                        <a href="<?= PANELURL ?>allData" class="nav-link">
                                            <i class="nav-icon fas fa-arrow-right"></i>
                                            <p>
                                                Data
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <?php if ($this->uri->segment(1) == 'rejected') {
                                            $rejected = 'rejected';
                                        } ?>
                                        <a href="<?= PANELURL ?>rejected" class="nav-link <?= @$rejected ?>">
                                            <i class="nav-icon fas fa-times-circle"></i>
                                            <p>
                                                Rejected Data
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($_SESSION['admin_role_id'] == 1 || $_SESSION['admin_role_id'] == 2 || $_SESSION['admin_role_id'] == 3) { ?>
                            <li class="nav-item">
                                <?php if ($this->uri->segment(1) == 'lead') {
                                    $lead = 'active';
                                } ?>
                                <a href="<?= PANELURL ?>lead" class="nav-link <?= @$lead ?>">
                                    <i class="nav-icon fas fa-clipboard"></i>
                                    <p>
                                        Leads
                                    </p>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if ($_SESSION['admin_role_id'] == 1 || $_SESSION['admin_role_id'] == 2 || $_SESSION['admin_role_id'] == 3) { ?>
                            <li class="nav-item">
                                <?php if ($this->uri->segment(1) == 'telecalling') {
                                    $telecalling = 'active';
                                } ?>
                                <a href="<?= PANELURL ?>telecalling" class="nav-link <?= @$telecalling ?>">
                                    <i class="nav-icon fas fa-headset"></i>
                                    <p>
                                        Telecalling
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= PANELURL . 'customer' ?>" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Customers
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= PANELURL . 'renewal?type=customer' ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Renewal</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= PANELURL . 'customer' ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Customers</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($_SESSION['admin_role_id'] == 1 || $_SESSION['admin_role_id'] == 2 || $_SESSION['admin_role_id'] == 3 || $_SESSION['admin_role_id'] == 4) { ?>
                            <li class="nav-item">
                                <a href="<?= PANELURL . 'trainer' ?>" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Trainers
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if ($_SESSION['admin_role_id'] != 3) { ?>
                                        <li class="nav-item">
                                            <a href="<?= PANELURL . 'recruiter' ?>" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Recruits</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li class="nav-item">
                                        <a href="<?= PANELURL . 'trainers' ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>All Trainers</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($_SESSION['admin_role_id'] == 13) { ?>
                            <li class="nav-item">
                                <a href="<?= PANELURL . 'yoga-bookings' ?>" class="nav-link">
                                    <i class="nav-icon fas fa-calender"></i>
                                    <p>
                                        Yoga Center
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= PANELURL . 'renewal?type=yoga' ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Renewal</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= PANELURL . 'yoga-bookings' ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Yoga Center</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($_SESSION['admin_role_id'] == 1 || $_SESSION['admin_role_id'] == 2 || $_SESSION['admin_role_id'] == 5) { ?>
                            <li class="nav-item">
                                <a href="<?= PANELURL ?>event" class="nav-link">
                                    <i class="nav-icon fas fa-calendar"></i>
                                    <p>
                                        Events
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= PANELURL . 'yoga-bookings' ?>" class="nav-link">
                                    <i class="nav-icon fas fa-calendar"></i>
                                    <p>
                                        Yoga Center
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= PANELURL . 'renewal?type=yoga' ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Renewal</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= PANELURL . 'yoga-bookings' ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Yoga Center</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="<?= PANELURL . 'accounts' ?>" class="nav-link">
                                    <i class="nav-icon fas fa-copy"></i>
                                    <p>
                                        Accounts
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= PANELURL . 'ledger' ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Ledger</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= PANELURL . 'summary' ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Summary</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= PANELURL . 'office-expences' ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Expenses</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>

                        <?php if ($_SESSION['admin_role_id'] == 1 || $_SESSION['admin_role_id'] == 2) { ?>
                            <li class="nav-item">
                                <a href="<?= PANELURL . 'admin/view' ?>" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        User
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= PANELURL . 'admin/view' ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>User list</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= PANELURL . 'admin/add' ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add User</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                        <li class="nav-item">
                            <a href="<?= PANELURL ?>logout" class="nav-link">
                                <i class="nav-icon fas fa-arrow-right"></i>
                                <p>
                                    Sign out
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>