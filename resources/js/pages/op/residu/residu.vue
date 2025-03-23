<template>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-home"></i>
                    </span> Residu
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
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-center" v-if="loading">
                                <div class="spinner-border text-danger" role="status"></div>
                            </div>
                            <div class="card-title">
                                RESIDU NISN GANDA
                            </div>
                            <div class="col-3 btn btn-danger mb-3 text-dark">
                                NISN GANDA {{jumlah}}
                            </div>
                            <div class="table table-responsive">
                                <table class="table table-danger">
                                    <thead>
                                    <th>No</th>
                                    <th>NAMA</th>
                                    <th>NISN</th>
                                    <th>ROMBEL</th>
                                    <th>TINGKAT</th>
                                    <th>NPSN SMP</th>
                                    <th>NAMA SMP</th>
                                    <th>NILAI</th>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(siswa, index) in siswas" :key="siswa.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ siswa.name }}</td>
                                        <td>{{ siswa.nisn }}</td>
                                        <td>{{ siswa.rombel }}</td>
                                        <td>{{ siswa.tingkat }}</td>
                                        <td>{{ siswa.npsn_smp }}</td>
                                        <td>{{ siswa.nama_smp }}</td>
                                        <td>{{ siswa.nilai }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import axios from 'axios';
import { useAuthStore } from '../../../store';
const store = useAuthStore();
const siswas = ref([]);
const jumlah = ref()
const loading = ref(false)
const getSiswa = () => {
    loading.value = true
    axios.get('/api/op/residu',  {
        headers: {
            "accept": "application/json",
            "Authorization": "Bearer " + store.token
        }
    })
        .then(res => {
            siswas.value = res.data.siswas
            jumlah.value = res.data.count
            loading.value = false
        })
}

onMounted(() => {
    getSiswa()
})

</script>

<style>
.error {
    border: 1px solid red;
}
</style>
