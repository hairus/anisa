<template>
    <div class="card">
        <div class="card-body">
            <button class="btn btn-sm btn-primary float-end" type="button" @click="ShowModal">
                <i class="mdi mdi-plus"></i>
            </button>
            <h4 class="card-title">Management SMP</h4>
            <div class="col-4 m-3">
                <input v-model="params.search" type="text" class="form-control" placeholder="Search By NPSN or Name" style="border-radius: 10px; height: 40px; background-color: rgb(239, 239, 239);" />
            </div>

            <div class="table-responsive">
                <vue3-datatable :rows="rows" :pageSizeOptions="pageSizeOptions" :columns="cols" :loading="loading"
                    :totalRows="total_rows" :isServerMode="true" :pageSize="params.pagesize" :sortable="false"
                    @change="changeServer" :search="params.search" class="table table-hover">
                    <template #nama_smp="data">
                        <button class="badge-info rounded p-2">{{ data.value.nama_smp }}</button>
                    </template>
                    <template #actions="data">
                        <div class="flex gap-4">
                            <button class="badge-gradient-danger btn-sm" @click="delSmp(data)"><i class="mdi mdi-delete"></i></button>
                        </div>
                    </template>
                </vue3-datatable>
            </div>
<!--            modal -->
            <Modal v-model:visible="visible" title="Add SMP" :okButton="{text: 'Save', onclick: saveSmp, loading: false}">
                <div class="modal-body">
                    <form action="">
                        <div class="form-group">
                            <label for="">NPSN SMP</label>
                            <input type="text" v-model="form.npsn" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">NAMA SMP</label>
                            <input type="text" class="form-control" v-model="form.smp">
                        </div>
                        <div class="form-group">
                            <label for="">KODE UN</label>
                            <input type="text" class="form-control" v-model="form.kodeUn">
                        </div>
                        <div class="form-group">
                            <label for="">KAB KOTA</label>
                            <select class="form-control" v-model="form.kab_id">
                                <option v-for="kab in kabs" :key="kab.id" :value="kab.id">{{ kab.kab_kota }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Jenjang</label>
                            <select class="form-control" v-model="form.jenjang">
                                <option value="SMP">SMP</option>
                                <option value="MTs">MTs</option>
                                <option value="Ponpes">Ponpes</option>
                                <option value="PKMB">PKMB</option>
                                <option value="SMPTK">SMPTK</option>
                                <option value="SLB">SLB</option>
                                <option value="SMPLB">SMPLB</option>
                            </select>
                        </div>
                    </form>
                </div>
            </Modal>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import axios from 'axios';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import '@bhplugin/vue3-datatable/dist/style.css';
import {Modal} from 'usemodal-vue3';

const props = defineProps(['store'])
const emits = defineEmits(['smps'])
const loading = ref(true);
const total_rows = ref(0);
const pageSizeOptions = [5 ,10, 20, 30, 50, 100];
const params = reactive({
    current_page: 1,
    pagesize: 5,
    search: '',
    column_filters: [],
});

const rows = ref(null);

const form = ref({
    npsn:null,
    smp:null,
    kodeUn:null,
    kab_id:null,
    jenjang:null
})



let visible = ref(false);
const ShowModal = () => {
    visible.value = true
}

const kabs = ref([]);

const saveSmp = async () => {
    await axios.post('/api/smps', {
        form:form.value
    }, {
        headers:{
            "accept" : "application/json",
            "Authorization": "Bearer " + props.store.token
        }
    })
        .then(() => {
            resetForm();
            visible.value = false
            getSmp()
        })
}

const resetForm = () => {
    form.value = {
        npsn:null,
        smp:null,
        kodeUn:null,
        kab_id:null,
        jenjang:null
    }
}

const delSmp = async (data) => {
    if(confirm("apakah anda Yakin Menghapus SMP ?")){
        await axios.delete('/api/smps/'+data.value.id, {
            headers:{
                "accept" : "application/json",
                "Authorization": "Bearer " + props.store.token
            }
        })
            .then(res => {
                getSmp()
            })
    }

}

const getKabs = async () => {
    await axios.get('/api/kabs', {
        headers:{
            "accept" : "application/json",
            "Authorization": "Bearer " + props.store.token
        }
    })
        .then(res => {
            kabs.value = res.data.kabs1
        })
}


const cols =
    ref([
        { field: 'id', title: 'No' },
        { field: 'npsn_smp', title: 'NPSN' },
        { field: 'nama_smp', title: 'NAMA' },
        { field: 'kode_un', title: 'KODE UN' },
        { field: 'kabs.kab_kota', title: 'Kab/Kota' },
        { field: 'actions', title: 'Actions' },
    ]) || [];

let timer;

const getSmp = () => {
    loading.value = true;
    axios.get('/api/smps?page='+params.current_page+"&params="+params.pagesize+"&search="+params.search, {
        headers:{
            "accept" : "application/json",
            "Authorization": "Bearer " + props.store.token
        }
    })
        .then(res => {
            rows.value = res.data.data;
            total_rows.value = res.data.total;
            params.current_page = res.data.to;

        })
        loading.value = false;
};

const count = ()  => {
    emits('smps', total_rows.value)
}

const filterUsers = () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        getSmp();
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
        getSmp();
    }
}

const viewUser = (user) => {
    showModView(user)
};
const deleteUser = (user) => {
    showMod(user)
};

onMounted(() => {
    getSmp()
    count()
    getKabs()
});
</script>

<style></style>
