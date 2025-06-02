<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'pelatihan-read',
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
const router = useRouter()
const isAddNewData = () => {
  router.push({ name: 'pelatihan-tambah' })
}

const deletedId = ref()
const isConfirmDialogVisible = ref()
const deleteData = async id => {
  deletedId.value = id
  isConfirmDialogVisible.value = true
}
const confirmDelete = async (val) => {
  if(val){
    await $api(`/referensi/destroy/pelatihan/${ deletedId.value }`, { 
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
const mulaiLatihan = id => {
  router.push({ name: 'pelatihan-aksi-id', params: {id: id} })
}
const sesiLatihan = async(val) => {
  router.push({ name: 'pelatihan-sesi-id', params: {id: val.pelatihan_id} })
}
const editData = async(val) => {
  router.push(`/pelatihan/${val}`)
}
watch(isAlertVisible, () => {
  if (!isAlertVisible.value)
  fetchData()
})
</script>

<template>
  <section>
    <VCard title="Data Pelatihan">
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
          <VBtn @click="isAddNewData" v-if="$can('read', 'referensi-sekolah-read')">Tambah <VIcon end icon="tabler-plus" /></VBtn>
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
        <template #item.mapel="{ item }">
          {{ item.pembelajaran?.nama_mata_pelajaran }}
        </template>

        <template #item.rombel="{ item }">
          {{ item.pembelajaran?.rombongan_belajar?.nama }}
        </template>
        
        <!-- Actions -->
        <template #item.actions="{ item }">
          <template v-if="$can('read', 'referensi-sekolah-read')">
            <IconBtn @click="sesiLatihan(item)">
              <VTooltip activator="parent" location="top">Sesi Latihan</VTooltip>
              <VIcon icon="tabler-versions" />
            </IconBtn>
            <IconBtn @click="editData(item.pelatihan_id)">
              <VTooltip activator="parent" location="top">Edit Data</VTooltip>
              <VIcon icon="tabler-pencil" />
            </IconBtn>
            <IconBtn @click="deleteData(item.pelatihan_id)">
              <VTooltip activator="parent" location="top">Hapus Data</VTooltip>
              <VIcon icon="tabler-trash" />
            </IconBtn>
          </template>
          <template v-else>
            <VBtn color="success" size="small" @click="mulaiLatihan(item.pelatihan_id)">Mulai <VIcon icon="tabler-chevrons-right" /></VBtn>
          </template>
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
