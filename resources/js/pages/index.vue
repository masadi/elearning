<script setup>
import Footer from '@/views/front-pages/front-page-footer.vue'
import Navbar from '@/views/front-pages/front-page-navbar.vue'
import Galeri from '@/views/pages/Galeri.vue'
import Program from '@/views/pages/Program.vue'
import noimage from '@images/banner/noimage.jpg'
import { register } from 'swiper/element/bundle'
definePage({
  meta: {
    layout: 'blank',
    public: true,
  },
})
const activeSectionId = ref()
register()
const isBusy = ref(true)
const galeri = ref([])
const lastProgram = ref([])
const programTerbaru = ref([])
const slider = ref([])
const dataPtk = ref([])
const visi = ref()
const misi = ref()
const getData = async (data) => {
  await $api(`/frontend/laman`, {
    query: {
      type: data,
    },
    onResponse({ request, response, options }) {
      let getData = response._data
      isBusy.value = false
      if (data == 'galeri') {
        galeri.value = getData
      }
      if (data == 'last-program') {
        lastProgram.value = getData
      }
      if (data == 'program-terbaru') {
        programTerbaru.value = getData
      }
      if (data == 'slider') {
        slider.value = getData
      }
      if (data == 'ptk') {
        dataPtk.value = getData
      }
      if (data == 'visi') {
        visi.value = getData
      }
      if (data == 'misi') {
        misi.value = getData
      }
    }
  })
}
getData('galeri')
getData('last-program')
getData('program-terbaru')
getData('slider')
getData('ptk')
getData('visi')
getData('misi')
</script>

<template>
  <div class="landing-page-wrapper">
    <Navbar :active-id="activeSectionId" />
    <template v-if="isBusy">
      <div style="margin-top: 65px;" class="text-center">
        <VProgressCircular indeterminate color="warning" size="150" />
      </div>
    </template>
    <template v-else>
      <div style="margin-top: 65px;">
        <swiper-container pagination="true" navigation="true" autoplay="true" events-prefix="swiper-">
          <swiper-slide v-for="swiperImg in slider" :key="swiperImg.id">
            <VImg :src="`/storage/images/${swiperImg.gambar}`" />
          </swiper-slide>
        </swiper-container>
      </div>
      <div class="white">
        <VContainer>
          <h3 class="text-h3">Pendidik dan Tenaga Kependidikan</h3>
          <swiper-container pagination-clickable="true" slides-per-view="5" space-between="50" events-prefix="swiper-"
            :breakpoints="{
              1024: {
                slidesPerView: 4,
                spaceBetween: 40,
              },
              768: {
                slidesPerView: 3,
                spaceBetween: 30,
              },
              640: {
                slidesPerView: 2,
                spaceBetween: 20,
              },
              320: {
                slidesPerView: 1,
                spaceBetween: 10,
              },
            }">
            <swiper-slide v-for="ptk in dataPtk" :key="ptk.ptk_id">
              <VCard color="#ECEFF1" class="text-center">
                <VCardItem>
                  <VAvatar size="200" :image="ptk.avatar" />
                </VCardItem>
                <VCardText>
                  <p class="font-weight-bold text-black mb-0">
                    {{ ptk.nama }}
                  </p>
                  <p class="clamp-text text-black mb-0">
                    {{ ptk.jabatan }}
                  </p>
                </VCardText>
              </VCard>
            </swiper-slide>
          </swiper-container>
          <VCard color="#fff" class="mt-5 mb-5 pa-5">
            <VCardItem class="text-center">
              <VCardTitle class="text-black text-h2">Visi dan Misi</VCardTitle>
            </VCardItem>
            <VCardText class="text-black">
              <v-row no-gutters>
                <v-col cols="12" sm="6" md="6" class="me-8">
                  <v-img fluid :src="`/storage/images/${visi?.image}`" alt="visi" v-if="visi?.image" />
                  <v-img fluid :src="noimage" alt="visi" v-else />
                </v-col>
                <v-col cols="12" sm="6" md="5">
                  <div class="text-h3 text-black">Visi</div>
                  <div class="text-h5 text-black" v-html="visi?.content"></div>
                </v-col>
              </v-row>
            </VCardText>
            <VCardText class="text-black">
              <v-row no-gutters>
                <v-col cols="12" sm="6" md="5" class="me-8">
                  <div class="text-h3 text-black">Misi</div>
                  <div class="text-h5 text-black" v-html="misi?.content"></div>
                </v-col>
                <v-col cols="12" sm="6" md="6">
                  <v-img fluid :src="`/storage/images/${misi?.image}`" alt="misi" v-if="misi?.image" />
                  <v-img fluid :src="noimage" alt="misi" v-else />
                </v-col>
              </v-row>
            </VCardText>
          </VCard>
        </VContainer>
      </div>
      <Galeri v-model:galeri="galeri" />
      <Program v-model:lastProgram="lastProgram" v-model:programTerbaru="programTerbaru" />
    </template>
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
