<script setup>
import Footer from '@/views/front-pages/front-page-footer.vue'
import Navbar from '@/views/front-pages/front-page-navbar.vue'
import Program from '@/views/pages/Program.vue'
import { ref } from 'vue'
definePage({
  meta: {
    layout: 'blank',
    public: true,
  },
})
const activeSectionId = ref()
const isBusy = ref(true)
const content = ref(null)
const lastProgram = ref([])
const programTerbaru = ref([])
const getData = async (data) => {
  await $api(`/frontend/laman`, {
    query: {
      type: data,
    },
    onResponse({ request, response, options }) {
      let getData = response._data
      console.log(getData);
      isBusy.value = false
      if (data == 'kontak') {
        content.value = getData?.content
      }
      if (data == 'last-program') {
        lastProgram.value = getData
      }
      if (data == 'program-terbaru') {
        programTerbaru.value = getData
      }
    }
  })
}
getData('kontak')
getData('last-program')
getData('program-terbaru')
</script>

<template>
  <div class="landing-page-wrapper">
    <Navbar :active-id="activeSectionId" />
    <div style="margin-top: 65px;" class="white">
      <VContainer>
        <h3 class="text-h3">Hubungi Kami</h3>
        <VProgressCircular indeterminate color="warning" size="150" v-if="isBusy" />
        <div v-html="content" v-else></div>
      </VContainer>
    </div>
    <Program v-model:lastProgram="lastProgram" v-model:programTerbaru="programTerbaru" />
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
