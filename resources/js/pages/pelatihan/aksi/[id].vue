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
const isUnggahTugas = ref(false)
const idSesi = ref(null)
const showAbsen = sesi_latihan_id => {
  isDialogVisible.value = true
  idSesi.value = sesi_latihan_id
}
const judul_sesi = ref()
const unggahTugas = sesi_latihan_id => {
  isUnggahTugas.value = true
  idSesi.value = sesi_latihan_id
  const findSesi = data.value.sesi.find(item => item.sesi_latihan_id === sesi_latihan_id);
  judul_sesi.value = findSesi?.judul;
  
}
const absen = (val) => {
  const findSesi = data.value.sesi.find(item => item.sesi_latihan_id === val && item.hadir && parseInt(item.hadir.hadir) === 1);
  return (findSesi) ? 'success' : 'secondary'
}
const cekTugas = (val) => {
  const findSesi = data.value.sesi.find(item => item.sesi_latihan_id === val && item.dokumen_tugas);
  return (findSesi) ? true : false
}
const radioGroup = ref()
const submitAbsen = async() => {
  await $api('/pelatihan/absen', {
    method: 'POST',
    body: {
      sesi_latihan_id: idSesi.value,
      hadir: radioGroup.value
    },
    onResponse({ request, response, options }) {
      fetchData()
      idSesi.value = null
      isDialogVisible.value = false
      radioGroup.value = false
    }
  })
}
const fileTugas = ref()
const submitTugas = async() => {
  const postData = new FormData();
  postData.append('sesi_latihan_id', idSesi.value);
  postData.append('file_tugas', fileTugas.value);
  await $api('/pelatihan/unggah', {
    method: 'POST',
    body: postData,
    onResponse({ request, response, options }) {
      fetchData()
      isUnggahTugas.value = false
      idSesi.value = null
      judul_sesi.value = null
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
          <template v-if="sesi.tugas.length">
            <VAlert color="warning" icon="tabler-pencil">
              <v-row justify="space-between">
                  <v-col cols="4">
                    TUGAS
                  </v-col>
                  <v-col cols="4" class="text-right pe-8">
                    <VBtn :disabled="cekTugas(sesi.sesi_latihan_id)" size="small" @click="unggahTugas(sesi.sesi_latihan_id)">Unggah Tugas <VIcon end icon="tabler-cloud-upload" /></VBtn>
                  </v-col>
                </v-row>
            </VAlert>
            <template v-for="tugas in sesi.tugas">
              <h4 class="text-h4 mb-2">{{ tugas.judul }}</h4>
              <span class="text-rata" v-html="tugas.deskripsi"></span>
              <template v-if="tugas.dokumen.length">
                <VAlert color="warning" icon="tabler-file-info">Dokumen Tugas ({{ tugas.judul }})</VAlert>
                <ListDokumen :dokumen="tugas.dokumen" />
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
    <VDialog v-model="isUnggahTugas" persistent class="v-dialog-sm">
      <VCard :title="`Unggah Tugas ${judul_sesi}`">
        <VCardText>
          <p>Unggah tugas dibatasi hanya 1 (satu) kali! Pastikan berkas yang akan di unggah telah diperiksa.</p>
          <VFileInput v-model="fileTugas" label="Unggah Berkas" />
        </VCardText>
        <VCardText class="d-flex justify-start gap-3 flex-wrap">
          <VBtn @click="submitTugas">
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
