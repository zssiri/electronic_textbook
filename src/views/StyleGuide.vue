<template>
  <div class="style-guide">
    <div class="guide-intro">
      <h1>Витрина компонентов курса</h1>
      <p>Используйте эту страницу для настройки стилей (CSS) и проверки отображения компонентов.</p>
    </div>

    <section class="component-section">
      <h2 class="section-label">1. ObjectivesCard (Цели занятия)</h2>
      <ObjectivesCard :objectives="demoObjectives" />
    </section>

    <section class="component-section">
      <h2 class="section-label">2. DefinitionCard (Определение)</h2>
      <DefinitionCard>
        <strong>Масштабирование</strong> — это процесс увеличения пропускной способности бизнеса и его охвата при сохранении или увеличении эффективности.
      </DefinitionCard>
    </section>

    <section class="component-section">
      <h2 class="section-label">3. FactCard (Интересный факт)</h2>
      <FactCard text="90% стартапов закрываются в первый год, если у них нет четкой финансовой модели и понимания своей аудитории." />
    </section>

    <section class="component-section">
      <h2 class="section-label">4. TaskCard (Практическое задание)</h2>
      <TaskCard :task="demoTask" />
    </section>

    <section class="component-section">
      <h2 class="section-label">5. QuizCard (Одиночный вопрос)</h2>
      <QuizCard :question="demoQuizQuestion" />
    </section>

    <section class="component-section">
      <h2 class="section-label">6. QuizCollectionCard (Итоговый тест)</h2>
      <QuizCollectionCard title="Тест по основам маркетинга" :items="demoQuizCollection" />
    </section>

    <section class="component-section">
      <h2 class="section-label">7. ComplexTestCard (PAEI Тест Адизеса)</h2>
      <ComplexTestCard :testData="demoComplexTest" />
    </section>

    <section class="component-section">
      <h2 class="section-label">8. QuestionsCard (Контрольные вопросы)</h2>
      <QuestionsCard :questions="demoControlQuestions" />
    </section>

    <div class="guide-footer">Конец витрины компонентов</div>
  </div>
</template>

<script>
// Импортируем все ваши компоненты
import ObjectivesCard from '@/components/ObjectivesCard.vue'
import DefinitionCard from '@/components/DefinitionCard.vue'
import FactCard from '@/components/FactCard.vue'
import TaskCard from '@/components/TaskCard.vue'
import QuizCard from '@/components/QuizCard.vue'
import QuizCollectionCard from '@/components/QuizCollectionCard.vue'
import ComplexTestCard from '@/components/ComplexTestCard.vue'
import QuestionsCard from '@/components/QuestionsCard.vue'

export default {
  name: "StyleGuide",
  components: {
    ObjectivesCard,
    DefinitionCard,
    FactCard,
    TaskCard,
    QuizCard,
    QuizCollectionCard,
    ComplexTestCard,
    QuestionsCard
  },
  data() {
    return {
      // Статичные данные для ObjectivesCard
      demoObjectives: [
        "Понимать разницу между ростом и масштабированием",
        "Изучить модель франчайзинга как инструмент экспансии",
        "Уметь выстраивать шаги для расширения системы"
      ],

      // Статичные данные для TaskCard
      demoTask: {
        title: "Анализ конкурентных преимуществ",
        text: "Заполните таблицу сравнения вашего продукта с двумя основными конкурентами на рынке.",
        content: {
          headers: ["Критерий", "Ваш проект", "Конкурент А", "Конкурент Б"],
          rows: [
            ["Цена", "1000 ₸", "1200 ₸", "950 ₸"],
            ["Качество", "Высокое", "Среднее", "Среднее"],
            ["Доставка", "Бесплатно", "200 ₸", "Нет"]
          ]
        }
      },

      // Статичные данные для QuizCard
      demoQuizQuestion: {
        text: "Какая стадия развития компании характеризуется самоуправляемыми командами и наставничеством?",
        options: ["Оранжевая", "Зеленая", "Бирюзовая", "Красная"],
        correct: 2
      },

      // Статичные данные для QuizCollectionCard
      demoQuizCollection: [
        {
          question: "Что такое роялти?",
          options: ["Разовый платеж", "Ежемесячный процент", "Рекламный бюджет"],
          correct: 1
        },
        {
          question: "Кто является франчайзером?",
          options: ["Покупатель франшизы", "Владелец бренда", "Государственный орган"],
          correct: 1
        }
      ],

      // Статичные данные для ComplexTestCard (PAEI)
      demoComplexTest: {
        title: "Тест на определение стиля управления",
        instructions: "Ответьте 'Да' или 'Нет' на следующие утверждения:",
        questions: [
          { id: 1, text: "Вам важно доводить начатое дело до конкретного результата?" },
          { id: 2, text: "Вы любите наводить порядок в документах и процессах?" },
          { id: 3, text: "Вы часто генерируете идеи, которые пугают окружающих масштабом?" },
          { id: 4, text: "Вам важно, чтобы в коллективе была хорошая атмосфера?" }
        ],
        scoring_keys: {
          P: [1],
          A: [2],
          E: [3],
          I: [4]
        }
      },

      // Статичные данные для QuestionsCard
      demoControlQuestions: [
        "В чем разница между залогом и поручительством?",
        "Как неустойка защищает интересы пострадавшей стороны?",
        "Опишите последовательность шагов при масштабировании бизнеса."
      ]
    }
  }
}
</script>

<style scoped>
.style-guide {
  max-width: 1000px;
  margin: 0 auto;
  padding: 60px 20px;
  background-color: #fcfcfd;
}

.guide-intro {
  text-align: center;
  margin-bottom: 80px;
}

.guide-intro h1 {
  font-size: 32px;
  color: #1d1d1f;
  margin-bottom: 10px;
}

.guide-intro p {
  color: #86868b;
  font-size: 18px;
}

.component-section {
  margin-bottom: 100px;
  padding-bottom: 40px;
  border-bottom: 1px dashed #d2d2d7;
}

.section-label {
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #ff3b30; /* Выделяем заголовки секций для удобства */
  margin-bottom: 30px;
  font-weight: 800;
}

.guide-footer {
  text-align: center;
  color: #d2d2d7;
  font-weight: 700;
  text-transform: uppercase;
  font-size: 12px;
  margin-top: 40px;
}
</style>