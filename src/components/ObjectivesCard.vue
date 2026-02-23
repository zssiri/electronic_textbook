<template>
  <div class="objectives-card">
    <div class="obj-title">Чему вы научитесь:</div>
    <ul class="obj-list">
      <li v-for="(goal, i) in objectives" :key="i">
        <span class="check-icon">✓</span>
        {{ goal }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    objectives: Array
  }
}
</script>

<style scoped>
.objectives-card {
  background: #ffffff;
  border: 2px dashed #e1e1e1;
  border-radius: 24px;
  padding: 25px;
  margin: 20px 0 40px;
}

.obj-title {
  font-weight: 800;
  font-size: 14px;
  color: #86868b;
  text-transform: uppercase;
  margin-bottom: 15px;
}

.obj-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.obj-list li {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  margin-bottom: 10px;
  font-weight: 500;
  color: #333;
}

.check-icon {
  color: #34c759;
  /* Apple Success Green */
  font-weight: 800;
}
</style>