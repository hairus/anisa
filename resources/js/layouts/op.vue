<template>
    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <router-link class="navbar-brand brand-logo" to="/dashboard">
            <h3 class="text-black" style="font-weight: 400;">ANISA JATIM</h3>
        </router-link>
          <router-link class="navbar-brand brand-logo-mini" to="/dashboard"><img src="../../../public/assets/images/logo-mini.svg" alt="logo" /></router-link>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <div class="search-field d-none d-md-block">
            <form class="d-flex align-items-center h-100" action="#">
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>
                </div>
                <input type="text" class="form-control bg-transparent border-0" placeholder="Search projects">
              </div>
            </form>
          </div>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-text">
                  <p class="mb-1 text-black">{{ user.name.toUpperCase() }}</p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                <router-link class="dropdown-item" to="#" @click="loginout">
                  <i class="mdi mdi-logout me-2 text-primary"></i> Signout</router-link>
              </div>
            </li>
          </ul>
        </div>
      </nav>
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <menu-page />
        <!-- partial -->
        <router-view></router-view>
        <!-- main-panel ends -->
      </div>




</template>

<script>
import axios from "axios";
import { useAuthStore } from '../store/index';
import menuPage from "../components/SidebarPage.vue"
// import contentPage from "../components/contents.vue"
export default{
    methods :{
        loginout(){
           axios.post('/api/logout', {
            headers:{
                "accept" : "application/json",
                "Authorization" : "Bearer "+ useAuthStore().token
            }
           })
           .then(res => {
            useAuthStore().isLogout();
            this.$router.push('/login');
           })
        }
    },
    computed:{
        user(){
            return useAuthStore().user;
        }
    },
    components:{
        menuPage,
        // contentPage
    }
};
</script>
