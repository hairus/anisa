<template>
    <div class="card">
        <div class="card-body">
<!--            <button class="btn btn-sm btn-primary float-end" type="button" @click="generateUser()">-->
<!--                <i class="mdi mdi-plus"></i>-->
<!--            </button>-->
            <h4 class="card-title">Management Users</h4>
            <div class="col-4 m-3">
                <input v-model="params.search" type="text" class="form-control" placeholder="Search By NPSN or Name"
                       style="border-radius: 10px; height: 40px; background-color: rgb(239, 239, 239);"/>
            </div>
            <div class="table-responsive">
                <vue3-datatable :rows="rows" :pageSizeOptions="pageSizeOptions" :columns="cols" :loading="loading"
                                :totalRows="total_rows" :isServerMode="true" :pageSize="params.pagesize"
                                :sortable="false"
                                @change="changeServer" :search="params.search" class="table table-hover">
                    <template #role.roles.role="data">
                        <button class="badge-gradient-dark rounded p-2">{{ data.value.role.roles.role }}</button>
                    </template>
                    <template #actions="data">
                        <div class="flex gap-4">
                            <button type="button" class="badge-gradient-success btn-sm m-1"
                                    @click="showUser(data.value)"><i class="mdi mdi-eye"></i></button>
                            <button class="badge-gradient-danger btn-sm" @click="showDelete(data.value)"><i
                                class="mdi mdi-delete"></i></button>
                        </div>
                    </template>
                </vue3-datatable>
            </div>
            <Modal v-model:visible="isVisible" title="SHOW USER" :okButton="{text: 'CLOSE'}"
                   :cancelButton="{text:'cancel'}">
                <ul>
                    <li>{{ user.name.toUpperCase() }}</li>
                    <li>{{ user.email }}</li>
                    <li><span class="badge-gradient-dark p-1 rounded-2">{{ user.role.roles.role }}</span></li>
                </ul>
            </Modal>

            <Modal v-model:visible="visible" title="DELETE USER" style="color: red">
                <ul>
                    <li>{{ user.name.toUpperCase() }}</li>
                    <li>{{ user.email }}</li>
                    <li><span class="badge-gradient-dark p-1 rounded-2">{{ user.role.roles.role }}</span></li>
                </ul>
            </Modal>
        </div>
    </div>
</template>

<script setup>

import {ref, onMounted, reactive} from 'vue'
import axios from 'axios';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import '@bhplugin/vue3-datatable/dist/style.css';
import {Modal} from 'usemodal-vue3';

let isVisible = ref(false);
let visible = ref(false);

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

const user = ref()

const rows = ref(null);
const cols =
    ref([
        {field: 'id', title: 'ID'},
        {field: 'name', title: 'NAMA'},
        {field: 'username', title: 'USERNAME'},
        {field: 'role.roles.role', title: 'ROLE'},
        {field: 'actions', title: 'Actions'},
    ]) || [];

const showUser = (data) => {
    user.value = data
    isVisible.value = true
}

const showDelete = (data) => {
    user.value = data
    visible.value = true
}

const generateUser = () => {
    axios.get('/api/users/create', {
        headers:{
            "accept": "application/json",
            "Authorization" : `Bearer `+props.store.token
        }
    })
        .then(res => {

        })
}

let timer;

const getSma = () => {
    loading.value = true;
    axios.get('/api/users?page=' + params.current_page + "&params=" + params.pagesize + "&search=" + params.search, {
        headers:{
            "accept": "application/json",
            "Authorization" : `Bearer `+props.store.token
        }
    })
        .then(res => {
            rows.value = res.data.data;
            total_rows.value = res.data.total;
            params.current_page = res.data.to;

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

