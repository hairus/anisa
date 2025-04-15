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
                <div class="col-md-12">
                    <div class="card mt-10 mb-10">
                        <div class="card-header">
                            <h3 class="m-3">{{ store.user.name }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex">
                <div class="col-md-6 justify-center items-center d-flex">
                    <div class="card mt-10">
                        <div class="d-flex justify-content-center" v-if="loading">
                            <div class="spinner-border text-primary mt-4" role="status"></div>
                        </div>
                        <div class="card-title">
                            <h5 class="ms-5 mt-4">Finalisasi Data Siswa</h5>
                        </div>
                        <div class="card-body">
                            <div class="col">
                                <p>Saya adalah Kepala Sekolah, dengan ini meyatakan bahwa data yang saya masukkan adalah data
                                    yang benar. Apabila kemudian hari terdapat kesalahan maka sepenuhnya menjadi tanggung jawab
                                    saya
                                </p>
                                <div v-if="jumlah !== 0">
                                    <button class="btn btn-danger" disabled>Maaf terdapat siswa nisn ganda {{jumlah}}</button>
                                </div>
                                <div v-else>
                                    <button class="btn btn-sm btn-gradient-success" v-if="store.fs === 0" @click="finalSiswa()">Finalisasi Siswa</button>
                                    <button class="btn btn-sm btn-gradient-success" v-else disabled>Finalisasi Siswa Selesai</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 justify-center items-center d-flex" v-if="store.fs === 1">
                    <div class="card mt-10">
                        <div class="d-flex justify-content-center" v-if="loading">
                            <div class="spinner-border text-primary mt-4" role="status"></div>
                        </div>
                        <div class="card-title">
                            <h5 class="ms-5 mt-4">Finalisasi Nilai</h5>
                        </div>
                        <div class="card-body">
                            <div class="col">
                                <p>Saya adalah Kepala Sekolah, dengan ini meyatakan bahwa data yang saya masukkan adalah data
                                    yang benar. Apabila kemudian hari terdapat kesalahan maka sepenuhnya menjadi tanggung jawab
                                    saya
                                </p>
                                <div v-if="alladata.nilais === 0">
                                    <button class="btn btn-danger" disabled>Maaf data nilai masih kosong</button>
                                </div>
                                <div v-else-if="alladata.nilai0 > 0">
                                    <button class="btn btn-danger" disabled>Maaf terdapat siswa belum diberi nilai</button>
                                </div>
                                <div v-else-if="alladata.nilais !== alladata.siswasDapodik">
                                    <button class="btn btn-danger" disabled>Maaf terdapat perbedaan jumlah data siswa dan data nilai</button>
                                </div>
                                <div v-else>
                                    <div v-if="alladata.length > 0">
                                        <button class="btn btn-sm btn-gradient-primary" @click="finalisasi()">Finalisasi Nilai</button>
                                    </div>
                                    <div v-else>
                                        <button class="btn btn-sm btn-gradient-primary" disabled>Finalisasi Nilai</button>
                                    </div>
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
const nilai = ref(0)
const loading = ref(false)
const alladata = ref([])

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

const getData = async () => {
    await axios.get('/api/op/residu/getdata',{
        headers: {
            "Accept": "application/json",
            "Authorization": "Bearer " + useAuthStore().token
        }
    })
        .then(res => {
            alladata.value = res.data
        })
}

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

const finalSiswa = async () => {
    if(confirm("apakah anda yakin finalisasi siswa?")){
        await axios.post('/api/op/finalSiswa',{}, {
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer ' + store.token,
            },
        }).then((res) => {
            store.fs = res.data;
        });
    }
};

const ceknilai = async () => {
    await axios.get('/api/op/cekNilai', {
        headers: {
            Accept: 'application/json',
            Authorization: 'Bearer ' + store.token,
        }
    })
        .then(res => {
            nilai.value = res.data
        })
}

onMounted(() => {
    getSiswa()
    jumSIswa()
    ceknilai()
    getData()
})
</script>
