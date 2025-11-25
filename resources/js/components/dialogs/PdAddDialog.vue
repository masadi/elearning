<script setup>
import { VForm } from 'vuetify/components/VForm'

const props = defineProps({
  sekolahId: {
    type: String,
    required: true,
  },
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'notif',
  //'update:importedData',
])
const isLoading = ref(false)
const fileExcel = ref('')
const isFormValid = ref(false)
const refForm = ref()
const onSubmit = async () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      isLoading.value = true
      await $api('/induk/store', {
        method: 'POST',
        body: {
          data: 'peserta-didik',
          sekolah_id: props.sekolahId,
          item: imported_data.value,
        },
        onResponse({ request, response, options }) {
          let getData = response._data
          emit('update:isDialogVisible', false)
          emit('notif', getData)
          imported_data.value = []
          fileExcel.value = ''
        }
      })
    }
  })
}

const onReset = () => {
  isLoading.value = false
  emit('update:isDialogVisible', false)
  fileExcel.value = ''
}
const showTable = ref(false)
const imported_data = ref([])
const importData = async (val) => {
  isLoading.value = true
  const postData = new FormData();
  postData.append('file_excel', val);
  await $api('/induk/import', {
    method: 'POST',
    body: postData,
    onResponse({ request, response, options }) {
      let getData = response._data
      imported_data.value = getData.imported_data
      showTable.value = true
      isLoading.value = false
    }
  })
}
</script>

<template>
  <VDialog fullscreen :scrim="false" scrollable content-class="scrollable-dialog" transition="dialog-bottom-transition"
    :model-value="props.isDialogVisible" @update:model-value="onReset">
    <!-- 👉 Dialog close btn -->
    <!--DialogCloseBtn @click="onReset" /-->

    <VCard>
      <div>
        <VToolbar color="primary">
          <VBtn icon variant="plain" @click="onReset">
            <VIcon color="white" icon="tabler-x" />
          </VBtn>
          <VToolbarTitle>Tambah Peserta Didik</VToolbarTitle>
          <VSpacer />
          <VToolbarItems>
            <VBtn variant="text" href="/unduhan/template-pd" target="_blank">Unduh Template
              <VIcon end icon="tabler-cloud-download" />
            </VBtn>
            <VBtn variant="text" @click="onSubmit" :loading="isLoading" :disabled="isLoading">Simpan</VBtn>
          </VToolbarItems>
        </VToolbar>
      </div>
      <VCardText>
        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
          <VFileInput accept=".xlsx" v-model="fileExcel" label="Import Excel" @update:modelValue="importData" />
          <VTable class="permission-table text-no-wrap mb-6 mt-4">
            <thead>
              <tr>
                <th>no</th>
                <th>nama</th>
                <th>nipd</th>
                <th>nisn</th>
                <th>jenis_kelamin</th>
                <th>tempat_lahir</th>
                <th>tanggal_lahir</th>
                <th>agama</th>
                <th>alamat</th>
                <th>telepon</th>
                <th>nama_ayah</th>
                <th>agama_ayah</th>
                <th>pekerjaan_ayah</th>
                <th>nama_ibu</th>
                <th>agama_ibu</th>
                <th>pekerjaan_ibu</th>
                <th>nama_wali</th>
                <th>agama_wali</th>
                <th>pekerjaan_wali</th>
                <th>alamat_wali</th>
                <th>sekolah_asal</th>
                <th>tanggal_diterima</th>
                <th>nomor_surat_pindah</th>
                <th>nipd_asal</th>
                <th>program_pilihan</th>
                <th>nilai_un</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in imported_data" :key="item.no">
                <td v-for="value, key, i in item">
                  <template v-if="key == 'no'">
                    {{ value }}
                  </template>
                  <template v-else>
                    <template v-if="key != 'nipd'">
                      <AppTextField v-model="item[key]" :rules="[requiredValidator]" />
                    </template>
                    <template v-else>
                      <AppTextField v-model="item[key]" />
                    </template>
                  </template>
                </td>
              </tr>
            </tbody>
          </VTable>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>

<style lang="scss">
.permission-table {
  td {
    border-block-end: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
    padding-block: 0.5rem;

    .v-checkbox {
      min-inline-size: 4.75rem;
    }

    &:not(:first-child) {
      padding-inline: 0.5rem;
    }

    .v-label {
      white-space: nowrap;
    }
  }
}
</style>
