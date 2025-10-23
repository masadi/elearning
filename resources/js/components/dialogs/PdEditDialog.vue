<script setup>
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  detilData: {
    type: Object,
    required: false,
    default: () => ({
      nama: '',
      nipd: '',
      nisn: '',
      jenis_kelamin: '',
      tempat_lahir: false,
      tanggal_lahir: '',
      alamat: '',
      agama_id: '',
      telepon: '',
      nama_ayah: '',
      nama_ibu: '',
      kerja_ayah: '',
      kerja_ibu: '',
      agama_ayah: '',
      agama_ibu: '',
      nama_wali: '',
      kerja_wali: '',
      agama_wali: '',
      alamat_wali: '',
      sekolah_asal: '',
      diterima: '',
      surat_pindah: '',
      nipd_asal: '',
      program_pilihan: '',
      nilai_un: '',
    }),
  },
  agama: {
    type: Array,
    required: true,
  },
  pekerjaan: {
    type: Array,
    required: true,
  }
})
const isLoading = ref(false)
const emit = defineEmits(['update:isDialogVisible', 'submit'])
const dialogModelValueUpdate = val => {
  emit('update:isDialogVisible', val)
}

const detilData = ref(structuredClone(toRaw(props.detilData)))
watch(props, () => {
  isLoading.value = false
  detilData.value = structuredClone(toRaw(props.detilData))
  detilData.value.avatar = null
})
const isFormValid = ref(false)
const refForm = ref()
const formSubmit = () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      isLoading.value = true
      emit('submit', detilData.value)
    }
  })
}
const jenisKelamin = [
  { title: 'Laki-laki', value: 'L' },
  { title: 'Perempuan', value: 'P' }
]
</script>

<template>
  <VDialog :width="$vuetify.display.smAndDown ? 'auto' : 800" :model-value="props.isDialogVisible" scrollable
    content-class="scrollable-dialog" @update:model-value="dialogModelValueUpdate">
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />
    <VCard :title="`Edit Data ${detilData.nama}`">
      <VCardText>
        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="formSubmit">
          <VRow>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="nama">Nama Lengkap</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="nama" v-model="detilData.nama" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="nipd">NIPD</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="nipd" v-model="detilData.nipd" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="nisn">NISN</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="nisn" v-model="detilData.nisn" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="jenis_kelamin">Jenis Kelamin</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppAutocomplete :items="jenisKelamin" v-model="detilData.jenis_kelamin" :rules="[requiredValidator]"
                    placeholder="== Pilih Jenis Kelamin ==" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="tempat_lahir">Tempat Lahir</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="tempat_lahir" v-model="detilData.tempat_lahir" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="tanggal_lahir">Tanggal Lahir</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="tanggal_lahir" v-model="detilData.tanggal_lahir"
                    :rules="[requiredValidator, dateValidator]" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="alamat">Alamat</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="alamat" v-model="detilData.alamat" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="telepon">Telepon/HP</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="telepon" v-model="detilData.telepon" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="agama_id">Agama</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppAutocomplete :items="props.agama" item-title="nama" item-value="id" v-model="detilData.agama_id"
                    placeholder="== Pilih Agama ==" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="nama_ayah">Nama Ayah</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="nama_ayah" v-model="detilData.nama_ayah" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="kerja_ayah">Pekerjaan Ayah</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppAutocomplete :items="props.pekerjaan" item-title="nama" item-value="id"
                    v-model="detilData.kerja_ayah" placeholder="== Pilih Pekerjaan ==" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="agama_ayah">Agama Ayah</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppAutocomplete :items="props.agama" item-title="nama" item-value="id" v-model="detilData.agama_ayah"
                    placeholder="== Pilih Agama ==" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="nama_ibu">Nama Ibu</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="nama_ibu" v-model="detilData.nama_ibu" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="kerja_ibu">Pekerjaan Ibu</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppAutocomplete :items="props.pekerjaan" item-title="nama" item-value="id"
                    v-model="detilData.kerja_ibu" placeholder="== Pilih Pekerjaan ==" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="agama_ibu">Agama Ibu</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppAutocomplete :items="props.agama" item-title="nama" item-value="id" v-model="detilData.agama_ibu"
                    placeholder="== Pilih Agama ==" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="nama_wali">Nama Wali</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="nama_wali" v-model="detilData.nama_wali" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="kerja_wali">Pekerjaan Wali</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppAutocomplete :items="props.pekerjaan" item-title="nama" item-value="id"
                    v-model="detilData.kerja_wali" placeholder="== Pilih Pekerjaan ==" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="agama_wali">Agama Wali</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppAutocomplete :items="props.agama" item-title="nama" item-value="id" v-model="detilData.agama_wali"
                    placeholder="== Pilih Agama ==" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="alamat_wali">Alamat Wali</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="alamat_wali" v-model="detilData.alamat_wali" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="sekolah_asal">Sekolah Asal</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="sekolah_asal" v-model="detilData.sekolah_asal" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="diterima">Tanggal Diterima</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="diterima" v-model="detilData.diterima" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="surat_pindah">Nomor Surat Pindah</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="surat_pindah" v-model="detilData.surat_pindah" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="nipd_asal">NIPD Asal</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="nipd_asal" v-model="detilData.nipd_asal" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="program_pilihan">Program Pilihan</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="program_pilihan" v-model="detilData.program_pilihan" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="nilai_un">Nilai UN</label>
                </VCol>
                <VCol cols="12" md="9">
                  <AppTextField id="nilai_un" v-model="detilData.nilai_un" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12" md="3" class="d-flex align-items-center">
                  <label class="v-label text-body-2 text-high-emphasis" for="avatar">Foto Profil</label>
                </VCol>
                <VCol cols="12" md="9">
                  <VFileInput accept="image/*" v-model="detilData.avatar" id="avatar" label="Foto Profil" />
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12" class="d-flex gap-4">
              <VBtn :loading="isLoading" :disabled="isLoading" type="submit">Simpan</VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
