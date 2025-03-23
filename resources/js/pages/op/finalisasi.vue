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
                <div class="col-md-8">
                    <div class="card mt-10">
                        <div class="card-header">
                            <h3 class="m-3">{{ store.user.name }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex">
                <div class="col-md-8 justify-center items-center d-flex">
                    <div class="card mt-10">
                        <div class="d-flex justify-content-center" v-if="loading">
                            <div class="spinner-border text-primary mt-4" role="status"></div>
                        </div>
                        <div class="card-title">
                            <h5 class="ms-5 mt-4">Finalisasi Data Siswa dan nilai</h5>
                        </div>
                        <div class="card-body">
                            <p>Saya adalah Kepala Sekolah, dengan ini meyatakan bahwa data yang saya masukkan adalah data
                                yang benar. Apabila kemudian hari terdapat kesalahan maka sepenuhnya menjadi tanggung jawab
                                saya
                            </p>
                            <div v-if="jumlah !== 0">
                                    <button class="btn btn-danger" disabled>Maaf terdapat siswa nisn ganda {{jumlah}}</button>
                            </div>
                            <div v-else>
                                <button class="btn btn-sm btn-primary" v-if="store.final === 1" disabled>Final</button>
                                <div v-else>
                                    <button class="btn btn-sm btn-primary" v-if="jum === 0" disabled>jumlah Siswa 0</button>
                                    <button class="btn btn-sm btn-primary" v-else @click="finalisasi()">Finalisasi Data</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script setup>
import { useAuthStore } from "@/store/index.js";
import axios from "axios";
import {onMounted, ref} from "vue";
const store = useAuthStore();
const siswas = ref([]);
const jumlah = ref()
const jum = ref()
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
const finalisasi = () => {
    if(confirm('apakah anda yakin untuk finalisasi ?')){
        axios.post('/api/op/final', {
            "rix": "rix"
        }, {
            headers: {
                "Accept": "application/json",
                "Authorization": "Bearer " + useAuthStore().token
            }
        })
            .then(res => {
                store.final = res.data
            })
    }

};

const jumSIswa = async () => {
    await axios.get('/api/op/getSiswa', {
        headers: {
            "Accept": "application/json",
            "Authorization": "Bearer " + store.token
        }
    })
        .then(res => {
            jum.value = res.data.siswa
        })
};
onMounted(() => {
    getSiswa()
    jumSIswa()
})
</script>
