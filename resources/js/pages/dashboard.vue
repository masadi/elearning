<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'dashboard-read',
  },
})
import UserList from '@/views/apps/roles/UserList.vue';
const {
  data: getData,
} = await useApi(createUrl('/admin/sekolah'))
const sekolah = computed(() => getData.value)
const getStatistik = ref([])
const getAplikasi = ref([])
</script>

<template>
  <section>
    <VRow>
      <VCol cols="12">
        <h4 class="text-h4 mb-1 mt-6">
          Data Sekolah
        </h4>
        <VCard v-if="$can('create', 'laman-tentang-create')">
          <VTable v-if="sekolah">
            <tbody>
              <tr>
                <td>Nama</td>
                <td>{{ sekolah.nama }}</td>
              </tr>
              <tr>
                <td>NPSN</td>
                <td>{{ sekolah.npsn }}</td>
              </tr>
              <tr>
                <td>Alamat</td>
                <td>{{ sekolah.alamat }}</td>
              </tr>
              <tr>
                <td>Desa/Kelurahan</td>
                <td>{{ sekolah.desa_kelurahan }}</td>
              </tr>
              <tr>
                <td>Kodepos</td>
                <td>{{ sekolah.kodepos }}</td>
              </tr>
              <tr>
                <td>Email</td>
                <td>{{ sekolah.email }}</td>
              </tr>
              <tr>
                <td>Website</td>
                <td>{{ sekolah.website }}</td>
              </tr>
            </tbody>
          </VTable>
          <div class="text-center" v-else>
            <VProgressCircular indeterminate color="warning" size="150" />
          </div>
        </VCard>
        <UserList @statistik="getStatistik" @aplikasi="getAplikasi" v-else />
      </VCol>
    </VRow>
  </section>
</template>
