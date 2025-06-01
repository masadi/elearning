<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'dashboard-read',
  },
})
import UserList from '@/views/apps/roles/UserList.vue';
import Aplikasi from '@/views/dashboards/Aplikasi.vue';
import Statistik from '@/views/dashboards/Statistik.vue';
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
    <template v-if="$can('read', 'referensi-sekolah-read')">
      <VRow class="match-height">
        <VCol cols="12" md="7" lg="8">
          <Statistik :statistik="statistik" />
        </VCol>
        <VCol cols="12" md="5" lg="4">
          <Aplikasi :aplikasi="aplikasi"></Aplikasi>
        </VCol>
      </VRow>
      <VRow>
        <VCol cols="12">
          <h4 class="text-h4 mb-1 mt-6">
            Data Pengguna
          </h4>
          <UserList @statistik="getStatistik" @aplikasi="getAplikasi" />
        </VCol>
      </VRow>
    </template>
  </section>
</template>
