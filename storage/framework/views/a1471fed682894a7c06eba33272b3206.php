<?php if (\Illuminate\Support\Facades\Blade::check('haspermission', 'setting')): ?>
<li class="nav-item dropdown dropdown-large">
    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button"
        data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-cog"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end megamenu">
        <div class="row g-3 p-3">
            
            <div class="col-4">
                <div class="tab">
                    <a href="javascript:void(0);" class="tablinks active"
                        onmouseenter="openMegaMenu(event, 'RBAC')">
                        <div class="col mb-1 megamenu-item">
                            <div class="app-box mx-auto text-white">
                                <i class='bx bx-group'></i>
                            </div>
                            <div class="app-title">Application Configuration</div>
                        </div>
                    </a>
                    <!-- <a href="javascript:void(0);" class="tablinks"
                                                    onmouseenter="openMegaMenu(event, 'Store')">
                                                    <div class="col mb-1 megamenu-item">
                                                        <div class="app-box mx-auto text-white">
                                                            <i class='bx bx-store'></i>
                                                        </div>
                                                        <div class="app-title">Store Management</div>
                                                    </div>
                                                </a> -->
                    <a class="tablinks" onmouseenter="openMegaMenu(event, 'Purchase')">
                        <div class="col mb-1 megamenu-item">
                            <div class="app-box mx-auto text-white">
                                <i class='bx bx-cart'></i>
                            </div>
                            <div class="app-title">Logistic Management</div>
                        </div>
                    </a>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['create.rgr', 'create.rgr.draft', 'send.rgr.draft', 'view.rgr.list'])): ?>
                    <a class="tablinks"
                        onmouseenter="openMegaMenu(event, 'Rev')">
                        <div class="col mb-1 megamenu-item">
                            <div class="app-box mx-auto text-white">
                                <i class='bx bx-store'></i>
                            </div>
                            <div class="app-title">Revision of Ground Rent</div>

                        </div>
                    </a>
                    <?php endif; ?>

                    <!-- <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('upload.excel')): ?> -->
                    <a href="<?php echo e(url('import')); ?>" class="tablinks" onmouseenter="openMegaMenu(event, 'UploadExcel')">
                        <div class="col mb-1 megamenu-item">
                            <div class="app-box mx-auto text-white">
                                <i class='bx bx-upload'></i>
                            </div>
                            <div class="app-title">Upload Excel</div>

                        </div>
                    </a>
                    <!-- <?php endif; ?> -->

                    <a href="javascript:void(0);" class="tablinks" onmouseenter="openMegaMenu(event, 'Miscellaneous')">
                        <div class="col mb-1 megamenu-item">
                            <div class="app-box mx-auto text-white">
                            <i class='bx bxs-grid-alt'></i>
                            </div>
                            <div class="app-title">Miscellaneous</div>

                        </div>
                    </a>

                    <a href="<?php echo e(url('user-actions-logs')); ?>" class="tablinks"
                        onmouseenter="openMegaMenu(event, 'UserActionLogs')">
                        <div class="col mb-1 megamenu-item">
                            <div class="app-box mx-auto text-white">
                                <i class="bx bx-group"></i>
                            </div>
                            <div class="app-title">Action Log History</div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-8">
                <div class="right-container">
                    <div class="tab-content-container">
                        <div id="RBAC" class="tabcontent" style="display: block;">
                            <div class="row col-partition">
                                <div class="col-lg-4">
                                    <h5>RBAC</h5>
                                    <ul class="nav-links">
                                        <li><a href="<?php echo e(url('roles')); ?>"><i class='bx bx-chevron-right'></i> Role</a></li>
                                        <li><a href="<?php echo e(url('permissions')); ?>"><i class='bx bx-chevron-right'></i> Permissions</a></li>
                                        <li><a href="<?php echo e(url('users')); ?>"><i class='bx bx-chevron-right'></i> Manage Users</a></li>
                                        
                                    </ul>
                                </div>
                                <?php if (\Illuminate\Support\Facades\Blade::check('haspermission', 'app.settings')): ?>
                                <div class="col-lg-4">
                                    <h5>Settings</h5>
                                    <ul class="nav-links">
                                        <li><a href="<?php echo e(route('settings.mail.index')); ?>"><i class='bx bx-chevron-right'></i> Email</a></li>
                                        <li><a href="<?php echo e(route('settings.sms.index')); ?>"><i class='bx bx-chevron-right'></i> SMS</a></li>
                                        <li><a href="<?php echo e(route('settings.whatsapp.index')); ?>"><i class='bx bx-chevron-right'></i> WhatsApp</a></li>
                                    </ul>
                                </div>
                                <?php endif; ?>
                                <div class="col-lg-4">
                                    <h5>Templates</h5>
                                    <ul class="nav-links">
                                        <li><a href="<?php echo e(route('msgtempletes')); ?>"><i class='bx bx-chevron-right'></i>Templates</a></li>
                                        <!-- <li><a href="#"><i class='bx bx-chevron-right'></i> SMS Template</a></li>
                                        <li><a href="#"><i class='bx bx-chevron-right'></i> WhatsApp Template</a></li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- <div id="Store" class="tabcontent">

                                                        <ul class="nav-links">

                                                        </ul>
                                                    </div> -->
                        <div id="Purchase" class="tabcontent">
                            <div class="row col-partition">
                                <div class="col-lg-4">
                                    <h5>Product</h5>
                                    <ul class="nav-links">
                                        <li><a href="<?php echo e(url('logistic/category')); ?>"><i class='bx bx-chevron-right'></i> Add Category</a></li>
                                        <li><a href="<?php echo e(url('logistic/items')); ?>"><i class='bx bx-chevron-right'></i> Add Items</a></li>
                                        <li><a href="<?php echo e(url('logistic/vendor')); ?>"><i class='bx bx-chevron-right'></i> Supplier/Vendor List</a></li>
                                        <li><a href="<?php echo e(url('logistic/purchase')); ?>"><i class='bx bx-chevron-right'></i> Purchase</a></li>
                                    </ul>
                                </div>
                                <?php if (\Illuminate\Support\Facades\Blade::check('haspermission', 'app.settings')): ?>
                                <div class="col-lg-4">
                                    <h5>Issues</h5>
                                    <ul class="nav-links">
                                        <li><a href="<?php echo e(url('/logistic/issued-item')); ?>"><i class='bx bx-chevron-right'></i> Issue an Item</a></li>
                                        <li><a href="<?php echo e(url('/logistic/requested-items')); ?>"><i class='bx bx-chevron-right'></i> Issue Requests</a></li>
                                    </ul>
                                </div>
                                <?php endif; ?>
                                <div class="col-lg-4">
                                    <h5>Stock</h5>
                                    <ul class="nav-links">
                                        <li><a href="<?php echo e(url('logistic/stock')); ?>"><i class='bx bx-chevron-right'></i> Available Stock</a></li>
                                        <li><a href="<?php echo e(url('logistic/history')); ?>"><i class='bx bx-chevron-right'></i> Stock History</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="Rev" class="tabcontent">
                            <ul class="nav-links">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create.rgr')): ?>
                                <li><a href="<?php echo e(url('rgr')); ?>"><i class='bx bx-chevron-right'></i> Calculate RGR</a></li>
                                <li><a href="<?php echo e(route('completeList')); ?>"><i class='bx bx-chevron-right'></i>List of Revised GR</a>
                                </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view.rgr.list')): ?>
                                <li><a href="<?php echo e(url('rgr/list')); ?>"><i class='bx bx-chevron-right'></i> Detailed RGR List </a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div id="Miscellaneous" class="tabcontent">
                            <ul class="nav-links">
                                <li><a href="<?php echo e(route('propertyAssignment')); ?>"><i class='bx bx-chevron-right'></i> Property Assignment</a></li>
                                <li><a href="<?php echo e(route('colony.merger.create')); ?>"><i class='bx bx-chevron-right'></i> Property Merging</a></li>
                            </ul>
                        </div>
                        <!-- <div id="LDORate" class="tabcontent">
                        </div>
                        <div id="CircleStore" class="tabcontent">
                            <ul class="nav-links">
                                <li><a href="<?php echo e(url('import-lndo-land-rates')); ?>"><i class='bx bx-chevron-right'></i> L&DO Land Rate Upload</a></li>
                                <li><a href="<?php echo e(url('import-circle-rates')); ?>"><i class='bx bx-chevron-right'></i> Circle Rate Upload</a></li>
                            </ul>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>
<?php endif; ?><?php /**PATH C:\Users\cyber\OneDrive\Desktop\edharti\resources\views/layouts/settings.blade.php ENDPATH**/ ?>