<template>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo">
                                <img src="../../../public/assets/images/logo.svg">
                            </div>
                            <h4>Anisa Jatim</h4>
                            <h6 class="font-weight-light">Analisi Nilai Sekolah Asal</h6>
                            <form class="pt-3" @submit.prevent="login">
                                <div class="form-group">
                                    <input type="email" v-model="email" class="form-control form-control-lg"
                                        id="exampleInputEmail1" placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" v-model="password" class="form-control form-control-lg"
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
            email: "zhomenep@gmail.com",
            password: "password"
        }
    },
    methods: {
        login() {
            axios.post('/api/login', {
                "email": this.email,
                "password": this.password
            })
                .then(res => {
                    if (res.data) {
                        console.log(res.data.role)
                        if(res.data.role == 1){
                            this.$router.push('/admin/dashboard');
                        }else{
                            this.$router.push('/op/home');
                        }
                        useAuthStore().isLogin(res.data['token'], res.data);
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
}</style>
