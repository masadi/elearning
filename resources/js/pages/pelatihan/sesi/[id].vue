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
    title: 'bobot kehadiran',
    key: 'bobot_hadir',
    align: 'center',
    sortable: false,
  },
  {
    title: 'bobot materi',
    key: 'bobot_materi',
    align: 'center',
    sortable: false,
  },
  {
    title: 'bobot tugas',
    key: 'bobot_tugas',
    align: 'center',
    sortable: false,
  },
  {
    title: 'bobot tes',
    key: 'bobot_tes',
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
const router = useRouter()
const route = useRoute()
const {
  data: getData,
  execute: fetchData,
} = await useApi(createUrl('/table', {
  query: {
    data: 'sesi',
    pelatihan_id: route.params.id,
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
const dataPelatihan = computed(() => getData.value.data)
const isAddNewData = () => {
  router.push({ name: 'pelatihan-sesi-tambah-id', params: {pelatihan_id: route.params.id} })
}

const deletedId = ref()
const isConfirmDialogVisible = ref()
const deleteData = async id => {
  deletedId.value = id
  isConfirmDialogVisible.value = true
}
const confirmDelete = async (val) => {
  if(val){
    await $api(`/referensi/destroy/sesi/${ deletedId.value }`, { 
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
const materiSesi = async(val) => {
  router.push({ name: 'pelatihan-sesi-materi-id', params: {id: val.sesi_latihan_id} })
}
const tugasSesi = async(val) => {
  router.push({ name: 'pelatihan-sesi-tugas-id', params: {id: val.sesi_latihan_id} })
}
const ujianSesi = async(val) => {
  router.push({ name: 'pelatihan-sesi-tes-formatif-id', params: {id: val.sesi_latihan_id} })
}
const editData = async(val) => {
  router.push({ name: 'pelatihan-sesi-edit-id', params: {id: val} })
}
watch(isAlertVisible, () => {
  if (!isAlertVisible.value)
  fetchData()
})
</script>

<template>
  <section>
    <VCard :title="`Data Sesi (${dataPelatihan.judul})`">
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
          <!--IconBtn @click="materiSesi(item)">
            <VTooltip activator="parent" location="top">Materi</VTooltip>
            <VIcon icon="tabler-file-export" />
          </IconBtn>
          <IconBtn @click="tugasSesi(item)">
            <VTooltip activator="parent" location="top">Tugas</VTooltip>
            <VIcon icon="tabler-user-check" />
          </IconBtn>
          <IconBtn @click="ujianSesi(item)">
            <VTooltip activator="parent" location="top">Tes Formatif</VTooltip>
            <VIcon icon="tabler-clock-search" />
          </IconBtn-->
          <IconBtn @click="editData(item.sesi_latihan_id)">
            <VTooltip activator="parent" location="top">Edit Sesi Latihan</VTooltip>
            <VIcon icon="tabler-pencil" />
          </IconBtn>
          <IconBtn @click="deleteData(item.sesi_latihan_id)">
            <VTooltip activator="parent" location="top">Hapus Sesi Latihan</VTooltip>
            <VIcon icon="tabler-trash" />
          </IconBtn>
          <VBtn icon variant="text" color="medium-emphasis">
            <VIcon icon="tabler-dots-vertical" />
            <VMenu activator="parent">
              <VList>
                <VListItem @click="materiSesi(item)">
                  <template #prepend>
                    <VIcon icon="tabler-file-export" />
                  </template>
                  <VListItemTitle>Materi</VListItemTitle>
                </VListItem>
                <VListItem @click="tugasSesi(item)">
                  <template #prepend>
                    <VIcon icon="tabler-user-check" />
                  </template>
                  <VListItemTitle>Tugas</VListItemTitle>
                </VListItem>
                <VListItem @click="ujianSesi(item)">
                  <template #prepend>
                    <VIcon icon="tabler-clock-search" />
                  </template>
                  <VListItemTitle>Tes Formatif</VListItemTitle>
                </VListItem>
              </VList>
            </VMenu>
          </VBtn>
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
