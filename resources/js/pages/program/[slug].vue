<script setup>
import Footer from '@/views/front-pages/front-page-footer.vue'
import Navbar from '@/views/front-pages/front-page-navbar.vue'
import Program from '@/views/pages/Program.vue'
definePage({
  meta: {
    layout: 'blank',
    public: true,
  },
})
const activeSectionId = ref()
const isBusy = ref(true)
const item = ref([])
const route = useRoute()
const getData = async (data) => {
  await $api(`/frontend/laman/program/${route.params.slug}`, {
    query: {
      type: data,
    },
    onResponse({ request, response, options }) {
      let getData = response._data
      isBusy.value = false
      item.value = getData
    }
  })
}
getData('galeri')
</script>

<template>
  <div class="landing-page-wrapper">
    <Navbar :active-id="activeSectionId" />
    <div style="margin-top: 65px;" class="white">
      <VContainer>
        <h3 class="text-h3">Program {{ item.nama }}</h3>
        <VProgressCircular indeterminate color="warning" size="150" v-if="isBusy" />
        <span v-html="item.deskripsi" v-else></span>
      </VContainer>
    </div>
    <Program />
    <Footer />
  </div>
</template>
<style lang="scss" scoped>
#myframe {
  height: 100vh;
  width: 100%;
  border: none;
}

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
