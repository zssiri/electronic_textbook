<template>
    <transition name="fade">
        <div v-if="showSelectionButton" class="floating-explain-btn" :style="{ top: btnY + 'px', left: btnX + 'px' }">
            <button @click="explainSelection">💡 Объяснить</button>
        </div>
    </transition>

    <div class="gemini-widget-container">
        <button class="chat-trigger" @click="toggleChat" :class="{ 'btn-active': isOpen }">
            <span v-if="!isOpen">AI Помощник</span>
            <span v-else>✕</span>
        </button>

        <transition name="popup">
            <div v-if="isOpen" class="chat-popup">

                <div class="chat-header">
                    <h3>Вопрос по теме:</h3>
                    <p>{{ contextTopic }}</p>
                </div>

                <div class="chat-messages" ref="scrollBox">
                    <div v-if="history.length === 0" class="empty-state">
                        Привет! Я помогу разобраться в теме "{{ contextTopic }}". Что именно тебе непонятно?
                    </div>

                    <div v-for="(msg, i) in history" :key="i" :class="['msg', msg.role]">
                        <div class="msg-content">{{ msg.text }}</div>
                    </div>
                    <div v-if="loading" class="msg ai loading">Думаю...</div>
                </div>

                <transition name="fade">
                    <div v-if="showHint" class="ai-hint-bubble">
                        Напоминаем, это узконаправленный специализированный ИИ помощник, сформулируйте ваш запрос
                        поконкретнее.
                    </div>
                </transition>

                <div class="chat-input-area">
                    <input v-model="userInput" @keyup.enter="askGemini()" placeholder="Введите ваш вопрос..."
                        :disabled="loading" />
                    <button @click="askGemini()" :disabled="loading || !userInput.trim()">➤</button>
                </div>

            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        contextTopic: String,
        hideWidget: Boolean
    },
    data() {
        return {
            isOpen: false,
            userInput: '',
            loading: false,
            history: [],
            showSelectionButton: false,
            btnX: 0,
            btnY: 0,
            selectedText: '',
            showHint: false
        };
    },
    mounted() {
        document.addEventListener('mouseup', this.handleSelection);
        document.addEventListener('mousedown', this.handleOutsideClick);
        document.addEventListener('selectionchange', this.handleSelection);
    },
    beforeUnmount() {
        document.removeEventListener('mouseup', this.handleSelection);
        document.removeEventListener('mousedown', this.handleOutsideClick);
        document.removeEventListener('selectionchange', this.handleSelection);
    },
    methods: {
        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                setTimeout(() => {
                    this.showHint = true;
                    setTimeout(() => { this.showHint = false; }, 5000);
                }, 1000);
            } else {
                this.showHint = false;
            }
        },

        handleSelection() {
            const selection = window.getSelection();
            const selectedText = selection.toString().trim();

            if (selectedText && selectedText.length > 3) {
                const range = selection.getRangeAt(0);
                const rect = range.getBoundingClientRect();

                this.selectedText = selectedText;
                this.btnX = rect.left + rect.width / 2;
                this.btnY = rect.top + window.scrollY - 15;

                this.showSelectionButton = true;
            } else {
                setTimeout(() => {
                    if (!window.getSelection().toString().trim()) {
                        this.showSelectionButton = false;
                    }
                }, 100);
            }
        },

        explainSelection() {
            if (!this.selectedText) return;

            const prompt = `Объясни кратко, что это такое: "${this.selectedText}"`;

            if (!this.isOpen) this.toggleChat();
            this.userInput = prompt;
            this.askGemini();

            this.showSelectionButton = false;
            window.getSelection().removeAllRanges();
        },

        handleOutsideClick(e) {
            if (!e.target.closest('.floating-explain-btn')) {
                this.showSelectionButton = false;
            }
        },

        async explainSelection() {
            const prompt = `Объясни кратко этот текст: ${this.selectedText}`;
            this.showSelectionButton = false;

            window.getSelection().removeAllRanges();

            if (!this.isOpen) this.isOpen = true;
            await this.askGemini(prompt);
        },

        async askGemini(forcedMsg) {
            const msg = typeof forcedMsg === 'string' ? forcedMsg : this.userInput;
            if (!msg || !msg.trim()) return;

            this.history.push({
                role: 'user',
                text: typeof forcedMsg === 'string' ? `Объясни: "${this.selectedText}"` : msg
            });

            this.userInput = '';
            this.loading = true;

            try {
                const res = await fetch('/electronic_textbook/api/chat.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: msg, topic: this.contextTopic })
                });
                const data = await res.json();
                this.history.push({ role: 'ai', text: data.text });
            } catch (e) {
                this.history.push({ role: 'ai', text: "Ошибка связи." });
            } finally {
                this.loading = false;
                this.$nextTick(() => {
                    if (this.$refs.scrollBox) this.$refs.scrollBox.scrollTop = this.$refs.scrollBox.scrollHeight;
                });
            }
        }
    }
};
</script>

<style scoped>
.gemini-widget-container {
    position: fixed;
    bottom: 30px;
    left: 30px;
    z-index: 1000;
}

.chat-trigger {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    min-width: 60px;
    height: 60px;
    padding: 0 20px;
    border-radius: 30px;
    background: #007aff;
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 8px 25px rgba(0, 122, 255, 0.3);
    font-weight: 700;
    font-size: 15px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.chat-trigger:hover {
    transform: translateY(-3px);
    background: #0063d1;
    box-shadow: 0 12px 30px rgba(0, 122, 255, 0.4);
}

.btn-active {
    background: #1d1d1f !important;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2) !important;
}

.chat-popup {
    position: absolute;
    bottom: 80px;
    left: 0;
    width: 380px;
    height: 550px;
    background: #ffffff;
    border-radius: 24px;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.chat-header {
    padding: 20px;
    background: #f5f5f7;
    border-bottom: 1px solid #eee;
    flex-shrink: 0;
}

.chat-header h3 {
    margin: 0;
    font-size: 17px;
    color: #1d1d1f;
    font-weight: 700;
}

.chat-header p {
    margin: 4px 0 0;
    font-size: 13px;
    color: #86868b;
    line-height: 1.3;
}

.chat-messages {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: #ffffff;
    scroll-behavior: smooth;
}

.chat-messages:empty,
.empty-state {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    height: 100%;
    color: #86868b;
    font-size: 14px;
    padding: 40px;
}

.chat-messages::-webkit-scrollbar {
    width: 4px;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #e5e5e5;
    border-radius: 10px;
}

.msg {
    max-width: 85%;
    padding: 12px 16px;
    border-radius: 18px;
    font-size: 15px;
    line-height: 1.45;
    word-wrap: break-word;
}

.user {
    align-self: flex-end;
    background: #007aff;
    color: white;
    border-bottom-right-radius: 4px;
}

.ai {
    align-self: flex-start;
    background: #f2f2f7;
    color: #1d1d1f;
    border-bottom-left-radius: 4px;
}

.loading {
    font-style: italic;
    opacity: 0.7;
}

.ai-hint-bubble {
    position: absolute;
    bottom: 90px;
    left: 20px;
    right: 20px;
    background: #1d1d1f;
    color: white;
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 13px;
    line-height: 1.4;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    z-index: 100;
}

.ai-hint-bubble::after {
    content: '';
    position: absolute;
    bottom: -6px;
    left: 20px;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 8px solid #1d1d1f;
}

.chat-input-area {
    padding: 16px;
    display: flex;
    gap: 12px;
    background: #ffffff;
    border-top: 1px solid #f0f0f0;
    flex-shrink: 0;
}

.chat-input-area input {
    flex: 1;
    border: 1px solid #e5e5e5;
    padding: 12px 18px;
    border-radius: 25px;
    outline: none;
    font-size: 15px;
    transition: border 0.2s;
}

.chat-input-area input:focus {
    border-color: #007aff;
}

.chat-input-area button {
    width: 44px;
    height: 44px;
    background: #007aff;
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: all 0.2s;
}

.floating-explain-btn {
    position: absolute;
    z-index: 10000;
    transform: translate(-50%, -100%);
    pointer-events: auto;
}

.floating-explain-btn button {
    background: #1d1d1f;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    white-space: nowrap;
    transition: all 0.2s ease;
}

.popup-enter-active,
.popup-leave-active {
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    transform-origin: bottom left;
}

.popup-enter-from,
.popup-leave-to {
    opacity: 0;
    transform: scale(0.7) translateY(40px);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(10px);
}

@media (max-width: 768px) {
    .gemini-widget-container {
        bottom: 15px;
        left: 15px;
    }

    .chat-popup {
        width: calc(100vw - 30px);
        height: 80vh;
        bottom: 75px;
        border-radius: 20px;
    }

    .chat-header {
        padding: 15px;
    }

    .chat-header h3 {
        font-size: 16px;
    }

    .msg {
        max-width: 90%;
        font-size: 16px;
        padding: 10px 14px;
    }

    .chat-input-area {
        padding: 12px;
        gap: 8px;
    }

    .chat-input-area input {
        padding: 14px 18px;
        font-size: 16px;
    }

    .chat-input-area button {
        width: 50px;
        height: 50px;
        flex-shrink: 0;
    }

    .floating-explain-btn button {
        padding: 12px 22px;
        font-size: 16px;
    }

    .ai-hint-bubble {
        bottom: 85px;
        left: 10px;
        right: 10px;
        font-size: 14px;
    }
}
</style>



// import { GoogleGenAI } from "@google/genai";

// export default {
// props: ['contextTopic'],
// data() {
// return {
// isOpen: false,
// userInput: '',
// loading: false,
// history: [],
// ai: null,
// }
// },
// mounted() {
// // Инициализация AI с ключом из env
// this.ai = new GoogleGenAI({ apiKey: import.meta.env.VITE_GEMINI_API_KEY });
// },
// methods: {
// toggleChat() {
// this.isOpen = !this.isOpen;
// },
// async askGemini() {
// if (!this.userInput.trim() || this.loading) return;

// const userText = this.userInput;
// this.history.push({ role: 'user', text: userText });
// this.userInput = '';
// this.loading = true;

// try {
// const result = await this.ai.models.generateContent({
// model: "gemini-3-flash-preview",
// contents: ` Ты — эксперт по основам предпринимательской деятельности. Отвечай **только** на вопросы, связанные с
предпринимательством, бизнес-моделями, стратегией, маркетингом, финансами и операционной деятельностью. Если вопрос не
по теме, отвечай строго: "Я могу обсуждать только темы, связанные с предпринимательской деятельностью. Пожалуйста,
задайте вопрос по теме." Отвечай кратко, ясно и по существу (2-3 предложения).Тема: "${this.contextTopic}" Вопрос
студента: ${userText}`,
// });

// const responseText = result?.text || "ИИ не дал ответа.";
// this.history.push({ role: 'ai', text: responseText });
// } catch (error) {
// console.error("GenAI Error:", error);
// this.history.push({ role: 'ai', text: "Ошибка ИИ: модель недоступна или ключ не активен." });
// } finally {
// this.loading = false;
// this.$nextTick(() => {
// const box = this.$refs.scrollBox;
// if (box) box.scrollTop = box.scrollHeight;
// });
// }
// }
// }
// }
