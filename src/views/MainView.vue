<template>
    <div class="app-wrapper" :class="{ 'no-scroll': tocOpen }">
        <button class="menu-button" @click="toggleMenu">
            <span v-if="!tocOpen">☰</span>
            <span v-else>✕</span>
        </button>

        <div class="scroll-container" ref="scrollTarget" @scroll="handleScroll">
            <main class="main-content">
                <div class="hero-section" :class="{ 'hero-mini': $route.params.id }">
                    <h1>Основы предпринимательской деятельности</h1>
                    <h2 v-if="!$route.params.id">Электронный учебник - авторская программа</h2>

                    <nav v-if="$route.params.id && currentSectionTopics.length" class="breadcrumb-nav">
                        <router-link to="/" exact class="nav-link-home">
                            Главная
                        </router-link>
                        <span class="sep">/</span>

                        <div class="anchor-links">
                            <router-link v-for="t in currentSectionTopics" :key="t.topic_id"
                                :to="`/topic/${t.topic_id}#topic-${t.topic_id}`"
                                @click.native="handleAnchorClick(t.topic_id)"
                                :class="{ 'active-anchor': $route.params.id === t.topic_id }">
                                {{ t.topic_id }}
                            </router-link>
                        </div>
                    </nav>
                </div>

                <router-view />
            </main>
        </div>

        <GeminiChat :contextTopic="currentTopicTitle" :hideWidget="tocOpen" />

        <aside class="side-panel" :class="{ 'panel-open': tocOpen }">
            <div class="panel-header">Содержание</div>

            <nav class="toc-nav">
                <ul>
                    <li v-for="(section, index) in sectionsWithImages" :key="section.section_id" class="section-item">
                        <button class="section-trigger" @click="toggleSection(index)">
                            <span class="num">{{ section.section_id }}</span>
                            <span class="txt">{{ section.section_title }}</span>
                            <span class="chevron" :class="{ rotate: openSections.includes(index) }">
                                ▾
                            </span>
                        </button>

                        <transition name="expand">
                            <div v-show="openSections.includes(index)" class="sub-panel">
                                <img v-if="section.image" :src="section.image" class="mini-img" alt="" />

                                <ul class="subtopics">
                                    <li v-for="topic in section.topics" :key="topic.topic_id">
                                        <router-link :to="`/topic/${topic.topic_id}#topic-${topic.topic_id}`"
                                            @click.native="tocOpen = false">
                                            {{ topic.topic_id }} {{ topic.topic_title }}
                                        </router-link>
                                    </li>
                                </ul>
                            </div>
                        </transition>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="overlay" v-if="tocOpen" @click="toggleMenu"></div>

        <transition name="fade">
            <button v-show="isScrolled" class="button_back-to-top" @click="backToTop">
                ▲
            </button>
        </transition>
    </div>
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

import GeminiChat from '../components/GeminiChat.vue'

export default {
    components: { GeminiChat },
    data() {
        return {
            tocOpen: false,
            isScrolled: false, 
            openSections: [],
            rawLessons: [
                lessonOne, lessonTwo, lessonThree,
                lessonFour, lessonFive, lessonSix,
                lessonSeven, lessonEight, lessonNine,
                lessonTen, lessonEleven
            ],
            folderMap: {
                1: 'psyhology', 2: 'buisnes-idia', 3: 'buisnes-model',
                4: 'marketing-and-explore', 5: 'strategy', 6: 'main-resources',
                7: 'operacionaya-deyatelnost', 8: 'finans-model',
                9: 'investiciya-and-progres', 10: 'strategy-of-progres', 11: 'otvetstvennost'
            }
        }
    },
    computed: {
        currentTopicTitle() {
        const topicId = this.$route.params.id;
        if (!topicId) return 'Главная страница';
        
        for (const section of this.rawLessons) {
            const topic = section.topics.find(t => t.topic_id === topicId);
            if (topic) return topic.topic_title;
        }
        return 'Учебник';
    },

    sectionsWithImages() {
        const base = import.meta.env.BASE_URL;
        return this.rawLessons.map(section => ({
            ...section,
            image: `${base}images/${this.folderMap[section.section_id]}.png`
        }));
    },
    currentSectionTopics() {
        const id = this.$route.params.id;
        if (!id) return [];
        const secId = parseInt(id.split('.')[0]);
        const section = this.rawLessons.find(s => s.section_id === secId);
        return section ? section.topics : [];
    }
    },
    methods: {
        toggleMenu() { 
            this.tocOpen = !this.tocOpen; 
        },
        toggleSection(i) {
            if (this.openSections.includes(i)) {
                this.openSections = this.openSections.filter(v => v !== i);
            } else {
                this.openSections.push(i);
            }
        },
        handleScroll(e) {
            const scrollTop = e.target.scrollTop || 0;
            this.isScrolled = scrollTop > 300;
        },
        backToTop() {
            if (this.$refs.scrollTarget) {
                this.$refs.scrollTarget.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        },
        handleAnchorClick(topicId) {
        }
    }
}
</script>

<style scoped>
:deep(html),
:deep(body) {
    margin: 0 !important;
    padding: 0 !important;
    height: 100vh !important;
    width: 100vw !important;
    overflow: hidden !important;
    position: fixed;
}

@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;800&display=swap');

.hero-section {
    text-align: center;
    padding: 80px 0 40px;
    transition: all 0.5s ease;
}

.hero-mini {
    padding: 20px 0 15px;
    border-bottom: 1px solid #f0f0f0;
    text-align: left;
}

.hero-mini h1 {
    font-size: 1.8rem;
    margin: 0;
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 15px;
}

.anchor-links {
    display: flex;
    gap: 8px;
}

.anchor-links a {
    text-decoration: none;
    color: #000000;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 700;
}

.anchor-links a.active-anchor {
    background: white;
    color: #007aff;
}

.sep {
    color: #ccc;
}

.app-wrapper {
    font-family: 'Manrope', sans-serif;
    position: fixed;
    inset: 0;
    background: #fff;
    display: flex;
    overflow: hidden;
    z-index: 1;
}

.scroll-container {
    width: 100%;
    height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    display: flex;
    flex-direction: column;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

.no-scroll .scroll-container {
    overflow: hidden !important;
    touch-action: none;
}

.main-content {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    min-height: 100%;
    padding: 60px 20px;
    box-sizing: border-box;
    position: relative;
    transition: filter 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.no-scroll .main-content {
    filter: blur(8px) grayscale(0.2);
}

.hero-section {
    width: 100%;
    max-width: 800px;
    margin-top: auto;
    margin-bottom: auto;
    text-align: center;
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.has-selection .hero-section {
    margin-top: 0;
    margin-bottom: 50px;
}

.hero-section {
    text-align: center;
    padding: 100px 0 40px;
    transition: all 0.5s ease;
}

.hero-mini {
    padding: 20px 0 15px;
    border-bottom: 1px solid #f0f0f0;
    text-align: left;
}

.hero-section h1 {
    font-size: clamp(1.8rem, 4vw, 3rem);
    transition: font-size 0.5s ease;
    margin-bottom: 20px;
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #636363;
    margin-top: 8px;
    animation: fadeIn 0.5s ease;
}

.breadcrumb-nav a {
    color: #616161;
    text-decoration: none;
}

.active-crumb {
    color: #333;
    font-weight: 600;
}

.sep {
    color: #ccc;
}

.reader-area {
    width: 100%;
    max-width: 800px;
    margin-bottom: 150px;
    text-align: left;
    animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.menu-button {
    position: fixed;
    top: 25px;
    left: 25px;
    z-index: 9999;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #000;
    color: #fff;
    border: none;
    cursor: pointer;
    font-size: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
    transition: transform 0.3s ease;
}

.menu-button:hover {
    transform: scale(1.1) rotate(5deg);
}

.side-panel {
    position: fixed;
    top: 0;
    left: 0;
    width: 480px;
    height: 100vh;
    background: #fff;
    z-index: 9500;
    padding: 80px 20px 20px;
    box-sizing: border-box;
    box-shadow: 20px 0 60px rgba(0, 0, 0, 0.15);
    transform: translateX(-105%);
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    overflow-y: auto;
}

.panel-open {
    transform: translateX(0);
}

.overlay {
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(12px);
    z-index: 8000;
    animation: fadeIn 0.5s ease-out;
}

.expand-enter-active,
.expand-leave-active {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    max-height: 600px;
    overflow: hidden;
}

.expand-enter-from,
.expand-leave-to {
    max-height: 0;
    opacity: 0;
    transform: translateY(-10px);
}

.panel-header {
    font-size: 26px;
    font-weight: 800;
    margin-bottom: 35px;
    border-bottom: 3px solid #000;
    padding-bottom: 10px;
}

h1 {
    font-size: clamp(2.2rem, 5vw, 3.5rem);
    font-weight: 800;
    margin: 0;
    line-height: 1.1;
}

h2 {
    font-size: 1.1rem;
    font-weight: 300;
    color: #555;
    margin-top: 15px;
}

.section-trigger {
    width: 100%;
    display: flex;
    align-items: center;
    background: none;
    border: none;
    padding: 18px 0;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
}

.num {
    width: 35px;
    font-weight: 800;
    color: #eee;
    font-size: 22px;
}

.txt {
    flex: 1;
    font-weight: 600;
    font-size: 15px;
    color: #333;
    text-align: left;
}

.sub-panel {
    padding: 20px 0 25px 20px;
    display: flex;
    align-items: flex-start;
    gap: 20px;
}

.mini-img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 14px;
}

.subtopics a {
    display: block;
    padding: 10px 0;
    font-size: 14px;
    color: #777;
    text-decoration: none;
    transition: 0.3s;
    cursor: pointer;
}

.subtopics a:hover {
    color: #000;
    transform: translateX(8px);
}

.chevron {
    transition: transform 0.4s;
    color: #ccc;
}

.rotate {
    transform: rotate(180deg);
    color: #000;
}

.badge {
    text-transform: uppercase;
    letter-spacing: 2px;
    font-size: 11px;
    font-weight: 800;
    color: #aaa;
    margin-bottom: 15px;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

::-webkit-scrollbar {
    width: 5px;
}

::-webkit-scrollbar-thumb {
    background: #ddd;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #000;
}

.button_back-to-top {
    position: fixed;
    bottom: 30px;
    right: 40px;
    z-index: 1000;

    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 20px;

    transition: opacity 0.3s, transform 0.3s;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.button_back-to-top:hover {
    transform: translateY(-5px);
    background-color: #0056b3;
}

@media (max-width: 1024px) {
    .side-panel {
        width: 380px;
    }
}

@media (max-width: 768px) {
    .main-content {
        padding: 40px 15px;
    }

    .side-panel {
        width: 100%;
        padding-top: 100px;
        padding-bottom: 120px !important; 
        box-sizing: border-box;
    }

    .toc-nav {
        padding-bottom: 50px;
    }

    h1 {
        font-size: 1.8rem !important;
        line-height: 1.2;
    }

    h2 {
        font-size: 1rem;
    }

    .hero-section {
        padding: 60px 0 20px;
    }

    .breadcrumb-nav {
        flex-wrap: wrap;
        gap: 8px;
    }

    .anchor-links {
        overflow-x: auto;
        width: 100%;
        padding-bottom: 5px;
        -webkit-overflow-scrolling: touch;
    }

    .mini-img {
        width: 80px;
        height: 80px;
    }

    .sub-panel {
        padding-left: 10px;
        gap: 15px;
    }

    .button_back-to-top {
        bottom: 20px;
        right: 20px;
        width: 44px;
        height: 44px;
    }

    .menu-button {
        top: 15px;
        left: 15px;
        width: 48px;
        height: 48px;
        font-size: 18px;
    }
}

@media (max-width: 480px) {
    .hero-mini h1 {
        font-size: 1.4rem !important;
    }

    .txt {
        font-size: 13px;
    }

    .num {
        font-size: 18px;
        width: 25px;
    }
}
</style>