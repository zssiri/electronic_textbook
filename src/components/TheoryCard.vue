<template>
  <div class="task-card">
    <div class="task-header">
      <div class="task-badge">
        <i class="icon-edit">✎</i>
        <span>Теоретическая информация</span>
      </div>
      <h3 class="task-title">{{ theory.title }}</h3>
    </div>

    <div class="task-content">
      <p class="task-text">{{ theory.text }}</p>
      
      <div v-if="theory.content && theory.content.headers" class="task-table-wrapper">
        <table class="task-table">
          <thead>
            <tr>
              <th v-for="header in theory.content.headers" :key="header">{{ header }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, index) in theory.content.rows" :key="index">
              <td v-for="cell in row" :key="cell">{{ cell }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    theory: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>
.task-card {
  background: #ffffff;
  border: 1px solid #eef0f2;
  border-radius: 24px;
  padding: 32px;
  margin: 40px 0;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
  transition: transform 0.2s ease;
}

.task-card:hover {
  transform: translateY(-4px);
}

.task-header {
  margin-bottom: 20px;
}

.task-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #f0f7ff;
  color: #ff0800;
  padding: 6px 14px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  margin-bottom: 12px;
}

.task-title {
  font-size: 22px;
  font-weight: 800;
  color: #1d1d1f;
  margin: 0;
}

.task-text {
  font-size: 16px;
  line-height: 1.6;
  color: #424245;
  margin-bottom: 24px;
}

.task-table-wrapper {
  overflow-x: auto;
  border-radius: 14px;
  border: 1px solid #eee;
  margin: 20px 0;
}

.task-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

.task-table th {
  background: #f5f5f7;
  padding: 12px;
  text-align: left;
  font-weight: 700;
}

.task-table td {
  padding: 12px;
  border-top: 1px solid #eee;
}

.icon-edit {
  font-style: normal;
}

@media (max-width: 768px) {
  .task-card {
    padding: 20px;
  }

  .task-table-wrapper {
    overflow-x: auto;
    margin: 0 -10px;
    padding: 0 10px;
  }

  .task-table {
    min-width: 450px;
    font-size: 14px;
  }
}
</style>