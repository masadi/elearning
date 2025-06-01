<script setup>
import { useConfigStore } from '@core/stores/config';

const props = defineProps({
  layouts: {
    type: Array,
    required: true,
  },
})

const configStore = useConfigStore()
const selectedItem = ref([configStore.layout])
//console.log(selectedItem.value);
// Update icon if theme is changed from other sources
watch(() => configStore.theme, () => {
  selectedItem.value = [configStore.layout]
}, { deep: true })
</script>

<template>
  <IconBtn color="rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity))">
    <VIcon :icon="props.layouts.find(t => t.name === configStore.layout)?.icon" />

    <VTooltip
      activator="parent"
      open-delay="1000"
      scroll-strategy="close"
    >
      <span class="text-capitalize">{{ configStore.layout }}</span>
    </VTooltip>

    <VMenu

      activator="parent"
      offset="12px"
      :width="180"
    >
      <VList
        v-model:selected="selectedItem"
        mandatory
      >
        <VListItem
          v-for="{ name, icon } in props.layouts"
          :key="name"
          :value="name"
          :prepend-icon="icon"
          color="primary"
          @click="() => { configStore.layout = name }"
        >
          <VListItemTitle class="text-capitalize">
            {{ name }}
          </VListItemTitle>
        </VListItem>
      </VList>
    </VMenu>
  </IconBtn>
</template>
