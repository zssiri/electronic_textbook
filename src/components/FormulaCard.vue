<template>
  <div class="formula-card">
    <div class="formula-badge">Формула расчёта</div>
    <div class="formula-display">
      <div v-if="isFraction" class="fraction-wrapper">
        <span class="formula-result">{{ resultPart }} = </span>
        <div class="fraction">
          <span class="numerator">{{ numerator }}</span>
          <span class="denominator">{{ denominator }}</span>
        </div>
      </div>
      <div v-else class="simple-formula">
        {{ value }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: String,
      required: true
    }
  },
  computed: {
    // Проверяем, является ли формула дробью (LaTeX \frac)
    isFraction() {
      return this.value.includes('\\frac');
    },
    // Извлекаем левую часть (например, CAC)
    resultPart() {
      return this.value.split('=')[0].trim();
    },
    // Извлекаем числитель
    numerator() {
      const match = this.value.match(/\{([^}]+)\}\{([^}]+)\}/);
      return match ? match[1].replace(/\\ /g, ' ') : '';
    },
    // Извлекаем знаменатель
    denominator() {
      const match = this.value.match(/\{([^}]+)\}\{([^}]+)\}/);
      return match ? match[2].replace(/\\ /g, ' ') : '';
    }
  }
}
</script>

<style scoped>
.formula-card {
  background: #ffffff;
  border: 1px solid #eef0f2;
  border-radius: 24px;
  padding: 30px;
  margin: 30px 0;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.formula-badge {
  position: absolute;
  top: -10px;
  left: 30px;
  background: #007aff;
  color: white;
  padding: 4px 12px;
  border-radius: 10px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
}

.formula-display {
  font-size: 22px;
  color: #1d1d1f;
  font-family: "SF Mono", "Monaco", "Courier New", monospace;
  width: 100%;
}

.fraction-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 15px;
}

.formula-result {
  font-weight: 700;
  color: #007aff;
}

.fraction {
  display: inline-flex;
  flex-direction: column;
  vertical-align: middle;
  align-items: center;
}

.numerator {
  padding: 0 10px;
  border-bottom: 2px solid #1d1d1f;
  text-align: center;
  padding-bottom: 4px;
}

.denominator {
  padding: 4px 10px 0;
  text-align: center;
}

@media (max-width: 600px) {
  .formula-display {
    font-size: 16px;
  }
}
</style>