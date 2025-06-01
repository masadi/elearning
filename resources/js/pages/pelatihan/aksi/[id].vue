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
      <VTab v-for="sesi in data.sesi">{{ `Sesi ${sesi.urut}` }}</VTab>
    </VTabs>
    <VCardText>
      <VWindow v-model="currentTab">
        <VWindowItem value="item-0">
          <span class="text-rata" v-html="data.deskripsi"></span>
          <template v-if="data.dokumen.length">
            <VAlert color="primary" icon="tabler-file-info">DOKUMEN</VAlert>
            <ListDokumen :dokumen="data.dokumen" />
          </template>
        </VWindowItem>
        <VWindowItem v-for="sesi in data.sesi" :value="`item-${sesi.urut}`">
          <h4 class="text-h4 mb-2">{{ sesi.judul }}</h4>
          <span class="text-rata" v-html="sesi.deskripsi"></span>
          <template v-if="sesi.dokumen.length">
            <VAlert color="primary" icon="tabler-file-info">DOKUMEN SESI {{ sesi.urut }}</VAlert>
            <ListDokumen :dokumen="sesi.dokumen" />
          </template>
          <template v-if="sesi.materi.length">
            <VAlert color="primary" icon="tabler-book">MATERI INISIASI</VAlert>
            <template v-for="materi in sesi.materi">
              <h4 class="text-h4 mb-2">{{ materi.judul }}</h4>
              <span class="text-rata" v-html="materi.deskripsi"></span>
              <template v-if="materi.dokumen.length">
                <VAlert color="warning" icon="tabler-file-info">Dokumen Materi ({{ materi.judul }})</VAlert>
                <ListDokumen :dokumen="materi.dokumen" />
              </template>
            </template>
          </template>
        </VWindowItem>
      </VWindow>
    </VCardText>
  </VCard>
</template>
<style lang="scss">
.text-rata {text-align: justify; line-height: 1.6rem;}
</style>
