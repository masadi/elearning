<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'referensi-sekolah-read',
  },
})
import ShowAlert from '@/components/ShowAlert.vue';
const isFormValid = ref(false)
const refForm = ref()
const notif = ref({
  icon: null,
  title: null,
  text: null,
  color: null,
})
const isAlertVisible = ref(false)
const onSubmit = () => {
  refForm.value?.validate().then(async({ valid }) => {
    if (valid) {
      const postData = new FormData();
      postData.append('data', 'sekolah');
      lists.value.forEach(e => {
        postData.append(e.id, (e.value) ? e.value : '');
      });
      await $api('/referensi/store', {
        method: 'POST',
        body: postData,
        onResponse({ request, response, options }) {
          let getData = response._data
          notif.value = getData
          isAlertVisible.value = true
          fetchData()
        }
      })
    }
  })
}
//computed(() => getData.value?.sekolah_id)
const {
  data: getData,
  execute: fetchData,
} = await useApi(createUrl('/referensi', {
  query: {
    data: 'sekolah'
  },
}))
const lists = ref([
  {
    id: 'sekolah_id',
    title: 'ID Sekolah',
    show: false,
    value: getData.value?.sekolah_id,
  },
  {
    id: 'nama',
    title: 'Nama Sekolah',
    show: true,
    value: getData.value?.nama,
    rules: [requiredValidator],
  },
  {
    id: 'npsn',
    title: 'NPSN',
    show: true,
    value: getData.value?.npsn,
    rules: [requiredValidator],
  },
  {
    id: 'alamat',
    title: 'Alamat',
    show: true,
    value: getData.value?.alamat,
    rules: [requiredValidator],
  },
  {
    id: 'desa_kelurahan',
    title: 'Desa/Kelurahan',
    show: true,
    value: getData.value?.desa_kelurahan,
    rules: [requiredValidator],
  },
  {
    id: 'kecamatan',
    title: 'Kecamatan',
    show: true,
    value: getData.value?.kecamatan,
    rules: [requiredValidator],
  },
  {
    id: 'kabupaten',
    title: 'Kabupaten/Kota',
    show: true,
    value: getData.value?.kabupaten,
    rules: [requiredValidator],
  },
  {
    id: 'provinsi',
    title: 'Provinsi',
    show: true,
    value: getData.value?.provinsi,
    rules: [requiredValidator],
  },
  {
    id: 'kodepos',
    title: 'Kodepos',
    show: true,
    value: getData.value?.kodepos,
  },
  {
    id: 'telpon',
    title: 'Nomor Telepon',
    show: true,
    value: getData.value?.telpon,
  },
  {
    id: 'fax',
    title: 'Nomor Fax',
    show: true,
    value: getData.value?.fax,
  },
  {
    id: 'email',
    title: 'Email',
    show: true,
    value: getData.value?.email,
    rules: [requiredValidator, emailValidator],
  },
  {
    id: 'website',
    title: 'Website',
    show: true,
    value: getData.value?.website,
  },
  {
    id: 'logo',
    title: 'Logo Sekolah',
    show: true,
    value: null,
    file: true,
  },
])
</script>

<template>
  <VCard title="Data Sekolah">
    <VCardText>
      <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
        <VRow>
          <template v-for="list in lists">
            <VCol cols="12" v-if="list.show">
              <VRow>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" :for="`app-text-field-${list.id}`">{{ list.title }}</label>
                </VCol>
                <VCol cols="12" md="9">
                  <VFileInput accept="image/*" v-model="list.value" :id="list.id" :label="list.title" v-if="list.file"/>
                  <AppTextField :id="list.id" v-model="list.value" :rules="list.rules" v-else>
                    <template #label>{{ list.title }} <!--VIcon icon="tabler-file-search" /--></template>
                  </AppTextField>
                </VCol>
              </VRow>
            </VCol>
          </template>
          <VCol offset-md="3" cols="12" md="9" class="d-flex gap-4">
            <VBtn type="submit">Submit</VBtn>
          </VCol>
        </VRow>
      </VForm>
    </VCardText>
    <ShowAlert :color="notif.color" :icon="notif.icon" :title="notif.title" :text="notif.text" :disable-time-out="false" v-model:isSnackbarVisible="isAlertVisible"></ShowAlert>
  </VCard>
</template>
