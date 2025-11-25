<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'pelatihan-read',
    navActiveLink: 'dashboard',
  },
})
// 👉 Store
const searchQuery = ref('')
// Data table options
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref('created_at')
const orderBy = ref('DESC')

const updateOptions = options => {
  if (options.sortBy.length) {
    sortBy.value = options.sortBy[0]?.key
    orderBy.value = options.sortBy[0]?.order
  }
}

// Headers
const headers = [
  {
    title: 'judul',
    key: 'judul',
    sortable: false,
  },
  {
    title: 'jml sesi',
    key: 'sesi_count',
    align: 'center',
    sortable: false,
  },
  {
    title: 'jml materi',
    key: 'materi_count',
    align: 'center',
    sortable: false,
  },
  {
    title: 'jml tugas',
    key: 'tugas_count',
    align: 'center',
    sortable: false,
  },
  {
    title: 'jml tes',
    key: 'tes_count',
    align: 'center',
    sortable: false,
  },
  {
    title: 'Actions',
    key: 'actions',
    align: 'center',
    sortable: false,
  },
]
const {
  data: getData,
  execute: fetchData,
} = await useApi(createUrl('/table', {
  query: {
    data: 'pelatihan',
    q: searchQuery,
    per_page: itemsPerPage,
    page,
    sortBy,
    orderBy,
  },
}))
const items = computed(() => getData.value.lists.data)
const total_item = computed(() => getData.value.lists.total)
</script>

<template>
  <section>
    <VCard title="Data Pelatihan">
      <VCardText class="d-flex flex-wrap gap-4">
        <div class="d-flex gap-2 align-center">
          <AppSelect :model-value="itemsPerPage" :items="[
            { value: 10, title: '10' },
            { value: 25, title: '25' },
            { value: 50, title: '50' },
            { value: 100, title: '100' },
            { value: -1, title: 'All' },
          ]" style="inline-size: 5.5rem;" @update:model-value="itemsPerPage = parseInt($event, 10)" />
        </div>

        <VSpacer />

        <div class="d-flex align-center flex-wrap gap-4">
          <!-- 👉 Search  -->
          <AppTextField v-model="searchQuery" placeholder="Cari..." style="inline-size: 15.625rem;" />
        </div>
      </VCardText>

      <VDivider />
      <!-- SECTION datatable -->
      <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page" :items-per-page-options="[
        { value: 10, title: '10' },
        { value: 20, title: '20' },
        { value: 50, title: '50' },
        { value: -1, title: '$vuetify.dataFooter.itemsPerPageAll' },
      ]" :items="items" :items-length="total_item" :headers="headers" class="text-no-wrap"
        @update:options="updateOptions">
        <!-- Actions -->
        <template #item.actions="{ item }">
          <VBtn color="success" size="small" :to="{ name: 'pelatihan-aksi-id', params: { id: item.pelatihan_id } }">Mulai
            <VIcon icon="tabler-chevrons-right" />
          </VBtn>
        </template>

        <template #bottom>
          <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="total_item" />
        </template>
      </VDataTableServer>
      <!-- SECTION -->
    </VCard>
  </section>
</template>
