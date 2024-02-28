<template>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo d-flex mx-auto">
                                <img src="../../../public/logo.png" class="mx-auto">
                            </div>
                            <div class="d-flex">
                                <h2 class="mx-auto">ANISA JATIM</h2>
                            </div>
                            <div class="d-flex">
                                <h6 class="font-weight-light mx-auto">APLIKASI NILAI SEKOLAH ASAL</h6>
                            </div>
                            <div class="d-flex">
                                <h3 class="font-weight-light mx-auto">TIKP DISDIK JATIM</h3>
                            </div>
                            <div class="alert alert-danger" role="alert" v-if="errors">
                                {{ errors }}
                            </div>
                            <form class="pt-3" @submit.prevent="login">
                                <div class="form-group">
                                    <input type="text" style="background-color: purple;" v-model="username" class="form-control form-control-lg"
                                        id="exampleInputEmail1" placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" v-model="password" style="background-color: purple;" class="form-control form-control-lg"
                                        id="exampleInputPassword1" placeholder="Password" required>
                                </div>
                                <div class="mt-3">
                                    <button type="submit"
                                        class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">Log
                                        In</button>
                                </div>
                            </form>
                            <hr>

                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <p id="dindik">Pemprov Dindik Jatim Copyright © 2023-2024</p>
        <!-- page-body-wrapper ends -->
    </div>
</template>

<script>
import axios from "axios";
import { useAuthStore } from '../store/index';
export default {
    data() {
        return {
            username: "",
            password: "",
            errors: ""
        }
    },
    methods: {
        login() {
            axios.post('/api/login', {
                "username": this.username,
                "password": this.password
            })
                .then(res => {
                    if (res.data) {
                        if (res.data.role == 1) {
                            this.$router.replace('/admin/dashboard');
                        } else {
                            this.$router.replace('/op/home');
                        }
                        useAuthStore().isLogin(res.data['token'], res.data);
                    }
                })
                .catch(e => {
                    if (e.response.status === 500) {
                        this.errors = "username and password tidak cocok"
                        setTimeout(() => {
                            this.errors = ""
                        }, 2000);
                    }
                })
        }
    }
}
</script>
<style>
#dindik {
    display: flex;
    align-items: center;
    /* Vertical centering */
    justify-content: center;
    font-weight: 600;
    /* Horizontal centering */
    /* 100% tinggi viewport */
}
</style>
