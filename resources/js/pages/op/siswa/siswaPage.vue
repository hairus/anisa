<!-- @format -->

<template>
	<div class="main-panel">
		<div class="content-wrapper">
			<div class="page-header">
				<h3 class="page-title">
					<span class="page-title-icon bg-gradient-primary text-white me-2">
						<i class="mdi mdi-home"></i>
					</span>
					Management Data
				</h3>
				<nav aria-label="breadcrumb">
					<ul class="breadcrumb">
						<li
							class="breadcrumb-item active"
							aria-current="page">
							<span></span>Overview
							<i
								class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
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
										placeholder="Search..." />
								</div>
                                <div v-if="store.fs === 0">
                                    <button class="btn btn-sm btn-info" @click="openAddModal">
                                        <i class="mdi mdi-plus"></i> Add
                                    </button>
                                    <button class="mx-1 btn btn-sm btn-primary" @click="finalSiswa">
                                        <i class="mdi mdi-account-box-multiple"></i>
                                        final Siswa
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
								@change="changeServer">
								<template #actions="data" v-if="store.fs === 0">
									<div class="d-flex">
										<button
											type="button"
											class="btn btn-rounded btn-success"
											@click="viewUser(data.value)">
											<i class="mdi mdi-eye"></i>
										</button>
										<button
											type="button"
											class="btn btn-danger btn-rounded"
											@click="confirmDelete(data.value)">
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
    <!--        modal    -->
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
                        <h5 class="modal-title" id="addModalLabel">Add Siswa</h5>
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
                                <label for="name" class="form-label">Nisn</label>
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
                            <!-- Add more fields as necessary -->

                            <button type="submit" class="btn btn-primary" @click="saveSiswa">
                                Save Siswa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<!--        modal delete -->
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
                        Apakah anda Yakin menghapus atas Nama
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
	</div>Ï
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

    const formData = {
        name:"",
        tingkat:"",
        rombel:"",
        nisn:"",
    }

    const user = ref()

    const confirmDelete = (userdata) => {
        user.value = userdata
        const modal = new window.bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    };


    const closeDeleteModal = () => {
        const modal = window.bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        modal.hide();
    };


    // Function to open the modal
    const openAddModal = () => {
        const modal = new window.bootstrap.Modal(document.getElementById('addModal'));
        modal.show();
    };

    const closeAddModal = () => {
        const modal = window.bootstrap.Modal.getInstance(document.getElementById('addModal'));
        modal.hide(); // This hides the modal
    };

	const loading = ref(true);
	const total_rows = ref(0);

	const params = reactive({ current_page: 1, pagesize: 10, search: '' });
	const rows = ref(null);

	const cols =
		ref([
			{ field: 'name', title: 'Nama' },
			{ field: 'rombel', title: 'Kelas' },
			{ field: 'tingkat', title: 'Tingkat' },
			{ field: 'sma', title: 'SMA' },
			{ field: 'npsn_sekolah_sekarang', title: 'NPSN' },
			{ field: 'actions', title: store.fs === 0 ? 'Action' : '' },
		]) || [];

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
						Authorization: 'Bearer ' + useAuthStore().token,
					},
				}
			);

			rows.value = response.data?.data;
			total_rows.value = response.data?.meta?.total;
		} catch (error) {
			console.error('Error fetching users:', error);
		} finally {
			loading.value = false;
		}
	};

    let timer;
    const filterUsers = () => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            getUsers();
        }, 300);
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

    const saveSiswa = () => {
        axios.post('/api/op/saveSiswasDapodik', {
            form : formData
        }, {
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer ' + useAuthStore().token,
            },
        })
            .then(res => {
                closeAddModal()
                getUsers()
                resetForm()
            })
    }

    const resetForm = () => {
        formData.name = '';
        formData.nisn = '';
        formData.rombel = '';
        formData.tingkat = '';
    };

    const viewUser = (user) => {
		alert(
			'View User \n' +
				user.id +
				', ' +
				user.name
		);
	};
	const deleteUser = () => {
		axios.delete('/api/op/delSiswaDapodik/'+user.value.id, {
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer ' + useAuthStore().token,
            }
        })
            .then(res =>{
                getUsers();
                closeDeleteModal();
            })
	};

    const finalSiswa = async () => {
        await axios.post('/api/op/finalSiswa', {
            user: user
        }, {
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer ' + useAuthStore().token,
            }
        })
            .then(res => {
                store.updateFinalSiswa()
            })
    }
</script>
