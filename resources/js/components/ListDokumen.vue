<script setup>
const props = defineProps({
  dokumen: {
    type: Array,
    required: true,
  },
})
</script>

<template>
  <VList>
    <VListItem v-for="item in dokumen" :key="item.dokumen_id" :value="item.dokumen_id" :to="`/storage/berkas/${item.path}`" target="_blank">
      <template #prepend>
        <VIcon :icon="`tabler-file-type-${item.extension.replace('xlsx', 'xls')}`" />
      </template>
      <VListItemTitle>
        {{ item.nama }}
      </VListItemTitle>
    </VListItem>
  </VList>
</template>
