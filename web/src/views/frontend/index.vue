<template>
    <div class="page-wrapper">
        <Header />
        <el-container class="container">
            <el-main class="main">
                <div class="main-container">
                    <div class="main-left animate-slide-in-left">
                        <div class="welcome-badge">
                            <el-icon class="badge-icon"><StarFilled /></el-icon>
                            <span>在线教学平台</span>
                        </div>
                        <h1 class="main-title">
                            {{ siteConfig.siteName }}
                        </h1>
                        <div class="main-subtitle">
                            {{ $t('index.Interest teacher') }}
                        </div>
                        <div class="feature-tags">
                            <el-tag type="info" effect="plain" round>接口服务</el-tag>
                            <el-tag type="success" effect="plain" round>实战项目</el-tag>
                            <el-tag type="warning" effect="plain" round>技术分享</el-tag>
                        </div>
                        <div class="button-group">
                            <el-button
                                v-if="memberCenter.state.open"
                                @click="$router.push(memberCenterBaseRoutePath)"
                                class="primary-button"
                                type="primary"
                                size="large"
                                round
                            >
                                <el-icon class="button-icon"><UserFilled /></el-icon>
                                {{ $t('Member Center') }}
                            </el-button>
                            <el-button class="secondary-button" size="large" round plain>
                                <el-icon class="button-icon"><Reading /></el-icon>
                                课程资料
                            </el-button>
                        </div>
                    </div>
                    <div class="main-right animate-slide-in-right">
                        <div class="image-wrapper">
                            <div class="image-decoration"></div>
                            <img src="https://attach.dracalon.com/2025/09/17/25.6.15-2.png" alt="Avatar" class="avatar-image" />
                        </div>
                    </div>
                </div>

                <!-- 特色功能区 -->
                <div class="features-section">
                    <el-row :gutter="30" justify="center">
                        <el-col :xs="24" :sm="8" :md="8" v-for="(feature, index) in features" :key="index">
                            <el-card shadow="hover" class="feature-card">
                                <div class="feature-icon">
                                    <el-icon :size="40"><component :is="feature.icon" /></el-icon>
                                </div>
                                <h3 class="feature-title">{{ feature.title }}</h3>
                                <p class="feature-desc">{{ feature.desc }}</p>
                            </el-card>
                        </el-col>
                    </el-row>
                </div>
            </el-main>
        </el-container>
        <Footer />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { StarFilled, UserFilled, Reading, TrophyBase, Monitor, ChatLineRound } from '@element-plus/icons-vue'
import { useSiteConfig } from '/@/stores/siteConfig'
import { useMemberCenter } from '/@/stores/memberCenter'
import Header from '/@/layouts/frontend/components/header.vue'
import Footer from '/@/layouts/frontend/components/footer.vue'
import { memberCenterBaseRoutePath } from '/@/router/static/memberCenterBase'

const siteConfig = useSiteConfig()
const memberCenter = useMemberCenter()

const features = ref([
    {
        icon: TrophyBase,
        title: '后端 API 服务',
        desc: '为学生提供完整的后端接口服务，支持实战练习',
    },
    {
        icon: Monitor,
        title: '项目实战',
        desc: '基于真实项目场景，提升实际开发能力',
    },
    {
        icon: ChatLineRound,
        title: '技术答疑',
        desc: '及时解答学习过程中遇到的技术问题',
    },
])
</script>

<style scoped lang="scss">
// 动画定义
@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes glow-pulse {
    0%,
    100% {
        opacity: 1;
        filter: blur(25px);
        transform: translate(-50%, -50%) scale(1);
    }
    50% {
        opacity: 0.8;
        filter: blur(40px);
        transform: translate(-50%, -50%) scale(1.15);
    }
}

@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

.page-wrapper {
    min-height: 100vh;
}

.container {
    width: 100vw;
    min-height: 100vh;
    background: url(/@/assets/bg.jpg) repeat;
    color: var(--el-color-white);
    position: relative;

    &::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(64, 158, 255, 0.1) 0%, rgba(103, 194, 58, 0.1) 100%);
        pointer-events: none;
    }

    .main {
        min-height: 100vh;
        padding: 80px 20px 100px;
        position: relative;
        z-index: 1;

        .main-container {
            display: flex;
            min-height: calc(100vh - 200px);
            max-width: 1200px;
            margin: 0 auto;
            align-items: center;
            justify-content: space-around;
            gap: 60px;
            flex-direction: row-reverse;

            .main-left {
                flex: 1;
                max-width: 600px;

                .animate-slide-in-left {
                    animation: slideInLeft 0.8s ease-out;
                }

                .welcome-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    padding: 8px 20px;
                    background: rgba(255, 255, 255, 0.15);
                    backdrop-filter: blur(10px);
                    border-radius: 20px;
                    font-size: 14px;
                    font-weight: 500;
                    margin-bottom: 24px;
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    animation: pulse 3s ease-in-out infinite;

                    .badge-icon {
                        color: #ffd700;
                    }
                }

                .main-title {
                    font-size: 56px;
                    font-weight: 700;
                    margin: 0 0 20px;
                    background: linear-gradient(135deg, #fff 0%, rgba(255, 255, 255, 0.8) 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    line-height: 1.2;
                }

                .main-subtitle {
                    font-size: 20px;
                    line-height: 1.6;
                    color: rgba(255, 255, 255, 0.9);
                    margin-bottom: 24px;
                }

                .feature-tags {
                    display: flex;
                    gap: 12px;
                    margin-bottom: 40px;
                    flex-wrap: wrap;

                    :deep(.el-tag) {
                        backdrop-filter: blur(10px);
                        background: rgba(255, 255, 255, 0.15);
                        border: 1px solid rgba(255, 255, 255, 0.2);
                        color: #fff;
                        font-size: 14px;
                        padding: 8px 16px;
                        transition: all 0.3s ease;

                        &:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                        }
                    }
                }

                .button-group {
                    display: flex;
                    gap: 16px;
                    flex-wrap: wrap;

                    .primary-button {
                        padding: 12px 32px;
                        font-size: 16px;
                        font-weight: 500;
                        box-shadow: 0 8px 16px rgba(64, 158, 255, 0.3);
                        transition: all 0.3s ease;

                        &:hover {
                            transform: translateY(-3px);
                            box-shadow: 0 12px 24px rgba(64, 158, 255, 0.4);
                        }

                        .button-icon {
                            margin-right: 8px;
                        }
                    }

                    .secondary-button {
                        padding: 12px 32px;
                        font-size: 16px;
                        font-weight: 500;
                        background: rgba(255, 255, 255, 0.1);
                        backdrop-filter: blur(10px);
                        border: 1px solid rgba(255, 255, 255, 0.3);
                        color: #fff;
                        transition: all 0.3s ease;

                        &:hover {
                            transform: translateY(-3px);
                            background: rgba(255, 255, 255, 0.2);
                            box-shadow: 0 8px 16px rgba(255, 255, 255, 0.2);
                        }

                        .button-icon {
                            margin-right: 8px;
                        }
                    }
                }
            }

            .main-right {
                flex-shrink: 0;

                .animate-slide-in-right {
                    animation: slideInRight 0.8s ease-out;
                }

                .image-wrapper {
                    position: relative;
                    width: 350px;
                    height: 350px;
                    border-radius: 50%;
                    border: 4px solid rgba(255, 255, 255, 0.3);
                    box-shadow:
                        0 0 20px rgba(64, 158, 255, 0.3),
                        0 0 40px rgba(64, 158, 255, 0.2),
                        0 0 0 8px rgba(255, 255, 255, 0.1),
                        0 20px 60px rgba(0, 0, 0, 0.3);

                    .image-decoration {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        width: 120%;
                        height: 120%;
                        border-radius: 50%;
                        background: radial-gradient(
                            circle,
                            rgba(64, 158, 255, 0.6) 0%,
                            rgba(64, 158, 255, 0.4) 30%,
                            rgba(64, 158, 255, 0.2) 60%,
                            transparent 80%
                        );
                        animation: glow-pulse 3s ease-in-out infinite;
                        z-index: 0;
                    }

                    .avatar-image {
                        position: relative;
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        z-index: 1;
                    }
                }
            }
        }

        // 特色功能区
        .features-section {
            max-width: 1200px;
            margin: 80px auto 0;
            padding: 0 20px;

            .feature-card {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 16px;
                padding: 32px 24px;
                text-align: center;
                transition: all 0.3s ease;
                margin-bottom: 30px;

                &:hover {
                    transform: translateY(-8px);
                    background: rgba(255, 255, 255, 0.15);
                    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
                }

                :deep(.el-card__body) {
                    padding: 0;
                }

                .feature-icon {
                    width: 80px;
                    height: 80px;
                    margin: 0 auto 20px;
                    background: linear-gradient(135deg, rgba(64, 158, 255, 0.3), rgba(41, 121, 255, 0.2));
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #fff;
                    transition: all 0.3s ease;
                }

                &:hover .feature-icon {
                    transform: scale(1.1) rotate(5deg);
                }

                .feature-title {
                    font-size: 22px;
                    font-weight: 600;
                    color: #fff;
                    margin: 0 0 12px;
                }

                .feature-desc {
                    font-size: 15px;
                    color: rgba(255, 255, 255, 0.8);
                    line-height: 1.6;
                    margin: 0;
                }
            }
        }
    }
}

.header {
    background: rgba(255, 255, 255, 0.05) !important;
    box-shadow: none !important;
    position: fixed;
    width: 100%;
    z-index: 1000;
    backdrop-filter: blur(10px);

    :deep(.header-logo) {
        span {
            padding-left: 4px;
            color: var(--el-color-white);
            font-weight: 600;
        }
    }

    :deep(.frontend-header-menu) {
        background: transparent;
        .el-menu-item,
        .el-sub-menu .el-sub-menu__title {
            color: var(--el-color-white);
            &.is-active {
                color: var(--el-color-white) !important;
                background: rgba(255, 255, 255, 0.1) !important;
            }
            &:hover {
                background-color: rgba(255, 255, 255, 0.1) !important;
                color: var(--el-menu-hover-text-color);
            }
        }
    }
}

// 响应式设计
@media screen and (max-width: 1024px) {
    .main-container {
        flex-direction: column !important;
        text-align: center;
        gap: 40px !important;

        .main-left {
            max-width: 100% !important;
            padding: 0 !important;

            .main-title {
                font-size: 42px !important;
            }

            .feature-tags {
                justify-content: center;
            }

            .button-group {
                justify-content: center;
            }
        }

        .main-right {
            .image-wrapper {
                width: 300px !important;
                height: 300px !important;
            }
        }
    }

    .features-section {
        margin-top: 40px !important;
    }
}

@media screen and (max-width: 768px) {
    .main-container {
        .main-left {
            .main-title {
                font-size: 36px !important;
            }

            .main-subtitle {
                font-size: 18px !important;
            }

            .button-group {
                flex-direction: column;
                gap: 12px !important;

                .primary-button,
                .secondary-button {
                    width: 100%;
                    margin-left: 0 !important;
                }
            }
        }

        .main-right {
            .image-wrapper {
                width: 260px !important;
                height: 260px !important;
            }
        }
    }
}

@media screen and (max-width: 480px) {
    .main-container {
        .main-left {
            .main-title {
                font-size: 32px !important;
            }

            .feature-tags {
                :deep(.el-tag) {
                    font-size: 12px !important;
                    padding: 6px 12px !important;
                }
            }
        }
    }
}

// 暗色模式适配
@at-root html.dark {
    .container {
        background: url(/@/assets/bg-dark.jpg) repeat;

        &::before {
            background: linear-gradient(135deg, rgba(64, 158, 255, 0.2) 0%, rgba(41, 121, 255, 0.25) 100%);
        }
    }
}
</style>
