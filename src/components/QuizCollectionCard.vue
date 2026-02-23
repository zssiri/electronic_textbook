<template>
  <div class="quiz-collection-card">
    <div class="collection-header">
      <div class="collection-badge">Итоговый тест</div>
      <h3>{{ title }}</h3>
      <div class="progress-bar">
        <div class="progress-fill" :style="{ width: progress + '%' }"></div>
      </div>
    </div>

    <div v-if="!showResults" class="question-section">
      <div class="q-info">Вопрос {{ currentIndex + 1 }} из {{ items.length }}</div>
      <p class="q-text">{{ currentQuestion.question }}</p>

      <div class="options-grid">
        <button v-for="(option, idx) in currentQuestion.options" :key="idx" @click="handleAnswer(idx)"
          :class="getOptionClass(idx)" :disabled="isAnswered">
          <span class="letter">{{ getLetter(idx) }}</span>
          {{ option }}
        </button>
      </div>

      <div v-if="isAnswered" class="navigation">
        <button @click="nextStep" class="btn-next">
          {{ isLast ? 'Посмотреть результат' : 'Дальше' }} →
        </button>
      </div>
    </div>

    <div v-else class="results-view">
      <div class="result-emoji">{{ resultEmoji }}</div>
      <h4>Тест завершен!</h4>
      <p class="score">Ваш результат: <strong>{{ score }}</strong> из <strong>{{ items.length }}</strong></p>
      <button @click="restart" class="btn-restart">Пройти еще раз</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    items: Array
  },
  data() {
    return {
      currentIndex: 0,
      score: 0,
      selectedOption: null,
      isAnswered: false,
      showResults: false
    }
  },
  computed: {
    currentQuestion() { return this.items[this.currentIndex]; },
    progress() { return (this.currentIndex / this.items.length) * 100; },
    isLast() { return this.currentIndex === this.items.length - 1; },
    resultEmoji() {
      const ratio = this.score / this.items.length;
      if (ratio === 1) return '🥇';
      if (ratio >= 0.7) return '🥈';
      return '📚';
    }
  },
  methods: {
    getLetter(i) { return String.fromCharCode(65 + i); },
    handleAnswer(idx) {
      this.selectedOption = idx;
      this.isAnswered = true;
      if (idx === this.currentQuestion.correct) this.score++;
    },
    getOptionClass(idx) {
      if (!this.isAnswered) return '';
      if (idx === this.currentQuestion.correct) return 'correct';
      if (idx === this.selectedOption) return 'wrong';
      return 'missed';
    },
    nextStep() {
      if (this.isLast) {
        this.showResults = true;
      } else {
        this.currentIndex++;
        this.isAnswered = false;
        this.selectedOption = null;
      }
    },
    restart() {
      this.currentIndex = 0;
      this.score = 0;
      this.isAnswered = false;
      this.selectedOption = null;
      this.showResults = false;
    }
  }
}
</script>

<style scoped>
.quiz-collection-card {
  background: #fff;
  border-radius: 28px;
  padding: 35px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
  margin: 40px 0;
  border: 1px solid #f0f0f2;
}

.collection-badge {
  display: inline-block;
  background: #5e5ce6;
  color: white;
  padding: 5px 15px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  margin-bottom: 15px;
}

.progress-bar {
  height: 6px;
  background: #f2f2f7;
  border-radius: 3px;
  margin: 20px 0;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: #5e5ce6;
  transition: width 0.4s ease;
}

.q-info {
  color: #86868b;
  font-size: 13px;
  margin-bottom: 10px;
}

.q-text {
  font-size: 22px;
  font-weight: 700;
  margin-bottom: 30px;
  line-height: 1.3;
}

.options-grid {
  display: grid;
  gap: 12px;
}

button {
  padding: 18px;
  border-radius: 16px;
  border: 1px solid #e5e5ea;
  background: #fff;
  text-align: left;
  cursor: pointer;
  display: flex;
  gap: 15px;
  font-size: 16px;
  transition: all 0.2s;
}

.letter {
  font-weight: 800;
  color: #aeaeb2;
}

button.correct {
  background: #e1f7e7;
  border-color: #34c759;
}

button.wrong {
  background: #ffebeb;
  border-color: #ff3b30;
}

.btn-next,
.btn-restart {
  background: #007aff;
  color: white;
  border: none;
  width: 100%;
  justify-content: center;
  margin-top: 25px;
  font-weight: 600;
}

.results-view {
  text-align: center;
  padding: 20px 0;
}

.result-emoji {
  font-size: 60px;
  margin-bottom: 15px;
}

.score {
  font-size: 18px;
  margin: 15px 0;
}

@media (max-width: 768px) {
  .quiz-collection-card {
    padding: 20px;
  }
  .q-text {
    font-size: 18px;
    margin-bottom: 20px;
  }
  .options-grid {
    grid-template-columns: 1fr; 
    gap: 10px;
  }
  .options-grid button {
    padding: 14px;
    font-size: 15px; 
  }
}
</style>