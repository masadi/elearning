<script setup>
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  detilPtk: {
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

const detilPtk = ref(structuredClone(toRaw(props.detilPtk)))
watch(props, () => {
  detilPtk.value = structuredClone(toRaw(props.detilPtk))
  detilPtk.value.avatar = null
})
const isFormValid = ref(false)
const refForm = ref()
const formSubmit = () => {
  refForm.value?.validate().then(async({ valid }) => {
    if (valid) {
      emit('submit', detilPtk.value)
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
    <VCard :title="`Edit Data ${detilPtk.nama}`">
      <VCardText>
        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="formSubmit">
          <VRow>
            <VCol cols="12">
              <AppTextField id="nama" v-model="detilPtk.nama" :rules="[requiredValidator]">
                <template #label>Nama Lengkap</template>
              </AppTextField>
            </VCol>
            <VCol cols="12">
              <AppTextField id="nik" v-model="detilPtk.nik" :rules="[requiredValidator]">
                <template #label>NIK</template>
              </AppTextField>
            </VCol>
            <VCol cols="12">
              <AppTextField id="email" v-model="detilPtk.email" :rules="[requiredValidator, emailValidator]">
                <template #label>Email</template>
              </AppTextField>
            </VCol>
            <VCol cols="12">
              <AppTextField id="jenis_kelamin" v-model="detilPtk.jenis_kelamin" :rules="[requiredValidator]">
                <template #label>Jenis Kelamin</template>
              </AppTextField>
            </VCol>
            <VCol cols="12">
              <AppTextField id="tempat_lahir" v-model="detilPtk.tempat_lahir" :rules="[requiredValidator]">
                <template #label>Tempat Lahir</template>
              </AppTextField>
            </VCol>
            <VCol cols="12">
              <AppTextField id="tanggal_lahir" v-model="detilPtk.tanggal_lahir" :rules="[requiredValidator, dateValidator]">
                <template #label>Tanggal Lahir</template>
              </AppTextField>
            </VCol>
            <VCol cols="12">
              <AppTextField id="nuptk" v-model="detilPtk.nuptk">
                <template #label>NUPTK</template>
              </AppTextField>
            </VCol>
            <VCol cols="12">
              <VFileInput accept="image/*" v-model="detilPtk.avatar" id="avatar" label="Foto Profil" />
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
