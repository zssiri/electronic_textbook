<template>
  <div v-if="currentSection" class="reader-page-wrapper">
    <div class="fullscreen-bg" :style="{ backgroundImage: `url('${resolveImage(currentSectionImage)}')` }"></div>

    <div class="reader-area">
      <router-link to="/" class="btn-home">← На главную</router-link>

      <div v-for="topic in currentSection.topics" 
           :key="topic.topic_id" 
           :id="'topic-' + topic.topic_id"
           class="topic-block">
        
        <span class="badge">Тема {{ topic.topic_id }}</span>

        <div class="content-body">
          <h2>{{ topic.topic_title }}</h2>

          <ObjectivesCard v-if="topic.objectives" :objectives="topic.objectives" />

          <div v-for="(block, index) in topic.content" :key="'block-' + index">
            <template v-if="block && block.type">
              <p v-if="block.type === 'text'" class="text-paragraph">
                {{ block.value }}
              </p>

              <TheoryCard v-if="block.type === 'theory'" :theory="block" />
              
              <DefinitionCard v-if="block.type === 'definition'">
                {{ block.value }}
              </DefinitionCard>
              
              <FactCard v-if="block.type === 'fact'" :text="block.value" />
              
              <TaskCard v-if="block.type === 'task'" :task="block" />
              
              <QuizCard v-if="block.type === 'quiz' || block.type === 'test'" :quiz="block" />
              
              <ComplexTestCard v-if="block.type === 'complex-test'" :testData="block" />
              
              <QuizCollectionCard v-if="block.type === 'quiz-collection'" 
                                  :title="block.title || 'Тесты'" 
                                  :items="block.items || block.value" />
              
              <FormulaCard v-if="block.type === 'formula'" :value="block.value" />
            </template>
          </div>

          <div v-if="topic.topic_image" class="topic-image-container">
            <img :src="resolveImage(topic.topic_image)" :alt="topic.topic_title" />
          </div>

          <div class="extra-assignments" v-if="topic.assignments">
            <TaskCard v-for="(t, i) in topic.assignments" :key="'assign-' + i" :task="t" />
          </div>

          <QuestionsCard v-if="topic.questions" :questions="topic.questions" />
        </div>

        <hr class="topic-divider" />
      </div>
    </div>
  </div>
  <div v-else class="loading-state">Загрузка раздела...</div>
</template>

<script>
import lessonOne from '@/data/lessonOne.json'
import lessonTwo from '@/data/lessonTwo.json'
import lessonThree from '@/data/lessonThree.json'
import lessonFour from '@/data/lessonFour.json'
import lessonFive from '@/data/lessonFive.json'
import lessonSix from '@/data/lessonSix.json'
import lessonSeven from '@/data/lessonSeven.json'
import lessonEight from '@/data/lessonEight.json'
import lessonNine from '@/data/lessonNine.json'
import lessonTen from '@/data/lessonTen.json'
import lessonEleven from '@/data/lessonElewen.json'

import TaskCard from '@/components/TaskCard.vue'
import QuizCard from '@/components/QuizCard.vue'
import QuizCollectionCard from '@/components/QuizCollectionCard.vue'
import QuestionsCard from '@/components/QuestionsCard.vue'
import DefinitionCard from '@/components/DefinitionCard.vue'
import FactCard from '@/components/FactCard.vue'
import ObjectivesCard from '@/components/ObjectivesCard.vue'
import ComplexTestCard from '@/components/ComplexTestCard.vue'
import FormulaCard from '@/components/FormulaCard.vue'
import TheoryCard from '@/components/TheoryCard.vue'

export default {
  name: 'PageView',
  props: ['id'],

  components: {
    DefinitionCard, FactCard, QuizCard, QuizCollectionCard,
    TaskCard, ObjectivesCard, QuestionsCard, ComplexTestCard,
    FormulaCard, TheoryCard
  },

  data() {
    return {
      allLessons: [
        lessonOne, lessonTwo, lessonThree, lessonFour, lessonFive,
        lessonSix, lessonSeven, lessonEight, lessonNine, lessonTen, lessonEleven
      ]
    }
  },

  computed: {
    currentSection() {
      if (!this.id) return null;
      const sectionId = parseInt(this.id.split('.')[0]);
      return this.allLessons.find(s => s.section_id === sectionId);
    },

    currentSectionImage() {
      if (!this.currentSection) return '';
      const map = {
        1: 'psyhology.png', 2: 'buisnes-idia.png', 3: 'buisnes-model.png',
        4: 'marketing-and-explore.png', 5: 'strategy.png', 6: 'main-resources.png',
        7: 'operacionaya-deyatelnost.png', 8: 'finans-model.png',
        9: 'investiciya-and-progres.png', 10: 'strategy-of-progres.png', 11: 'otvetstvennost.png'
      };
      return map[this.currentSection.section_id] || '';
    }
  },

  methods: {
    resolveImage(file) {
      return `${import.meta.env.BASE_URL}images/${file}`;
    },

    scrollToTopic(topicId) {
      this.$nextTick(() => {
        const el = document.getElementById(`topic-${topicId}`);
        const container = document.querySelector('.scroll-container');
        if (el && container) {
          container.scrollTo({
            top: el.offsetTop - 20,
            behavior: 'smooth'
          });
        }
      });
    }
  },

  watch: {
    id(newId) {
      if (newId) this.scrollToTopic(newId);
    }
  },

  mounted() {
    if (this.id) {
      setTimeout(() => this.scrollToTopic(this.id), 500);
    }
  }
}
</script>

<style scoped>
.btn-home {
  display: inline-block;
  margin-top: 20px;
  margin-bottom: 60px;
  color: #007aff;
  text-decoration: none;
  font-weight: 600;
  padding: 8px 16px;
  background: #f0f7ff;
  border-radius: 8px;
  transition: 0.3s;
}

.btn-home:hover {
  background: #e0efff;
}

.topic-block {
  padding-top: 20px;
  margin-bottom: 40px;
  font-size: 16px;
  font-weight: 500;
}

.topic-divider {
  border: 0;
  height: 1px;
  background: #eee;
  margin: 60px 0;
}

.badge {
  background: #007aff;
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 18px;
}

.topic-image-container {
  margin: 25px 0;
  width: 100%;
  display: flex;
  justify-content: center;
}

.topic-illustration {
  max-width: 100%;
  height: auto;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.text-paragraph {
  line-height: 1.7;
  font-size: 18px;
  color: #3a3a3c;
  margin-bottom: 24px;
  max-width: 750px;
  -webkit-font-smoothing: antialiased;
  letter-spacing: -0.011em;
  text-indent: 1.5em;
}

.content-body h2 {
  margin-top: 10px;
  margin-bottom: 30px;
  font-size: 32px;
  color: #1d1d1f;
  letter-spacing: -0.02em;
}

.text-paragraph strong {
  color: #000;
  font-weight: 700;
}

.reader-page-wrapper {
  position: relative;
  width: 100%;
}

.fullscreen-bg {
  position: fixed;
  top: 0;
  left: 0;
  width: 110%;
  height: 110%;
  background-repeat: no-repeat;
  background-position: right;
  background-size: contain;
  opacity: 0.07;
  filter: grayscale(100%);
  z-index: -1;
  pointer-events: none;
}

.reader-area {
  position: relative;
  z-index: 1;
  max-width: 800px;
  margin: 0 auto;
  padding: 40px 20px;
  background: transparent;
}

@media (max-width: 768px) {
  .reader-area {
    padding: 20px 15px;
    margin: 0;
    max-width: 100%;
  }

  .btn-home {
    margin-top: 10px;
    margin-bottom: 30px;
    font-size: 14px;
    padding: 6px 12px;
  }

  .content-body h2 {
    font-size: 24px;
    margin-bottom: 20px;
    line-height: 1.2;
  }

  .text-paragraph {
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 16px;
    text-indent: 1em;
  }

  .badge {
    font-size: 14px;
    padding: 3px 10px;
  }

  .topic-image-container {
    margin: 15px 0;
  }

  .topic-illustration {
    border-radius: 8px;
  }

  .topic-divider {
    margin: 40px 0;
  }

  .fullscreen-bg {
    width: 150%;
    height: 100%;
    background-position: center right;
    opacity: 0.04;
  }

  .fullscreen-bg {
    width: 100%;
    height: 100vh;
    background-position: center 20%;
    background-size: 60%;
    opacity: 0.04;
  }

  .reader-area {
    padding: 20px 15px;
    margin: 0;
  }
}


@media (max-width: 480px) {
  .content-body h2 {
    font-size: 20px;
  }

  .text-paragraph {
    font-size: 15px;
  }

  .fullscreen-bg {
    background-size: 100%;
    background-position: center 15%;
  }
}
</style>