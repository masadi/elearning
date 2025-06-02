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
const isDialogVisible = ref(false)
const idLatihan = ref(null)
const showAbsen = sesi_latihan_id => {
  isDialogVisible.value = true
  idLatihan.value = sesi_latihan_id
}
const absen = (val) => {
  const firstMatchingObject = data.value.sesi.find(item => item.sesi_latihan_id === val && item.hadir && parseInt(item.hadir.hadir) === 1);
  return (firstMatchingObject) ? 'success' : 'secondary'
}
const radioGroup = ref()
const submitAbsen = async() => {
  await $api('/pelatihan/absen', {
    method: 'POST',
    body: {
      sesi_latihan_id: idLatihan.value,
      hadir: radioGroup.value
    },
    onResponse({ request, response, options }) {
      fetchData()
      idLatihan.value = null
      isDialogVisible.value = false
      radioGroup.value = false
    }
  })
}
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
        </VWindowItem>
        <VWindowItem v-for="sesi in data.sesi" :value="`item-${sesi.urut}`">
          <h4 class="text-h4 mb-2">{{ sesi.judul }}</h4>
          <span class="text-rata" v-html="sesi.deskripsi"></span>
          <VAlert color="info" icon="tabler-pencil-check" class="mb-4">
            <template #text>KEHADIRAN</template>
          </VAlert>
          <span class="text-rata">
            <v-row justify="space-between" class="mb-3">
              <v-col cols="4">
                <a href="javascript:void(0)" @click="showAbsen(sesi.sesi_latihan_id)"><VIcon size="25" icon="tabler-user-scan" /> Kehadiran Sesi {{ sesi.urut }}</a>
              </v-col>
              <v-col cols="4" class="text-right pe-8">
                <VIcon :color="absen(sesi.sesi_latihan_id)" size="25" icon="tabler-checkbox" />
              </v-col>
            </v-row>
            <VAlert density="comfortable" color="secondary" variant="tonal" class="mb-4">
              Untuk konfirmasi kehadiran Anda dalam kelas Tuton, silakan klik Kehadiran Sesi ke-{{ sesi.urut }}
            </VAlert>
          </span>
          <template v-if="sesi.materi.length">
            <VAlert color="info" icon="tabler-book">MATERI INISIASI</VAlert>
            <template v-for="materi in sesi.materi">
              <h4 class="text-h4 mb-2">{{ materi.judul }}</h4>
              <span class="text-rata" v-html="materi.deskripsi"></span>
              <template v-if="materi.dokumen.length">
                <VAlert color="info" icon="tabler-file-info">Dokumen Materi ({{ materi.judul }})</VAlert>
                <ListDokumen :dokumen="materi.dokumen" />
              </template>
            </template>
          </template>
        </VWindowItem>
      </VWindow>
    </VCardText>
    <VDialog v-model="isDialogVisible" persistent class="v-dialog-sm">
      <VCard title="Proses Kehadiran">
        <VCardText>
          <p>Untuk konfirmasi kehadiran Anda dalam kelas Tuton, silakan ceklist "Hadir" kemudian klik tombol Submit!</p>
          <VRadioGroup v-model="radioGroup">
            <VRadio value="1" label="Hadir" />
            <VRadio value="0" label="-----------" />
          </VRadioGroup>
        </VCardText>
        <VCardText class="d-flex justify-start gap-3 flex-wrap">
          <VBtn @click="submitAbsen">
            Submit
          </VBtn>
        </VCardText>
      </VCard>
    </VDialog>
  </VCard>
</template>
<style lang="scss">
.text-rata {text-align: justify; line-height: 1.6rem;}
</style>
