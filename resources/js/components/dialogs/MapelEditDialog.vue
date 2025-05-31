<script setup>
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  detilData: {
    type: Object,
    required: false,
    default: () => ({
      nama: '',
      nuptk: '',
      email: '',
      jenis_kelamin: '',
      tempat_lahir: false,
      tanggal_lahir: '',
      nik: '',
    }),
  },
})

const emit = defineEmits(['update:isDialogVisible', 'submit'])
const dialogModelValueUpdate = val => {
  emit('update:isDialogVisible', val)
}

const detilData = ref(structuredClone(toRaw(props.detilData)))
watch(props, () => {
  detilData.value = structuredClone(toRaw(props.detilData))
})
const isFormValid = ref(false)
const refForm = ref()
const formSubmit = () => {
  refForm.value?.validate().then(async({ valid }) => {
    if (valid) {
      emit('submit', detilData.value)
    }
  })
}
</script>

<template>
  <!-- 👉 upgrade plan -->
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 500"
    :model-value="props.isDialogVisible"
    scrollable
    content-class="scrollable-dialog"
    @update:model-value="dialogModelValueUpdate"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />
    <VCard :title="`Edit Mata Pelajaran`">
      <VCardText>
        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="formSubmit">
          <VRow>
            <VCol cols="12">
              <AppTextField id="nama" v-model="detilData.nama" :rules="[requiredValidator]">
                <template #label>Nama Mata Pelajaran</template>
              </AppTextField>
            </VCol>
            <VCol cols="12">
              <AppTextField id="nik" v-model="detilData.alias" :rules="[requiredValidator]">
                <template #label>Nama Alias</template>
              </AppTextField>
            </VCol>
            <VCol cols="12" class="d-flex gap-4">
              <VBtn type="submit">Submit</VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
