<template>
  <router-view />
</template>

<script>
export default {
  name: 'App'
}
</script>

<style>
::selection {
  background-color: rgba(0, 255, 170, 0.15);  
  color: #000;
}

::-moz-selection {
  background-color: rgba(0, 255, 170, 0.15);
  color: #000;
}
</style>