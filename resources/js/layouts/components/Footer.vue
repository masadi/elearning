<script setup>
import { themeConfig } from '@themeConfig'
</script>
<template>
  <div class="h-100 d-flex align-center justify-md-space-between justify-center">
    <!-- 👉 Footer: left content -->
    <span class="d-flex align-center text-medium-emphasis">
      Copyright &copy;
      {{ new Date().getFullYear() }}
      <a
        :href="themeConfig.app.url"
        target="_blank"
        rel="noopener noreferrer"
        class="text-primary ms-1"
      >{{ themeConfig.app.title }}</a>, All rights Reserved
    </span>
    <!-- 👉 Footer: right content -->
    <span class="d-md-flex gap-x-4 d-none">
      Dev By <a href="https://impactsotf.id" target="noopener noreferrer" class="text-primary">IMPACTSOFT INTEGRA SOLUSI</a>
    </span>
  </div>
</template>
