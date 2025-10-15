<script setup>
const props = defineProps({
  lastProgram: {
    type: Array,
    required: true,
  },
  programTerbaru: {
    type: Array,
    required: true,
  },
})
</script>
<template>
  <div class="white">
    <VContainer>
      <v-row no-gutters class="mb-8">
        <v-col>
          <h3 class="text-h3 text-black">Program Kegiatan</h3>
        </v-col>
        <v-spacer></v-spacer>
        <v-col class="text-right">
          <VBtn variant="elevated" :to="{ name: 'page-program' }">
            Lihat Program Lainnya
          </VBtn>
        </v-col>
      </v-row>
      <v-row no-gutters>
        <v-col class="me-4" v-for="program in lastProgram" :key="program.id">
          <v-card color="#fff">
            <v-img class="align-end text-white" height="200" :src="`/storage/images/${program.foto}`" cover>
            </v-img>
            <v-card-text>
              <v-card-title class="text-black pb-2">
                <RouterLink :to="{ name: 'program-slug', params: { slug: program.slug } }">{{ program.nama }}
                </RouterLink>
              </v-card-title>
              <v-card-subtitle class="text-black mt-2">{{ program.tanggal_indo }}</v-card-subtitle>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col>
          <template v-for="terbaru in programTerbaru" :key="terbaru.id">
            <div class="mb-8">
              <RouterLink :to="{ name: 'program-slug', params: { slug: terbaru.slug } }">
                <v-row no-gutters>
                  <v-col cols="4" class="me-4">
                    <v-card color="#BBDEFB" class="text-center">
                      <v-card-title style="color:#0D47A1" class="text-h3 pb-0">{{ terbaru.tanggal }}</v-card-title>
                      <v-card-title style="color:#0D47A1" class="pt-0">{{ terbaru.bulan }}</v-card-title>
                    </v-card>

                  </v-col>
                  <v-col cols="7">
                    <v-card class="no-elevated">
                      <VCardText class="text-black pt-4">
                        <p class="font-weight-bold text-black mb-0">
                          <a href="#">Kategori</a>
                        </p>
                        <p class="clamp-text text-black mb-0">
                          {{ terbaru.nama }}
                        </p>
                      </VCardText>
                    </v-card>
                  </v-col>
                </v-row>
              </RouterLink>
            </div>
          </template>
        </v-col>
      </v-row>
    </VContainer>
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
