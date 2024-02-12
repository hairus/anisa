

<template>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-home"></i>
                    </span> Dashboard
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
                <div class="col-md-3 stretch-card grid-margin">
                    <div class="card bg-gradient-danger card-img-holder text-white">
                        <div class="card-body">
                            <img src="../../../../public/assets/images/dashboard/circle.svg" class="card-img-absolute"
                                alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">SMA/SMK SE JAWA TIMUR <i
                                    class="mdi mdi-chart-line mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5">{{ kabs.length }} Kab/Kota</h2>
                            <h6 class="card-text">JUMLAH SMA/SMK {{ smas.length }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 stretch-card grid-margin">
                    <div class="card bg-gradient-info card-img-holder text-white">
                        <div class="card-body">
                            <img src="../../../../public/assets/images/dashboard/circle.svg" class="card-img-absolute"
                                alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">jUMLAH OPERATOR <i
                                    class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5">38</h2>
                            <h6 class="card-text">Decreased by 10%</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 stretch-card grid-margin">
                    <div class="card bg-gradient-success card-img-holder text-white">
                        <div class="card-body">
                            <img src="../../../../public/assets/images/dashboard/circle.svg" class="card-img-absolute"
                                alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">Visitors Online <i
                                    class="mdi mdi-diamond mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5">95,5741</h2>
                            <h6 class="card-text">Increased by 5%</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 stretch-card grid-margin">
                    <div class="card bg-gradient-secondary card-img-holder text-white">
                        <div class="card-body">
                            <img src="../../../../public/assets/images/dashboard/circle.svg" class="card-img-absolute"
                                alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">Visitors Online <i
                                    class="mdi mdi-diamond mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5">95,5741</h2>
                            <h6 class="card-text">Increased by 5%</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 grid-margin">
                <man-sma :store="store"/>
                </div>
                <div class="col-6 grid-margin">
                <man-smp :store="store"/>
                </div>
            </div>
            <div class="row">
                <div class="col-6 grid-margin">
                    <man-role :roles="roles"/>
                </div>
                <div class="col-6 grid-margin">
                    <man-operator :store="store"/>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
        <modal-add @role="simpan"  @message="clearMessage" :errors="errors" />

        <!-- partial:partials/_footer.html -->
        <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
                <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © bootstrapdash.com
                    2021</span>
                <span class="float-none float-sm-end mt-1 mt-sm-0 text-end"> Free <a
                        href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin
                        template</a> from Bootstrapdash.com</span>
            </div>
        </footer>
        <!-- partial -->
    </div>
</template>
<script setup>

import {onMounted, ref} from "vue";
import axios from 'axios';
import modalAdd from './modalAdd.vue';
import { useAuthStore } from '../../store/';
import manSmp from './manSmp/manSmp.vue'
import manSma from './manSma/manSma.vue'
import manRole from './manRole/manRole.vue'
import manOperator from "./manOperator/manOperator.vue"

const roles = ref();
const errors = ref();
const kabs = ref([]);
const smas = ref([]);
const store = useAuthStore();

const getKabs = () => {
    axios.get('/api/kabs', {
        headers: {
            "accept": "application/json",
            "Authorization": "Bearer " + store.token
        }
    })
    .then(res => {
        kabs.value = res.data.kabs;
        smas.value = res.data.smas;
    })
}


const ShowModal = () => {
    var myModal = new bootstrap.Modal(document.getElementById('myModal'))
    myModal.show()
}

const getRoles = () => {
    axios.get("/api/roles", {
                headers: {
                    "accept": "application/json",
                    "Authorization": "Bearer " + store.token
                }
            })
        .then(res => {
            roles.value = res.data
        })
}

const del = (id) => {
    axios.delete("/api/roles/" + id, {
                headers: {
                    "accept": "application/json",
                    "Authorization": "Bearer " + useAuthStore().token
                }
            })
                .then(res => {
                    getRoles()
                })
}

const simpan = (role) => {
    axios.post('/api/roles', {
                "role": role
            }, {
                headers: {
                    "accept": "application/json",
                    "Authorization": "Bearer " + useAuthStore().token
                }
            })
                .then(() => {
                    getRoles()
                    hideModal()
                })
        .catch(e => {
            errors.value = e.response.data['message'];
        })
}

const hideModal = () => {
   document.getElementById("Close").click();
}

const clearMessage = () => {
    errors.value = "";
}

onMounted(() => {
    getRoles()
    getKabs()
})

</script>
