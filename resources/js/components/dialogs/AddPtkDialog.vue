<script setup>
import { ref } from 'vue'
import { VForm } from 'vuetify/components/VForm'

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  sekolah: {
    type: Array,
    required: true,
  },
  sekolahId: {
    type: String,
    required: false,
    default: null,
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
  //'update:importedData',
])

const isFormValid = ref(false)
const refForm = ref()
const form = ref({
  ptk_id: null,
  sekolah_id: props.sekolahId,
  nama: null,
  avatar: null,
  jabatan: null,
  urut: 0,
})
const onSubmit = async () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      const postData = new FormData();
      postData.append('data', 'ptk');
      postData.append('ptk_id', form.value.ptk_id ?? '');
      postData.append('sekolah_id', form.value.sekolah_id ?? '');
      postData.append('nama', form.value.nama ?? '');
      postData.append('jabatan', form.value.jabatan ?? '');
      postData.append('urut', form.value.urut ?? '');
      postData.append('avatar', form.value.avatar ?? '');
      await $api('/admin/konten/store', {
        method: 'POST',
        body: postData,
        onResponse({ request, response, options }) {
          let getData = response._data
          emit('notif', getData)
          onReset()
        }
      })
    }
  })
}

const onReset = () => {
  emit('update:isDialogVisible', false)
  form.value = {
    ptk_id: null,
    sekolah_id: props.sekolahId,
    nama: null,
    jabatan: null,
    avatar: null,
    urut: 0,
  }
}
watch(props, async () => {
  if (props.isDialogVisible && props.detilData) {
    form.value = {
      ptk_id: props.detilData.ptk_id,
      sekolah_id: props.detilData.sekolah_id,
      nama: props.detilData.nama,
      jabatan: props.detilData.jabatan,
      avatar: null,
      urut: props.detilData.urut,
    }
  }
})
</script>

<template>
  <VDialog width="500" :model-value="props.isDialogVisible" @update:model-value="onReset">
    <!-- 👉 Dialog close btn -->
    <!--DialogCloseBtn @click="onReset" /-->

    <VCard title="Tambah PTK">
      <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
        <VCardText>
          <VRow>
            <VCol cols="12" v-if="!$can('create', 'laman-tentang-create')">
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
                  <label class="v-label text-body-2 text-high-emphasis" for="jabatan">Jabatan</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="jabatan" v-model="form.jabatan" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="urut">Nomor Urut</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="urut" v-model="form.urut" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="avatar">Foto</label>
                </VCol>
                <VCol cols="12" md="9">
                  <VFileInput accept="image/*" v-model="form.avatar" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
          </VRow>
        </VCardText>
        <VCardText class="d-flex justify-end">
          <VBtn type="submit">
            Simpan
          </VBtn>
        </VCardText>
      </VForm>
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
