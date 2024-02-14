<template>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-home"></i>
                    </span> Change Password
                </h3>
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">
                            <span></span>Overview <i
                                class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="row">
                <div class="col-6 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Your Profile</h4>
                            <div class="col">
                                <div class="d-flex">
                                    <img src="../../../../../public/assets/images/faces/face.jpg" alt="profile"
                                        height="200">
                                    <div class="card-body profile-details">
                                        <h5 class="card-title">Hai Operator</h5>
                                        <p class="card-text">{{ store.user.name }}</p>
                                        <p class="card-text"><small class="text-muted">Joined in 2023</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Change Password</h4>
                            <div class="d-flex justify-content-center" v-if="loading">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                            <form @submit.prevent="cp">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="cursor: pointer;" @click="toggleInput"><i
                                            class="mdi mdi-lock"></i></span>
                                    <input id="password" :type="inputType" minlength="8" required v-model="password"
                                        class="form-control" :class="[show ? 'error' : '']" placeholder="Password">
                                </div>

                                <p v-if="errors"></p>
                                <div class="alert alert-danger" role="alert" v-if="errors">
                                    <ul>
                                        <li> Atasn Nama <b>{{ errors.values.name }}</b></li>
                                        <li> {{ errors.attribute }} di baris {{ errors.row }}</li>
                                    </ul>

                                </div>
                                <div class="alert alert-primary" role="alert" v-if="message">
                                    {{ message }}
                                </div>

                                <button class="btn btn-sm btn-primary" type="submit">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios';
import { useAuthStore } from '../../../store';
import router from '../../../routes';
const store = useAuthStore();
const password = ref("");
const message = ref('');
const inputType = ref('password')
const show = ref(false)
const cp = () => {
    axios.post('/api/op/cp', {
        "password": password.value,
    }, {
        headers: {
            "accept": "application/json",
            "Authorization": "Bearer " + store.token
        }
    })
        .then(res => {
            password.value = ""
            message.value = "Update Password Berhasil"
            setTimeout(() => {
                message.value = ""
            }, 2000);
            localStorage.removeItem('auth')
            router.push({path:'/login'});

        })
}

const toggleShow = () => {
    show.value = !show.value
}

const toggleInput = () => {
    inputType.value = (inputType.value === 'password') ? 'text' : 'password';
};

</script>

<style>
.error {
    border: 1px solid red;
}
</style>
