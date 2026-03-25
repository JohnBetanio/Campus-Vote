<template>
  <div class="auth-container auth-pro">
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>
    <div class="bg-grid"></div>
    <slot />
  </div>
</template>

<style scoped>
.auth-pro {
  position: relative;
  overflow: hidden;
  background:
    radial-gradient(circle at 15% 20%, rgba(56, 189, 248, 0.18), transparent 36%),
    radial-gradient(circle at 85% 80%, rgba(16, 185, 129, 0.18), transparent 38%),
    linear-gradient(135deg, #0f172a 0%, #1e293b 45%, #0b2534 100%);
}

.bg-grid {
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.06) 1px, transparent 1px);
  background-size: 42px 42px;
  opacity: 0.14;
  pointer-events: none;
}

.bg-orb {
  position: absolute;
  border-radius: 999px;
  filter: blur(50px);
  opacity: 0.32;
  pointer-events: none;
}

.orb-1 {
  width: 360px;
  height: 360px;
  left: -110px;
  top: -90px;
  background: #14b8a6;
}

.orb-2 {
  width: 320px;
  height: 320px;
  right: -90px;
  bottom: -70px;
  background: #0ea5e9;
}
</style>
