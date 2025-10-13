<script setup>
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
  kontenType: {
    type: String,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'notif',
  //'update:importedData',
])
const form = ref({
  sekolah_id: null,
  gambar: null,
  data: props.kontenType,
})
const isFormValid = ref(false)
const refForm = ref()
const onSubmit = async () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      const postData = new FormData();
      postData.append('data', form.value.data ?? '');
      postData.append('sekolah_id', form.value.sekolah_id ?? '');
      postData.append('gambar', form.value.gambar ?? '');
      await $api('/konten/store', {
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
    sekolah_id: null,
    gambar: null,
    data: props.kontenType,
  }
  refForm.value.resetValidation()
}
</script>

<template>
  <VDialog width="500" :model-value="props.isDialogVisible" @update:model-value="onReset">
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="onReset" />

    <VCard title="Upload Slide">
      <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
        <VCardText>
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
                  <label class="v-label text-body-2 text-high-emphasis" for="gambar">Gambar</label>
                </VCol>
                <VCol cols="12" md="9">
                  <VFileInput accept="image/*" v-model="form.gambar" :rules="[requiredValidator]" />
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
