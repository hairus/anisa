<template>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4">Demo DataTable</h4>

      <DataTable
        :columns="columns"
        :items="items"
        :loading="loading"
        :total-items="totalItems"
        :per-page="perPage"
        :current-page="currentPage"
        @page-changed="handlePageChange"
      >
        <template #cell-status="{ item }">
          <span class="badge" :class="statusClass(item.status)">
            {{ item.status }}
          </span>
        </template>

        <template #actions="{ item }">
          <button
            class="btn btn-sm btn-primary me-2"
            @click="handleView(item)"
          >
            <i class="mdi mdi-eye"></i>
          </button>
          <button
            class="btn btn-sm btn-danger"
            @click="handleDelete(item)"
          >
            <i class="mdi mdi-delete"></i>
          </button>
        </template>
      </DataTable>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      columns: [
        { key: 'id', label: 'ID' },
        { key: 'name', label: 'Nama Siswa' },
        { key: 'nisn', label: 'NISN' },
        { key: 'smas', label: 'SMA' },
        { key: 'smps', label: 'SMP' },
        { key: 'nilai', label: 'Nilai' }
      ],
      items: [],
      loading: false,
      totalItems: 0,
      perPage: 10,
      currentPage: 1
    }
  },
  methods: {
    handlePageChange(page) {
      this.currentPage = page
      this.fetchData()
    },
    async fetchData() {
      this.loading = true
      try {
        const response = await axios.get('/api/siswa', {
          params: {
            page: this.currentPage,
            per_page: this.perPage
          },
          headers: {
            Authorization: "Bearer " + useAuthStore().token
          }
        })
        this.items = response.data.data
        this.totalItems = response.data.total
      } catch (error) {
        console.error('Gagal memuat data:', error)
      } finally {
        this.loading = false
      }
    },
    statusClass(status) {
      return {
        'bg-success': status === 'active',
        'bg-warning': status === 'pending',
        'bg-danger': status === 'inactive'
      }
    },
    handleView(item) {
      alert(`View item: ${item.id} - ${item.name}`)
    },
    handleDelete(item) {
      if(confirm(`Hapus siswa ${item.name}?`)) {
        axios.delete(`/api/siswa/${item.id}`)
          .then(() => {
            this.items = this.items.filter(i => i.id !== item.id)
            this.totalItems--
          })
      }
    }
  }
}
</script>

<style scoped>
.badge {
  padding: 0.5em 0.75em;
}
</style>
