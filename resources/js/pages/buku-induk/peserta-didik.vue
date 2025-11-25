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
const sortBy = ref('nama')
const orderBy = ref('ASC')

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
        title: 'nipd',
        key: 'nipd',
    },
    {
        title: 'nisn',
        key: 'nisn',
    },
    {
        title: 'tempat, tanggal lahir',
        key: 'ttl',
        sortable: false,
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
        data: 'peserta-didik',
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
const agama = computed(() => getData.value.agama)
const pekerjaan = computed(() => getData.value.pekerjaan)
const isAddNewPtkVisible = ref(false)

const deletedId = ref()
const isConfirmDialogVisible = ref()
const deleteData = async (val) => {
    deletedId.value = val.id
    isConfirmDialogVisible.value = true
}
const confirmDelete = async (val) => {
    if (val) {
        await $api(`/induk/destroy/pd/${deletedId.value}`, {
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
const isEditPtkVisible = ref(false)
const detilPtk = ref()
const editData = async (val) => {
    isEditPtkVisible.value = true
    detilPtk.value = val
}
watch(isAlertVisible, () => {
    if (!isAlertVisible.value)
        fetchData()
})
const updateData = async userData => {
    console.log(userData);
    const postData = new FormData();
    postData.append('data', 'peserta-didik');
    for (const [key, value] of Object.entries(userData)) {
        postData.append(key, (value) ? value : '');
    }
    await $api('/induk/store', {
        method: 'POST',
        body: postData,
        onResponse({ request, response, options }) {
            let getData = response._data
            notif.value = getData
            isAlertVisible.value = true
            isEditPtkVisible.value = false
        }
    })
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
                    <VBtn @click="isAddNewPtkVisible = true">Tambah
                        <VIcon end icon="tabler-cloud-upload" />
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
                <!-- User -->
                <template #item.user="{ item }">
                    <div class="d-flex align-center gap-x-4">
                        <VAvatar size="34" :variant="!item.avatar ? 'tonal' : undefined"
                            :color="!item.avatar ? 'success' : undefined">
                            <VImg v-if="item.avatar" :src="item.avatar" />
                            <span v-else>{{ avatarText(item.name) }}</span>
                        </VAvatar>
                        <div class="d-flex flex-column">
                            <h6 class="text-base">
                                {{ item.nama }}
                            </h6>
                            <div class="text-sm">
                                {{ item.email }}
                            </div>
                        </div>
                    </div>
                </template>

                <!-- 👉 Role -->
                <template #item.ttl="{ item }">
                    {{ item.tempat_lahir }}, {{ new Date(item.tanggal_lahir).toLocaleString('id-ID', {
                        hour12: false,
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                    }) }}
                </template>

                <!-- Actions -->
                <template #item.actions="{ item }">
                    <IconBtn @click="editData(item)">
                        <VIcon icon="tabler-pencil" />
                    </IconBtn>
                    <IconBtn @click="deleteData(item)">
                        <VIcon icon="tabler-trash" />
                    </IconBtn>
                </template>

                <template #bottom>
                    <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="total_item" />
                </template>
            </VDataTableServer>
            <!-- SECTION -->
        </VCard>

        <!-- 👉 Add New User -->
        <PdAddDialog v-model:is-dialog-visible="isAddNewPtkVisible" @notif="handleNotif"
            v-model:sekolahId="sekolahId" />
        <PdEditDialog v-model:is-dialog-visible="isEditPtkVisible" v-model:detilData="detilPtk" v-model:agama="agama"
            v-model:pekerjaan="pekerjaan" @submit="updateData" />
        <ShowAlert :color="notif.color" :icon="notif.icon" :title="notif.title" :text="notif.text"
            :disable-time-out="false" v-model:isSnackbarVisible="isAlertVisible" v-if="notif.color"></ShowAlert>
        <ConfirmDialog v-model:isDialogVisible="isConfirmDialogVisible"
            confirmation-question="Apakah Anda yakin ingin menghapus data ini?" :show-notif="false"
            @confirm="confirmDelete" />
    </section>
</template>
