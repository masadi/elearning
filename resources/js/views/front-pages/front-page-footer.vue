<script setup>
import footerDarkBg from '@images/front-pages/backgrounds/footer-bg-dark.png'
import footerLightBg from '@images/front-pages/backgrounds/footer-bg-light.png'
import driveIcon from '@images/front-pages/landing-page/drive-icon.png'
import sourceForge from '@images/front-pages/landing-page/sourceforge-icon.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
const footerBg = useGenerateImageVariant(footerLightBg, footerDarkBg)

const pagesList = [
  {
    name: 'Tentang Kami',
    to: { name: 'page-tentang' },
  },
  {
    name: 'Galeri',
    to: { name: 'page-galeri' },
  },
  {
    name: 'Program',
    to: { name: 'page-program' },
  },
  {
    name: 'Kontak Kami',
    to: { name: 'page-kontak' },
  },
  {
    name: 'Admin',
    to: { name: 'dashboard' },
  },
]

const demoList = [
  {
    title: 'Kemendikdasmen RI',
    to: 'https://kemendikdasmen.go.id/',
  },
  {
    title: 'Direktorat GTK',
    to: 'https://gtk.dikdasmen.go.id/',
  },
  {
    title: 'Dapodik',
    to: '#https://dapo.kemendikdasmen.go.id/',
  },
  {
    title: 'SDM Pusdatin',
    to: 'https://sdm.data.kemdikbud.go.id/',
  },
  {
    title: 'CE. Sejahtera',
    to: 'https://mas-adi.net',
  },
]
</script>

<template>
  <div class="footer">
    <div class="footer-top pt-11" :style="{ 'background-image': `url(${footerBg})` }">
      <VContainer>
        <VRow>
          <!-- 👉 Footer  -->
          <VCol cols="12" md="5">
            <div class="mb-4" :class="$vuetify.display.smAndDown ? 'w-100' : 'w-75'">
              <div class="app-logo mb-6">
                <VNodeRenderer :nodes="themeConfig.app.logo" />
                <h1 class="app-logo-title text-white">
                  {{ themeConfig.app.title }}
                </h1>
              </div>

              <div class="mb-6" :class="$vuetify.theme.current.dark ? 'text-body-1' : 'text-white-variant'">
                Aplikasi E-Learning PKBM adalah platform terpadu yang menyajikan profil, visi, dan misi lembaga,
                dilengkapi dengan laporan kegiatan serta galeri foto-foto pembelajaran daring, sekaligus berfungsi
                sebagai pusat akses ke seluruh modul interaktif dan bank soal ujian bagi warga belajar.
              </div>
            </div>
          </VCol>

          <!-- 👉 Demos -->
          <VCol md="2" sm="4" xs="6">
            <div class="footer-links">
              <h6 class="footer-title text-h6 mb-6">
                Link
              </h6>
              <ul style="list-style: none;">
                <li v-for="(item, index) in demoList" :key="index" class="mb-4">
                  <a :href="item.to" target="_blank" rel="noopener noreferrer"
                    :class="$vuetify.theme.current.dark ? 'text-body-1' : 'text-white-variant'">
                    {{ item.title }}
                  </a>
                </li>
              </ul>
            </div>
          </VCol>

          <!-- 👉 Pages  -->
          <VCol md="2" sm="4" xs="6">
            <div class="footer-links">
              <h6 class="footer-title text-h6 mb-6">
                Laman
              </h6>
              <ul style="list-style: none;">
                <li v-for="(item, index) in pagesList" :key="index" class="mb-4">
                  <RouterLink :class="$vuetify.theme.current.dark ? 'text-body-1' : 'text-white-variant'" class="me-2"
                    :to="item.to">
                    {{ item.name }}
                  </RouterLink>
                  <template v-if="item.isNew">
                    <VChip color="primary" variant="elevated" label size="small">
                      New
                    </VChip>
                  </template>
                </li>
              </ul>
            </div>
          </VCol>

          <!-- 👉 Download App -->
          <VCol cols="12" md="3" sm="4">
            <div>
              <h6 class="footer-title text-h6 mb-6">
                Unduh Aplikasi Kami
              </h6>

              <div>
                <VBtn v-for="(item, index) in [
                  { image: driveIcon, store: 'Google Drive', href: themeConfig.app.link_drive },
                  { image: sourceForge, store: 'Source Forge', href: themeConfig.app.link_sc },
                ]" :key="index" :href="item.href" target="_blank" color="#282c3e" height="56" class="mb-4 d-block">
                  <template #default>
                    <div class="d-flex align-center gap-x-12 footer-logo-buttons">
                      <VImg :src="item.image" height="40" width="40" class="mt-2" />
                      <div class="d-flex flex-column justify-content-start">
                        <div :class="$vuetify.theme.current.dark ? 'text-body-2' : 'text-white-variant text-body-2'">
                          Download on the
                        </div>
                        <h6 class="text-h6 text-start"
                          :class="$vuetify.theme.current.dark ? 'text-body-1' : 'footer-title'">
                          {{ item.store }}
                        </h6>
                      </div>
                    </div>
                  </template>
                </VBtn>
              </div>
            </div>
          </VCol>
        </VRow>
      </VContainer>
    </div>

    <!-- 👉 Footer Line -->
    <div class="footer-line w-100">
      <VContainer>
        <div class="d-flex justify-space-between flex-wrap gap-y-5 align-center">
          <div class="text-body-1 text-white-variant text-wrap me-4">
            &copy;

            {{ new Date().getFullYear() }}
            <a href="https://mas-adi.net/" target="_blank" rel="noopener noreferrer"
              class="font-weight-bold ms-1 text-white">{{ themeConfig.app.title }}</a>
          </div>

          <div class="d-flex gap-x-6">
            <template v-for="(item, index) in [
              { title: 'github', icon: 'tabler-brand-github-filled', href: 'https://github.com/masadi' },
              { title: 'facebook', icon: 'tabler-brand-facebook-filled', href: 'https://www.facebook.com/adidev.web.id/' },
              { title: 'google', icon: 'tabler-brand-youtube-filled', href: 'https://www.youtube.com/@cedutorial3794' },
            ]" :key="index">
              <a :href="item.href" target="_blank" rel="noopener noreferrer">
                <VIcon :icon="item.icon" size="16" color="white" />
              </a>
            </template>
          </div>
        </div>
      </VContainer>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.footer-title {
  color: rgba(255, 255, 255, 92%);
}

.footer-top {
  background-size: cover;
  color: #fff;
}

.footer-links {

  .text-white-variant,
  .text-body-1 {
    &:hover {
      color: #fff;
    }
  }
}

.footer-line {
  background: #282c3e;
}
</style>

<style lang="scss">
.subscribe-form {
  .v-label {
    color: rgba(225, 222, 245, 90%) !important;
  }

  .v-field {
    border-end-end-radius: 0;
    border-end-start-radius: 10px;
    border-start-end-radius: 0;
    border-start-start-radius: 10px;

    input.v-field__input::placeholder {
      color: rgba(225, 222, 245, 40%) !important;
    }

    input.v-field__input {
      color: rgba(255, 255, 255, 78%);
    }
  }
}

.footer {
  border-radius: 50%;

  @media (min-width: 600px) and (max-width: 960px) {
    .v-container {
      padding-inline: 2rem !important;
    }

    .footer-logo-buttons {
      gap: 0.75rem;
    }
  }
}
</style>
