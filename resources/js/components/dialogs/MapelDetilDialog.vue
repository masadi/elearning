<script setup>
import { VForm } from 'vuetify/components/VForm'

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  detilData: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'notif',
])
const items = ref([{
  id: 1,
  ptk_id: null,
  rombongan_belajar_id: null,
}])
const mataPelajaranId = ref()
const nextTodoId = ref(2)
const isFormValid = ref(false)
const refForm = ref()
const onSubmit = async() => {
  refForm.value?.validate().then(async({ valid }) => {
    if (valid) {
      const postData = new FormData();
      postData.append('data', 'pembelajaran');
      postData.append('mata_pelajaran_id', props.detilData.id);
      postData.append('nama_mata_pelajaran', props.detilData.nama);
      items.value.forEach(e => {
        postData.append('ptk_id[]', (e.ptk_id) ? e.ptk_id : '');
        postData.append('rombongan_belajar_id[]', (e.rombongan_belajar_id) ? e.rombongan_belajar_id : '');
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
  items.value = [{
    id: 1,
    ptk_id: null,
  rombongan_belajar_id: null,
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
const ptk = ref([]);
const rombel = ref([])
const isBusy = ref(true)
watch(props, async() => {
  if(props.isDialogVisible){
    getData('ptk')
    getData('rombel')
  }
  mataPelajaranId.value = props.detilData.id
})
const getData = async(data) => {
  await $api(`/referensi`, { 
    query: {
      data: data,
    },
    onResponse({ request, response, options }) {
      let getData = response._data
      if(data == 'ptk'){
        ptk.value = getData
      }
      if(data == 'rombel'){
        rombel.value = getData
      }
      isBusy.value = false
    }
  })
}
</script>

<template>
  <VDialog
    fullscreen
    :scrim="false"
    scrollable
    content-class="scrollable-dialog"
    transition="dialog-bottom-transition"
    :model-value="props.isDialogVisible"
    @update:model-value="onReset"
  >
    <!-- 👉 Dialog close btn -->
    <!--DialogCloseBtn @click="onReset" /-->

    <VCard>
      <div>
        <VToolbar color="primary">
          <VBtn icon variant="plain" @click="onReset">
            <VIcon color="white" icon="tabler-x" />
          </VBtn>
          <VToolbarTitle>Detil Mata Pelajaran {{ detilData.nama }}</VToolbarTitle>
          <VSpacer />
          <VToolbarItems>
            <VBtn variant="text" @click="addForm">Tambah Form <VIcon end icon="tabler-copy" /></VBtn>
            <VBtn variant="text" @click="onSubmit">Simpan</VBtn>
          </VToolbarItems>
        </VToolbar>
      </div>
      <VCardText>
        <VProgressCircular indeterminate color="warning" size="150" v-if="isBusy"/>
        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit" v-else>
          <VRow v-for="(item, index) in items" :id="item.id" :key="item.id">
            <VCol md="5">
              <VAutocomplete chips v-model="item.ptk_id" label="Guru" variant="outlined" :items="ptk" item-title="nama" item-value="ptk_id" placeholder="== Pilih Guru ==" :rules="[requiredValidator]">
                <template #chip="{ props, item }">
                  <VChip
                    v-bind="props"
                    :prepend-avatar="item.raw.avatar"
                    :text="item.raw.name"
                  />
                </template>

                <template #item="{ props, item }">
                  <VListItem
                    v-bind="props"
                    :prepend-avatar="item?.raw?.avatar"
                    :title="item?.raw?.nama"
                    :subtitle="item?.raw?.email"
                  />
                </template>
              </VAutocomplete>
            </VCol>
            <VCol md="5">
              <VAutocomplete v-model="item.rombongan_belajar_id" label="Rombel" variant="outlined" :items="rombel" item-title="nama" item-value="rombongan_belajar_id" placeholder="== Pilih Rombel ==" :rules="[requiredValidator]" />
            </VCol>
            <VCol md="2">
              <VBtn block color="warning" @click="delForm(index)"><VIcon icon="tabler-x" /> Hapus</VBtn>
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
