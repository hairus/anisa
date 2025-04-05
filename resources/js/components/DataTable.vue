<template>
  <div class="datatable-container">
    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th v-for="column in columns" :key="column.key">
            {{ column.label }}
          </th>
          <th v-if="hasActions">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in items" :key="item.id || index">
          <td v-for="column in columns" :key="column.key">
            <slot :name="`cell-${column.key}`" :item="item">
              {{ item[column.key] }}
            </slot>
          </td>
          <td v-if="hasActions">
            <slot name="actions" :item="item">
              <button
                class="btn btn-sm btn-primary me-2"
                @click="$emit('edit', item)"
              >
                <i class="mdi mdi-pencil"></i>
              </button>
              <button
                class="btn btn-sm btn-danger"
                @click="$emit('delete', item)"
              >
                <i class="mdi mdi-delete"></i>
              </button>
            </slot>
          </td>
        </tr>
      </tbody>
    </table>

    <nav v-if="showPagination" aria-label="Page navigation">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <a class="page-link" href="#" @click.prevent="prevPage">Previous</a>
        </li>
        <li
          v-for="page in totalPages"
          :key="page"
          class="page-item"
          :class="{ active: page === currentPage }"
        >
          <a class="page-link" href="#" @click.prevent="changePage(page)">
            {{ page }}
          </a>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <a class="page-link" href="#" @click.prevent="nextPage">Next</a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {
  props: {
    columns: {
      type: Array,
      required: true
    },
    items: {
      type: Array,
      default: () => []
    },
    loading: {
      type: Boolean,
      default: false
    },
    error: {
      type: String,
      default: null
    },
    totalItems: {
      type: Number,
      default: 0
    },
    perPage: {
      type: Number,
      default: 10
    },
    currentPage: {
      type: Number,
      default: 1
    }
  },

  computed: {
    hasActions() {
      return this.$slots.actions || this.$attrs.onEdit || this.$attrs.onDelete
    },
    totalPages() {
      return Math.ceil(this.totalItems / this.perPage)
    },
    showPagination() {
      return this.totalPages > 1
    }
  },

  methods: {
    changePage(page) {
      if (page !== this.currentPage) {
        this.$emit('page-changed', page)
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.changePage(this.currentPage - 1)
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.changePage(this.currentPage + 1)
      }
    }
  }
}
</script>

<style scoped>
.datatable-container {
  margin: 20px 0;
}

.table {
  margin-top: 1rem;
}

.pagination {
  justify-content: center;
  margin-top: 1rem;
}
</style>
