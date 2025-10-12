<script setup>
import Footer from '@/views/front-pages/front-page-footer.vue'
import Navbar from '@/views/front-pages/front-page-navbar.vue'
import Galeri from '@/views/pages/Galeri.vue'
definePage({
  meta: {
    layout: 'blank',
    public: true,
  },
})
const activeSectionId = ref()
const isBusy = ref(true)
const items = ref([])
const getData = async (data) => {
  await $api(`/laman`, {
    query: {
      type: data,
    },
    onResponse({ request, response, options }) {
      let getData = response._data
      isBusy.value = false
      const chunkSize = 3;
      for (let i = 0; i < getData.length; i += chunkSize) {
        items.value.push(getData.slice(i, i + chunkSize));
      }
    }
  })
}
getData('program')
</script>

<template>
  <div class="landing-page-wrapper">
    <Navbar :active-id="activeSectionId" />
    <div style="margin-top: 65px;" class="white">
      <VContainer>
        <h3 class="text-h3">Program</h3>
        <VProgressCircular indeterminate color="warning" size="150" v-if="isBusy" />
        <VRow v-for="(item, index) in items" :key="index" class="m-4" v-else>
          <VCol v-for="(subItem, subIndex) in item" :key="subIndex">
            <v-card color="#fff">
              <v-img class="align-end text-white" height="200" :src="`/storage/images/${subItem.foto}`" cover></v-img>
              <v-card-title>
                <RouterLink :to="{ name: 'program-slug', params: { slug: subItem.slug } }">{{ subItem.nama
                }}</RouterLink>
              </v-card-title>
              <v-card-text>
                <v-row no-gutters>
                  <v-col>
                    <VIcon icon="tabler-calendar-week" /> {{ subItem.tanggal_indo }}
                  </v-col>
                  <v-col>
                    <VIcon icon="tabler-map-pin" /> {{ subItem.lokasi }}
                  </v-col>
                </v-row>
              </v-card-text>
              <VCardText class="justify-center">
                <VBtn variant="elevated" :to="{ name: 'galeri-slug', params: { slug: subItem.slug } }">
                  Lihat
                </VBtn>
              </VCardText>
            </v-card>
          </VCol>
        </VRow>
      </VContainer>
    </div>
    <Galeri />
    <Footer />
  </div>
</template>
<style lang="scss" scoped>
.white {
  background-color: #FFFFFF;
  color: black !important;
}

.blue-lighten-5 {
  background-color: #E0E0E0;
  color: black !important;
}

.white h3,
.text-black {
  color: black !important;
}

.no-elevated {
  box-shadow: none !important;
  background: none !important;
}
</style>
