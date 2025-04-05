<template>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-home"></i>
                    </span> Management Data
                </h3>
            </div>
            <div class="row" v-if="final != 1">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-center" v-if="loading">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                            <button class="btn btn-sm btn-success mt-4 mb-4" @click="download()">
                                <i class="mdi mdi-file"></i> Download Template
                            </button>
                            <button class="btn btn-sm btn-info mt-4 mb-4 ms-2" @click="downloadSmp()">
                                <i class="mdi mdi-file"></i> Download NPSN SMP/MTS
                            </button>
                            <form @submit.prevent="submitFile()">
                                <div class="form-group">
                                    <label>File upload</label>
                                    <input type="file" name="file" ref="fileupload" class="form-control"
                                        @change="handleUpload" id="validationCustom03" required>
                                </div>
                                <div class="alert alert-danger" role="alert" v-if="errors">
                                    <ul>
                                        <li> Atas Nama <b>{{ errors.values.name }} - {{ errors.attribute }}</b> pada file excel baris Ke-<b>{{ errors.row }}</b></li>
                                    </ul>
                                </div>

                                <div class="alert alert-primary col-4" role="alert" v-if="message">
                                    {{ message }}
                                </div>
                                <div v-if="batchMe.progress === 100 || batchMe.length === 0">
                                    <button class="btn btn-sm btn-primary">Upload</button>
                                </div>
                                <div class="alert alert-success col-4" role="alert" v-else>
                                    File excel dalam antrian...
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <DataTableDemo />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <FormInput />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from "@/store/index.js";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import FormInput from "./formInput.vue"
import DataTableDemo from "@/components/DataTableDemo.vue"

export default {
    data() {
        return {
            file: "",
            errors: "",
            errors1: "",
            message: "",
            loading: false,
            loading1: false,
            siswas: [],
            total: "",
            attribute: "",
            baris: "",
            batchMe: [],
            show: false,
            paginate: 10,
            search: "",
            currentPage: 1,
            sort_direction: "asc",
            sort_field: "id",
            final : useAuthStore().final
        }
    },
    watch: {
        paginate: function (e) {
            this.getData()
        },
        search: function (e) {
            this.getData()
        }
    },
    methods: {
        handleUpload(e) {
            this.file = e.target.files[0]
            this.errors1 = ""
        },
        async submitFile() {
            this.loading = true
            this.errors1 = ""
            let formData = new FormData();
            formData.append('file', this.file);
            await axios.post('/api/upload', formData, {
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
                    this.getBatch()
                    setTimeout(() => {
                        this.message = ""
                    }, 10000);
                    this.getData()


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
                    } else if (e.response.status == 422) {
                        this.loading = false
                        this.errors1 = "Maaf file tidak sesuai dengan format"
                        setTimeout(() => {
                            this.errors1 = ""
                        }, 5000);

                        this.$refs.fileupload.type = 'text';
                        this.$refs.fileupload.type = 'file';
                        this.file = "";
                    } else {
                        this.errors = e.response.data['message']
                    }
                })
        },
        change_sort(field){
            this.sort_field = field
            if(this.sort_field == field){
                this.sort_direction = this.sort_direction == "asc" ? "desc" : "asc"
            }else{
                this.sort_field = field
            }
            this.getData()
        },
        getData(page = 1) {
            this.loading1 = true
            setTimeout(() => {
                axios.get('/api/op/siswa?page=' + page +
                '&paginate=' + this.paginate +
                '&q=' + this.search +
                '&sort_direction=' + this.sort_direction +
                '&sort_field=' + this.sort_field, {
                    headers: {
                        "Accept": "application/json",
                        "Authorization": "Bearer " + useAuthStore().token
                    }
                })
                    .then(res => {
                        this.loading1 = false
                        this.siswas = res.data
                        this.total = res.data.meta.total
                        this.getBatch()
                        this.show = false
                        setTimeout(() => {
                            this.show = true
                        }, 5000)

                    })
            }, 0);

        },
        getBatch() {
            axios.get("/api/upload/create", {
                headers: {
                    "Accept": "application/json",
                    "Authorization": "Bearer " + useAuthStore().token
                }
            })
                .then(res => {
                    this.batchMe = res.data
                })
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
        downloadSmp() {
            axios.get('/api/eximSmp/create', {
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
                    link.setAttribute('download', "smp.xlsx")
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
    components: {
        Bootstrap5Pagination,
        FormInput,
        DataTableDemo
    },
    mounted() {
        this.getData()
        this.getBatch()
    }
}
</script>
