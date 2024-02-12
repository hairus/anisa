<template>
    <div class="card">
        <div class="card-body">
            <button class="btn btn-sm btn-primary float-end" type="button" @click="ShowModal()">
                <i class="mdi mdi-plus"></i>
            </button>
            <h4 class="card-title">Management Role</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th> Role </th>
                            <th> Subject </th>
                            <th> Status </th>
                            <th> Created At </th>
                            <th> # </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="role in roles" :key="role.id">
                            <td>
                                <img src="../../../../../public/assets/images/faces/face.jpg" class="me-2" alt="image">
                            </td>
                            <td> {{ role.role }} </td>
                            <td>
                                <label class="badge badge-gradient-success">ACTIVE</label>
                            </td>
                            <td> {{ role.created_at }} </td>
                            <td>
                                <button class="btn badge badge-gradient-danger btn-sm" @click="del(role.id)">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps(["roles"]);
</script>

<style lang="scss" scoped></style>
