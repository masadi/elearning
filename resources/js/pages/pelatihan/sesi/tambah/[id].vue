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
    data: 'sesi',
    pelatihan_id: route.params.id,
  },
}))
const pelatihan = computed(() => getData.value)
const notif = ref({
  icon: null,
  title: null,
  text: null,
  color: null,
})
const isAlertVisible = ref(false)
const isSnackbarClicked = ref(false)
const inputData = ref({
  pelatihan_id: pelatihan.value.pelatihan_id,
  judul: `Sesi ${(pelatihan.value.sesi_count + 1)}`,
  urut: (pelatihan.value.sesi_count + 1),
  deskripsi: null,
  bobot_hadir: null,
  bobot_materi: null,
  bobot_tugas: null,
  bobot_tes: null,
})
const isFormValid = ref(false)
const refForm = ref()
const isBusy = ref(false)
const onSubmit = async() => {
  refForm.value?.validate().then(async({ valid }) => {
    if (valid) {
      isBusy.value = true
      const postData = new FormData();
      postData.append('data', 'sesi');
      for (const [key, value] of Object.entries(inputData.value)) {
        postData.append(key, (value) ? value : '');
      }
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
    router.push({ name: 'pelatihan-sesi-id', params: {id: route.params.id} })
  }
})
</script>

<template>
  <VCard :title="`Tambah Sesi Pelatihan ${pelatihan.judul}`">
    <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
      <VCardText class="pb-12">
        <VRow>
          <VCol cols="12">
            <AppTextField v-model="inputData.bobot_hadir" :rules="[requiredValidator]">
              <template #label>Bobot Kehadiran</template>
            </AppTextField>
          </VCol>
          <VCol cols="12">
            <AppTextField v-model="inputData.bobot_materi" :rules="[requiredValidator]">
              <template #label>Bobot Materi</template>
            </AppTextField>
          </VCol>
          <VCol cols="12">
            <AppTextField v-model="inputData.bobot_tugas" :rules="[requiredValidator]">
              <template #label>Bobot Tugas</template>
            </AppTextField>
          </VCol>
          <VCol cols="12">
            <AppTextField v-model="inputData.bobot_tes" :rules="[requiredValidator]">
              <template #label>Bobot Tes Formatif</template>
            </AppTextField>
          </VCol>
          <VCol cols="12">
            <AppTextField v-model="inputData.judul" :rules="[requiredValidator]">
              <template #label>Judul Sesi</template>
            </AppTextField>
          </VCol>
          <VCol cols="12">
            <label for="">Deskripsi Sesi</label>
            <QuillEditor theme="snow" toolbar="full" v-model:content="inputData.deskripsi" contentType="html" :rules="[requiredValidator]" />
          </VCol>
        </VRow>
      </VCardText>
      <VCardText class="mt-16">
        <VRow>
          <VCol cols="12">
            <VBtn type="submit" :loading="isBusy" :disabled="isBusy">Submit</VBtn>
          </VCol>
        </VRow>
      </VCardText>
    </VForm>
    <ShowAlert :color="notif.color" :icon="notif.icon" :title="notif.title" :text="notif.text" :disable-time-out="false" v-model:isSnackbarVisible="isAlertVisible" v-model:isSnackbarClicked="isSnackbarClicked"></ShowAlert>
  </VCard>
</template>
