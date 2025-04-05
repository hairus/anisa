<template>
    <div class="card">
        <div class="card-body">
            <button class="btn btn-danger" @click="showModal = true">Open Modal</button>
            <button class="btn btn-warning" @click="final">Final Data siswa</button>
            <div v-if="showModal" class="modal active">
                <div class="modal-content">
                    <h4>Tambah Siswa</h4>
                    <form action="" class="form-simple">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" v-model="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Nisn</label>
                            <input type="text" class="form-control" v-model="nisn">
                        </div>
                        <div class="form-group">
                            <label for="name">Tingkat</label>
                            <select class="form-control" v-model="tingkat" style="padding-top: 15px; padding-bottom: 15px">
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Jenjang</label>
                            <select class="form-control" v-model="jenjang" style="padding-top: 15px; padding-bottom: 15px">
                                <option value="SMA">SMA</option>
                                <option value="SMK">SMK</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Nama Kelas</label>
                            <input type="text" class="form-control" v-model="nmKelas">
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label for="name">Pilih SMP</label>
                                <v-select v-model="selectedSmp" :options="smps" label="nama_smp" placeholder="Pilih SMP">
                                    <template #search="{attributes, events}">
                                        <input
                                            class="vs__search"
                                            :required="!selectedSmp"
                                            v-bind="attributes"
                                            v-on="events"
                                        />
                                    </template>
                                </v-select>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button class="btn btn-info" @click="showModal = false">Cancel</button>
                        <button type="submit" class="btn btn-success" @click="save">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {onMounted, ref} from "vue";
import axios from "axios";
import {useAuthStore} from "@/store/index.js";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";


const store = useAuthStore();

const showModal = ref(false)
const smps = ref([])
const nama = ref()
const nisn = ref()
const tingkat = ref()
const nmKelas = ref()
const jenjang = ref()
const selectedSmp = ref()

const final = async () => {
    await axios.get('/api/op/final/create', {
        headers: {
            "accept": "application/json",
            "Authorization": "Bearer " + store.token
        }
    })
        .then(res => {
            console.log(res.data)
        })
}



const getSmps = async () => {
    await axios.get('/api/op/smps/create', {
        headers: {
            "accept": "application/json",
            "Authorization": "Bearer " + store.token
        }
    })
        .then(res => {
            smps.value = res.data
        })
}

const save = async () => {
    await axios.post('/api/op/siswa', {
        nama : nama.value,
        nisn : nisn.value,
        nmKelas :nmKelas.value,
        tingkat: tingkat.value,
        jenjang:jenjang.value,
        selectedSmp :selectedSmp.value
    }, {
        headers: {
            "accept": "application/json",
            "Authorization": "Bearer " + store.token
        }
    })
        .then(res => {
            console.log(res.data)
        })
}
onMounted(() => {
    getSmps()
})
</script>



<style scoped>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    visibility: hidden;
}
.modal.active {
    visibility: visible;
}
.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    width: 50%;
    text-align: left;
}
.modal-footer {
    margin-top: 10px;
}
>>> {
    /* css untuk dropdown  */
    --vs-actions-padding: 10px;
    --vs-dropdown-option--active-color: #eeeeee;
    --vs-dropdown-option-padding: 10px;
    /* css untuk dropdown  */
}
</style>
