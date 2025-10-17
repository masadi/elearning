<script setup>
import { VForm } from 'vuetify/components/VForm'

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
})
const isLoading = ref(false)
const emit = defineEmits([
  'update:isDialogVisible',
  'notif',
])
const items = ref([{
  id: 1,
  mapel_id: null,
  nama: null,
  alias: null,
}])
const nextTodoId = ref(2)
const isFormValid = ref(false)
const refForm = ref()
const onSubmit = async () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      isLoading.value = true
      const postData = new FormData();
      postData.append('data', 'mapel');
      items.value.forEach(e => {
        postData.append('nama[]', (e.nama) ? e.nama : '');
        postData.append('alias[]', (e.alias) ? e.alias : '');
      });
      await $api('/referensi/store', {
        method: 'POST',
        body: postData,
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
  isLoading.value = false
  items.value = [{
    id: 1,
    mapel_id: null,
    nama: null,
    alias: null,
  }]
  nextTodoId.value = 2
}
const addForm = () => {
  items.value.push({
    id: nextTodoId.value += nextTodoId.value,
  })
}
const delForm = (index) => {
  items.value.splice(index, 1)
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
          <VToolbarTitle>Tambah Mata Pelajaran</VToolbarTitle>
          <VSpacer />
          <VToolbarItems>
            <VBtn variant="text" @click="addForm">Tambah Form
              <VIcon end icon="tabler-copy" />
            </VBtn>
            <VBtn variant="text" @click="onSubmit">Simpan</VBtn>
          </VToolbarItems>
        </VToolbar>
      </div>
      <VCardText>
        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
          <VRow v-for="(item, index) in items" :id="item.id" :key="item.id">
            <VCol md="8">
              <AppTextField id="nama" v-model="item.nama" :rules="[requiredValidator]">
                <template #label>Nama Mata Pelajaran</template>
              </AppTextField>
            </VCol>
            <VCol md="2">
              <AppTextField id="jenis_kelamin" v-model="item.alias" :rules="[requiredValidator]">
                <template #label>Nama Alias</template>
              </AppTextField>
            </VCol>
            <VCol md="2">
              <VBtn color="warning" @click="delForm(index)">
                <VIcon icon="tabler-x" /> Hapus
              </VBtn>
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
