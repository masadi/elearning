<script setup>
const notif = ref({
  icon: null,
  title: null,
  text: null,
  color: null,
})
const isAlertVisible = ref(false)
// 👉 Store
const searchQuery = ref('')
const selectedRole = ref()
const emit = defineEmits(['statistik', 'aplikasi'])
// Data table options
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref('id')
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
    sortable: false,
  },
  {
    title: 'npsn',
    key: 'npsn',
    sortable: false,
  },
  {
    title: 'email',
    key: 'email',
    sortable: false,
  },
  {
    title: 'Actions',
    key: 'actions',
    align: 'center',
    sortable: false,
  },
]

const {
  data: getData,
  execute: fetchData,
} = await useApi(createUrl('/table', {
  query: {
    data: 'sekolah',
    q: searchQuery,
    role_id: selectedRole,
    itemsPerPage,
    page,
    sortBy,
    orderBy,
  },
}))
const items = computed(() => getData.value.lists.data)
const total_item = computed(() => getData.value.lists.total)
const statistik = computed(() => getData.value.statistik)
const aplikasi = computed(() => getData.value.aplikasi)
emit('statistik', statistik.value)
emit('aplikasi', aplikasi.value)
// 👉 search filters

const resolveUserRoleVariant = role => {
  if (!role)
    return {
      color: 'primary',
      icon: 'tabler-user',
    }
  const roleLowerCase = role.toLowerCase()
  if (roleLowerCase === 'administrator')
    return {
      color: 'primary',
      icon: 'tabler-user',
    }
  if (roleLowerCase === 'operator')
    return {
      color: 'warning',
      icon: 'tabler-settings',
    }
  if (roleLowerCase === 'koperasi')
    return {
      color: 'success',
      icon: 'tabler-chart-donut',
    }
  if (roleLowerCase === 'editor')
    return {
      color: 'info',
      icon: 'tabler-pencil',
    }
  if (roleLowerCase === 'admin')
    return {
      color: 'error',
      icon: 'tabler-device-laptop',
    }

  return {
    color: 'primary',
    icon: 'tabler-user',
  }
}

const isConfirmDialogVisible = ref()
const deletedId = ref()
const deleteUser = async id => {
  deletedId.value = id
  isConfirmDialogVisible.value = true
}

const confirmDelete = async () => {
  await $api(`/settings/destroy/user/${deletedId.value}`, {
    method: 'DELETE',
    onResponse({ request, response, options }) {
      let getData = response._data
      notif.value = getData
      isAlertVisible.value = true
      //isEditPtkVisible.value = false
    }
  })
}
const generateUser = async () => {
  await $api(`/settings/generate-user`, {
    onResponse({ request, response, options }) {
      let getData = response._data
      notif.value = getData
      isAlertVisible.value = true
      //isEditPtkVisible.value = false
    }
  })
}
watch(isAlertVisible, () => {
  if (!isAlertVisible.value)
    fetchData()
})
</script>

<template>
  <section>
    <VCard>
      <VCardText class="d-flex flex-wrap gap-4">
        <div class="d-flex gap-2 align-center">
          <p class="text-body-1 mb-0">
            Show
          </p>
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

        <!-- Actions -->
        <template #item.actions="{ item }">
          <IconBtn @click="deleteUser(item.id)">
            <VIcon icon="tabler-trash" />
          </IconBtn>

          <IconBtn>
            <VIcon icon="tabler-eye" />
          </IconBtn>

          <VBtn icon variant="text" color="medium-emphasis">
            <VIcon icon="tabler-dots-vertical" />
            <VMenu activator="parent">
              <VList>
                <VListItem :to="{ name: 'apps-user-view-id', params: { id: item.id } }">
                  <template #prepend>
                    <VIcon icon="tabler-eye" />
                  </template>

                  <VListItemTitle>View</VListItemTitle>
                </VListItem>

                <VListItem link>
                  <template #prepend>
                    <VIcon icon="tabler-pencil" />
                  </template>
                  <VListItemTitle>Edit</VListItemTitle>
                </VListItem>

                <VListItem @click="deleteUser(item.id)">
                  <template #prepend>
                    <VIcon icon="tabler-trash" />
                  </template>
                  <VListItemTitle>Delete</VListItemTitle>
                </VListItem>
              </VList>
            </VMenu>
          </VBtn>
        </template>

        <template #bottom>
          <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="total_item" />
        </template>
      </VDataTableServer>
      <!-- SECTION -->
    </VCard>
    <ShowAlert :color="notif.color" :icon="notif.icon" :title="notif.title" :text="notif.text" :disable-time-out="false"
      v-model:isSnackbarVisible="isAlertVisible" v-if="notif.color"></ShowAlert>
    <ConfirmDialog v-model:isDialogVisible="isConfirmDialogVisible"
      confirmation-question="Apakah Anda yakin ingin menghapus data ini?" :show-notif="false"
      @confirm="confirmDelete" />
    <!-- 👉 Add New User -->
    <!--AddNewUserDrawer
      v-model:isDrawerOpen="isAddNewUserDrawerVisible"
      @user-data="addNewUser"
    /-->
  </section>
</template>

<style lang="scss">
.text-capitalize {
  text-transform: capitalize;
}

.user-list-name:not(:hover) {
  color: rgba(var(--v-theme-on-background), var(--v-medium-emphasis-opacity));
}
</style>
