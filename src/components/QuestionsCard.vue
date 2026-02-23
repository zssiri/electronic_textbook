<template>
  <div class="questions-box">
    <div class="box-title">
      <i class="icon-help">?</i>
      <h3>Контрольные вопросы</h3>
    </div>
    <ul class="questions-list">
      <li v-for="(q, i) in questions" :key="i">
        <span class="q-num">{{ i + 1 }}</span>
        <p>{{ q }}</p>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: ['questions']
}
</script>

<style scoped>
.questions-box {
  background: #f5f5f7;
  border-radius: 24px;
  padding: 30px;
  margin: 40px 0;
}

.box-title {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
}

.icon-help {
  background: #000;
  color: #fff;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-style: normal;
  font-weight: 800;
}

.questions-list {
  list-style: none;
  padding: 0;
}

.questions-list li {
  display: flex;
  gap: 15px;
  margin-bottom: 15px;
  padding-bottom: 15px;
  border-bottom: 1px solid #e0e0e0;
}

.q-num {
  font-weight: 800;
  color: #000;
}

@media (max-width: 768px) {
  .questions-box {
    padding: 20px;
  }
  .questions-list li {
    gap: 10px;
  }
  .q-num {
    min-width: 24px;
    height: 24px;
    font-size: 12px;
  }
}
</style>