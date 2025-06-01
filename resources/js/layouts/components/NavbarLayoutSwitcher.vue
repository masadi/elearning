<script setup>
const layouts = [
  {
    name: 'horizontal',
    icon: 'tabler-device-desktop',
  },
  {
    name: 'vertical',
    icon: 'tabler-device-mobile',
  },
]
</script>

<template>
  <LayoutSwitcher :layouts="layouts" />
</template>
