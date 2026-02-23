<template>
  <div class="fact-card">
    <div class="fact-icon">💡</div>
    <div class="fact-content">
      <span class="fact-label">Это интересно</span>
      <p class="fact-text">{{ text }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: ['text']
}
</script>

<style scoped>
.fact-card {
  background: #fffaf0;
  border-left: 5px solid #ffa117;
  border-radius: 20px;
  padding: 24px;
  margin: 30px 0;
  display: flex;
  gap: 16px;
  align-items: flex-start;
}

.fact-icon {
  font-size: 24px;
}

.fact-label {
  display: block;
  font-weight: 800;
  color: #ffa117;
  text-transform: uppercase;
  font-size: 12px;
  margin-bottom: 4px;
  letter-spacing: 0.5px;
}

.fact-text {
  margin: 0;
  color: #4a3f35;
  line-height: 1.5;
  font-size: 16px;
  font-weight: 700;
}


@media (max-width: 768px) {
  .fact-card {
    padding: 16px;
    gap: 12px;
  }
  .fact-icon {
    font-size: 20px;
  }
  .fact-text {
    font-size: 14px;
  }
}
</style>