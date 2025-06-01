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
const route = useRoute()
const router = useRouter()
const {
  data: detilData,
  execute: fetchData,
} = await useApi(createUrl('/referensi/show', {
  query: {
    data: 'pelatihan',
    id: route.params.id,
  },
}))
const notif = ref({
  icon: null,
  title: null,
  text: null,
  color: null,
})
const isAlertVisible = ref(false)
const isSnackbarClicked = ref(false)
const pelatihan = computed(() => detilData.value)
const inputData = ref({
  judul: pelatihan.value.judul,
  deskripsi: pelatihan.value.deskripsi,
})
const dokumen = ref([{
  id: 1,
  berkas: null,
  nama: null,
}])
const nextDokumenId = ref(2)
const dokId = ref()
const isConfirmDialogVisible = ref(false)
const isFormValid = ref(false)
const refForm = ref()
const isBusy = ref(false)
const onSubmit = async() => {
  refForm.value?.validate().then(async({ valid }) => {
    if (valid) {
      isBusy.value = true
      const postData = new FormData();
      postData.append('data', 'pelatihan');
      postData.append('pelatihan_id', route.params.id);
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
    if(dokId.value){
      dokId.value = null
    } else {
      router.push({ name: 'pelatihan' })
    }
    isSnackbarClicked.value = false
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
const delDok = async(id) => {
  dokId.value = id
  isConfirmDialogVisible.value = true
}
const confirmDelete = async (val) => {
  if(val){
    await $api(`/referensi/destroy/dokumen/${ dokId.value }`, { 
      method: 'DELETE',
      onResponse({ request, response, options }) {
        let getData = response._data
        notif.value = getData
        isAlertVisible.value = true
        fetchData()
      }
    })
  }
}

</script>

<template>
  <VCard title="Edit Data Pelatihan">
    <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
      <VCardText class="pb-12">
        <VRow>
          <VCol cols="12">
            <AppTextField v-model="inputData.judul" :rules="[requiredValidator]">
              <template #label>Judul Pelatihan</template>
            </AppTextField>
          </VCol>
          <VCol cols="12">
            <label for="">Deskripsi Pelatihan</label>
            <QuillEditor theme="snow" toolbar="full" v-model:content="inputData.deskripsi" contentType="html" :rules="[requiredValidator]" />
          </VCol>
        </VRow>
      </VCardText>
      <VCardText class="mt-16">
        <VRow v-for="(dok, index) in pelatihan.dokumen" :id="dok.id" :key="dok.id">
          <VCol md="6">
            {{ dok.nama }}
          </VCol>
          <VCol md="4">
            <a :href="`/storage/berkas/${dok.path}`" target="_blank">
              <VTooltip activator="parent" location="top">Lihat Dokumen</VTooltip>
              <VIcon :icon="`tabler-file-type-${dok.extension.replace('xlsx', 'xls')}`" /> Lihat Dokumen
            </a>
          </VCol>
          <VCol md="2">
            <VBtn block color="error" @click="delDok(dok.dokumen_id)"><VIcon icon="tabler-x" /> Hapus</VBtn>
          </VCol>
        </VRow>
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
    <ConfirmDialog
        v-model:isDialogVisible="isConfirmDialogVisible"
        confirmation-question="Apakah Anda yakin ingin menghapus data ini?"
        :show-notif="false"
        @confirm="confirmDelete"
      />
  </VCard>
</template>
