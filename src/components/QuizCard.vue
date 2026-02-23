<template>
  <div class="quiz-card">
    <div class="quiz-header">
      <span class="quiz-badge">Проверь себя</span>
      <p class="quiz-question">{{ question.text }}</p>
    </div>

    <div class="quiz-options">
      <button v-for="(option, index) in question.options" :key="index" @click="selectOption(index)"
        :class="getOptionClass(index)" :disabled="isAnswered">
        <span class="option-letter">{{ getLetter(index) }}</span>
        {{ option }}
      </button>
    </div>

    <transition name="fade">
      <div v-if="isAnswered" class="quiz-feedback">
        <p v-if="isCorrect" class="feedback-correct">✨ Верно! Вы отлично усвоили материал.</p>
        <p v-else class="feedback-wrong">❌ Не совсем так.</p>
        <button @click="resetQuiz" class="reset-btn">Попробовать снова</button>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  props: ['question'],
  data() {
    return {
      selectedOption: null,
      isAnswered: false
    }
  },
  computed: {
    isCorrect() {
      return this.selectedOption === this.question.correct;
    }
  },
  methods: {
    selectOption(index) {
      this.selectedOption = index;
      this.isAnswered = true;
    },
    getOptionClass(index) {
      if (!this.isAnswered) return '';
      if (index === this.question.correct) return 'correct';
      if (index === this.selectedOption && index !== this.question.correct) return 'wrong';
      return 'dimmed';
    },
    getLetter(i) {
      return String.fromCharCode(65 + i);
    },
    resetQuiz() {
      this.selectedOption = null;
      this.isAnswered = false;
    }
  }
}
</script>

<style scoped>
.quiz-card {
  background: #fdfdfd;
  border: 1px solid #eee;
  border-radius: 24px;
  padding: 30px;
  margin: 40px 0;
  text-align: left;
}

.quiz-badge {
  background: #000;
  color: #fff;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 10px;
  font-weight: 800;
  text-transform: uppercase;
}

.quiz-question {
  font-size: 20px;
  font-weight: 700;
  margin: 15px 0 25px;
  line-height: 1.3;
}

.quiz-options {
  display: grid;
  gap: 12px;
}

.quiz-options button {
  display: flex;
  align-items: center;
  padding: 16px;
  background: #fff;
  border: 1px solid #eee;
  border-radius: 14px;
  cursor: pointer;
  font-family: inherit;
  font-size: 15px;
  transition: all 0.2s;
}

.option-letter {
  font-weight: 800;
  margin-right: 15px;
  color: #ccc;
}

.quiz-options button:hover:not(:disabled) {
  border-color: #000;
  transform: translateY(-2px);
}

.quiz-options button.correct {
  background: #e8f5e9;
  border-color: #4caf50;
  color: #2e7d32;
}

.quiz-options button.wrong {
  background: #ffebee;
  border-color: #ef5350;
  color: #c62828;
}

.quiz-options button.dimmed {
  opacity: 0.5;
}

.reset-btn {
  border-radius: 14px;
  padding: 14px;
  border: 1px solid #eee;
  background-color: #e2e2e2;
  cursor: pointer;
  color: #000;
}

.reset-btn:hover {
  background-color: #b9b9b9;

}
</style>