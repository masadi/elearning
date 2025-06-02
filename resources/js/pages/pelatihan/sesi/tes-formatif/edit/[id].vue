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
    data: 'tes-formatif',
    id: route.params.id,
  },
}))
const tesFormatif = computed(() => getData.value)
const notif = ref({
  icon: null,
  title: null,
  text: null,
  color: null,
})
const isAlertVisible = ref(false)
const isSnackbarClicked = ref(false)
const inputData = ref({
  tes_id: tesFormatif.value.tes_id,
  deskripsi: tesFormatif.value.deskripsi,
  jawaban: tesFormatif.value.jawaban,
})
const isFormValid = ref(false)
const refForm = ref()
const isBusy = ref(false)
const onSubmit = async() => {
  refForm.value?.validate().then(async({ valid }) => {
    if (valid) {
      isBusy.value = true
      await $api('/referensi/store', {
        method: 'POST',
        body: {
          data: 'tes-sesi',
          tes_id: tesFormatif.value.tes_id,
          input: JSON.stringify(inputData.value)
        },
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
    router.push({ name: 'pelatihan-sesi-tes-formatif-id', params: {id: tesFormatif.value.sesi_latihan_id} })
  }
})
const kunci = [
  {
    key: '0',
    val: 'Salah',
  },
  {
    key: '1',
    val: 'Benar',
  }
];
</script>
<template>
  <VCard title="Edit Tes Formatif">
    <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
      <VCardText class="pb-12">
        <VRow>
          <VCol cols="12" class="mb-6">
            <label for="">Soal Tes</label>
            <QuillEditor theme="snow" toolbar="full" v-model:content="inputData.deskripsi" contentType="html" :rules="[requiredValidator]" />
          </VCol>
        </VRow>
        <template v-for="jawaban in inputData.jawaban">
          <VCardText class="d-flex justify-space-between align-center flex-wrap gap-4 px-0 mt-14">
            <div class="d-flex gap-4 align-center flex-wrap">Jawaban {{ jawaban.opsi }}</div>
            <div class="d-flex align-center flex-wrap gap-4">
              <AppSelect :items="kunci" v-model="jawaban.benar" placeholder="Jawaban Benar" item-title="val" item-value="key" style="inline-size: 12rem;" :rules="[requiredValidator]" />
            </div>
          </VCardText>    
          <VRow>
            <VCol cols="12">
              <QuillEditor theme="snow" toolbar="full" v-model:content="jawaban.deskripsi" contentType="html" :rules="[requiredValidator]" />
            </VCol>
          </VRow>
        </template>
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
