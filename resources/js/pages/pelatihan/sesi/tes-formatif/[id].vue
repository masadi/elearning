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
    title: 'pertanyaan',
    key: 'deskripsi',
    sortable: false,
  },
  {
    title: 'jml jawaban',
    key: 'jawaban_count',
    align: 'center',
    sortable: false,
  },
  {
    title: 'kunci jawaban',
    key: 'kunci',
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
    data: 'tes-sesi',
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
  router.push({ name: 'pelatihan-sesi-tes-formatif-tambah-id', params: {id: route.params.id} })
}

const deletedId = ref()
const isConfirmDialogVisible = ref()
const deleteData = async id => {
  deletedId.value = id
  isConfirmDialogVisible.value = true
}
const confirmDelete = async (val) => {
  if(val){
    await $api(`/referensi/destroy/tes-formatif/${ deletedId.value }`, { 
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
  router.push({ name: 'pelatihan-sesi-tes-formatif-edit-id', params: {id: val} })
}
watch(isAlertVisible, () => {
  if (!isAlertVisible.value)
  fetchData()
})
const backToSesiLatihan = () => {
  router.push({ name: 'pelatihan-sesi-id', params: {id: sesiLatihan.value.pelatihan_id} })
}
</script>

<template>
  <section>
    <VCard>
      <VCardItem>
        <VCardTitle>Data Tes Formatif ({{sesiLatihan.judul}})</VCardTitle>
        <template #append>
          <div class="mt-n4 me-n2">
            <VBtn size="small" color="warning" @click="backToSesiLatihan">Kembali Ke Sesi latihan</VBtn>
          </div>
        </template>
      </VCardItem>
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
        <template #item.kunci="{item}">
          {{ item.kunci?.opsi }}
        </template>
        <template #item.deskripsi="{item}">
          <span v-html="item.deskripsi"></span>
        </template>
        <!-- Actions -->
        <template #item.actions="{ item }">
          <IconBtn @click="editData(item.tes_id)">
            <VTooltip activator="parent" location="top">Edit Soal</VTooltip>
            <VIcon icon="tabler-pencil" />
          </IconBtn>
          <IconBtn @click="deleteData(item.tes_id)">
            <VTooltip activator="parent" location="top">Hapus Soal</VTooltip>
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
