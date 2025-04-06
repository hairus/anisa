<template>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Prosentase Sekolah Per Kabupaten</h4>
            <div class="table-responsive">
                <table class="table table-primary">
                    <thead>
                        <th>No</th>
                        <th>KAB</th>
                        <th>Total</th>
                        <th>Sudah</th>
                        <th>Belum</th>
                        <th>Final</th>
                        <th>No Final</th>
                        <th>%</th>
                    </thead>
                    <tbody>
                    <tr v-for="kab in kabs" :key="kab">
                        <td>{{ kab.id }}</td>
                        <td>{{kab.kab_kota}}</td>
                        <td>
                            <button class="btn btn-sm btn-info">{{(kab.smas.length + kab.sma_no.length)  }}</button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" @click="modalSudah(kab.id)">{{kab.smas.length }}</button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger" @click="modalBelum(kab.id)">{{kab.sma_no.length}}</button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-success" @click="modalFinal(kab.id)">{{kab.finals.length}}</button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning text-dark" @click="modalNoFinal(kab.id)">{{kab.no_finals.length}}</button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary">
                                {{Math.floor(kab.smas.length / (kab.smas.length + kab.sma_no.length) * 100) }} %
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <Modal v-model:visible="isVisible" :title="'Sekolah'" :cancleButton="false">
                <ul v-show="smas">
                    <li v-for="sma in smas">{{sma.nm_sekolah}} - {{sma}}</li>
                </ul>
                <ul v-show="kabFinal">
                    <li v-for="sekolah in kabFinal" :key="sekolah.id">{{sekolah.smas.nm_sekolah}}</li>
                </ul>
            </Modal>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import axios from 'axios';
import '@bhplugin/vue3-datatable/dist/style.css';
import { Modal } from 'usemodal-vue3';

const props = defineProps(['store']);
const loading = ref(true);
const kabs = ref([]);
const isVisible = ref(false);
const smas = ref([]);
const jums = ref(0);
const kabFinal = ref([]);

const getKabs = () => {
    loading.value = true;
    axios.get('/api/kabs/create', {
        headers: {
            "accept": "application/json",
            "Authorization": "Bearer " + props.store.token
        }
    })
        .then(res => {
            kabs.value = res.data
        })
};


const modalSudah = (data) => {
    axios.get("/api/kabs/sudah/"+data, {
        headers: {
            "accept": "application/json",
            "Authorization": "Bearer " + props.store.token
        }
    })
        .then(res => {
            smas.value = res.data
            kabFinal.value = []
            isVisible.value = true
        })
}

const modalBelum = (data) => {
    axios.get("/api/kabs/belum/"+data, {
        headers: {
            "accept": "application/json",
            "Authorization": "Bearer " + props.store.token
        }
    })
        .then(res => {
            smas.value = res.data
            kabFinal.value = []
            isVisible.value = true
        })
}

const modalFinal = (data) => {
    axios.get("/api/kabs/final/"+data, {
        headers: {
            "accept": "application/json",
            "Authorization": "Bearer " + props.store.token
        }
    })
        .then(res => {
            kabFinal.value = res.data
            smas.value = []
            isVisible.value = true
        })
}

const modalNoFinal = (data) => {
    axios.get("/api/kabs/nofinal/"+data, {
        headers: {
            "accept": "application/json",
            "Authorization": "Bearer " + props.store.token
        }
    })
        .then(res => {
            kabFinal.value = res.data
            smas.value = []
            isVisible.value = true
        })
}

onMounted(() => {
    getKabs()
});
</script>

<style></style>
