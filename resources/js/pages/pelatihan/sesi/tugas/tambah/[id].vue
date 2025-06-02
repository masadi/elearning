<script setup>
definePage({
  meta: {
    action: 'create',
    subject: 'pelatihan-create',
    navActiveLink: 'pelatihan',
  },
})
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
const router = useRouter()
const route = useRoute()
const {
  data: getData,
  execute: fetchData,
} = await useApi(createUrl('/referensi/show', {
  query: {
    data: 'materi-sesi',
    sesi_latihan_id: route.params.id,
  },
}))
const sesi_latihan = computed(() => getData.value)
const notif = ref({
  icon: null,
  title: null,
  text: null,
  color: null,
})
const isAlertVisible = ref(false)
const isSnackbarClicked = ref(false)
const inputData = ref({
  sesi_latihan_id: sesi_latihan.value.sesi_latihan_id,
  judul: null,
  deskripsi: null,
})
const dokumen = ref([{
  id: 1,
  berkas: null,
  nama: null,
}])
const nextDokumenId = ref(2)
const isFormValid = ref(false)
const refForm = ref()
const isBusy = ref(false)
const onSubmit = async() => {
  refForm.value?.validate().then(async({ valid }) => {
    if (valid) {
      isBusy.value = true
      const postData = new FormData();
      postData.append('data', 'tugas-sesi');
      for (const [key, value] of Object.entries(inputData.value)) {
        postData.append(key, (value) ? value : '');
      }
      dokumen.value.forEach(e => {
        postData.append('nama[]', (e.nama) ? e.nama : '');
        postData.append('berkas[]', (e.berkas) ? e.berkas : '');
      });
      await $api('/referensi/store', {
        method: 'POST',
        body: postData,
        onResponse({ request, response, options }) {
          let getData = response._data
          notif.value = getData
          isAlertVisible.value = true
          isBusy.value = false
        }
      })
    }
  })
}
watch(isSnackbarClicked, () => {
  if(isSnackbarClicked.value){
    router.push({ name: 'pelatihan-sesi-tugas-id', params: {id: sesi_latihan.value.sesi_latihan_id} })
  }
})
const addForm = () => {
  dokumen.value.push({
    id: nextDokumenId.value += nextDokumenId.value,
  })
}
const delForm = (index) => {
  dokumen.value.splice(index, 1)
}
</script>

<template>
  <VCard :title="`Tambah Tugas Sesi ${sesi_latihan.judul}`">
    <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
      <VCardText class="pb-12">
        <VRow>
          <VCol cols="12">
            <AppTextField v-model="inputData.judul" :rules="[requiredValidator]">
              <template #label>Judul Tugas</template>
            </AppTextField>
          </VCol>
          <VCol cols="12">
            <label for="">Deskripsi Tugas</label>
            <QuillEditor theme="snow" toolbar="full" v-model:content="inputData.deskripsi" contentType="html" :rules="[requiredValidator]" />
          </VCol>
        </VRow>
      </VCardText>
      <VCardText class="mt-16">
        <VRow v-for="(dok, index) in dokumen" :id="dok.id" :key="dok.id">
          <VCol md="6">
            <AppTextField id="nama" v-model="dok.nama">
              <template #label>Nama Dokumen</template>
            </AppTextField>
          </VCol>
          <VCol md="4">
            <VFileInput v-model="dok.berkas" label="Berkas Dokumen" />
          </VCol>
          <VCol md="2">
            <VBtn block color="warning" @click="delForm(index)"><VIcon icon="tabler-x" /> Hapus</VBtn>
          </VCol>
        </VRow>
        <VRow justify="space-between">
          <VCol cols="4">
            <VBtn type="submit" :loading="isBusy" :disabled="isBusy">Submit</VBtn>
          </VCol>
          <VCol cols="4" class="text-right">
            <VBtn color="info" @click="addForm">Tambah Form Dokumen <VIcon end icon="tabler-copy" /></VBtn>
          </VCol>
        </VRow>
      </VCardText>
    </VForm>
    <ShowAlert :color="notif.color" :icon="notif.icon" :title="notif.title" :text="notif.text" :disable-time-out="false" v-model:isSnackbarVisible="isAlertVisible" v-model:isSnackbarClicked="isSnackbarClicked"></ShowAlert>
  </VCard>
</template>
