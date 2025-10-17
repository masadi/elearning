<script setup>
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { VForm } from 'vuetify/components/VForm';
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  sekolah: {
    type: Array,
    required: true,
  },
  pembelajaran: {
    type: Array,
    required: true,
  },
  detilData: {
    type: Object,
    required: false,
    default: null,
  },
  sekolahId: {
    type: String,
    required: false,
    default: null,
  },
})
const isLoading = ref(false)
const emit = defineEmits([
  'update:isDialogVisible',
  'notif',
])
const isFormValid = ref(false)
const refForm = ref()
const form = ref({
  id: null,
  pembelajaran_id: null,
  deskripsi: null,
  jawaban: [
    { jawaban_id: null, opsi: 'A', deskripsi: null, benar: 0 },
    { jawaban_id: null, opsi: 'B', deskripsi: null, benar: 0 },
    { jawaban_id: null, opsi: 'C', deskripsi: null, benar: 0 },
    { jawaban_id: null, opsi: 'D', deskripsi: null, benar: 0 },
    //{opsi: 'E', deskripsi: null, benar: 0},
  ],
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
const onSubmit = async () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      isLoading.value = true
      await $api('/admin/tes/store', {
        method: 'POST',
        body: form.value,
        onResponse({ request, response, options }) {
          let getData = response._data
          onReset()
          emit('notif', getData)
        }
      })
    }
  })
}
const onReset = () => {
  emit('update:isDialogVisible', false)
  isLoading.value = false
  form.value = {
    id: null,
    pembelajaran_id: null,
    deskripsi: null,
    jawaban: [
      { jawaban_id: null, opsi: 'A', deskripsi: null, benar: 0 },
      { jawaban_id: null, opsi: 'B', deskripsi: null, benar: 0 },
      { jawaban_id: null, opsi: 'C', deskripsi: null, benar: 0 },
      { jawaban_id: null, opsi: 'D', deskripsi: null, benar: 0 },
      //{opsi: 'E', deskripsi: null, benar: 0},
    ],
  }
}
watch(props, async () => {
  if (props.isDialogVisible) {
    console.log(props.detilData);

    if (props.detilData) {
      let setJawaban = []
      if (props.detilData.jawaban.length > 0) {
        props.detilData.jawaban.forEach(element => {
          setJawaban.push({
            jawaban_id: element.jawaban_id,
            opsi: element.opsi,
            deskripsi: element.deskripsi,
            benar: element.benar,
          })
        });
      } else {
        setJawaban = [
          { jawaban_id: null, opsi: 'A', deskripsi: null, benar: 0 },
          { jawaban_id: null, opsi: 'B', deskripsi: null, benar: 0 },
          { jawaban_id: null, opsi: 'C', deskripsi: null, benar: 0 },
          { jawaban_id: null, opsi: 'D', deskripsi: null, benar: 0 },
          //{opsi: 'E', deskripsi: null, benar: 0},
        ]
      }
      form.value = {
        id: props.detilData.tes_id,
        pembelajaran_id: props.detilData.pembelajaran_id,
        deskripsi: props.detilData.deskripsi,
        jawaban: setJawaban,
      }
    } else {
      form.value = {
        id: null,
        pembelajaran_id: null,
        deskripsi: null,
        jawaban: [
          { jawaban_id: null, opsi: 'A', deskripsi: null, benar: 0 },
          { jawaban_id: null, opsi: 'B', deskripsi: null, benar: 0 },
          { jawaban_id: null, opsi: 'C', deskripsi: null, benar: 0 },
          { jawaban_id: null, opsi: 'D', deskripsi: null, benar: 0 },
          //{opsi: 'E', deskripsi: null, benar: 0},
        ],
      }
    }
  }
})
</script>

<template>
  <VDialog fullscreen scrollable content-class="scrollable-dialog" transition="dialog-bottom-transition"
    :model-value="props.isDialogVisible" @update:model-value="onReset">
    <!-- 👉 Dialog close btn -->
    <!--DialogCloseBtn @click="onReset" /-->

    <VCard>
      <div>
        <VToolbar color="primary">
          <VBtn icon variant="plain" @click="onReset">
            <VIcon color="white" icon="tabler-x" />
          </VBtn>
          <VToolbarTitle>{{ detilData ? 'Edit' : 'Tambah' }} Tes
          </VToolbarTitle>
          <VSpacer />
          <VToolbarItems>
            <VBtn variant="text" @click="onSubmit">Simpan</VBtn>
          </VToolbarItems>
        </VToolbar>
      </div>
      <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
        <VCardText>
          <VRow>
            <VCol cols="12" v-if="!$can('create', 'laman-tentang-create')">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="sekolah_id">Sekolah</label>
                </VCol>
                <VCol cols="12" md="9">
                  <VAutocomplete v-model="form.sekolah_id" variant="outlined" :items="sekolah" item-title="nama"
                    item-value="sekolah_id" placeholder="== Pilih Sekolah ==" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="pembelajaran_id">Pembelajaran</label>
                </VCol>
                <VCol cols="12" md="9">
                  <VAutocomplete v-model="form.pembelajaran_id" variant="outlined" :items="props.pembelajaran"
                    item-title="judul" item-value="pembelajaran_id" placeholder="== Pilih Mata Pembelajaran =="
                    :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="deskripsi">Deskripsi Soal</label>
                </VCol>
                <VCol cols="12" md="9">
                  <QuillEditor theme="snow" toolbar="full" v-model:content="form.deskripsi" contentType="html"
                    :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
          </VRow>
          <template v-for="jawaban in form.jawaban">
            <div class="mt-10">
              <VRow no-gutters>
                <VCol cols="12">
                  <VRow no-gutters>
                    <VCol cols="12" md="3" class="d-flex align-items-center">
                      <label class="v-label text-body-2 text-high-emphasis" for="deskripsi">
                        Jawaban {{ jawaban.opsi }}
                        <AppSelect class="ms-4" :items="kunci" v-model="jawaban.benar" placeholder="Jawaban Benar"
                          item-title="val" item-value="key" style="inline-size: 12rem;" :rules="[requiredValidator]" />
                      </label>
                    </VCol>
                    <VCol cols="12" md="9" class="mt-10">
                      <QuillEditor theme="snow" toolbar="full" v-model:content="jawaban.deskripsi" contentType="html"
                        :rules="[requiredValidator]" />
                    </VCol>
                  </VRow>
                </VCol>
              </VRow>
            </div>
          </template>
        </VCardText>
      </VForm>
    </VCard>
  </VDialog>
</template>

<style lang="scss">
.permission-table {
  td {
    border-block-end: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
    padding-block: 0.5rem;

    .v-checkbox {
      min-inline-size: 4.75rem;
    }

    &:not(:first-child) {
      padding-inline: 0.5rem;
    }

    .v-label {
      white-space: nowrap;
    }
  }
}
</style>
