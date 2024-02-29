<template>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-home"></i>
                    </span> Management Data
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
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                            <button class="btn btn-sm btn-success mt-4 mb-4" @click="download()">
                                <i class="mdi mdi-file"></i> Download Template
                            </button>
                            <button class="btn btn-sm btn-info mt-4 mb-4 ms-2" @click="downloadSmp()">
                                <i class="mdi mdi-file"></i> Download Npsn SMP
                            </button>
                            <form @submit.prevent="submitFile()">
                                <div class="form-group">
                                    <label>File upload</label>
                                    <input type="file" name="file" ref="fileupload" class="form-control"
                                        @change="handleUpload" id="validationCustom03" required>
                                </div>
                                <div class="alert alert-danger" role="alert" v-if="errors">
                                    <ul>
                                        <li> Atas Nama <b>{{ errors.values.name }}</b></li>
                                        <li><b>{{ errors.attribute }}</b> di baris <b>{{ errors.row }}</b></li>
                                    </ul>
                                </div>
                                <div class="alert alert-danger col-6" role="alert" v-if="errors1">
                                    <ul>
                                        <li><b>{{ errors1 }}</b></li>
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
                            <h4 class="card-title">Siswa</h4>

                            <button class="btn btn-sm btn-primary mb-3" @click="getData()" v-show="show">
                                <i class="mdi mdi-refresh"></i> Refresh Data Siswa
                            </button>
                            <div class="alert alert-primary col-3" v-if="siswas"> Total Siswa : {{ total }}
                            </div>
                            <div class="mb-3" v-if="loading1">
                                <div class="spinner-border text-danger" role="status"></div>
                            </div>
                            <div class="d-flex">
                                <div class="d-flex justify-content-between col-12">
                                    <div class="col-1 me-3 mt-3 mb-3">
                                        <div class="form-group">
                                            <select class="form-control text-black p-3" v-model="paginate"
                                                style="background-color: #f0e2ff;">
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control rounded-3 text-black" v-model.lazy="search"
                                            style="background-color: #ded2eb;" placeholder="Jony Batu"
                                            aria-label="Username">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive" style="font-size: 12px">
                                <table class="table alert-primary text-black">
                                    <thead>
                                        <th>
                                            <a href="#" @click.prevent="change_sort('id')">No</a>
                                            <span v-if="sort_direction == 'desc' && sort_field == 'id'">&uarr;</span>
                                            <span v-if="sort_direction == 'asc' && sort_field == 'id'">&darr;</span>
                                        </th>
                                        <th>
                                            <a href="#" @click.prevent="change_sort('name')">NAMA</a>
                                            <span v-if="sort_direction == 'asc' && sort_field == 'name'">&uarr;</span>
                                            <span v-if="sort_direction == 'desc' && sort_field == 'name'">&darr;</span>
                                        </th>
                                        <th>
                                            <a href="#" @click.prevent="change_sort('nisn')">NISN</a>
                                            <span v-if="sort_direction == 'asc' && sort_field == 'nisn'">&uarr;</span>
                                            <span v-if="sort_direction == 'desc' && sort_field == 'nisn'">&darr;</span>
                                        </th>
                                        <th>
                                            <a href="#" @click.prevent="change_sort('tingkat')">TINGKAT</a>
                                            <span v-if="sort_direction == 'asc' && sort_field == 'tingkat'">&uarr;</span>
                                            <span v-if="sort_direction == 'desc' && sort_field == 'tingkat'">&darr;</span>
                                        </th>
                                        <th>
                                            <a href="#" @click.prevent="change_sort('rombel')">ROMBEL</a>
                                            <span v-if="sort_direction == 'asc' && sort_field == 'rombel'">&uarr;</span>
                                            <span v-if="sort_direction == 'desc' && sort_field == 'rombel'">&darr;</span>
                                        </th>
                                        <th>NILAI</th>
                                        <th>
                                            NPSN SMP
                                        </th>
                                        <th>
                                            SMP
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(siswa, index) in siswas.data" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ siswa.name.toUpperCase() }}</td>
                                            <td>{{ siswa.nisn ? siswa.nisn : "NISN TIDAK DI TEMUKAN" }}</td>
                                            <td>{{ siswa.tingkat }}</td>
                                            <td>{{ siswa.rombel }}</td>
                                            <td>{{ siswa.nilai }}</td>
                                            <td>{{ siswa.smps ? siswa.npsn_smp : "SEKOLAH TIDAK DI TEMUKAN" }}</td>
                                            <td>{{ siswa.smps ? siswa.smps : "SEKOLAH TIDAK DI TEMUKAN" }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class=" d-flex justify-center items-center">
                                <Bootstrap5Pagination :data="siswas" :limit=2 @pagination-change-page="getData" class="mt-5"
                                    style="background-color: aqua;" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from "@/store/index.js";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

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
            sort_field: "id"
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
                        // this.getBatch()
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
        Bootstrap5Pagination
    },
    mounted() {
        this.getData()
        this.getBatch()
    }
}
</script>
