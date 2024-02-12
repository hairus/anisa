<template>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-6 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Management Role User</h4>
                            <div class="mb-5 col-3">
                                <input v-model="params.search" type="text" class="form-control"
                                    placeholder="Search By name" />
                            </div>
                            <div class="table-responsive">
                                <vue3-datatable :rows="rows" :pageSizeOptions="pageSizeOptions" :columns="cols"
                                    :loading="loading" :totalRows="total_rows" :isServerMode="true"
                                    :pageSize="params.pagesize" :sortable="true" @change="changeServer"
                                    :search="params.search" class="table table-hover">
                                    <template #actions="data">
                                        <div class="flex gap-4">
                                            <button type="button" class="btn btn-success btn-sm"
                                                @click="viewUser(data.value)">View</button>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                @click="deleteUser(data.value)">Delete</button>
                                        </div>
                                    </template>
                                </vue3-datatable>
                            </div>
                            <!-- modal hapus -->
                            <Modal v-model:visible="isVisible" title="Delete" style="color: red;" :okButton="{text: 'Delete', onclick: () => eksekusi(), loading: true}">
                                <p>anda yakin menghapus</p>
                                <div>{{ user.name }}</div>
                            </Modal>
                            <!-- modal show -->
                            <Modal v-model:visible="visible" width="700px" title="Show" :okButton="{text: 'OK', onclick: () => eksekusi1(), loading: true}">
                                <ul>
                                    <li>{{ user.name }}</li>
                                    <li><b>{{ user.role.roles['role'] }}</b></li>
                                </ul>
                                <!-- <form>
                                    <div class="form-group">
                                        <label for="">Pilih</label>
                                        <input type="text" name="" id="" class="form-control">
                                    </div>
                                </form> -->
                            </Modal>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- <vue3-datatable :rows="rows" :columns="cols" :loading="loading"> </vue3-datatable> -->
</template>
<script setup>
import { ref, onMounted, reactive } from 'vue';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import '@bhplugin/vue3-datatable/dist/style.css';
import axios from 'axios';
import { useAuthStore } from "../store/index";
import { Modal } from 'usemodal-vue3';

let isVisible = ref(false);
let visible = ref(false);

const user = ref()

const showMod = (data) => {
    user.value = data
    isVisible.value = !isVisible.value;
}

const showModView = (data) => {
    user.value = data
    visible.value = !visible.value;

}

const eksekusi = () => {
   showMod()
   user.value = ""
}

const eksekusi1 = () => {
    showModView()
    user.value = ""
}

const store = useAuthStore();
const loading = ref(true);
const total_rows = ref(0);
const pageSizeOptions = [2, 10, 20, 30, 50, 100];
const params = reactive({
    current_page: 1,
    pagesize: 10,
    search: '',
    column_filters: [],
});
const rows = ref(null);

const cols =
    ref([
        { field: 'id', title: 'ID' },
        { field: 'name', title: 'First Name' },
        { field: 'role.roles.role', title: 'Role' },
        { field: 'actions', title: 'Actions' },
    ]) || [];

let timer;


const getUsers = async () => {
    try {
        loading.value = true;
        const res = await axios.get("/api/roleUsers?page=" + params.current_page+ {
            headers: {
                "Authorization": "Bearer " + store.token
            }
        })
            .then(res => {
                rows.value = res.data.data;
                total_rows.value = res.data.total;
                params.current_page.value = res.data.to;
                params.nextArrow = res.data.next_page_url;
            })
    } catch { }

    loading.value = false;
};

const carinama = async () => {
    await axios.get("/api/roleUsers/" + params.search, {
        headers: {
            "Authorization": "Bearer " + store.token
        },
    })
        .then(res => {
            rows.value = res.data.data;
            total_rows.value = res.data.total;
            params.current_page.value = res.data.to;
            params.nextArrow = res.data.next_page_url;
        })
}

const filterUsers = () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        carinama();
    }, 300);
};


const changeServer = (data) => {
    // console.log(data);
    params.current_page = data.current_page;
    params.pagesize = data.pagesize;
    params.column_filters = data.column_filters;
    params.search = data.search;

    if (data.change_type === 'search') {
        filterUsers();
    } else {
        getUsers();
    }
}

const viewUser = (user) => {
    showModView(user)
};
const deleteUser = (user) => {
    showMod(user)
};


onMounted(() => {
    getUsers();
});

</script>
