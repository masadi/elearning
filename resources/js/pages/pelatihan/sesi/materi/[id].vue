<script setup>
definePage({
  meta: {
    action: 'create',
    subject: 'pelatihan-create',
    navActiveLink: 'pelatihan',
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
  if(options.sortBy.length){
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
    title: 'jml dokumen',
    key: 'dokumen_count',
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
const router = useRouter()
const route = useRoute()
const {
  data: getData,
  execute: fetchData,
} = await useApi(createUrl('/table', {
  query: {
    data: 'materi-sesi',
    sesi_latihan_id: route.params.id,
    q: searchQuery,
    itemsPerPage,
    page,
    sortBy,
    orderBy,
  },
}))
if(getData.value.color){
    notif.value = getData.value
    isAlertVisible.value = true
}
const items = computed(() => getData.value.lists.data)
const total_item = computed(() => getData.value.lists.total)
const sesiLatihan = computed(() => getData.value.data)
const isAddNewData = () => {
  router.push({ name: 'pelatihan-sesi-materi-tambah-id', params: {id: route.params.id} })
}

const deletedId = ref()
const isConfirmDialogVisible = ref()
const deleteData = async id => {
  deletedId.value = id
  isConfirmDialogVisible.value = true
}
const confirmDelete = async (val) => {
  if(val){
    await $api(`/referensi/destroy/materi-sesi/${ deletedId.value }`, { 
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
const editData = async(val) => {
  router.push({ name: 'pelatihan-sesi-materi-edit-id', params: {id: val} })
}
watch(isAlertVisible, () => {
  if (!isAlertVisible.value)
  fetchData()
})
</script>

<template>
  <section>
    <VCard :title="`Data Materi (${sesiLatihan.judul})`">
      <VCardText class="d-flex flex-wrap gap-4">
        <div class="d-flex gap-2 align-center">
          <AppSelect
            :model-value="itemsPerPage"
            :items="[
              { value: 10, title: '10' },
              { value: 25, title: '25' },
              { value: 50, title: '50' },
              { value: 100, title: '100' },
              { value: -1, title: 'All' },
            ]"
            style="inline-size: 5.5rem;"
            @update:model-value="itemsPerPage = parseInt($event, 10)"
          />
        </div>

        <VSpacer />

        <div class="d-flex align-center flex-wrap gap-4">
          <!-- 👉 Search  -->
          <AppTextField
            v-model="searchQuery"
            placeholder="Cari..."
            style="inline-size: 15.625rem;"
          />
          <VBtn @click="isAddNewData">Tambah <VIcon end icon="tabler-plus" /></VBtn>
        </div>
      </VCardText>

      <VDivider />
      <!-- SECTION datatable -->
      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:page="page"
        :items-per-page-options="[
          { value: 10, title: '10' },
          { value: 20, title: '20' },
          { value: 50, title: '50' },
          { value: -1, title: '$vuetify.dataFooter.itemsPerPageAll' },
        ]"
        :items="items"
        :items-length="total_item"
        :headers="headers"
        class="text-no-wrap"
        @update:options="updateOptions"
      >
        <!-- Actions -->
        <template #item.actions="{ item }">
          <IconBtn @click="editData(item.materi_sesi_id)">
            <VTooltip activator="parent" location="top">Edit Materi</VTooltip>
            <VIcon icon="tabler-pencil" />
          </IconBtn>
          <IconBtn @click="deleteData(item.materi_sesi_id)">
            <VTooltip activator="parent" location="top">Hapus Materi</VTooltip>
            <VIcon icon="tabler-trash" />
          </IconBtn>

        </template>

        <template #bottom>
          <TablePagination
            v-model:page="page"
            :items-per-page="itemsPerPage"
            :total-items="total_item"
          />
        </template>
      </VDataTableServer>
      <!-- SECTION -->
    </VCard>
    <ShowAlert :color="notif.color" :icon="notif.icon" :title="notif.title" :text="notif.text" :disable-time-out="false" v-model:isSnackbarVisible="isAlertVisible" v-if="notif.color"></ShowAlert>
    <ConfirmDialog
        v-model:isDialogVisible="isConfirmDialogVisible"
        confirmation-question="Apakah Anda yakin ingin menghapus data ini?"
        :show-notif="false"
        @confirm="confirmDelete"
      />
  </section>
</template>
