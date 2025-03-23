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
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-danger card-img-holder text-white">
                        <div class="card-body">
                            <img src="../../../public/assets/images/dashboard/circle.svg" class="card-img-absolute"
                                alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">SMA/SMK SE JAWA TIMUR <i
                                    class="mdi mdi-chart-line mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5">130 Kab/Kota</h2>
                            <h6 class="card-text">JUMLAH SISWA 12.000</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-info card-img-holder text-white">
                        <div class="card-body">
                            <img src="../../../public/assets/images/dashboard/circle.svg" class="card-img-absolute"
                                alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">jUMLAH OPERATOR <i
                                    class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5">38</h2>
                            <h6 class="card-text">Decreased by 10%</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-success card-img-holder text-white">
                        <div class="card-body">
                            <img src="../../../public/assets/images/dashboard/circle.svg" class="card-img-absolute"
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
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Testing Upload File</h4>
                            <div class="d-flex justify-content-center" v-if="loading">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
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
                                        <li> <b>{{ errors.attribute }}</b> di baris <b>{{ errors.row }}</b></li>
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


                <div class="col-6 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Testing Upload File</h4>
                            <div class="d-flex justify-content-center" v-if="loading1">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                            <div class="table-responsive">
                                <button class="btn btn-sm btn-info float-end" @click="download()">
                                    <i class="mdi mdi-file"></i>
                                </button>
                                <button class="btn btn-sm btn-primary float-end" @click="getData()">
                                    <i class="mdi mdi-refresh"></i>
                                </button>

                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Nis</th>
                                        <th>Rerata</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(siswa, index) in siswas" :key="siswa.id">
                                        <td>{{ index + 1 }}</td>
                                            <td>{{ siswa.name }}</td>
                                            <td>{{ siswa.nisn }}</td>
                                            <td>{{ siswa.nilai.nilai }}</td>
                                        </tr>
                                    </tbody>
                                    total siswa = {{ total }}
                                </table>
                                <button class="btn btn-sm btn-primary float-end" @click="getData()">
                                    <i class="mdi mdi-refresh"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- partial -->
    </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from '../store/index';
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
                    this.message = "Upload Berhasil masuk dalam antrian silakan di cek secara berkala"
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
                axios.get('/api/upload', {
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
            // console.log("kesini");
            axios.get('/api/exim', {
                responseType: 'blob',
                headers:{
                    Authorization : "Bearer "+useAuthStore().token
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

<style></style>
