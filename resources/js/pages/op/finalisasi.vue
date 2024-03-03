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
                            <h3 class="m-3">{{store.user.name}}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex">
                <div class="col-md-8 justify-center items-center d-flex">
                    <div class="card mt-10">
                        <div class="card-title">
                            <h5 class="ms-5 mt-4">Finalisasi Data Siswa dan nilai</h5>
                        </div>
                        <div class="card-body">
                            <p>Saya adalah Kepala Sekolah, dengan ini meyatakan bahwa data yang saya masukkan adalah data
                                yang benar. Apabila kemudian hari terdapat kesalahan maka sepenuhnya menjadi tanggung jawab
                                saya
                            </p>
                            <button class="btn btn-sm btn-primary">Finalisasi Data</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAuthStore } from "@/store/index.js";
const store = useAuthStore();
</script>
