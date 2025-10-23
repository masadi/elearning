<script setup>
import { ref } from 'vue'
import { VForm } from 'vuetify/components/VForm'

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  sekolahId: {
    type: String,
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
  //'update:importedData',
])

const isFormValid = ref(false)
const refForm = ref()
const form = ref({
  data: 'mata-pelajaran',
  id: null,
  sekolah_id: props.sekolahId,
  nama: null,
  alias: null,
})
const onSubmit = async () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      await $api('/induk/store', {
        method: 'POST',
        body: form.value,
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
    data: 'mata-pelajaran',
    id: null,
    sekolah_id: props.sekolahId,
    nama: null,
    alias: null,
  }
}
watch(props, async () => {
  if (props.isDialogVisible && props.detilData) {
    form.value = {
      data: 'mata-pelajaran',
      id: props.detilData.id,
      sekolah_id: props.detilData.sekolah_id,
      nama: props.detilData.nama,
      alias: props.detilData.alias,
    }
  }
})
</script>

<template>
  <VDialog width="500" :model-value="props.isDialogVisible" @update:model-value="onReset">
    <!-- 👉 Dialog close btn -->
    <!--DialogCloseBtn @click="onReset" /-->

    <VCard :title="form.id ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran'">
      <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
        <VCardText>
          <VRow>
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
                  <label class="v-label text-body-2 text-high-emphasis" for="alias">Alias</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="alias" v-model="form.alias" :rules="[requiredValidator]" />
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
