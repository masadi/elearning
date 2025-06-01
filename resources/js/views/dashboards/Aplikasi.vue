<script setup>
const props = defineProps({
  aplikasi: {
    type: Array,
    required: true,
  },
})
</script>

<template>
  <VCard title="Info Aplikasi">
    <VCardText>
      <VRow>
        <VCol v-for="item in aplikasi" :key="item.title" cols="6">
          <div class="d-flex align-center gap-4 mt-0">
            <VAvatar :color="item.color" variant="tonal" rounded size="40">
              <VIcon :icon="item.icon" />
            </VAvatar>
            <div class="d-flex flex-column">
              <h5 class="text-h5">
                {{ item.stats }}
              </h5>
              <div class="text-sm">
                {{ item.title }}
              </div>
            </div>
          </div>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>
