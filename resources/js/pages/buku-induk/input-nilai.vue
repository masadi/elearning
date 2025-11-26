<script setup>
definePage({
    meta: {
        action: 'read',
        subject: 'buku-induk-read',
    },
})
const tingkatKelas = [
    { title: '1', value: '1' },
    { title: '2', value: '2' },
    { title: '3', value: '3' },
    { title: '4', value: '4' },
    { title: '5', value: '5' },
    { title: '6', value: '6' },
]
const rombonganBelajar = ref([])
const {
    data: getData,
} = await useApi(createUrl('/induk/get-data', {
    query: {
        data: 'nilai',
    },
}))
const item_semester = computed(() => getData.value.semester)
const sekolahId = computed(() => getData.value.sekolah_id)
const semesterId = computed(() => getData.value.semester_id)
const showForm = ref(false)
const form = ref({
    semester_id: semesterId.value,
    tingkat: null,
    rombongan_belajar_id: null,
    pembelajaran_id: null,
    nilai: {}
})
const changeSmt = () => {
    form.value.tingkat = null
    form.value.rombongan_belajar_id = null
    form.value.pembelajaran_id = null
}
const getRombel = async (val) => {
    form.value.rombongan_belajar_id = null
    form.value.pembelajaran_id = null
    await $api('/induk/get-data', {
        method: 'POST',
        body: {
            sekolah_id: sekolahId.value,
            semester_id: form.value.semester_id,
            tingkat: val,
            data: 'rombel',
        },
        onResponse({ request, response, options }) {
            let getData = response._data
            rombonganBelajar.value = getData
        }
    })
}
const pembelajaran = ref([])
const siswa = ref([])
const getMapel = async (val) => {
    form.value.pembelajaran_id = null
    await $api('/induk/get-data', {
        method: 'POST',
        body: {
            sekolah_id: sekolahId.value,
            semester_id: form.value.semester_id,
            rombongan_belajar_id: val,
            data: 'mapel',
        },
        onResponse({ request, response, options }) {
            let getData = response._data
            pembelajaran.value = getData
        }
    })
}
const getSiswa = async (val) => {
    await $api('/induk/get-data', {
        method: 'POST',
        body: {
            sekolah_id: sekolahId.value,
            semester_id: form.value.semester_id,
            rombongan_belajar_id: form.value.rombongan_belajar_id,
            pembelajaran_id: val,
            data: 'siswa',
        },
        onResponse({ request, response, options }) {
            let getData = response._data
            siswa.value = getData
            showForm.value = (val) ? true : false
            siswa.value.forEach((pd) => {
                form.value.nilai[pd.anggota_rombel.id] = pd.anggota_rombel?.nilai?.nilai
            })
        }
    })
}
const isLoading = ref(false)
const notif = ref({
    icon: null,
    title: null,
    text: null,
    color: null,
})
const isAlertVisible = ref(false)
const onSubmit = async () => {
    isLoading.value = true
    const defaultForm = { data: 'nilai' }
    const mergedForm = { ...defaultForm, ...form.value };
    await $api('/induk/store', {
        method: 'POST',
        body: mergedForm,
        onResponse({ request, response, options }) {
            let getData = response._data
            notif.value = getData
            isAlertVisible.value = true
            showForm.value = false
            form.value = {
                semester_id: semesterId.value,
                tingkat: null,
                rombongan_belajar_id: null,
                pembelajaran_id: null,
                nilai: {}
            }
        }
    })
}
</script>

<template>
    <section>
        <VCard>
            <VCardItem>
                <VCardTitle>Input Nilai</VCardTitle>
            </VCardItem>
            <VDivider />
            <VCardText>
                <VForm @submit.prevent="onSubmit">
                    <VRow>
                        <VCol cols="12">
                            <VRow no-gutters>
                                <!-- 👉 First Name -->
                                <VCol cols="12" md="3" class="d-flex align-items-center">
                                    <label class="v-label text-body-2 text-high-emphasis"
                                        for="semester_id">Semester</label>
                                </VCol>

                                <VCol cols="12" md="9">
                                    <AppSelect id="semester_id" :items="item_semester" v-model="form.semester_id"
                                        item-title="nama" item-value="semester_id" @update:model-value="changeSmt" />
                                </VCol>
                            </VRow>
                        </VCol>

                        <VCol cols="12">
                            <VRow no-gutters>
                                <VCol cols="12" md="3" class="d-flex align-items-center">
                                    <label class="v-label text-body-2 text-high-emphasis" for="tingkat">Tingkat
                                        Kelas</label>
                                </VCol>

                                <VCol cols="12" md="9">
                                    <AppSelect id="tingkat" :items="tingkatKelas" v-model="form.tingkat"
                                        @update:model-value="getRombel" placeholder="== Pilih Tingkat Kelas ==" />
                                </VCol>
                            </VRow>
                        </VCol>

                        <VCol cols="12">
                            <VRow no-gutters>
                                <VCol cols="12" md="3" class="d-flex align-items-center">
                                    <label class="v-label text-body-2 text-high-emphasis"
                                        for="rombongan_belajar_id">Rombongan Belajar</label>
                                </VCol>

                                <VCol cols="12" md="9">
                                    <AppAutocomplete id="rombongan_belajar_id" :items="rombonganBelajar"
                                        item-title="nama" item-value="id" v-model="form.rombongan_belajar_id"
                                        @update:model-value="getMapel" placeholder="== Pilih Rombongan Belajar =="
                                        clearable />
                                </VCol>
                            </VRow>
                        </VCol>
                        <VCol cols="12">
                            <VRow no-gutters>
                                <VCol cols="12" md="3" class="d-flex align-items-center">
                                    <label class="v-label text-body-2 text-high-emphasis" for="pembelajaran_id">Mata
                                        Pelajaran</label>
                                </VCol>

                                <VCol cols="12" md="9">
                                    <AppAutocomplete id="pembelajaran_id" :items="pembelajaran" item-title="id"
                                        item-value="id" v-model="form.pembelajaran_id" @update:model-value="getSiswa"
                                        placeholder="== Pilih Mata Pelajaran ==" clearable>
                                        <template #item="{ props, item }">
                                            <VListItem v-bind="props" :title="item?.raw?.mata_pelajaran?.nama" />
                                        </template>
                                        <template #selection="{ item }">
                                            {{ item?.raw?.mata_pelajaran?.nama }}
                                        </template>
                                    </AppAutocomplete>
                                </VCol>
                            </VRow>
                        </VCol>
                        <template v-if="showForm">
                            <VCol cols="12">
                                <VTable>
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama Peserta Didik</th>
                                            <th class="text-center">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="pd in siswa">
                                            <tr>
                                                <td class="text-no-wrap pt-2">
                                                    {{ pd.nama }}
                                                </td>
                                                <td class="pt-2">
                                                    <AppTextField type="number"
                                                        v-model="form.nilai[pd.anggota_rombel.id]" />
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </VTable>
                            </VCol>
                            <!-- 👉 submit and reset button -->
                            <VCol cols="12">
                                <VRow no-gutters>
                                    <VCol cols="12">
                                        <VBtn type="submit" class="me-4">
                                            Simpan
                                        </VBtn>
                                    </VCol>
                                </VRow>
                            </VCol>
                        </template>
                    </VRow>
                </VForm>
            </VCardText>
        </VCard>
        <ShowAlert :color="notif.color" :icon="notif.icon" :title="notif.title" :text="notif.text"
            :disable-time-out="false" v-model:isSnackbarVisible="isAlertVisible" v-if="notif.color"></ShowAlert>
    </section>
</template>
