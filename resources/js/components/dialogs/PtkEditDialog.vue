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
})
const formSubmit = () => {
  emit('submit', detilPtk.value)
}
</script>

<template>
  <!-- 👉 upgrade plan -->
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 800"
    :model-value="props.isDialogVisible"
    scrollable
    content-class="scrollable-dialog"
    @update:model-value="dialogModelValueUpdate"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />
    <VCard class="pa-2 pa-sm-10" :title="`Edit Data ${detilPtk.nama}`">
      <VCardText>
        <VForm @submit.prevent="() => {}">
          <VRow>
            <VCol cols="12">
              <VRow>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="app-text-field-nama">Nama Lengkap</label>
                </VCol>
                <VCol cols="12" md="9">
                      <!--VFileInput accept="image/*" v-model="list.value" :id="list.id" :label="list.title" v-if="list.file"/-->
                    <AppTextField id="nama" v-model="detilPtk.nama" :rules="[requiredValidator]">
                      <template #label>Nama Lengkap</template>
                    </AppTextField>
                  </VCol>
              </VRow>
            </VCol>
            <VCol offset-md="3" cols="12" md="9" class="d-flex gap-4">
              <VBtn type="submit" @click="formSubmit">Submit</VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
