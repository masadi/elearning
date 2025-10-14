<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'konten-visi-misi-read',
  },
})
const currentTab = ref('visi')
const {
  data: getData,
  execute: fetchData,
} = await useApi(createUrl('/table', {
  query: {
    data: 'visi',
  },
}))
const item = computed(() => getData.value)
const sekolah_id = computed(() => getData.value.sekolah_id)
const isFormValid = ref(false)
const refForm = ref()
const form = ref({
  id: null,
  visi_text: null,
  visi_image: null,
  misi_text: null,
  misi_image: null,
})
form.value.visi_text = item.value?.visi?.content
form.value.misi_text = item.value?.misi?.content
const onSubmit = async () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      const postData = new FormData();
      postData.append('id', form.value.id ?? '');
      postData.append('sekolah_id', sekolah_id.value)
      postData.append('data', currentTab.value ? 'misi' : 'visi');
      postData.append('visi_text', form.value.visi_text ?? '');
      postData.append('visi_image', form.value.visi_image ?? '');
      postData.append('misi_text', form.value.misi_text ?? '');
      postData.append('misi_image', form.value.misi_image ?? '');
      await $api('/admin/konten/store', {
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
const notif = ref({
  icon: null,
  title: null,
  text: null,
  color: null,
})
const isAlertVisible = ref(false)
</script>

<template>
  <section>
    <template v-if="$can('create', 'laman-tentang-create')">
      <VTabs v-model="currentTab" class="v-tabs-pill">
        <VTab>Visi</VTab>
        <VTab>Misi</VTab>
      </VTabs>
      <VCard class="mt-5">
        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
          <VCardText>
            <VWindow v-model="currentTab">
              <VWindowItem value="visi">
                <VRow no-gutters>
                  <VCol>
                    <VRow>
                      <VCol cols="12">Deskripsi Visi</VCol>
                      <VCol cols="12">
                        <TiptapEditor v-model="form.visi_text" class="border rounded basic-editor" />
                      </VCol>
                    </VRow>
                  </VCol>
                  <VCol class="text-center ms-4">
                    <p>Foto Visi</p>
                    <v-img class="align-end text-white" height="200" :src="`/storage/images/${item.visi?.image}`" cover
                      v-if="item.visi?.image"></v-img>
                    <VFileInput accept="image/*" v-model="form.visi_image" class="mt-4" />
                  </VCol>
                </VRow>
              </VWindowItem>
              <VWindowItem value="misi">
                <VRow no-gutters>
                  <VCol>
                    <VRow>
                      <VCol cols="12">Deskripsi Misi</VCol>
                      <VCol cols="12">
                        <TiptapEditor v-model="form.misi_text" class="border rounded basic-editor" />
                      </VCol>
                    </VRow>
                  </VCol>
                  <VCol class="text-center ms-4">
                    <p>Foto Misi</p>
                    <v-img class="align-end text-white" height="200" :src="`/storage/images/${item.misi?.image}`" cover
                      v-if="item.misi?.image"></v-img>
                    <VFileInput accept="image/*" v-model="form.misi_image" class="mt-4" />
                  </VCol>
                </VRow>
              </VWindowItem>
            </VWindow>
          </VCardText>
          <VCardText class="d-flex justify-end">
            <VBtn type="submit">
              Simpan
            </VBtn>
          </VCardText>
        </VForm>
      </VCard>
    </template>
    <p v-else>admin area</p>
    <ShowAlert :color="notif.color" :icon="notif.icon" :title="notif.title" :text="notif.text" :disable-time-out="false"
      v-model:isSnackbarVisible="isAlertVisible" v-if="notif.color"></ShowAlert>
  </section>
</template>
