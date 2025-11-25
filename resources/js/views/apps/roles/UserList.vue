<script setup>
const isAlertVisible = ref(false)
const isAlertClicked = ref(false)
const notif = ref({
  icon: null,
  title: null,
  text: null,
  color: null,
})
// 👉 Store
const searchQuery = ref('')
const selectedRole = ref()
const emit = defineEmits(['statistik', 'aplikasi'])
// Data table options
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref('nama')
const orderBy = ref('ASC')

const updateOptions = options => {
  if (options.sortBy.length) {
    sortBy.value = options.sortBy[0]?.key
    orderBy.value = options.sortBy[0]?.order
  }
}

// Headers
const headers = [
  {
    title: 'nama',
    key: 'nama',
    sortable: false,
  },
  {
    title: 'npsn',
    key: 'npsn',
    sortable: false,
  },
  {
    title: 'email',
    key: 'email',
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
    data: 'sekolah',
    q: searchQuery,
    role_id: selectedRole,
    per_page: itemsPerPage,
    page,
    sortBy,
    orderBy,
  },
}))
const items = computed(() => getData.value.lists.data)
const total_item = computed(() => getData.value.lists.total)
const mataPelajaran = computed(() => getData.value.mata_pelajaran)
const statistik = computed(() => getData.value.statistik)
const aplikasi = computed(() => getData.value.aplikasi)
emit('statistik', statistik.value)
emit('aplikasi', aplikasi.value)
// 👉 search filters
const isDialogVisible = ref(false)
const namaSekolah = ref()
const form = ref({
  sekolah_id: null,
  selected: [],
})
const mappingMapel = (item) => {
  isDialogVisible.value = true
  namaSekolah.value = item.nama
  form.value.sekolah_id = item.sekolah_id
  item.mapel.forEach(element => {
    form.value.selected.push(element.mata_pelajaran_id)
  });
}
const saveMappingMapel = async () => {
  console.log(form.value);
  await $api('/admin/sekolah/save-mapping', {
    method: 'POST',
    body: form.value,
    onResponse({ request, response, options }) {
      let getData = response._data
      isDialogVisible.value = false
      isAlertVisible.value = true
      notif.value = getData
      fetchData()
    }
  })
}
watch(isDialogVisible, () => {
  if (!isDialogVisible.value) {
    console.log('close-modal');
    form.value = {
      sekolah_id: null,
      selected: [],
    }
  }
})
</script>

<template>
  <section>
    <VCard>
      <VCardText class="d-flex flex-wrap gap-4">
        <div class="d-flex gap-2 align-center">
          <p class="text-body-1 mb-0">
            Show
          </p>
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
          <VBtn @click="mappingMapel(item)">Mapping Mapel</VBtn>
        </template>

        <template #bottom>
          <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="total_item" />
        </template>
      </VDataTableServer>
      <!-- SECTION -->
    </VCard>
    <VDialog v-model="isDialogVisible" scrollable content-class="scrollable-dialog" max-width="500">
      <DialogCloseBtn @click="isDialogVisible = !isDialogVisible" />
      <VCard>
        <VCardItem class="pb-5">
          <VCardTitle>Pilih Mata Untuk {{ namaSekolah }}</VCardTitle>
        </VCardItem>
        <VDivider />
        <VCardText>
          <div v-for="mapel in mataPelajaran" :key="mapel.id">
            <VCheckbox v-model="form.selected" :label="mapel.nama" :value="mapel.id" />
          </div>
        </VCardText>
        <VDivider />
        <VCardText class="d-flex justify-end flex-wrap gap-3 pt-5 overflow-visible">
          <VBtn color="secondary" variant="tonal" @click="isDialogVisible = false">
            Batal
          </VBtn>
          <VBtn @click="saveMappingMapel">
            Simpan
          </VBtn>
        </VCardText>
      </VCard>
    </VDialog>
    <ShowAlert :color="notif.color" :icon="notif.icon" :title="notif.title" :text="notif.text" :disable-time-out="false"
      v-model:isSnackbarVisible="isAlertVisible" v-model:isSnackbarClicked="isAlertClicked" v-if="notif.color">
    </ShowAlert>
  </section>
</template>

<style lang="scss">
.text-capitalize {
  text-transform: capitalize;
}

.user-list-name:not(:hover) {
  color: rgba(var(--v-theme-on-background), var(--v-medium-emphasis-opacity));
}
</style>
