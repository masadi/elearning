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
  mata_pelajaran_id: null,
  nama_mata_pelajaran: null,
}])
const nextTodoId = ref(2)
const isFormValid = ref(false)
const refForm = ref()
const onSubmit = async() => {
  refForm.value?.validate().then(async({ valid }) => {
    if (valid) {
      const postData = new FormData();
      postData.append('data', 'update-rombel');
      postData.append('rombongan_belajar_id', detilData.value.rombongan_belajar_id);
      postData.append('nama', detilData.value.nama);
      postData.append('wali_id', detilData.value.ptk_id);
      postData.append('tingkat', detilData.value.tingkat);
      items.value.forEach(e => {
        postData.append('ptk_id[]', (e.ptk_id) ? e.ptk_id : '');
        postData.append('mata_pelajaran_id[]', (e.mata_pelajaran_id) ? e.mata_pelajaran_id : '');
        postData.append('nama_mata_pelajaran[]', (e.nama_mata_pelajaran) ? e.nama_mata_pelajaran : '');
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
const mapel = ref([])
const isBusy = ref(true)
const detilData = ref(structuredClone(toRaw(props.detilData)))
watch(props, async() => {
  detilData.value = structuredClone(toRaw(props.detilData))
  if(props.isDialogVisible){
    getData('ptk')
    getData('mapel')
  }
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
      if(data == 'mapel'){
        mapel.value = getData
      }
      isBusy.value = false
    }
  })
}
const tingkat = [10, 11, 12, 13];
const changeMapel = (index, val) => {
  const firstMatchingObject = mapel.value.find(item => item.id === val);
  console.log(firstMatchingObject);
  items.value[index].nama_mata_pelajaran = firstMatchingObject.nama
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
          <VToolbarTitle>Detil Rombel {{ detilData.nama }}</VToolbarTitle>
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
          <VRow>
            <VCol cols="12">
              <VRow>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="app-text-field-nama">Nama Rombel</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="nama" v-model="detilData.nama" :rules="[requiredValidator]">
                    <template #label>Nama Rombel</template>
                  </AppTextField>
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="app-text-field-tingkat">Tingkat</label>
                </VCol>
                <VCol cols="12" md="9">
                  <VAutocomplete v-model="detilData.tingkat" label="Tingkat" variant="outlined" :items="tingkat" placeholder="== Pilih Tingkat ==" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="app-text-field-ptk_id">Wali Kelas</label>
                </VCol>
                <VCol cols="12" md="9">
                  <VAutocomplete chips v-model="detilData.ptk_id" label="Guru" variant="outlined" :items="ptk" item-title="nama" item-value="ptk_id" placeholder="== Pilih Guru ==" :rules="[requiredValidator]">
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
              </VRow>
            </VCol>
            <VCol cols="12">
              <h2>Pembelajaran</h2>
            </VCol>
          </VRow>
          <VRow v-for="(item, index) in items" :id="item.id" :key="item.id">
            <VCol md="4">
              <VAutocomplete v-model="item.mata_pelajaran_id" label="Mata Pelajaran" variant="outlined" :items="mapel" item-title="nama" item-value="id" placeholder="== Pilih Mata Pelajaran ==" :rules="[requiredValidator]" @update:modelValue="changeMapel(index, $event)" />
            </VCol>
            <VCol md="4">
              <AppTextField v-model="item.nama_mata_pelajaran" :rules="[requiredValidator]">
                <template #label>Nama Mata Pelajaran</template>
              </AppTextField>
            </VCol>
            <VCol md="3">
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
            <VCol md="1">
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
