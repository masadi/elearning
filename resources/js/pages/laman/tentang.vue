<script setup>
import Kontak from '@/views/laman/Tentang.vue';
definePage({
  meta: {
    action: 'read',
    subject: 'laman-tentang-read',
  },
})
const {
  data: getData,
  execute: fetchData,
} = await useApi(createUrl('/table', {
  query: {
    data: 'tentang',
  },
}))
const item = computed(() => getData.value)
const isFormValid = ref(false)
const refForm = ref()
const form = ref({
  id: '',
  content: null,
})
form.value.content = item.value?.content
const isAlertVisible = ref(false)
const onSubmit = async () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      await $api('/admin/laman/store', {
        method: 'POST',
        body: {
          id: form.value.id,
          type: 'tentang',
          content: form.value.content,
        },
        onResponse({ request, response, options }) {
          let getData = response._data
          console.log(getData);
          fetchData()
          notif.value = getData
          isAlertVisible.value = true
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
</script>

<template>
  <section>
    <template v-if="$can('create', 'laman-tentang-create')">
      <VCard>
        <VCardText>
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <VCol cols="12">Deskripsi Laman Tentang</VCol>
              <VCol cols="12">
                <TiptapEditor v-model="form.content" class="border rounded basic-editor" />
              </VCol>
              <VCol cols="12" class="d-flex gap-4">
                <VBtn type="submit">
                  Simpan
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
      <ShowAlert :color="notif.color" :icon="notif.icon" :title="notif.title" :text="notif.text"
        :disable-time-out="false" v-model:isSnackbarVisible="isAlertVisible" v-if="notif.color"></ShowAlert>
    </template>
    <Kontak v-else />
  </section>
</template>
