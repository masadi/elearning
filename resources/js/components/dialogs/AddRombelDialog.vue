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
  listSemester: {
    type: Array,
    required: false,
    default: [],
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
  data: 'rombongan-belajar',
  id: null,
  sekolah_id: props.sekolahId,
  nama: null,
  tingkat: null,
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
    data: 'rombongan-belajar',
    id: null,
    sekolah_id: props.sekolahId,
    semester_id: null,
    nama: null,
    tingkat: null,
  }
}
watch(props, async () => {
  if (props.isDialogVisible && props.detilData) {
    form.value = {
      data: 'rombongan-belajar',
      id: props.detilData.id,
      sekolah_id: props.detilData.sekolah_id,
      semester_id: props.detilData.semester_id,
      nama: props.detilData.nama,
      tingkat: props.detilData.tingkat,
    }
  }
})
const tingkatKelas = [
  { title: '1', value: '1' },
  { title: '2', value: '2' },
  { title: '3', value: '3' },
  { title: '4', value: '4' },
  { title: '5', value: '5' },
  { title: '6', value: '6' },
]
</script>

<template>
  <VDialog width="500" :model-value="props.isDialogVisible" @update:model-value="onReset">
    <!-- 👉 Dialog close btn -->
    <!--DialogCloseBtn @click="onReset" /-->

    <VCard :title="form.id ? 'Edit Rombongan Belajar' : 'Tambah Rombongan Belajar'">
      <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
        <VCardText>
          <VRow>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="semester_id">Semester</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppSelect id="semester_id" v-model="form.semester_id" :rules="[requiredValidator]"
                    :items="props.listSemester" item-title="nama" item-value="semester_id"
                    placeholder="== Pilih Semester ==" />
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
                  <label class="v-label text-body-2 text-high-emphasis" for="tingkat">Tingkat Kelas</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppSelect id="tingkat" v-model="form.tingkat" :rules="[requiredValidator]" :items="tingkatKelas"
                    placeholder="== Pilih Tingkat Kelas ==" />
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
