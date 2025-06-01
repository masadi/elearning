<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'pelatihan-read',
    navActiveLink: 'pelatihan',
  },
})
const route = useRoute()
const router = useRouter()
const {
  data: detilData,
  execute: fetchData,
} = await useApi(createUrl('/pelatihan', {
  query: {
    id: route.params.id,
  },
}))
const data = computed(() => detilData.value)
const currentTab = ref('item-0')
</script>

<template>
  <VCard :title="`Pelatihan ${data.judul}`">
    <VTabs v-model="currentTab">
      <VTab>Pendahuluan</VTab>
      <VTab v-for="sesi in data.sesi">{{ sesi.judul }}</VTab>
    </VTabs>
    <VCardText>
      <VWindow v-model="currentTab">
        <VWindowItem value="item-0">
          <span class="text-justify" v-html="data.deskripsi"></span>
        </VWindowItem>
        <VWindowItem v-for="sesi in data.sesi" :value="`item-${sesi.urut}`">
          <span class="text-justify" v-html="sesi.deskripsi"></span>
        </VWindowItem>
      </VWindow>
    </VCardText>
  </VCard>
</template>
