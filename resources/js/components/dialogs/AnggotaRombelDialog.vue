<script setup>
import { debounce } from 'lodash';
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  dialogTitle: {
    type: String,
    required: true,
  },
  rombonganBelajarId: {
    type: String,
    required: true,
  },
  semesterId: {
    type: String,
    required: true,
  },
})
const updateModelValue = val => {
  emit('update:isDialogVisible', val)
  emit('close')
}
const emit = defineEmits([
  'update:isDialogVisible',
  'refresh',
  'close'
])
const isLoading = ref([])
const selectedAnggota = ref([])
const selectedNonAnggota = ref([])
const isAlertDialog = ref(false)
const dialogText = ref('')
const aksiAll = async (aksi) => {
  if (aksi == 'add-all') {
    if (!selectedNonAnggota.value.length) {
      dialogText.value = 'Silahkan Pilih PD Non Anggota Rombel! terlebih dahulu!'
      isAlertDialog.value = true
    } else {
      await handleAksiAll(aksi)
    }
  }
  if (aksi == 'remove-all') {
    if (!selectedAnggota.value.length) {
      dialogText.value = 'Silahkan Pilih PD Anggota Rombel! terlebih dahulu!'
      isAlertDialog.value = true
    } else {
      await handleAksiAll(aksi)
    }
  }
}
const handleAksiAll = async (aksi) => {
  await $api('/induk/store', {
    method: 'POST',
    body: {
      data: aksi,
      ids: aksi == 'add-all' ? selectedNonAnggota.value : selectedAnggota.value,
      rombongan_belajar_id: props.rombonganBelajarId,
    },
    onResponse() {
      fetchData('anggota')
      fetchData('non-anggota')
      selectedNonAnggota.value = []
      selectedAnggota.value = []
    }
  })
}
const aksi = async (id, aksi) => {
  await $api('/induk/store', {
    method: 'POST',
    body: {
      data: aksi,
      id: id,
      rombongan_belajar_id: props.rombonganBelajarId,
    },
    onResponse() {
      fetchData('anggota')
      fetchData('non-anggota')
    }
  })
}
const optionAnggota = ref({
  page: 1,
  itemsPerPage: 10,
  searchQuery: '',
  sortby: 'nama',
  sortbydesc: 'ASC',
});

const optionNonAnggota = ref({
  page: 1,
  itemsPerPage: 10,
  searchQuery: '',
  sortby: 'nama',
  sortbydesc: 'ASC',
});
watch(optionAnggota, async () => {
  await fetchData('anggota');
}, { deep: true });
watch(
  () => optionAnggota.value.searchQuery,
  () => {
    optionAnggota.value.page = 1
  }
)
watch(optionNonAnggota, async () => {
  await fetchData('non-anggota');
}, { deep: true });
watch(
  () => optionNonAnggota.value.searchQuery,
  () => {
    optionNonAnggota.value.page = 1
  }
)
const cariAnggota = ref('')
const cariNonAnggota = ref('')
const itemAnggota = ref([])
const totalAnggota = ref(0)
const headers = [
  {
    key: 'nama',
    title: 'nama',
    nowrap: true,
  },
  {
    key: 'nisn',
    title: 'nisn',
    align: 'center',
    nowrap: true,
  },
  /*{
    key: 'jenis_kelamin',
    title: 'JK',
    align: 'center',
    nowrap: true,
  },*/
  {
    key: 'actions',
    title: 'keluarkan',
    align: 'center',
    nowrap: true,
  },
]
const pageAnggota = ref(1)
const itemNonAnggota = ref([])
const totalNonAnggota = ref(0)
const pageNonAnggota = ref(1)
const fetchData = async (data) => {
  isLoading.value[data] = true;
  try {
    const response = await useApi(createUrl(`/induk/get-data`, {
      query: {
        data: data,
        //q: options.value.searchQuery,
        page: (data == 'anggota') ? optionAnggota.value.page : optionNonAnggota.value.page,
        nama: (data == 'anggota') ? optionAnggota.value.searchQuery : optionNonAnggota.value.searchQuery,
        per_page: (data == 'anggota') ? optionAnggota.value.itemsPerPage : optionNonAnggota.value.itemsPerPage,
        rombongan_belajar_id: props.rombonganBelajarId,
        semester_id: props.semesterId,
      }
    }));
    let getData = response.data.value
    if (data == 'anggota') {
      itemAnggota.value = getData.data
      totalAnggota.value = getData.total
    } else {
      itemNonAnggota.value = getData.data
      totalNonAnggota.value = getData.total
    }
  } catch (error) {
    console.error(error);
  } finally {
    isLoading.value[data] = false;
  }
}
watch(() => props.isDialogVisible, () => {
  cariAnggota.value = null
  cariNonAnggota.value = null
  if (props.isDialogVisible) {
    fetchData('anggota')
    fetchData('non-anggota')
  }
})
const form = ref({
  namaAnggota: null,
  namaNonAnggota: null,
})
const findAnggota = debounce(async (val) => {
  optionAnggota.value.searchQuery = val
}, 500)
const findNonAnggota = debounce(async (val) => {
  optionNonAnggota.value.searchQuery = val
}, 500)
</script>

<template>
  <VDialog :model-value="props.isDialogVisible" @update:model-value="updateModelValue" fullscreen :scrim="false"
    transition="dialog-bottom-transition">
    <VCard>
      <VToolbar color="primary" class="sticky-header">
        <VToolbarTitle>{{ dialogTitle }}</VToolbarTitle>
        <VSpacer />
        <VToolbarItems>
          <VBtn variant="text" @click="updateModelValue(false)">
            <VIcon icon="tabler-x" class="me-2"></VIcon> Tutup
          </VBtn>
        </VToolbarItems>
      </VToolbar>
      <VRow class="mt-4">
        <VCol cols="6">
          <VCardText>
            <VRow>
              <VCol cols="4">
                <AppSelect v-model="optionAnggota.itemsPerPage" :items="[
                  { value: 10, title: '10' },
                  { value: 25, title: '25' },
                  { value: 50, title: '50' },
                  { value: 100, title: '100' },
                ]" />
              </VCol>
              <VCol cols="4">
                <AppTextField v-model="form.namaAnggota" @update:model-value="findAnggota" placeholder="Cari Data" />
              </VCol>
              <VCol cols="4">
                <VBtn block color="error" @click="aksiAll('remove-all')">Keluarkan Terpilih</VBtn>
              </VCol>
            </VRow>
          </VCardText>
          <VDataTableServer v-model:items-per-page="optionAnggota.itemsPerPage" v-model:page="optionAnggota.page"
            :items="itemAnggota" :items-length="totalAnggota" :headers="headers" :loading="isLoading['anggota']"
            v-model:model-value="selectedAnggota" item-value="id" show-select loading-text="Loading...">
            <template #item.actions="{ item }">
              <VBtn color="error" size="small" @click="aksi(item.id, 'remove-anggota')">Keluarkan</VBtn>
            </template>
            <template #bottom>
              <TablePagination v-model:page="optionAnggota.page" :items-per-page="optionAnggota.itemsPerPage"
                :total-items="totalAnggota" />
            </template>
          </VDataTableServer>
        </VCol>
        <VCol cols="6">
          <VCardText>
            <VRow>
              <VCol cols="4">
                <AppSelect v-model="optionNonAnggota.itemsPerPage" :items="[
                  { value: 10, title: '10' },
                  { value: 25, title: '25' },
                  { value: 50, title: '50' },
                  { value: 100, title: '100' },
                ]" />
              </VCol>
              <VCol cols="4">
                <AppTextField v-model="form.namaNonAnggota" @update:model-value="findNonAnggota"
                  placeholder="Cari Data" />
              </VCol>
              <VCol cols="4">
                <VBtn block color="success" @click="aksiAll('add-all')">Masukkan Terpilih</VBtn>
              </VCol>
            </VRow>
          </VCardText>
          <VDataTableServer v-model:items-per-page="optionNonAnggota.itemsPerPage" v-model:page="optionNonAnggota.page"
            :items="itemNonAnggota" :items-length="totalNonAnggota" :headers="headers"
            :loading="isLoading['non-anggota']" v-model:model-value="selectedNonAnggota" item-value="id" show-select
            loading-text="Loading...">
            <template #item.actions="{ item }">
              <VBtn color="success" size="small" @click="aksi(item.id, 'add-anggota')">Masukkan</VBtn>
            </template>
            <template #bottom>
              <TablePagination v-model:page="optionNonAnggota.page" :items-per-page="optionNonAnggota.itemsPerPage"
                :total-items="totalNonAnggota" />
            </template>
          </VDataTableServer>
        </VCol>
      </VRow>
    </VCard>
  </VDialog>
  <VDialog v-model="isAlertDialog" persistent max-width="300">
    <VCard>
      <VCardText class="text-center">
        <v-icon size="100" color="error" icon="tabler-alert-circle" />
        <h2 class="error--text">Error!</h2>
        <p>{{ dialogText }}</p>
      </VCardText>
      <VCardText class="text-center">
        <VBtn @click="isAlertDialog = false">OK</VBtn>
      </VCardText>
    </VCard>
  </VDialog>
</template>
<style lang="scss">
.sticky-header {
  position: sticky !important;
  top: 0;
  z-index: 1;
}
</style>
