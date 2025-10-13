<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'dashboard-read',
  },
})
import UserList from '@/views/apps/roles/UserList.vue';
const statistik = ref([])
const aplikasi = ref([])
const getStatistik = val => {
  statistik.value = val
}
const getAplikasi = val => {
  aplikasi.value = val
}
</script>

<template>
  <section>
    <VRow>
      <VCol cols="12">
        <h4 class="text-h4 mb-1 mt-6">
          Data Sekolah
        </h4>
        <p v-if="$can('create', 'laman-tentang-create')">detil sekolah</p>
        <UserList @statistik="getStatistik" @aplikasi="getAplikasi" v-else />
      </VCol>
    </VRow>
  </section>
</template>
