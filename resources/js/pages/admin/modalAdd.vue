<template>
    <div class="modal fade" id="myModal" ref="Close" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" @click="clear" aria-label="Close"></button>
                </div>
                <form @submit.prevent="gg()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Input Role</label>
                            <input type="text" v-model="role" class="form-control" required>
                        </div>
                    <span v-if="props.errors"><code>{{props.errors}}</code></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="Close" @click="clear" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";
const emit = defineEmits(['role', "message"])
const props = defineProps(['errors'])
const role = ref();
const gg = () => {
    emit('role', role.value)
    role.value = ""
}

const clear = () => {
    emit('message')
}
</script>

