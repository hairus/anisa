<template>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
          <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
          </span>
                    Management Siswa
                </h3>
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">
                            <span></span>Overview
                            <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Management Siswa</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12 mb-4 d-flex justify-content-between items-center">
                                <div class="col-md-6">
                                    <input
                                        v-model="params.search"
                                        type="text"
                                        class="form-control rounded-3"
                                        placeholder="Search..."
                                    />
                                </div>
                                <div v-if="store.fs === 0">
                                    <button class="btn btn-gradient-success btn-rounded" @click="openAddModal">
                                        <i class="mdi mdi-plus"></i> Add
                                    </button>
                                    <button class="mx-1 btn btn-gradient-danger btn-rounded" @click="finalSiswa">
                                        <i class="mdi mdi-account-box-multiple"></i>
                                        Finalisasi Siswa
                                    </button>
                                </div>
                            </div>
                            <vue3-datatable
                                :rows="rows"
                                :columns="cols"
                                :loading="loading"
                                :search="params.search"
                                :totalRows="total_rows"
                                :isServerMode="true"
                                :sortColumn="params.nama"
                                :pageSize="params.pagesize"
                                @change="changeServer"
                            >
                                <template #actions="data" v-if="store.fs === 0">
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-gradient-primary btn-rounded btn-icon"  @click="openEditModal(data.value)">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-danger btn-gradient-danger btn-rounded btn-icon"
                                            @click="confirmDelete(data.value)"
                                        >
                                            <i class="mdi mdi-trash-can"></i>
                                        </button>
                                    </div>
                                </template>
                            </vue3-datatable>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Add/Edit Siswa -->
        <div
            class="modal fade"
            id="addModal"
            tabindex="-1"
            aria-labelledby="addModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">{{ isEdit ? 'Edit' : 'Add' }} Siswa</h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitForm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formData.name"
                                    id="name"
                                    required
                                />
                            </div>
                            <div class="mb-3">
                                <label for="nisn" class="form-label">Nisn</label>
                                <input
                                    type="text"
                                    v-model="formData.nisn"
                                    class="form-control"
                                    id="nisn"
                                    required
                                />
                            </div>
                            <div class="mb-3">
                                <label for="rombel" class="form-label">Rombel</label>
                                <input
                                    type="text"
                                    v-model="formData.rombel"
                                    class="form-control"
                                    id="rombel"
                                    placeholder="Kelas Fase E 1"
                                    required
                                />
                            </div>
                            <div class="mb-3">
                                <label for="tingkat" class="form-label">Tingkat</label>
                                <select class="form-control py-3" v-model="formData.tingkat" required>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" @click="saveSiswa">
                                {{ isEdit ? 'Update' : 'Save' }} Siswa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Delete -->
        <div
            class="modal fade"
            id="deleteModal"
            tabindex="-1"
            aria-labelledby="deleteModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin menghapus atas Nama
                        <h3 v-if="user">{{ user.name }}</h3>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                            @click="closeDeleteModal"
                        >
                            No
                        </button>
                        <button type="button" class="btn btn-danger" @click="deleteUser(user)">
                            Yes, Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import '@bhplugin/vue3-datatable/dist/style.css';
import axios from 'axios';
import { useAuthStore } from '@/store/index.js';

const store = useAuthStore();

onMounted(() => {
    getUsers();
});

const isEdit = ref(false); // To toggle between Add and Edit modes
const formData = reactive({
    name: '',
    tingkat: '',
    rombel: '',
    nisn: '',
});

const user = ref(null);

const confirmDelete = (userdata) => {
    user.value = userdata;
    const modal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
};

const closeDeleteModal = () => {
    const modal = window.bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
    modal.hide();
};

const openAddModal = () => {
    isEdit.value = false;
    resetForm();
    const modal = new window.bootstrap.Modal(document.getElementById('addModal'));
    modal.show();
};

const openEditModal = (userdata) => {
    isEdit.value = true;
    formData.id = userdata.id;
    formData.name = userdata.name;
    formData.nisn = userdata.nisn;
    formData.rombel = userdata.rombel;
    formData.tingkat = userdata.tingkat;
    const modal = new window.bootstrap.Modal(document.getElementById('addModal'));
    modal.show();
};

const closeAddModal = () => {
    const modal = window.bootstrap.Modal.getInstance(document.getElementById('addModal'));
    modal.hide();
};

const loading = ref(true);
const total_rows = ref(0);

const params = reactive({ current_page: 1, pagesize: 10, search: '' });
const rows = ref(null);

const cols = ref([
    { field: 'name', title: 'Nama' },
    { field: 'nisn', title: 'Nisn' },
    { field: 'rombel', title: 'Kelas' },
    { field: 'tingkat', title: 'Tingkat' },
    { field: 'sma', title: 'SMA' },
    { field: 'npsn_sekolah_sekarang', title: 'NPSN' },
    { field: 'actions', title: store.fs === 0 ? 'Action' : '' },
]);

const getUsers = async () => {
    try {
        loading.value = true;
        const response = await axios.get(
            '/api/op/getSiswas?page=' +
            params.current_page +
            '&params=' +
            params.pagesize +
            '&search=' +
            params.search,
            {
                headers: {
                    Accept: 'application/json',
                    Authorization: 'Bearer ' + store.token,
                },
            }
        );

        rows.value = response.data?.data.map(user => ({
            ...user,
            name: user.name.toUpperCase(),
        }));
        total_rows.value = response.data?.meta?.total;
    } catch (error) {
        console.error('Error fetching users:', error);
    } finally {
        loading.value = false;
    }
};

const saveSiswa = () => {
    if (isEdit.value) {
        // Update existing siswa
        console.log(formData.id)
        axios.put('/api/op/updateSiswasDapodik/' + formData.id, { form: formData }, {
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer ' + store.token,
            },
        }).then(() => {
            closeAddModal();
            getUsers();
        });
    } else {
        // Add new siswa
        axios.post('/api/op/saveSiswasDapodik', { form: formData }, {
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer ' + store.token,
            },
        }).then(() => {
            closeAddModal();
            getUsers();
        });
    }
};

const resetForm = () => {
    formData.name = '';
    formData.nisn = '';
    formData.rombel = '';
    formData.tingkat = '';
};

const deleteUser = (userdata) => {
    axios.delete('/api/op/delSiswaDapodik/' + userdata.id, {
        headers: {
            Accept: 'application/json',
            Authorization: 'Bearer ' + store.token,
        },
    }).then(() => {
        getUsers();
        closeDeleteModal();
    });
};

const changeServer = (data) => {
    params.current_page = data.current_page;
    params.pagesize = data.pagesize;
    params.search = data.search;
    if (data.change_type === 'search') {
        filterUsers();
    } else {
        getUsers();
    }

};

let timer;
const filterUsers = () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        getUsers();
    }, 300);
};

const finalSiswa = async () => {
    await axios.post('/api/op/finalSiswa', {
        user: user.value,
    }, {
        headers: {
            Accept: 'application/json',
            Authorization: 'Bearer ' + store.token,
        },
    }).then((res) => {
        store.fs = res.data;
    });
};
</script>
