<script setup>
import { register } from 'swiper/element/bundle';
register()
const props = defineProps({
  galeri: {
    type: Array,
    required: true,
  },
})
</script>
<template>
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
        <template v-for="gal in galeri" :key="gal.id">
          <swiper-slide>
            <v-card max-width="400" color="#fff">
              <v-img class="align-end text-white" height="200"
                :src="`https://drive.google.com/thumbnail?id=${gal.foto_id_gdrive}`" cover>
                <v-card-title>{{ gal.nama }}</v-card-title>
              </v-img>
              <v-card-text>
                <v-row no-gutters>
                  <v-col>
                    <VIcon icon="tabler-calendar-week" /> {{ gal.tanggal_indo }}
                  </v-col>
                  <v-col>
                    <VIcon icon="tabler-map-pin" /> {{ gal.lokasi }}
                  </v-col>
                </v-row>
              </v-card-text>
              <VCardText class="justify-center">
                <VBtn variant="elevated" :to="{ name: 'galeri-slug', params: { slug: gal.slug } }">
                  Lihat
                </VBtn>
              </VCardText>
            </v-card>
          </swiper-slide>
        </template>
      </swiper-container>
    </VContainer>
  </div>
</template>
