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
                                        <li> {{ errors.attribute }} di baris {{ errors.row }}</li>
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
                                        <li> {{ errors.attribute }} di baris {{ errors.row }}</li>
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
        </div>
    </div>
</template>

<script setup>

</script>

<style></style>
