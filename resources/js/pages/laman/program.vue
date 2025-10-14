<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'laman-program-read',
  },
})
const notif = ref({
  icon: null,
  title: null,
  text: null,
  color: null,
})
const isAlertVisible = ref(false)
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
    title: 'sekolah',
    key: 'sekolah',
    sortable: false,
  },
  {
    title: 'nama',
    key: 'nama',
    sortable: false,
  },
  {
    title: 'deskripsi',
    key: 'deskripsi',
    sortable: false,
  },
  {
    title: 'Actions',
    key: 'actions',
    align: 'center',
    sortable: false,
  },
]
const handleNotif = (val) => {
  notif.value = val
  isAlertVisible.value = true
}
const pageType = 'program'
const {
  data: getData,
  execute: fetchData,
} = await useApi(createUrl('/table', {
  query: {
    data: pageType,
    q: searchQuery,
    itemsPerPage,
    page,
    sortBy,
    orderBy,
  },
}))
if (getData.value.color) {
  notif.value = getData.value
  isAlertVisible.value = true
}
const items = computed(() => getData.value.lists.data)
const total_item = computed(() => getData.value.lists.total)
const sekolah = computed(() => getData.value.sekolah)
const sekolahId = computed(() => getData.value.sekolah_id)
const isAddNewData = ref(false)

const deletedId = ref()
const isConfirmDialogVisible = ref()
const deleteData = async id => {
  deletedId.value = id
  isConfirmDialogVisible.value = true
}
const confirmDelete = async (val) => {
  if (val) {
    await $api(`/laman/destroy/${deletedId.value}`, {
      method: 'DELETE',
      onResponse({ request, response, options }) {
        let getData = response._data
        notif.value = getData
        isAlertVisible.value = true
        deletedId.value = null
        fetchData()
      }
    })
  }
}
const detilData = ref()
const editData = async (val) => {
  isAddNewData.value = true
  detilData.value = val
}
const addNewData = () => {
  isAddNewData.value = true
  detilData.value = null
}
watch(isAlertVisible, () => {
  if (!isAlertVisible.value)
    fetchData()
})
</script>

<template>
  <section>
    <VCard>
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
          <VBtn @click="addNewData">Tambah
            <VIcon end icon="tabler-plus" />
          </VBtn>
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
        <!-- User -->
        <template #item.sekolah="{ item }">
          {{ item.sekolah?.nama ?? '-' }}
        </template>

        <template #item.deskripsi="{ item }">
          <span v-html="item.deskripsi"></span>
        </template>

        <!-- Actions -->
        <template #item.actions="{ item }">
          <IconBtn @click="deleteData(item.id)">
            <VIcon icon="tabler-trash" />
          </IconBtn>

          <IconBtn @click="editData(item)">
            <VIcon icon="tabler-pencil" />
          </IconBtn>
        </template>

        <template #bottom>
          <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="total_item" />
        </template>
      </VDataTableServer>
      <!-- SECTION -->
    </VCard>

    <!-- 👉 Add New User -->
    <LamanAddDialog v-model:is-dialog-visible="isAddNewData" v-model:sekolah="sekolah" v-model:sekolahId="sekolahId"
      v-model:pageType="pageType" v-model:detil-data="detilData" @notif="handleNotif" />
    <ShowAlert :color="notif.color" :icon="notif.icon" :title="notif.title" :text="notif.text" :disable-time-out="false"
      v-model:isSnackbarVisible="isAlertVisible" v-if="notif.color"></ShowAlert>
    <ConfirmDialog v-model:isDialogVisible="isConfirmDialogVisible"
      confirmation-question="Apakah Anda yakin ingin menghapus data ini?" :show-notif="false"
      @confirm="confirmDelete" />
  </section>
</template>
