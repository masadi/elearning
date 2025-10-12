<script setup>
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
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
  pageType: {
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
])
const isFormValid = ref(false)
const refForm = ref()
const form = ref({
  sekolah_id: null,
  type: props.pageType,
  content: null,
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
    sekolah_id: null,
    type: props.pageType,
    content: null,
  }
}
watch(props, async () => {
  console.log(props);

  if (props.isDialogVisible && props.detilData) {
    form.value = {
      sekolah_id: props.detilData.sekolah_id,
      type: props.pageType,
      content: props.detilData.content,
    }
  }
})
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
          <VToolbarTitle>{{ detilData ? 'Edit' : 'Tambah' }} Laman <span class="text-capitalize">{{ pageType }}</span>
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
                  <label class="v-label text-body-2 text-high-emphasis" for="content">Deskripsi</label>
                </VCol>
                <VCol cols="12" md="9">
                  <QuillEditor theme="snow" toolbar="full" v-model:content="form.content" contentType="html"
                    :rules="[requiredValidator]" />
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
