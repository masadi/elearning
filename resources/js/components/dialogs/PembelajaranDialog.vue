<script setup>
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  isLoading: {
    type: Boolean,
    required: true,
  },
  dialogTitle: {
    type: String,
    required: true,
  },
  form: {
    type: Object,
    required: true,
  },
  listData: {
    type: Array,
    required: true,
  },
  listKelompok: {
    type: Array,
    required: true,
  },
})
const updateModelValue = val => {
  emit('update:isDialogVisible', val)
}
const emit = defineEmits([
  'update:isDialogVisible',
  'save',
  'refresh'
])
const onSubmit = async () => {
  emit('save')
}
const loadings = ref([])
const hapus = async (pembelajaran_id) => {
  loadings.value[pembelajaran_id] = true
  await $api(`/induk/destroy/pembelajaran/${pembelajaran_id}`, {
    method: 'DELETE',
    onResponse() {
      loadings.value[pembelajaran_id] = false
      emit('refresh')
    }
  })
}
</script>

<template>
  <VDialog :model-value="props.isDialogVisible" @update:model-value="updateModelValue" fullscreen :scrim="false"
    transition="dialog-bottom-transition">
    <VCard>
      <VToolbar color="primary" class="sticky-header">
        <VBtn icon variant="plain" @click="updateModelValue(false)">
          <VIcon color="white" icon="tabler-x" />
        </VBtn>
        <VToolbarTitle>{{ dialogTitle }}</VToolbarTitle>
        <VSpacer />
        <VToolbarItems>
          <VBtn variant="text" @click="onSubmit">
            <VIcon icon="tabler-device-floppy" class="me-2"></VIcon> Simpan
          </VBtn>
        </VToolbarItems>
      </VToolbar>
      <VTable class="permission-table mb-6">
        <thead>
          <tr>
            <th class="text-center">No</th>
            <th class="text-center">Mata Pelajaran</th>
            <th class="text-center">Kelompok</th>
            <th class="text-center">Nomor Urut</th>
            <th class="text-center">Reset</th>
          </tr>
        </thead>
        <tbody>
          <template v-if="isLoading">
            <tr>
              <td class="text-center" colspan="8">
                <VProgressCircular :size="60" indeterminate color="error" class="my-10" />
              </td>
            </tr>
          </template>
          <template v-else-if="listData.length">
            <tr v-for="(item, index) in listData">
              <td class="text-center">{{ index + 1 }}</td>
              <td>
                <AppTextField style="inline-size: 15.5rem;" v-model="form.nama[item.id]" />
              </td>
              <td>
                <AppAutocomplete :items="listKelompok" placeholder="== Pilih Kelompok ==" item-title="nama"
                  item-value="id" v-model="form.kelompok_id[item.id]" clearable />
              </td>
              <td class="text-center">
                <AppTextField style="inline-size: 4rem;" v-model="form.nomor_urut[item.id]" />
              </td>
              <td class="text-center">
                <VBtn :loading="loadings[item.id]" :disabled="loadings[item.id]" color="error" icon="tabler-trash"
                  @click="hapus(item.pembelajaran?.id)"
                  v-if="item.pembelajaran?.kelompok_id && item.pembelajaran?.nomor_urut" />
              </td>
            </tr>
          </template>
          <template v-else>
            <tr>
              <td class="text-center" colspan="5">Tidak ada untuk ditampilkan</td>
            </tr>
          </template>
        </tbody>
      </VTable>
    </VCard>
  </VDialog>
</template>
<style lang="scss">
.permission-table {
  td {
    border-block-end: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
    padding-block: 0.5rem;

    &:not(:first-child) {
      padding-inline: 0.5rem;
    }

    .v-label {
      white-space: nowrap;
    }
  }
}

.sticky-header {
  position: sticky !important;
  top: 0;
  z-index: 1;
}
</style>
