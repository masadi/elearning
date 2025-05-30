<script setup>
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  detilPtk: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(['update:isDialogVisible'])

const dialogModelValueUpdate = val => {
  emit('update:isDialogVisible', val)
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

    <VCard class="pa-2 pa-sm-10" v-if="props.detilPtk">
      <VCardText>
        <div class="text-center">
          <VAvatar
            rounded
            :size="100"
            :color="!props.detilPtk.avatar ? 'primary' : undefined"
            :variant="!props.detilPtk.avatar ? 'tonal' : undefined"
          >
            <VImg
              v-if="props.detilPtk.avatar"
              :src="props.detilPtk.avatar"
            />
            <span
              v-else
              class="text-5xl font-weight-medium"
            >
              {{ avatarText(props.detilPtk.nama) }}
            </span>
          </VAvatar>
        </div>
        <!-- 👉 Title -->
        <h4 class="text-h4 text-center mb-2">
          {{ detilPtk.nama }}
        </h4>
        <VTable class="permission-table text-no-wrap mb-6 mt-4">
          <tr>
            <td>Nama</td>
            <td>: {{ detilPtk.nama }}</td>
          </tr>
          <tr>
            <td>NIK</td>
            <td>: {{ detilPtk.nik }}</td>
          </tr>
          <tr>
            <td>NUPTK</td>
            <td>: {{ detilPtk.nuptk }}</td>
          </tr>
          <tr>
            <td>Email</td>
            <td>: {{ detilPtk.email }}</td>
          </tr>
          <tr>
            <td>Jenis Kelamin</td>
            <td>: {{ detilPtk.jenis_kelamin }}</td>
          </tr>
          <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>: {{ detilPtk.tempat_lahir }}, {{ new Date(detilPtk.tanggal_lahir).toLocaleString('id-ID', {
          hour12: false,
          day: 'numeric',
          month: 'long',
          year: 'numeric',
        }) }}</td>
          </tr>
        </VTable>
      </VCardText>
    </VCard>
  </VDialog>
</template>
