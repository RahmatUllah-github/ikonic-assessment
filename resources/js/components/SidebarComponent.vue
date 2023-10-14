<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <!-- Sidebar -->
        <div class="sidebar w-100">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img :src="image ?? '/admin/img/AdminLTELogo.png'"
                        class="img-circle elevation-2" alt="User Image" style="height: 2.1rem;">
                </div>
                <div class="info">
                    <a href="javascript:void(0);" class="d-block">{{ name }} Admin LTE</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
        with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">USERS</li>
                    <li class="nav-item">
                        <a href="#"
                            class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Top Sellers
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#"
                            class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                All Sellers
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#"
                            class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Buyers
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

</template>


<script>
    export default {
        props: ['image', 'name'],
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
