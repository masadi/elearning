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
  await $api(`/frontend/laman`, {
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
              <v-card-text>
                <v-card-title class="ps-0 pb-2">
                  <RouterLink :to="{ name: 'program-slug', params: { slug: subItem.slug } }">
                    {{ subItem.nama
                    }}</RouterLink>
                </v-card-title>
                <v-card-sub-title class="mt-2">{{ subItem.tanggal_indo }}</v-card-sub-title>
              </v-card-text>
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
