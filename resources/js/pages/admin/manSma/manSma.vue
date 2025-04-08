<template>
    <div class="card">
        <div class="card-body">
            <button class="btn btn-sm btn-primary float-end" type="button" @click="ShowModal()">
                <i class="mdi mdi-plus"></i>
            </button>
            <h4 class="card-title">Management SMA</h4>
            <div class="col-4 m-3">
                <input v-model="params.search" type="text" class="form-control" placeholder="Search By NPSN or Name"
                    style="border-radius: 10px; height: 40px; background-color: rgb(239, 239, 239);" />
            </div>

            <div class="table-responsive">
                <vue3-datatable :rows="rows" :pageSizeOptions="pageSizeOptions" :columns="cols" :loading="loading"
                    :totalRows="total_rows" :isServerMode="true" :pageSize="params.pagesize" :sortable="false"
                    @change="changeServer" :search="params.search" class="table table-hover">
                    <template #nm_sekolah="data">
                        <button class="badge-info rounded p-2">{{ data.value.nm_sekolah }}</button>
                    </template>

                    <template #siswa="data">
                        <button class="badge-info rounded p-2">{{ data.value.siswas.length }}</button>
                    </template>

                    <template #actions="data">
                        <div class="flex gap-4">
                            <button type="button" class="badge-gradient-success btn-sm m-1"
                                @click="viewUser(data.value)">
                                <i class="mdi mdi-eye"></i>
                            </button>
                            <button type="button" class="badge-gradient-primary btn-sm m-1" @click="unlockNilai(data.value)">
                                <i class="mdi mdi-lock-clock"></i>
                            </button>
                            <button type="button" class="badge-gradient-info btn-sm m-1" @click="unlock(data.value)">
                                <i class="mdi mdi-lock"></i>
                            </button>
                            <button class="badge-gradient-danger btn-sm">
                                <i class="mdi mdi-delete"></i>
                            </button>
                            <!-- <button type="button" class="btn btn-danger btn-sm"
                                @click="deleteUser(data.value)">Delete</button> -->
                        </div>
                    </template>
                </vue3-datatable>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import axios from 'axios';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import '@bhplugin/vue3-datatable/dist/style.css';

const props = defineProps(['store']);
const loading = ref(true);
const total_rows = ref(0);
const pageSizeOptions = [5, 10, 20, 30, 50, 100];
const params = reactive({
    current_page: 1,
    pagesize: 5,
    search: '',
    column_filters: [],
});
const rows = ref(null);

const cols =
    ref([
        { field: 'id', title: 'No' },
        { field: 'npsn', title: 'NPSN' },
        { field: 'nm_sekolah', title: 'NAMA' },
        { field: 'kode_un', title: 'KODE UN' },
        { field: 'siswa', title: 'SISWA' },
        { field: 'kabs.kab_kota', title: 'Kab/Kota' },
        { field: 'user.password_asli', title: 'Password' },
        { field: 'actions', title: 'Actions' },
    ]) || [];

let timer;

const getSma = () => {
    loading.value = true;
    axios.get('/api/smas?page=' + params.current_page + "&params=" + params.pagesize + "&search=" + params.search, {
        headers: {
            "accept": "application/json",
            "Authorization": "Bearer " + props.store.token
        }
    })
        .then(res => {
            rows.value = res.data.data;
            total_rows.value = res.data.total;
            params.current_page = res.data.to;
            // params.nextArrow = res.data.next_page_url;

        })
    loading.value = false;
};


const filterUsers = () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        getSma();
    }, 300);
};


const changeServer = (data) => {
    params.current_page = data.current_page;
    params.pagesize = data.pagesize;
    params.column_filters = data.column_filters;
    params.search = data.search;

    if (data.change_type === 'search') {
        filterUsers();
    } else {
        getSma();
    }
}

const unlock = (value) => {
    if (confirm('apakah anda akan men Unclok finalisasi ' + value.nm_sekolah)) {
        axios.put('/api/final/' + value.id, {
            id: value.id
        }, {
            headers: {
                "accept": "application/json",
                "Authorization": "Bearer " + props.store.token
            }
        })
            .then(res => {
                alert("berhasil unlock")
            })
    }
}

const unlockNilai = async (data) => {
    if(confirm("apakah sekolah " +data.user.name + ' unlock Siswa?')){
        await axios.get('/api/final/' + data.user.id, {
            headers: {
                "accept": "application/json",
                "Authorization": "Bearer " + props.store.token
            }
        })
            .then(res => {
                alert('unlock berhasil')
            })
    }

}
const viewUser = (user) => {
    showModView(user)
};
const deleteUser = (user) => {
    showMod(user)
};

onMounted(() => {
    getSma()
});
</script>

<style></style>
