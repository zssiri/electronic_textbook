<template>
  <div v-if="currentSection" class="reader-page-wrapper">
    <div class="fullscreen-bg" :style="{ backgroundImage: `url(${currentSectionImage})` }"></div>
  </div>

  <div v-if="currentSection" class="reader-area">
    <router-link to="/" class="btn-home">← На главную</router-link>

    <div v-for="topic in currentSection.topics" :key="topic.topic_id" :id="'topic-' + topic.topic_id"
      class="topic-block">

      <span class="badge">Тема {{ topic.topic_id }}</span>

      <div class="content-body">
        <h2>{{ topic.topic_title }}</h2>

        <ObjectivesCard v-if="topic.objectives" :objectives="topic.objectives" />

        <div v-for="(block, index) in topic.content" :key="'block-' + index">

          <template v-if="block && block.type">

            <p v-if="block.type === 'text'" class="text-paragraph">{{ block.value }}</p>

            <TheoryCard v-if="block.type === 'theory'" :theory="block" />

            <DefinitionCard v-if="block.type === 'definition'">
              {{ block.value }}
            </DefinitionCard>

            <FactCard v-if="block.type === 'fact'" :text="block.value" />

            <TaskCard v-if="block.type === 'task'" :task="block" />

            <QuizCard v-if="block.type === 'quiz'" :question="block.question" />

            <ComplexTestCard v-if="block.type === 'complex-test'" :testData="block" />

            <QuizCollectionCard v-if="block.type === 'quiz-collection'" :title="block.title" :items="block.items" />

            <FormulaCard v-if="block.type === 'formula'" :value="block.value" />

          </template>
        </div>

        <div v-if="topic.image" class="topic-image-container">
          <img :src="baseUrl + topic.image.replace(/^\//, '')" :alt="topic.topic_title" class="topic-illustration">
        </div>

        <div class="extra-assignments" v-if="topic.assignments">
          <TaskCard v-for="(t, i) in topic.assignments" :key="'assign-' + i" :task="t" />
        </div>

        <QuestionsCard v-if="topic.questions" :questions="topic.questions" />
      </div>

      <hr class="topic-divider" />
    </div>
  </div>
</template>

<script>
import lessonsData from '@/data/lessons.json'
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
  props: ['id'],
  baseUrl() {
    return import.meta.env.BASE_URL;
  },
  components: {
    DefinitionCard,
    FactCard,
    QuizCard,
    QuizCollectionCard,
    TaskCard,
    ObjectivesCard,
    QuestionsCard,
    ComplexTestCard,
    FormulaCard,
    TheoryCard
  },
  computed: {
    currentSection() {
      if (!this.id) return null;
      const sectionId = parseInt(this.id.split('.')[0]);
      return lessonsData.lessons.find(s => s.section_id === sectionId);
    }
  },
  watch: {
    id(newId) {
      this.scrollToTopic(newId);
    },
    '$route.hash'(newHash) {
      if (newHash) {
        const topicId = newHash.replace('#topic-', '');
        this.scrollToTopic(topicId);
      }
    }
  },
  mounted() {
    if (this.$route.hash || this.id) {
      const targetId = this.$route.hash ? this.$route.hash.replace('#topic-', '') : this.id;
      setTimeout(() => this.scrollToTopic(targetId), 600);
    }
  },
  methods: {
    scrollToTopic(topicId) {
      this.$nextTick(() => {
        const el = document.getElementById(`topic-${topicId}`);
        const container = document.querySelector('.scroll-container');
        if (el && container) {
          container.scrollTo({
            top: el.offsetTop - 40,
            behavior: 'smooth'
          });
        }
      });
    }
  },
  computed: {
    currentSection() {
      if (!this.id) return null;
      const sectionId = parseInt(this.id.split('.')[0]);
      return lessonsData.lessons.find(s => s.section_id === sectionId);
    },
    // currentSectionImage() {
    //   if (!this.currentSection) return '';
    //   const folderMap = {
    //     1: "psyhology", 2: "buisnes-idia", 3: "buisnes-model",
    //     4: "marketing-and-explore", 5: "strategy", 6: "main-resources",
    //     7: "operacionaya-deyatelnost", 8: "finans-model",
    //     9: "investiciya-and-progres", 10: "strategy-of-progres", 11: "otvetstvennost"
    //   };
    //   const folder = folderMap[this.currentSection.section_id] || 'default';
    //   return `/images/${folder}.png`;
    // }
    currentSectionImage() {
      if (!this.currentSection) return '';
      const folderMap = {
        1: "psyhology", 2: "buisnes-idia", 3: "buisnes-model",
        4: "marketing-and-explore", 5: "strategy", 6: "main-resources",
        7: "operacionaya-deyatelnost", 8: "finans-model",
        9: "investiciya-and-progres", 10: "strategy-of-progres", 11: "otvetstvennost"
      };
      const folder = folderMap[this.currentSection.section_id] || 'default';
      return `${import.meta.env.BASE_URL}images/${folder}.png`;
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
}

@media (max-width: 480px) {
  .content-body h2 {
    font-size: 20px;
  }

  .text-paragraph {
    font-size: 15px;
  }
}
</style>