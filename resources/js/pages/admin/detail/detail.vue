<template>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h3>Detail Siswa Per Kabupaten</h3>
                </div>
                <form action="">
                    <div class="form-group">
                        <label for="">Pilih Kab / Kota</label>
                        <select class="form-control" @change="getData" v-model="kab_id">
                            <option value=""></option>
                            <option v-for="k in kab" :key="k.id" :value="k.id">{{k.kab_kota}}</option>
                        </select>
                    </div>
                </form>
                <p v-show="loading">
                    <div class="spinner-border text-primary" role="status"></div>
                </p>
                <table class="table table-primary">
                    <thead>
                    <th>Kab Kota</th>
                    <th>Nama Sekolah</th>
                    <th>Dapodik</th>
                    <th>Jumlah Siswa</th>
                    <th>Status</th>
                    </thead>
                    <tbody>
                    <tr v-for="sekolah in sekolahs" :key="sekolah.id">
                        <td>{{sekolah.kabs.kab_kota}}</td>
                        <td>{{sekolah.nm_sekolah}}</td>
                        <td class="table-success" v-if="sekolah.dapodik">{{sekolah.dapodik.jum}}</td>
                        <td v-else>belum</td>
                        <td>{{sekolah.siswas_count}}</td>
                        <td>
                            <div v-if="sekolah.finals.final === 1">Final</div>
                            <div v-else>Belum</div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>
<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";

const props = defineProps(['store']);
const loading = ref(false)
const kab = ref([]);
const kab_id = ref()
const sekolahs = ref([])

const getKabs1 = () => {
    axios.get('/api/kabs/create', {
        headers: {
            "accept": "application/json",
            "Authorization": "Bearer " + props.store.token
        }
    })
        .then(res => {
            kab.value = res.data
        })
};

const getData = () => {
    loading.value = true
    axios.get("/api/kabs/getData/"+kab_id.value, {
        headers: {
            "accept": "application/json",
            "Authorization": "Bearer " + props.store.token
        }
    })
        .then(res => {
            loading.value = false
            sekolahs.value = res.data
        })
}

onMounted(() => {
    getKabs1()
});
</script>
