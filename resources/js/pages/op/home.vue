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
                <div class="col-md-6 stretch-card grid-margin">
                    <div class="card bg-gradient-danger card-img-holder text-white">
                        <div class="card-body">
                            <img src="../../../../public/assets/images/dashboard/circle.svg" class="card-img-absolute"
                                alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">Status Finalisasi <i
                                    class="mdi mdi-chart-line mdi-24px float-right"> </i>
                            </h4>
                            <h2 class="mb-5" v-if="store.final == 1">Sudah</h2>
                            <h2 class="mb-5" v-else>Belum</h2>
                            <h6 class="card-text"></h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 stretch-card grid-margin">
                    <div class="card bg-gradient-info card-img-holder text-white">
                        <div class="card-body">
                            <img src="../../../../public/assets/images/dashboard/circle.svg" class="card-img-absolute"
                                alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">Jumlah Siswa <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                            </h4>
                            <div class="spinner-border text-white" role="status" v-show="loading"></div>
                            <h2 class="mb-5" v-if="loading === false">Jumlah Siswa {{ siswas }}</h2>
                            <h6 class="card-text"></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-10">
                        <div class="card-header">
                            <h3 class="m-3">{{ store.user.name }}</h3>
                        </div>
                        <div class="card-title">
                            <h5 class="ms-5 mt-5"> Status Finalisasi</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Jenis Finalisasi</th>
                                    <th>Keterangan</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Finalisasi Siswa</td>
                                        <td class="text-success" v-if="store.fs === true">Sudah</td>
                                        <td class="text-danger" v-else>belum</td>
                                    </tr>
                                    <tr>
                                        <td>Finalisasi Sekolah</td>
                                        <td class="text-success" v-if="store.final == 1">Sudah</td>
                                        <td class="text-danger" v-else>Belum</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card my-10">
                        <div class="card-header">
                            <h3 class="m-3">REKAP PENGISIAN ANISA JATIM</h3>
                        </div>
                        <div class="card-title">
                            <h5 class="ms-5 mt-5">Status Finalisasi</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Jenis Data</th>
                                    <th>Kelas 10</th>
                                    <th>Kelas 11</th>
                                    <th>Kelas 12</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Data Siswa Dan Nilai</td>
                                        <td>{{ kelas10 }}</td>
                                        <td>{{ kelas11 }}</td>
                                        <td>{{ kelas12 }}</td>
                                        <td>{{ siswas }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue'
import { useAuthStore } from "@/store/index.js";



const store = useAuthStore();
const siswas = ref([]);
const loading = ref(false)
const kelas10 = ref()
const kelas11 = ref()
const kelas12 = ref()

const getSIswa = async () => {
    loading.value = true
    await axios.get('/api/op/getSiswa', {
        headers: {
            "Accept": "application/json",
            "Authorization": "Bearer " + store.token
        }
    })
        .then(res => {
            siswas.value = res.data.siswa
            kelas10.value = res.data.kelas10
            kelas11.value = res.data.kelas11
            kelas12.value = res.data.kelas12
            loading.value = false
        })
};

onMounted(() => {
    getSIswa()
});
</script>

<style></style>
