<script setup>
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import { VForm } from 'vuetify/components/VForm';
const dateConfig = ref({
  locale: Indonesian,
  altFormat: "j F Y",
  altInput: true,
});

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  sekolah: {
    type: Array,
    required: true,
  },
  detilData: {
    type: Object,
    required: false,
    default: null,
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'notif',
])
const isFormValid = ref(false)
const refForm = ref()
const form = ref({
  type: 'galeri',
  id: null,
  sekolah_id: null,
  nama: null,
  foto_id_gdrive: null,
  folder_id_gdrive: null,
  tanggal: null,
  lokasi: null,
})
const onSubmit = async () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      await $api('/laman/store', {
        method: 'POST',
        body: form.value,
        onResponse({ request, response, options }) {
          let getData = response._data
          onReset()
          emit('notif', getData)
        }
      })
    }
  })
}
const onReset = () => {
  emit('update:isDialogVisible', false)
  form.value = {
    type: 'galeri',
    id: null,
    sekolah_id: null,
    nama: null,
    foto_id_gdrive: null,
    folder_id_gdrive: null,
    tanggal: null,
    lokasi: null,
  }
}
watch(props, async () => {
  if (props.isDialogVisible && props.detilData) {
    form.value = {
      type: 'galeri',
      id: props.detilData.id,
      sekolah_id: props.detilData.sekolah_id,
      nama: props.detilData.nama,
      foto_id_gdrive: props.detilData.foto_id_gdrive,
      folder_id_gdrive: props.detilData.folder_id_gdrive,
      tanggal: props.detilData.tanggal,
      lokasi: props.detilData.lokasi,
    }
  }
})
</script>

<template>
  <VDialog fullscreen scrollable content-class="scrollable-dialog" transition="dialog-bottom-transition"
    :model-value="props.isDialogVisible" @update:model-value="onReset">
    <!-- 👉 Dialog close btn -->
    <!--DialogCloseBtn @click="onReset" /-->

    <VCard>
      <div>
        <VToolbar color="primary">
          <VBtn icon variant="plain" @click="onReset">
            <VIcon color="white" icon="tabler-x" />
          </VBtn>
          <VToolbarTitle>{{ detilData ? 'Edit' : 'Tambah' }} Laman Galeri
          </VToolbarTitle>
          <VSpacer />
          <VToolbarItems>
            <VBtn variant="text" @click="onSubmit">Simpan</VBtn>
          </VToolbarItems>
        </VToolbar>
      </div>
      <VCardText>
        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
          <VRow>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="sekolah_id">Sekolah</label>
                </VCol>
                <VCol cols="12" md="9">
                  <VAutocomplete v-model="form.sekolah_id" variant="outlined" :items="sekolah" item-title="nama"
                    item-value="sekolah_id" placeholder="== Pilih Sekolah ==" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="nama">Nama</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="nama" v-model="form.nama" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="foto_id_gdrive">Thumbnail (Foto ID
                    Drive)</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="foto_id_gdrive" v-model="form.foto_id_gdrive" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="folder_id_gdrive">Folder ID Drive</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="folder_id_gdrive" v-model="form.folder_id_gdrive" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="tanggal">Tanggal</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppDateTimePicker id="tanggal" v-model="form.tanggal" placeholder="== Pilih Tanggal =="
                    :config="dateConfig" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="lokasi">Lokasi</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="lokasi" v-model="form.lokasi" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
          </VRow>
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
