<script setup>
import { register } from 'swiper/element/bundle';
register()
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
</template>
