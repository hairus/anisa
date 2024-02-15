<template>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-home"></i>
                    </span> Upload File
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
                            <h4 class="card-title">Testing Upload File</h4>
                            <div class="d-flex justify-content-center" v-if="loading">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                            <button class="btn btn-sm btn-success mt-4 mb-4" @click="download()">
                                <i class="mdi mdi-file"></i> Download Excel
                            </button>
                            <form @submit.prevent="submitFile()">
                                <div class="form-group">
                                    <label>File upload</label>
                                    <input type="file" name="file" ref="fileupload" class="form-control"
                                           @change="handleUpload" id="validationCustom03" required>
                                </div>

                                <p v-if="errors"></p>
                                <div class="alert alert-danger" role="alert" v-if="errors">
                                    <ul>
                                        <li> Atasn Nama <b>{{ errors.values.name }}</b></li>
                                        <li><b>{{ errors.attribute }}</b> di baris <b>{{ errors.row }}</b></li>
                                    </ul>

                                </div>
                                <div class="alert alert-primary" role="alert" v-if="message">
                                    {{ message }}
                                </div>

                                <button class="btn btn-sm btn-primary">Upload</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Siswa</h4>

                            <button class="btn btn-sm btn-primary mb-3" @click="getData()">
                                <i class="mdi mdi-refresh"></i> Refresh Data Siswa
                            </button>
                            <div class="alert alert-primary col-3" v-if="siswas"> Total Siswa : {{ total }}
                            </div>
                            <div class="d-flex justify-content-start mb-3" v-if="loading1">
                                <div class="spinner-border text-danger" role="status"></div>
                            </div>
                            <table class="table alert-primary text-black">
                                <thead>
                                <th>No</th>
                                <th>NAMA</th>
                                <th>NISN</th>
                                <th>SMA</th>
                                <th>SMP</th>
                                <th>TINGKAT</th>
                                <th>ROMBEL</th>
                                <th>NILAI</th>
                                </thead>
                                <tbody>
                                <tr v-for="(siswa, index) in siswas" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ siswa.name.toUpperCase() }}</td>
                                    <td>{{ siswa.nisn ? siswa.nisn : "NISN TIDAK DI TEMUKAN" }}</td>
                                    <td>{{ siswa.smas ? siswa.smas.nm_sekolah : "SEKOLAH TIDAK DI TEMUKAN" }}</td>
                                    <td>{{ siswa.smps ? siswa.smps.nama_smp : "SEKOLAH TIDAK DI TEMUKAN" }}</td>
                                    <td>{{ siswa.tingkat }}</td>
                                    <td>{{ siswa.rombel }}</td>
                                    <td>{{ siswa.nilai.nilai }}</td>
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

<script>
import axios from 'axios';
import {useAuthStore} from "@/store/index.js";

export default {
    data() {
        return {
            file: "",
            errors: "",
            message: "",
            loading: false,
            loading1: false,
            siswas: [],
            total: "",
            attribute: "",
            baris: ""
        }
    },
    methods: {
        handleUpload(e) {
            this.file = e.target.files[0]
        },
        submitFile() {
            this.loading = true
            let formData = new FormData();
            formData.append('file', this.file);
            axios.post('/api/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    "Accept": "application/json",
                    "Authorization": "Bearer " + useAuthStore().token
                }
            })
                .then(res => {
                    this.loading = false
                    this.clear()
                    this.message = "Upload Berhasil di entri dalam antrian silakan di cek secara berkala"
                    this.getData()
                    setTimeout(() => {
                        this.message = ""
                    }, 10000);


                })
                .catch(e => {
                    if (e.response.status == 403) {
                        this.errors = "tidak hak akses untuk anda";
                    } else if (e.response.status == 402) {
                        this.errors = e.response.data;
                        this.loading = false
                        this.$refs.fileupload.type = 'text';
                        this.$refs.fileupload.type = 'file';
                        this.file = "";
                    } else {
                        this.errors = e.response.data['message']
                    }
                })
        },
        getData() {
            this.loading1 = true
            setTimeout(() => {
                axios.get('/api/op/siswa', {
                    headers: {
                        "Accept": "application/json",
                        "Authorization": "Bearer " + useAuthStore().token
                    }
                })
                    .then(res => {
                        this.loading1 = false
                        this.siswas = res.data.data
                        this.total = res.data.total
                    })
            }, 0);

        },
        download() {
            axios.get('/api/exim', {
                responseType: 'blob',
                headers: {
                    Authorization: "Bearer " + useAuthStore().token
                }
            })
                .then(response => {
                    const url = URL.createObjectURL(new Blob([response.data], {
                        type: 'application/vnd.ms-excel'
                    }))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', "siswa.xlsx")
                    document.body.appendChild(link)
                    link.click()
                })
        },
        clear() {
            this.$refs.fileupload.type = 'text';
            this.$refs.fileupload.type = 'file';
            this.file = "";
            this.errors = "";
        },
    },
    mounted() {
        this.getData()
    }
}
</script>
