<script setup>
import Footer from '@/views/front-pages/front-page-footer.vue'
import Navbar from '@/views/front-pages/front-page-navbar.vue'
import swiper12 from '@images/banner/banner-12.jpg'
import swiper20 from '@images/banner/banner-20.jpg'
import swiper7 from '@images/banner/banner-7.jpg'
import swiper8 from '@images/banner/banner-8.jpg'
import swiper9 from '@images/banner/banner-9.jpg'
import banner from '@images/front-pages/misc/checkout-image.png'
import { register } from 'swiper/element/bundle'
definePage({
  meta: {
    layout: 'blank',
    public: true,
  },
})
const activeSectionId = ref()
register()
</script>

<template>
  <div class="landing-page-wrapper">
    <Navbar :active-id="activeSectionId" />
    <div style="margin-top: 65px;">
      <swiper-container pagination="true" navigation="true" autoplay="true" events-prefix="swiper-">
        <swiper-slide v-for="swiperImg in [
          swiper12,
          swiper9,
          swiper8,
          swiper7,
          swiper20,
        ]" :key="swiperImg">
          <VImg :src="swiperImg" />
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
          <swiper-slide>
            <VCard color="#ECEFF1" class="text-center">
              <VCardItem>
                <VAvatar size="200" :image="swiper12" />
              </VCardItem>
              <VCardText>
                <p class="font-weight-bold text-black mb-0">
                  Si Fulan, S.Pd.I.
                </p>
                <p class="clamp-text text-black mb-0">
                  Kepala Sekolah
                </p>
              </VCardText>
            </VCard>
          </swiper-slide>
          <swiper-slide>
            <VCard color="#ECEFF1" class="text-center">
              <VCardItem>
                <VAvatar size="200" :image="swiper12" />
              </VCardItem>
              <VCardText>
                <p class="font-weight-bold text-black mb-0">
                  Si Fulan, S.Kom.
                </p>
                <p class="clamp-text text-black mb-0">
                  Jabatan A
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
              <v-col class="me-8">
                <v-img fluid :src="banner" alt="" />
              </v-col>
              <v-col>
                <div class="text-h3 text-black">Visi</div>
                <p class="text-h5 text-black">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                  Lorem
                  Ipsum has been the
                  industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
                  scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap
                  into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the
                  release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing
                  software like Aldus PageMaker including versions of Lorem Ipsum.</p>
              </v-col>
            </v-row>
          </VCardText>
          <VCardText class="text-black">
            <v-row no-gutters>
              <v-col class="me-8">
                <div class="text-h3 text-black">Misi</div>
                <p class="text-h5 text-black">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                  Lorem
                  Ipsum has been the
                  industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
                  scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap
                  into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the
                  release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing
                  software like Aldus PageMaker including versions of Lorem Ipsum.</p>
              </v-col>
              <v-col>
                <v-img fluid :src="banner" alt="" />
              </v-col>
            </v-row>
          </VCardText>
        </VCard>
      </VContainer>
    </div>
    <div class="blue-lighten-5">
      <VContainer>
        <h3 class="text-h3 text-black">Galeri Kegiatan</h3>
      </VContainer>
      <VContainer fluid>
        <swiper-container navigation="true" centered-slides="true" space-between="30" slides-per-view="5"
          events-prefix="swiper-" :injectStyles="[
            `
        .swiper-button-next, .swiper-button-prev{
          background: rgb(var(--v-theme-primary)) !important;
          color: #fff !important;
          padding-inline: 0.45rem !important;
          padding-block: 0.45rem !important;
          inline-size: 1rem !important;
          block-size: 1rem !important;
          border-radius: 50%
        }
        `,
          ]" :breakpoints="{
            992: {
              slidesPerView: 4,
              spaceBetween: 30,
            },
            780: {
              slidesPerView: 3,
              spaceBetween: 30,
            },
            460: {
              slidesPerView: 2,
              spaceBetween: 20,
            },
          }">
          <template v-for="i in 10">
            <swiper-slide>
              <v-card max-width="400" color="#fff">
                <v-img class="align-end text-white" height="200" src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
                  cover>
                  <v-card-title>Top 10 Australian beaches</v-card-title>
                </v-img>
                <v-card-text>
                  <v-row no-gutters>
                    <v-col>
                      <VIcon icon="tabler-calendar-week" /> 27 Oktober 2023
                    </v-col>
                    <v-col>
                      <VIcon icon="tabler-map-pin" /> Lapangan Bola
                    </v-col>
                  </v-row>
                </v-card-text>
                <VCardText class="justify-center">
                  <VBtn variant="elevated">
                    Lihat
                  </VBtn>
                </VCardText>
              </v-card>
            </swiper-slide>
          </template>
        </swiper-container>
      </VContainer>
    </div>
    <div class="white">
      <VContainer>
        <v-row no-gutters class="mb-8">
          <v-col>
            <h3 class="text-h3 text-black">Program Kegiatan</h3>
          </v-col>
          <v-spacer></v-spacer>
          <v-col class="text-right">
            <VBtn variant="elevated">
              Lihat Program Lainnya
            </VBtn>
          </v-col>
        </v-row>
        <v-row no-gutters>
          <v-col class="me-4">
            <v-card color="#fff">
              <v-img class="align-end text-white" height="200" src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
                cover>
              </v-img>
              <v-card-text>
                <v-card-sub-title>Kategori</v-card-sub-title>
                <v-card-title class="text-black ps-0 pb-2"><a href="#">Judul Kegiatan</a></v-card-title>
                <v-card-sub-title class="mt-2">27 Oktober 2023</v-card-sub-title>
              </v-card-text>
            </v-card>
          </v-col>
          <v-col class="me-4">
            <v-card color="#fff">
              <v-img class="align-end text-white" height="200" src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
                cover>
              </v-img>
              <v-card-text>
                <v-card-sub-title>Kategori</v-card-sub-title>
                <v-card-title class="text-black ps-0 pb-2"><a href="#">Judul Kegiatan</a></v-card-title>
                <v-card-sub-title class="mt-2">27 Oktober 2023</v-card-sub-title>
              </v-card-text>
            </v-card>
          </v-col>
          <v-col>
            <template v-for="i in 3">
              <div class="mb-8">
                <a href="#">
                  <v-row no-gutters>
                    <v-col cols="4" class="me-4">
                      <v-card color="#BBDEFB" class="text-center">
                        <v-card-title style="color:#0D47A1" class="text-h3 pb-0">10</v-card-title>
                        <v-card-title style="color:#0D47A1" class="pt-0">Oktober</v-card-title>
                      </v-card>

                    </v-col>
                    <v-col cols="7">
                      <v-card class="no-elevated">
                        <VCardText class="text-black pt-4">
                          <p class="font-weight-bold text-black mb-0">
                            <a href="#">Kategori</a>
                          </p>
                          <p class="clamp-text text-black mb-0">
                            Judul Kegiatan
                          </p>
                        </VCardText>
                      </v-card>
                    </v-col>
                  </v-row>
                </a>
              </div>
            </template>
          </v-col>
        </v-row>
      </VContainer>
    </div>
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
