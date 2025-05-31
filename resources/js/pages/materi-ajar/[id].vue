<script setup>
definePage({
  meta: {
    action: 'create',
    subject: 'materi-ajar-create',
    navActiveLink: 'materi-ajar',
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
    data: 'materi-ajar',
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
const getInputData = computed(() => detilData.value.materi)
const rombel = computed(() => detilData.value.rombel)
const pembelajaran = computed(() => detilData.value.pembelajaran)
const inputData = ref({
  tingkat: getInputData.value.pembelajaran.rombongan_belajar.tingkat,
  rombongan_belajar_id: getInputData.value.pembelajaran.rombongan_belajar_id,
  pembelajaran_id: getInputData.value.pembelajaran_id,
  judul: getInputData.value.judul,
  deskripsi: getInputData.value.deskripsi,
})
const tingkat = [10, 11, 12, 13];
const isFormValid = ref(false)
const refForm = ref()
const onSubmit = async() => {
  refForm.value?.validate().then(async({ valid }) => {
    if (valid) {
      const postData = new FormData();
      postData.append('data', 'update-materi-ajar');
      postData.append('materi_ajar_id', route.params.id);
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
        }
      })
    }
  })
}
const changeTingkat = async(val) => {
  inputData.value.rombongan_belajar_id = null
  getData({
    data: 'rombel',
    tingkat: val,
  })
}
const changeRombel = async(val) => {
  inputData.value.pembelajaran_id = null
  getData({
    data: 'pembelajaran',
    rombongan_belajar_id: val,
  })
}
const getData = async(query) => {
  await $api(`/referensi`, { 
    query: query,
    onResponse({ request, response, options }) {
      let getData = response._data
      if(query.data == 'rombel'){
        rombel.value = getData
      }
      if(query.data == 'pembelajaran'){
        pembelajaran.value = getData
      }
    }
  })
}
watch(isSnackbarClicked, () => {
  if(isSnackbarClicked.value){
    router.push({ name: 'materi-ajar' })
  }
})
</script>

<template>
  <VCard title="Edit Data Materi Ajar">
    <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
      <VCardText>
        <VRow>
          <VCol md="4">
            <VAutocomplete v-model="inputData.tingkat" label="Tingkat" variant="outlined" :items="tingkat" placeholder="== Pilih Tingkat ==" :rules="[requiredValidator]" @update:modelValue="changeTingkat" />
          </VCol>
          <VCol md="4">
            <VAutocomplete v-model="inputData.rombongan_belajar_id" label="Rombel" variant="outlined" :items="rombel" item-title="nama" item-value="rombongan_belajar_id" placeholder="== Pilih Rombel ==" :rules="[requiredValidator]" @update:modelValue="changeRombel" />
          </VCol>
          <VCol md="4">
            <VAutocomplete v-model="inputData.pembelajaran_id" label="Mata Pelajaran" variant="outlined" :items="pembelajaran" item-title="nama_mata_pelajaran" item-value="pembelajaran_id" placeholder="== Pilih Mata Pelajaran ==" :rules="[requiredValidator]" />
          </VCol>
          <VCol cols="12">
            <AppTextField v-model="inputData.judul" :rules="[requiredValidator]">
              <template #label>Judul Materi</template>
            </AppTextField>
          </VCol>
          <VCol cols="12">
            <QuillEditor theme="snow" toolbar="full" v-model:content="inputData.deskripsi" contentType="html" :rules="[requiredValidator]" />
          </VCol>
        </VRow>
      </VCardText>
      <VCardText class="mt-16">
        <VRow>
          <VCol cols="12" class="text-right">
            <VBtn type="submit">Submit</VBtn>
          </VCol>
        </VRow>
      </VCardText>
    </VForm>
    <ShowAlert :color="notif.color" :icon="notif.icon" :title="notif.title" :text="notif.text" :disable-time-out="false" v-model:isSnackbarVisible="isAlertVisible" v-model:isSnackbarClicked="isSnackbarClicked"></ShowAlert>
  </VCard>
</template>
