<script setup>
import { VForm } from 'vuetify/components/VForm'

const props = defineProps({
  sekolah: {
    type: Object,
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

const fileExcel = ref('')
const isFormValid = ref(false)
const refForm = ref()
const onSubmit = async () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      await $api('/referensi/store', {
        method: 'POST',
        body: {
          data: 'import-ptk',
          sekolah_id: props.sekolah.sekolah_id,
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
  emit('update:isDialogVisible', false)
  fileExcel.value = ''
}
const showTable = ref(false)
const imported_data = ref([])
const importData = async (val) => {
  const postData = new FormData();
  postData.append('file_excel', val);
  await $api('/referensi/import', {
    method: 'POST',
    body: postData,
    onResponse({ request, response, options }) {
      let getData = response._data
      imported_data.value = getData.imported_data
      showTable.value = true
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
          <VToolbarTitle>Tambah PTK</VToolbarTitle>
          <VSpacer />
          <VToolbarItems>
            <VBtn variant="text" href="/unduhan/template-ptk" target="_blank">Unduh Template
              <VIcon end icon="tabler-cloud-download" />
            </VBtn>
            <VBtn variant="text" @click="onSubmit">Simpan</VBtn>
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
                <th>nuptk</th>
                <th>email</th>
                <th>jenis_kelamin</th>
                <th>tempat_lahir</th>
                <th>tanggal_lahir</th>
                <th>nik</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in imported_data" :key="item.no">
                <td v-for="value, key, i in item">
                  <template v-if="key == 'no'">
                    {{ value }}
                  </template>
                  <template v-else>
                    <AppTextField v-model="item[key]" :rules="[requiredValidator, emailValidator]"
                      v-if="key == 'email'" />
                    <AppTextField v-model="item[key]" :rules="[requiredValidator]" v-else-if="key != 'nuptk'" />
                    <AppTextField v-model="item[key]" v-else />
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
