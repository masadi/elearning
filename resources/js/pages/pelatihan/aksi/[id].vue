<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'pelatihan-read',
    navActiveLink: 'pelatihan',
  },
})
const route = useRoute()
const isAlertVisible = ref(false)
const isConfirmDialogVisible = ref(false)
const notif = ref({
  icon: null,
  title: null,
  text: null,
  color: null,
})
const {
  data: detilData,
  execute: fetchData,
} = await useApi(createUrl('/pelatihan', {
  query: {
    id: route.params.id,
  },
}))
const data = computed(() => detilData.value)
const jmlSoal = ref(0)
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
const sesiLatihanId = ref()
const confirmTes = async(val) => {
  if(val){
    await $api('/pelatihan/tes-selesai', {
      method: 'POST',
      body: {
        sesi_latihan_id: sesiLatihanId.value,
      },
      onResponse({ request, response, options }) {
        fetchData()
        sesiLatihanId.value = null
      }
    })
  }
}
const isChecked = ref()
const submitTes = async(sesi_latihan_id) => {
  isConfirmDialogVisible.value = true
  sesiLatihanId.value = sesi_latihan_id
}
const soalAktif = ref(0)
const countSoal = ref(0)
const prevSoal = (count, tes_id) => {
  countSoal.value = count
  soalAktif.value = soalAktif.value - 1
  getSoal(soalAktif.value, tes_id)
}
const nextSoal = (count, tes_id) => {
  countSoal.value = count
  soalAktif.value = soalAktif.value + 1
  getSoal(soalAktif.value, tes_id)
}
const prevColor = ref('secondary')
const nextColor = ref('primary')
const prevDisabled = ref(true)
const nextDisabled = ref(false)
watch(soalAktif, () => {
  if(soalAktif.value){
    if(soalAktif.value == (countSoal.value - 1)){
      nextColor.value = 'secondary'
      nextDisabled.value = true
    } else {
      nextColor.value = 'primary'
      nextDisabled.value = false
    }
    prevDisabled.value = false
    prevColor.value = 'primary'
  } else {
    nextColor.value = 'primary'
    nextDisabled.value = false
    prevDisabled.value = true
    prevColor.value = 'secondary'
  }
})
const soal = ref()
const indexTab = ref()
const changeTab = async(val) => {
  let index = val - 1
  indexTab.value = index
  if(val > 0){
    getSoal(index)
  }
}
const isBusy = ref()
const getSoal = async(index, tes_id) => {
  isBusy.value = true
  await $api('/pelatihan/get-soal', {
    method: 'POST',
    body: {
      sesi_latihan_id: data.value.sesi[indexTab.value]?.sesi_latihan_id,
      tes_id: data.value.sesi[indexTab.value]?.tes[index]?.tes_id,
      tes_id_jawaban: tes_id,
      jawaban_id: isChecked.value,
    },
    onResponse({ request, response, options }) {
      let getData = response._data
      soal.value = getData.soal
      isBusy.value = false
      isChecked.value = getData.soal.user_jawaban?.jawaban_id
      jmlSoal.value = getData.jml_soal
      if(jmlSoal.value === 1){
        nextColor.value = 'secondary'
        nextDisabled.value = true
      }
    }
  })
}
</script>

<template>
  <VCard :title="`Pelatihan ${data.judul}`">
    <VTabs v-model="currentTab" @update:modelValue="changeTab">
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
                    <VBtn :disabled="cekTugas(sesi.sesi_latihan_id)" :color="(cekTugas(sesi.sesi_latihan_id) ? 'secondary' : 'success')" size="small" @click="unggahTugas(sesi.sesi_latihan_id)">Unggah Tugas <VIcon end icon="tabler-cloud-upload" /></VBtn>
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
          <template v-if="sesi.tes.length">
            <VExpansionPanels class="no-icon-rotate">
              <VExpansionPanel>
                <VExpansionPanelTitle class="bg-primary" disable-icon-rotate>TES FORMATIF
                  <template #actions>
                    <VIcon size="26" icon="tabler-file-report" color="white" />
                  </template>
                </VExpansionPanelTitle>
                <VExpansionPanelText class="mt-4">
                  <template v-if="isBusy">
                    <VProgressCircular :size="60" color="primary" class="text-center" indeterminate />
                  </template>
                  <template v-else>
                    <span class="text-soal" v-html="soal.deskripsi"></span>
                    <VRadioGroup v-model="isChecked">
                      <VRadio v-for="jawaban in soal.jawaban" :key="jawaban.jawaban_id" :value="jawaban.jawaban_id" :disabled="parseInt(sesi.user_tes?.status)">
                        <template #label>
                          <span v-html="jawaban.deskripsi"></span>
                        </template>
                      </VRadio>
                    </VRadioGroup>
                    <VRow justify="space-between" class="mb-3">
                      <VCol cols="2">
                        <VBtn @click="prevSoal(sesi.tes.length, soal.tes_id)" :color="prevColor" :disabled="prevDisabled || parseInt(sesi.user_tes?.status)">
                          <VIcon icon="tabler-chevrons-left" /> Prev
                        </VBtn>
                      </VCol>
                      <VCol cols="2" class="text-center" v-if="nextDisabled">
                        <VBtn @click="submitTes(sesi.sesi_latihan_id)" :disabled="parseInt(sesi.user_tes?.status)">Simpan</VBtn>
                      </VCol>
                      <VCol cols="2" class="text-right">
                        <VBtn @click="nextSoal(sesi.tes.length, soal.tes_id)" :color="nextColor" :disabled="nextDisabled || parseInt(sesi.user_tes?.status)">
                          Next <VIcon icon="tabler-chevrons-right" />
                        </VBtn>
                      </VCol>
                    </VRow>
                  </template>
                </VExpansionPanelText>
              </VExpansionPanel>
            </VExpansionPanels>
          </template>
        </VWindowItem>
      </VWindow>
    </VCardText>
    <VDialog v-model="isDialogVisible" persistent class="v-dialog-sm">
      <DialogCloseBtn @click="isDialogVisible = !isDialogVisible" />
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
      <DialogCloseBtn @click="isUnggahTugas = !isUnggahTugas" />
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
    <ShowAlert :color="notif.color" :icon="notif.icon" :title="notif.title" :text="notif.text" :disable-time-out="false" v-model:isSnackbarVisible="isAlertVisible" v-if="notif.color"></ShowAlert>
    <ConfirmDialog
      v-model:isDialogVisible="isConfirmDialogVisible"
      confirmation-question="Apakah Anda yakin akan mengakhiri Tes Formatif ini?<br>Tes Formatif akan terkunci setelah Anda mengakhiri!"
      :show-notif="false"
      @confirm="confirmTes"
    />
  </VCard>
</template>
<style lang="scss">
.text-rata {text-align: justify; line-height: 1.6rem;}
.text-soal {text-align: justify; line-height: 1.6rem; color: black; font-size: 115%;}
.v-expansion-panel {border-radius: 0px !important;}
.v-expansion-panel-text__wrapper {padding: 0px !important;}
</style>
