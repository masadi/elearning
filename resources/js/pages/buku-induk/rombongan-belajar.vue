<script setup>
definePage({
    meta: {
        action: 'read',
        subject: 'buku-induk-read',
    },
})
const notif = ref({
    icon: null,
    title: null,
    text: null,
    color: null,
})
const isAlertVisible = ref(false)
// 👉 Store
const searchQuery = ref('')
// Data table options
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref('tingkat')
const orderBy = ref('asc')

const updateOptions = options => {
    if (options.sortBy.length) {
        sortBy.value = options.sortBy[0]?.key
        orderBy.value = options.sortBy[0]?.order
    }
}

// Headers
const headers = [
    {
        title: 'nama',
        key: 'nama',
    },
    {
        title: 'tingkat',
        key: 'tingkat',
    },
    {
        title: 'anggota rombel',
        key: 'anggota_rombel',
    },
    {
        title: 'pembelajaran',
        key: 'pembelajaran',
    },
    {
        title: 'Actions',
        key: 'actions',
        align: 'center',
        sortable: false,
    },
]
const handleNotif = (val) => {
    notif.value = val
    isAlertVisible.value = true
}
const {
    data: getData,
    execute: fetchData,
} = await useApi(createUrl('/induk', {
    query: {
        data: 'rombongan-belajar',
        q: searchQuery,
        per_page: itemsPerPage,
        page,
        sortBy,
        orderBy,
    },
}))
if (getData.value.color) {
    notif.value = getData.value
    isAlertVisible.value = true
}
const items = computed(() => getData.value.lists.data)
const total_item = computed(() => getData.value.lists.total)
const sekolahId = computed(() => getData.value.sekolah_id)
//const sekolah = computed(() => getData.value.sekolah)
const isAddNewData = ref(false)

const deletedId = ref()
const isConfirmDialogVisible = ref()
const deleteData = async (item) => {
    deletedId.value = item.id
    isConfirmDialogVisible.value = true
}
const confirmDelete = async (val) => {
    if (val) {
        await $api(`/induk/destroy/rombongan-belajar/${deletedId.value}`, {
            method: 'DELETE',
            onResponse({ request, response, options }) {
                let getData = response._data
                notif.value = getData
                isAlertVisible.value = true
                deletedId.value = null
                fetchData()
            }
        })
    }
}
const detilData = ref()
const editData = async (val) => {
    isAddNewData.value = true
    detilData.value = val
}
watch(isAlertVisible, () => {
    if (!isAlertVisible.value)
        fetchData()
})
const addNewData = () => {
    isAddNewData.value = true
    detilData.value = null
}
const showAnggota = ref(false)
const isLoading = ref(false)
const dialogTitle = ref('')
const anggotaRombel = ref([])
const rombonganBelajarId = ref()
const loadingAnggota = ref([])
const getAnggota = async (rombongan_belajar_id) => {
    rombonganBelajarId.value = rombongan_belajar_id
    showAnggota.value = true
    /*
    loadingAnggota.value[rombongan_belajar_id] = true
    isLoading.value = true
    rombonganBelajarId.value = rombongan_belajar_id
    showAnggota.value = true
    await $api('/induk/get-data', {
        method: 'POST',
        body: {
            rombongan_belajar_id: rombonganBelajarId.value,
            sekolah_id: sekolahId.value,
            type: 'anggota',
        },
        onResponse({ request, response, options }) {
            let getData = response._data
            dialogTitle.value = `Anggota Rombel Kelas ${getData.rombel.nama}`
            anggotaRombel.value = getData.data
            isLoading.value = false
            loadingAnggota.value[rombongan_belajar_id] = false
        }
    })*/
}
const form = ref({
    nama: {},
    mata_pelajaran_id: {},
    pembelajaran_id: {},
    kelompok_id: {},
    nomor_urut: {},
})
const mataPelajaran = ref([])
const kelompok = ref([])
const showPembelajaran = ref(false)
const savePembelajaran = async () => {
    console.log('savePembelajaran');
    console.log(form.value);
    const defaultForm = { data: 'pembelajaran', rombongan_belajar_id: rombonganBelajarId.value }
    const mergedForm = { ...defaultForm, ...form.value };
    await $api('/induk/store', {
        method: 'POST',
        body: mergedForm,
        onResponse() {
            showPembelajaran.value = false
            fetchData()
        }
    })
}
const getPembelajaran = async (rombongan_belajar_id) => {
    isLoading.value = true
    rombonganBelajarId.value = rombongan_belajar_id
    showPembelajaran.value = true
    await $api('/induk/get-data', {
        query: {
            rombongan_belajar_id: rombonganBelajarId.value,
            sekolah_id: sekolahId.value,
            data: 'pembelajaran',
        },
        onResponse({ request, response, options }) {
            let getData = response._data
            dialogTitle.value = `Pembelajaran Kelas ${getData.rombel.nama}`
            mataPelajaran.value = getData.mata_pelajaran
            getData.mata_pelajaran.forEach(item => {
                form.value.nama[item.id] = item.nama
                form.value.mata_pelajaran_id[item.id] = item.id
                form.value.pembelajaran_id[item.id] = item.pembelajaran?.id
                form.value.kelompok_id[item.id] = item.pembelajaran?.kelompok_id
                form.value.nomor_urut[item.id] = item.pembelajaran?.nomor_urut
            });
            kelompok.value = getData.kelompok
            isLoading.value = false
        }
    })
}
const reFecthData = async () => {
    getPembelajaran(rombonganBelajarId.value)
}
const reFecthAnggota = async () => {
    getAnggota(rombonganBelajarId.value)
}
</script>

<template>
    <section>
        <VCard>
            <VCardText class="d-flex flex-wrap gap-4">
                <div class="d-flex gap-2 align-center">
                    <AppSelect :model-value="itemsPerPage" :items="[
                        { value: 10, title: '10' },
                        { value: 25, title: '25' },
                        { value: 50, title: '50' },
                        { value: 100, title: '100' },
                        { value: -1, title: 'All' },
                    ]" style="inline-size: 5.5rem;" @update:model-value="itemsPerPage = parseInt($event, 10)" />
                </div>

                <VSpacer />

                <div class="d-flex align-center flex-wrap gap-4">
                    <!-- 👉 Search  -->
                    <AppTextField v-model="searchQuery" placeholder="Cari..." style="inline-size: 15.625rem;" />
                    <VBtn @click="addNewData">Tambah
                        <VIcon end icon="tabler-plus" />
                    </VBtn>
                </div>
            </VCardText>

            <VDivider />
            <!-- SECTION datatable -->
            <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page" :items-per-page-options="[
                { value: 10, title: '10' },
                { value: 20, title: '20' },
                { value: 50, title: '50' },
                { value: -1, title: '$vuetify.dataFooter.itemsPerPageAll' },
            ]" :items="items" :items-length="total_item" :headers="headers" class="text-no-wrap"
                @update:options="updateOptions">
                <template #item.anggota_rombel="{ item }">
                    <VBadge :content="item.anggota_rombel_count" color="success">
                        <VBtn :loading="loadingAnggota[item.rombongan_belajar_id]"
                            :disabled="loadingAnggota[item.rombongan_belajar_id]" @click="getAnggota(item.id)"
                            size="x-small">
                            Anggota Rombel
                        </VBtn>
                    </VBadge>
                </template>
                <template #item.pembelajaran="{ item }">
                    <VBadge :content="item.pembelajaran_count" color="success">
                        <VBtn :loading="loadingAnggota[item.rombongan_belajar_id]"
                            :disabled="loadingAnggota[item.rombongan_belajar_id]" @click="getPembelajaran(item.id)"
                            size="x-small">
                            Pembelajaran
                        </VBtn>
                    </VBadge>
                </template>
                <!-- Actions -->
                <template #item.actions="{ item }">
                    <IconBtn @click="deleteData(item)">
                        <VIcon icon="tabler-trash" />
                    </IconBtn>

                    <IconBtn @click="editData(item)">
                        <VIcon icon="tabler-pencil" />
                    </IconBtn>

                </template>

                <template #bottom>
                    <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="total_item" />
                </template>
            </VDataTableServer>
            <!-- SECTION -->
        </VCard>

        <!-- 👉 Add New User -->
        <AddRombelDialog v-model:is-dialog-visible="isAddNewData" @notif="handleNotif" v-model:sekolahId="sekolahId"
            v-model:detil-data="detilData" />
        <PembelajaranDialog v-model:isDialogVisible="showPembelajaran" v-model:isLoading="isLoading"
            :dialog-title="dialogTitle" v-model:listData="mataPelajaran" v-model:form="form" :list-kelompok="kelompok"
            @save="savePembelajaran" @refresh="reFecthData" />
        <!--AnggotaRombelDialog v-model:isDialogVisible="showAnggota" v-model:isLoading="isLoading"
            :dialog-title="dialogTitle" v-model:listData="anggotaRombel" @refresh="reFecthAnggota" /-->
        <AnggotaRombelDialog v-model:isDialogVisible="showAnggota" :rombongan-belajar-id="rombonganBelajarId"
            :dialog-title="dialogTitle" @close="fetchData"></AnggotaRombelDialog>
        <ShowAlert :color="notif.color" :icon="notif.icon" :title="notif.title" :text="notif.text"
            :disable-time-out="false" v-model:isSnackbarVisible="isAlertVisible" v-if="notif.color"></ShowAlert>
        <ConfirmDialog v-model:isDialogVisible="isConfirmDialogVisible"
            confirmation-question="Apakah Anda yakin ingin menghapus data ini?" :show-notif="false"
            @confirm="confirmDelete" />
    </section>
</template>
