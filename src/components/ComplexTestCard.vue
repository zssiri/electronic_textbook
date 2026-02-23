<template>
  <div class="complex-test-card">
    <div class="test-header">
      <span class="test-badge">Психологический профиль</span>
      <h3>{{ testData.title }}</h3>
      <p class="instructions">{{ testData.instructions }}</p>
    </div>

    <div class="questions-container">
      <div v-for="q in testData.questions" :key="q.id" class="q-row">
        <span class="q-text">{{ q.id }}. {{ q.text }}</span>
        <div class="q-controls">
          <button @click="setAnswer(q.id, true)" :class="{ active: answers[q.id] === true }">Да</button>
          <button @click="setAnswer(q.id, false)" :class="{ active: answers[q.id] === false }">Нет</button>
        </div>
      </div>
    </div>

    <div v-if="testCompleted" class="results-section">
      <h4>Ваш результат (PAEI):</h4>
      <div class="score-grid">
        <div v-for="(score, type) in finalScores" :key="type" class="score-item">
          <div class="score-circle" :style="{ height: (score * 8) + 'px' }">
            <span class="score-val">{{ score }}</span>
          </div>
          <span class="score-type">{{ type }}</span>
        </div>
      </div>
      <p class="result-interpretation">
        Ваш доминирующий стиль: <strong>{{ dominantStyle }}</strong>
      </p>
      <button @click="resetTest" class="reset-btn">Пройти заново</button>
    </div>

    <div v-else class="test-footer">
      <p>Отвечено: {{ Object.keys(answers).length }} из {{ testData.questions.length }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    testData: { type: Object, required: true }
  },
  data() {
    return {
      answers: {}
    }
  },
  computed: {
    answeredCount() {
      return Object.keys(this.answers).length;
    },
    testCompleted() {
      return this.answeredCount === this.testData.questions.length;
    },
    finalScores() {
      if (!this.testCompleted) return null;

      const scores = { P: 0, A: 0, E: 0, I: 0 };

      for (const [type, questionIds] of Object.entries(this.testData.scoring_keys)) {
        questionIds.forEach(id => {
          if (this.answers[id] === true) {
            scores[type]++;
          }
        });
      }
      return scores;
    },
    dominantStyle() {
      if (!this.finalScores) return '';
      return Object.keys(this.finalScores).reduce((a, b) =>
        this.finalScores[a] >= this.finalScores[b] ? a : b
      );
    }
  },
  methods: {
    setAnswer(id, val) {
      this.answers = { ...this.answers, [id]: val };
    },
    resetTest() {
      this.answers = {};
    }

  }
}
</script>

<style scoped>
.complex-test-card {
  background: #fff;
  border-radius: 30px;
  padding: 40px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
  margin: 40px 0;
}

.test-badge {
  background: #f0f0f5;
  padding: 5px 15px;
  border-radius: 10px;
  font-size: 12px;
  font-weight: 700;
  color: #666;
}

.questions-container {
  margin: 30px 0;
  max-height: 500px;
  overflow-y: auto;
  padding-right: 10px;
}

.questions-container::-webkit-scrollbar {
  width: 6px;
}

.questions-container::-webkit-scrollbar-thumb {
  background: #eee;
  border-radius: 10px;
}

.q-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 0;
  border-bottom: 1px solid #f9f9f9;
}

.q-text {
  font-size: 15px;
  color: #333;
  flex: 1;
  padding-right: 20px;
}

.q-controls {
  display: flex;
  gap: 8px;
}

.q-controls button {
  border: 1px solid #eee;
  background: #fff;
  padding: 8px 15px;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.2s;
}

.q-controls button.active {
  background: #007aff;
  color: #fff;
  border-color: #007aff;
}

.score-grid {
  display: flex;
  justify-content: space-around;
  align-items: flex-end;
  height: 150px;
  margin: 40px 0;
  border-bottom: 2px solid #eee;
}

.score-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 60px;
}

.score-circle {
  background: #007aff;
  width: 40px;
  border-radius: 10px 10px 0 0;
  position: relative;
  transition: height 1s ease;
}

.score-val {
  position: absolute;
  top: -25px;
  width: 100%;
  text-align: center;
  font-weight: 800;
}

.score-type {
  margin-top: 10px;
  font-weight: 700;
}

.reset-btn {
  background: #f0f0f5;
  border: none;
  padding: 12px 25px;
  border-radius: 12px;
  cursor: pointer;
  font-weight: 600;
}
@media (max-width: 768px) {
  .complex-test-card {
    padding: 20px; 
    margin: 20px 0;
  }
  .test-header h3 {
    font-size: 18px; 
  }
  .q-row {
    flex-direction: column; 
    align-items: flex-start;
    gap: 12px;
  }
  .q-controls {
    width: 100%;
    display: grid;
    grid-template-columns: 1fr 1fr; 
  }
  .score-grid {
    gap: 5px; 
  }
}
</style>