<template>
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <!--    admin    -->
        <ul class="nav" v-if="user.role == 1">
            <li class="nav-item nav-profile">
                <a href="#" class="nav-link">
                    <div class="nav-profile-image">
                        <img src="../../../public/assets/images/faces/face1.jpg" alt="profile">
                        <span class="login-status online"></span>
                        <!--change to offline or busy as needed-->
                    </div>
                    <div class="nav-profile-text d-flex flex-column">
                        <span class="font-weight-bold mb-2">{{ user.name.toUpperCase() }}</span>
                        <span class="text-secondary text-small">Admin</span>
                    </div>
                    <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                </a>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/admin/dashboard">
                    <span class="menu-title">
                        Dashboard
                    </span>
                    <i class="mdi mdi-home menu-icon"></i>
                </router-link>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false"
                    aria-controls="ui-basic">
                    <span class="menu-title">Basic UI Elements</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-crosshairs-gps menu-icon"></i>
                </a>
                <div class="collapse" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a></li>
                        <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Typography</a>
                        </li>
                    </ul>
                </div>
            </li> -->
            <li class="nav-item">
                <RouterLink class="nav-link" to="/admin/about">
                    <span class="menu-title">
                        Upload
                    </span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </RouterLink>
            </li>
            <li class="nav-item">
                <RouterLink class="nav-link" to="/admin/tables">
                    <span class="menu-title">
                        Tables
                    </span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </RouterLink>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pages/forms/basic_elements.html">
                    <span class="menu-title">Forms</span>
                    <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pages/charts/chartjs.html">
                    <span class="menu-title">Charts</span>
                    <i class="mdi mdi-chart-bar menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pages/tables/basic-table.html">
                    <span class="menu-title">Tables</span>
                    <i class="mdi mdi-table-large menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#general-pages" aria-expanded="false"
                    aria-controls="general-pages">
                    <span class="menu-title">Sample Pages</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-medical-bag menu-icon"></i>
                </a>
                <div class="collapse" id="general-pages">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="pages/samples/blank-page.html"> Blank Page </a></li>
                        <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
                        <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>
                        <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404 </a></li>
                        <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500 </a></li>
                    </ul>
                </div>
            </li>
        </ul>
        <!--  end admin      -->
        <ul class="nav" v-if="user.role == 2">
            <li class="nav-item nav-profile">
                <a href="#" class="nav-link">
                    <div class="nav-profile-text d-flex flex-column">
                        <span class="font-weight-bold mb-2">{{ user.name.toUpperCase() }}</span>
                        <span class="text-secondary text-small">Operator</span>
                    </div>
                    <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                </a>
            </li>
            <li class="nav-item">
                <RouterLink class="nav-link" to="/op/home">
                    <span class="menu-title">
                        Home
                    </span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </RouterLink>
            </li>
            <li class="nav-item">
                <RouterLink class="nav-link" to="/op/siswa">
                    <span class="menu-title">
                        Management Siswa
                    </span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </RouterLink>
            </li>
            <li class="nav-item">
                <RouterLink class="nav-link" to="/op/upload">
                    <span class="menu-title">
                        Management Nilai
                    </span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </RouterLink>
            </li>
            <li class="nav-item">
                <RouterLink class="nav-link" to="/op/residu">
                    <span class="menu-title">
                        Residu
                    </span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </RouterLink>
            </li>
            <li class="nav-item">
                <RouterLink class="nav-link" to="/op/finalisasi">
                    <span class="menu-title">
                        Finalisasi
                    </span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </RouterLink>
            </li>
            <li class="nav-item">
                <RouterLink class="nav-link" to="/op/cp">
                    <span class="menu-title">
                        Change Password
                    </span>
                    <i class="mdi mdi-contacts menu-icon"></i>
                </RouterLink>
            </li>
        </ul>
    </nav>
</template>

<script>
import { useAuthStore } from '../store/index';
export default {
   computed:{
    user(){
        return useAuthStore().user
    }
   }
}
</script>

<style scoped>
.router-link-active>.menu-title {
    background-color: #b7b3e3;
    color: white;
    padding: 10px;
    border-radius: 10px;
}

.router-link-active>.menu-title::before {
    color: white;
}

.router-link-active>.mdi::before {
    color: #5E50F9;
}
</style>

