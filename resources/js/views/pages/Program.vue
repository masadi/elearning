<script setup>
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
