<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="Dextrade — Trade. Invest. Grow. Your premium gateway to global Forex, Crypto &amp; Digital Asset markets." />
    <title>Dextrade | Trade. Invest. Grow.</title>
    <link rel="icon" type="image/png" href="{{ asset('web/assets/images/logo.png') }}" />
    <link rel="stylesheet" href="{{ asset('web/assets/css/style.css') }}" />
</head>

<body>

    <!-- ============================================================
     NAVBAR
     ============================================================ -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="#home" class="nav-logo">
                <img src="{{ asset('web/assets/images/logo.png') }}" alt="Dextrade Logo" />
            </a>
            <div class="nav-links" id="navLinks">
                <a href="#home">Home</a>
                <a href="#about">About Us</a>
                <a href="#services">Our Services</a>
                <a href="#why-us">Why Dextrade</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#faq">FAQs</a>
            <div class="nav-cta-mobile-row">
    <a href="{{ Route::has('user.register') ? route('user.register') : '#' }}" class="nav-cta nav-cta-mobile">
        Sign Up
    </a>
    
    <a href="{{ Route::has('user.login') ? route('user.login') : '#' }}" class="nav-cta nav-cta-mobile">
        Sign In
    </a>
    </div>
<a href="{{ Route::has('user.register') ? route('user.register') : '#' }}" class="nav-cta nav-cta-desktop">
    Sign Up
</a>

<a href="{{ Route::has('user.login') ? route('user.login') : '#' }}" class="nav-cta nav-cta-desktop">
    Sign In
</a>
            <div class="nav-toggle" id="navToggle" aria-label="Toggle Menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </div>
            </div>
            </nav>

    <!-- ============================================================
     PAGE 01 — HERO
     ============================================================ -->
    <section class="hero" id="home">
        <div class="hero-noise"></div>
        <div class="particles" id="particles"></div>
        <div class="hero-grid-lines"></div>
        <div class="hero-blob hero-blob-1"></div>
        <div class="hero-blob hero-blob-2"></div>
        <div class="hero-blob hero-blob-3"></div>
        <div class="hero-scan-line"></div>

        <div class="hero-v2-wrap">
            <div class="hero-stage">
                <div class="hero-stage-line hero-stage-line--left reveal-left"></div>
                <div class="hero-stage-core">

                    <!-- Logo coin RIGHT -->
                    <div class="hero-logo-frame reveal">
                        <div class="hlf-rings">
                            <div class="hlf-ring hlf-ring-1"></div>
                            <div class="hlf-ring hlf-ring-2"></div>
                            <div class="hlf-ring hlf-ring-3"></div>
                        </div>
                        <div class="hlf-orbit-dots">
                            <div class="hlf-odot hlf-odot-1"></div>
                            <div class="hlf-odot hlf-odot-2"></div>
                            <div class="hlf-odot hlf-odot-3"></div>
                        </div>
                        <div class="hlf-coin-wrap">
                            <img src="{{ asset('web/assets/images/mainlogo.png') }}" alt="Dextrade Logo" class="hlf-coin-img" />
                            <div class="hlf-shine"></div>
                        </div>
                        <div class="hlf-chip hlf-chip-1">
                            <img src="{{ asset('web/assets/icons/lucide/trending-up.svg') }}" alt="" />
                            <span>Smart Trade</span>
                        </div>
                        <div class="hlf-chip hlf-chip-2">
                            <img src="{{ asset('web/assets/icons/lucide/coins.svg') }}" alt="" />
                            <span>Crypto</span>
                        </div>
                        <div class="hlf-chip hlf-chip-3">
                            <img src="{{ asset('web/assets/icons/lucide/globe.svg') }}" alt="" />
                            <span>Global</span>
                        </div>
                    </div>

                    <!-- Headline LEFT -->
                    <div class="hero-headline-block reveal">
                        <h1 class="hero-h1">
                            <span class="hero-h1-top">DEX</span>
                            <span class="hero-h1-bottom">
                                <span class="hero-h1-fx">TRADE</span>
                                <span class="hero-h1-underline"></span>
                            </span>
                        </h1>
                        <p class="hero-sub">
                            Your Edge in Global Markets.<br />
                            <strong>Trade Smart. Build Wealth.</strong>
                        </p>
                        <p class="hero-tagline-dots">
                            <span>TRADE</span>
                            <span class="htd-sep">◆</span>
                            <span>INVEST</span>
                            <span class="htd-sep">◆</span>
                            <span>GROW</span>
                        </p>
                        <div class="hero-v2-cta">
                            <a href="#about" class="hv2-btn-primary">
                                <span class="hv2-btn-bg"></span>
                                <img src="{{ asset('web/assets/icons/lucide/rocket.svg') }}" alt="" />
                                <span>Get Started</span>
                                <svg class="hv2-btn-arrow" viewBox="0 0 16 16" fill="none">
                                    <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                            <a href="#services" class="hv2-btn-outline">
                                <span>Our Services</span>
                                <img src="{{ asset('web/assets/icons/lucide/external-link.svg') }}" alt="" />
                            </a>
                        </div>
                    </div>

                </div>
                <div class="hero-stage-line hero-stage-line--right reveal-right"></div>
            </div>
            </div>

        <div class="hero-scroll-cue">
            <div class="hsc-line"></div>
            <span>Scroll</span>
        </div>
    </section>

    <!-- ============================================================
     PAGE 02 — ABOUT DEXTRADE
     ============================================================ -->
    <section class="about" id="about">
        <div class="about-container">

            <div class="about-img-wrap reveal-left">
                <img src="{{ asset('web/assets/images/icons.png') }}" alt="About Dextrade"
                    onerror="this.src='{{ asset('web/assets/images/image1.png') }}'" />
            </div>

            <div class="about-text reveal-right">
                <span class="section-tag">
                    <img src="{{ asset('web/assets/icons/lucide/info.svg') }}" alt="" class="tag-icon" />
                    Who We Are
                </span>
                <h2 class="section-title">Building the Future of<br /><span class="about-accent">Digital Finance</span>
                </h2>

                <div class="about-overview-block">
                    <div class="aob-header">
                        <div class="aob-icon">
                            <img src="{{ asset('web/assets/icons/lucide/globe.svg') }}" alt="Vision" />
                        </div>
                        <h4 class="aob-title">Our Vision &amp; Mission</h4>
                        </div>
                        <p class="aob-desc">
                        Dextrade is a next-generation financial technology platform built for the modern trader and
                        investor.
                        We bridge the gap between traditional finance and the rapidly evolving digital economy —
                        empowering
                        people across the globe to take control of their financial future. With deep roots in Forex,
                        cryptocurrency, and blockchain innovation, Dextrade delivers real-time intelligence, structured
                        investment strategies, and community-powered growth — all through one unified platform.
                        </p>
                        </div>
                        
                        <div class="about-overview-block about-overview-block--green">
                            <div class="aob-header">
                                <div class="aob-icon aob-icon--green">
                                    <img src="{{ asset('web/assets/icons/lucide/trending-up.svg') }}" alt="Growth" />
                                </div>
                                <h4 class="aob-title aob-title--green">Why Traders Choose Dextrade</h4>
                            </div>
                            <p class="aob-desc">
                                From beginner investors to seasoned professionals, Dextrade offers a structured, transparent,
                                and scalable ecosystem. Our platform combines live market data, expert analysis, smart trading
                                tools, and a performance-based rewards system — giving every participant a genuine edge in the
                                global marketplace.
                            </p>
                        </div>

            </div>
        </div>
        </section>

    <!-- ============================================================
     PAGE 03 — OUR SERVICES
     ============================================================ -->
    <section class="sectors-section" id="services">
        <div class="sectors-bg-overlay"></div>
        <div class="sectors-glow-top"></div>

        <div class="sectors-container">
            <div class="text-center reveal">
                <span class="section-tag">
                    <img src="{{ asset('web/assets/icons/lucide/layers.svg') }}" alt="" class="tag-icon" />
                    What We Offer
                </span>
                <h2 class="section-title">Our Core <span class="sectors-accent">Services</span></h2>
                <div class="divider"></div>
                <p class="section-subtitle">
                    Six powerful pillars that define the Dextrade advantage — built for traders, investors, and builders
                    of tomorrow.
                </p>
            </div>

            <div class="sectors-grid">
                <!-- 1. Forex Trading -->
                <div class="sector-card reveal" style="--delay:0s" id="forex">
                    <div class="sector-card-inner">
                        <div class="sector-icon-wrap">
                            <div class="sector-icon">
                                <img src="{{ asset('web/assets/icons/lucide/dollar-sign.svg') }}" alt="Forex" />
                            </div>
                            <div class="sector-icon-ring"></div>
                        </div>
                        <h3 class="sector-title">Forex Trading</h3>
                        <p class="sector-desc">Access the world's most liquid financial market. Get real-time currency
                            pair analysis, exchange rate movements, and expert Forex strategies tailored for every
                            trader level.</p>
                        <div class="sector-tag">Currency Pairs</div>
                    </div>
                    <div class="sector-card-glow"></div>
                </div>

                <!-- 2. Crypto Investment -->
                <div class="sector-card reveal" style="--delay:0.08s" id="crypto">
                    <div class="sector-card-inner">
                        <div class="sector-icon-wrap">
                            <div class="sector-icon sector-icon--green">
                                <img src="{{ asset('web/assets/icons/lucide/coins.svg') }}" alt="Crypto" />
                            </div>
                            <div class="sector-icon-ring sector-icon-ring--green"></div>
                        </div>
                        <h3 class="sector-title">Crypto Investment</h3>
                        <p class="sector-desc">Invest in the digital asset revolution with guided strategies. From
                            Bitcoin and Ethereum to high-potential altcoins — Dextrade helps you navigate crypto cycles
                            with clarity and confidence.</p>
                        <div class="sector-tag sector-tag--green">Digital Assets</div>
                        </div>
                        <div class="sector-card-glow sector-card-glow--green"></div>
                </div>

                <!-- 3. Smart Analytics -->
                <div class="sector-card reveal" style="--delay:0.16s" id="analytics">
                    <div class="sector-card-inner">
                        <div class="sector-icon-wrap">
                            <div class="sector-icon">
                                <img src="{{ asset('web/assets/icons/lucide/activity.svg') }}" alt="Analytics" />
                            </div>
                            <div class="sector-icon-ring"></div>
                        </div>
                        <h3 class="sector-title">Smart Analytics</h3>
                        <p class="sector-desc">Data-driven decisions start here. Our advanced analytics engine delivers
                            live market trends, pattern recognition, and deep economic insights so you always trade with
                            an edge.</p>
                        <div class="sector-tag">Market Intelligence</div>
                    </div>
                    <div class="sector-card-glow"></div>
                </div>

                <!-- 4. DeFi & Web3 -->
                <div class="sector-card reveal" style="--delay:0.24s" id="defi">
                    <div class="sector-card-inner">
                        <div class="sector-icon-wrap">
                            <div class="sector-icon sector-icon--green">
                                <img src="{{ asset('web/assets/icons/lucide/cpu.svg') }}" alt="DeFi" />
                            </div>
                            <div class="sector-icon-ring sector-icon-ring--green"></div>
                        </div>
                        <h3 class="sector-title">DeFi &amp; Web3</h3>
                        <p class="sector-desc">Step into the decentralized future. Explore DeFi protocols, smart
                            contract opportunities, liquidity pools, and Web3 integrations that unlock new streams of
                            passive income.</p>
                        <div class="sector-tag sector-tag--green">Decentralized Finance</div>
                    </div>
                    <div class="sector-card-glow sector-card-glow--green"></div>
                </div>
                <!-- 5. Trading Education -->
                <div class="sector-card reveal" style="--delay:0.32s" id="education">
                    <div class="sector-card-inner">
                        <div class="sector-icon-wrap">
                            <div class="sector-icon">
                                <img src="{{ asset('web/assets/icons/lucide/book-open.svg') }}" alt="Education" />
                            </div>
                            <div class="sector-icon-ring"></div>
                        </div>
                        <h3 class="sector-title">Trading Academy</h3>
                        <p class="sector-desc">Learn to trade like a pro. Dextrade Academy offers structured courses,
                            live webinars, and in-depth guides — from trading basics to advanced technical analysis and
                            risk management.</p>
                        <div class="sector-tag">Academy &amp; Courses</div>
                    </div>
                    <div class="sector-card-glow"></div>
                </div>

                <!-- 6. Passive Income -->
                <div class="sector-card reveal" style="--delay:0.40s" id="passive">
                    <div class="sector-card-inner">
                        <div class="sector-icon-wrap">
                            <div class="sector-icon sector-icon--green">
                                <img src="{{ asset('web/assets/icons/lucide/gift.svg') }}" alt="Passive Income" />
                            </div>
                            <div class="sector-icon-ring sector-icon-ring--green"></div>
                        </div>
                        <h3 class="sector-title">Passive Income</h3>
                        <p class="sector-desc">Let your portfolio work for you. Dextrade's structured referral network,
                            staking rewards, and performance-based ranks create multiple income streams that grow as
                            your community grows.</p>
                        <div class="sector-tag sector-tag--green">Earn &amp; Reward</div>
                        </div>
                    <div class="sector-card-glow sector-card-glow--green"></div>
                </div>

            </div>
        </div>
        </section>

    <!-- ============================================================
                             PAGE 04 — HOW IT WORKS
                             ============================================================ -->
    <section class="how-it-works" id="how-it-works">
        <div class="hiw-bg-overlay"></div>
        <div class="hiw-container">

            <div class="text-center reveal">
                <span class="section-tag">
                    <img src="{{ asset('web/assets/icons/lucide/layers.svg') }}" alt="" class="tag-icon" />
                    Simple Process
                </span>
                <h2 class="section-title">How Dextrade<br /><span class="hiw-accent">Works</span></h2>
                <div class="divider"></div>
                <p class="section-subtitle">
                    From registration to your first trade — Dextrade keeps it simple, transparent, and rewarding at
                    every step.
                </p>
            </div>
            
            <div class="hiw-steps">
            
                <div class="hiw-step reveal" style="--delay:0s">
                    <div class="hiw-step-num">01</div>
                    <div class="hiw-step-icon">
                        <img src="{{ asset('web/assets/icons/lucide/user-plus.svg') }}" alt="Register" />
                    </div>
                    <h3>Create Account</h3>
                    <p>Sign up in minutes. Complete your profile, verify your identity, and unlock instant access to the
                        full Dextrade ecosystem.</p>
                    <div class="hiw-step-tag">Quick &amp; Secure</div>
                </div>
            
                <div class="hiw-connector reveal" style="--delay:0.1s">
                    <div class="hiw-connector-line"></div>
                    <div class="hiw-connector-dot"></div>
                </div>
            
                <div class="hiw-step reveal" style="--delay:0.2s">
                    <div class="hiw-step-num">02</div>
                    <div class="hiw-step-icon hiw-step-icon--green">
                        <img src="{{ asset('web/assets/icons/lucide/dollar-sign.svg') }}" alt="Fund" />
                    </div>
                    <h3>Fund Your Portfolio</h3>
                    <p>Deposit seamlessly using crypto or supported payment methods. Your funds are protected by
                        multi-layer security at every stage.</p>
                    <div class="hiw-step-tag hiw-step-tag--green">Safe &amp; Fast</div>
                </div>
            
                <div class="hiw-connector reveal" style="--delay:0.3s">
                    <div class="hiw-connector-line"></div>
                    <div class="hiw-connector-dot"></div>
                </div>
            
                <div class="hiw-step reveal" style="--delay:0.4s">
                    <div class="hiw-step-num">03</div>
                    <div class="hiw-step-icon">
                        <img src="{{ asset('web/assets/icons/lucide/trending-up.svg') }}" alt="Trade" />
                    </div>
                    <h3>Start Trading</h3>
                    <p>Use real-time market data, expert signals, and smart tools to execute Forex and crypto trades
                        with total confidence.</p>
                    <div class="hiw-step-tag">Live Markets</div>
                </div>
            
                <div class="hiw-connector reveal" style="--delay:0.5s">
                    <div class="hiw-connector-line"></div>
                    <div class="hiw-connector-dot"></div>
                </div>
            
                <div class="hiw-step reveal" style="--delay:0.6s">
                    <div class="hiw-step-num">04</div>
                    <div class="hiw-step-icon hiw-step-icon--green">
                        <img src="{{ asset('web/assets/icons/lucide/award.svg') }}" alt="Earn" />
                    </div>
                    <h3>Grow &amp; Earn</h3>
                    <p>Unlock performance ranks, referral rewards, and passive income streams. Build your network and
                        watch your wealth multiply.</p>
                    <div class="hiw-step-tag hiw-step-tag--green">Earn Rewards</div>
                </div>
            
            </div>
        </div>
        </section>
        
        <!-- ============================================================
     PAGE 05 — WHY DEXTRADE (Feature Cards)
     ============================================================ -->
    <section class="why-us-section" id="why-us">
        <div class="why-us-bg-overlay"></div>

        <div class="why-us-container">
            <div class="text-center reveal">
                <span class="section-tag">
                    <img src="{{ asset('web/assets/icons/lucide/star.svg') }}" alt="" class="tag-icon" />
                    Our Edge
                    </span>
                <h2 class="section-title">Why Choose<br /><span class="why-accent">Dextrade?</span></h2>
                <div class="divider"></div>
                <p class="section-subtitle">
                    Six reasons thousands of traders and investors trust Dextrade as their platform of choice.
                    </p>
                    </div>

            <div class="why-us-grid">

                <!-- 1 -->
                <div class="why-card reveal" style="--delay:0s">
                    <div class="why-card-top">
                        <div class="why-card-icon-wrap">
                            <div class="why-card-icon">
                                <img src="{{ asset('web/assets/icons/lucide/activity.svg') }}" alt="Live Data" />
                            </div>
                            <div class="why-icon-glow"></div>
                            </div>
                            </div>
                    <h3>Live Market Intelligence</h3>
                    <p>Real-time price feeds, live charts, and instant market alerts across all major Forex pairs and
                        cryptocurrencies — refreshed every second so your edge is always current.</p>
                    <span class="why-card-tag">Real-Time Data</span>
                    </div>

                <!-- 2 -->
                <div class="why-card reveal" style="--delay:0.1s">
                    <div class="why-card-top">
                        <div class="why-card-icon-wrap">
                            <div class="why-card-icon why-card-icon--amber">
                                <img src="{{ asset('web/assets/icons/lucide/shield-check.svg') }}" alt="Secure" />
                            </div>
                            <div class="why-icon-glow why-icon-glow--amber"></div>
                            </div>
                            </div>
                    <h3>Bank-Grade Security</h3>
                    <p>End-to-end encryption, 2FA authentication, and multi-layer asset protection keep your funds and
                        personal data completely safe on Dextrade.</p>
                    <span class="why-card-tag why-card-tag--amber">256-bit Encrypted</span>
                    </div>

                <!-- 3 -->
                <div class="why-card reveal" style="--delay:0.2s">
                    <div class="why-card-top">
                        <div class="why-card-icon-wrap">
                            <div class="why-card-icon">
                                <img src="{{ asset('web/assets/icons/lucide/users.svg') }}" alt="Community" />
                            </div>
                            <div class="why-icon-glow"></div>
                            </div>
                            </div>
                    <h3>Community-Powered Growth</h3>
                    <p>Join a global network of traders and investors. Dextrade's referral ecosystem and performance
                        ranks let your community become one of your most powerful income streams.</p>
                    <span class="why-card-tag">Global Network</span>
                    </div>

                <!-- 4 -->
                <div class="why-card reveal" style="--delay:0.3s">
                    <div class="why-card-top">
                        <div class="why-card-icon-wrap">
                            <div class="why-card-icon why-card-icon--amber">
                                <img src="{{ asset('web/assets/icons/lucide/globe.svg') }}" alt="Global" />
                            </div>
                            <div class="why-icon-glow why-icon-glow--amber"></div>
                            </div>
                            </div>
                    <h3>Borderless Access</h3>
                    <p>Trade and invest from anywhere in the world. Dextrade removes geographical barriers — all you
                        need is a device and an internet connection to access global markets 24/7.</p>
                    <span class="why-card-tag why-card-tag--amber">Worldwide</span>
                    </div>

                <!-- 5 -->
                <div class="why-card reveal" style="--delay:0.4s">
                    <div class="why-card-top">
                        <div class="why-card-icon-wrap">
                            <div class="why-card-icon">
                                <img src="{{ asset('web/assets/icons/lucide/zap.svg') }}" alt="Fast" />
                            </div>
                            <div class="why-icon-glow"></div>
                            </div>
                            </div>
                            <h3>Ultra-Fast Execution</h3>
                            <p>Every millisecond matters in trading. Dextrade's infrastructure is built for speed — delivering
                                the lowest latency trade execution and data delivery in the market.</p>
                            <span class="why-card-tag">Low Latency</span>
                </div>

                <!-- 6 -->
                <div class="why-card reveal" style="--delay:0.5s">
                    <div class="why-card-top">
                        <div class="why-card-icon-wrap">
                            <div class="why-card-icon why-card-icon--amber">
                                <img src="{{ asset('web/assets/icons/lucide/badge-check.svg') }}" alt="Trusted" />
                            </div>
                            <div class="why-icon-glow why-icon-glow--amber"></div>
                            </div>
                            </div>
                            <h3>Transparent &amp; Trusted</h3>
                            <p>No hidden fees, no fine print surprises. Dextrade operates on full transparency — every
                                transaction, rank, and reward is visible, auditable, and fair for all participants.</p>
                            <span class="why-card-tag why-card-tag--amber">100% Transparent</span>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
     PAGE 06 — BENEFITS / FEATURES SPLIT ROWS
     ============================================================ -->
    <section class="benefits-section vs11" id="benefits">
        <div class="ben-glow-line"></div>
        <div class="ben-bg-overlay"></div>
        <div class="ben-container">

            <div class="text-center reveal">
                <span class="section-tag">
                    <img src="{{ asset('web/assets/icons/lucide/star.svg') }}" alt="" class="tag-icon" />
                    Platform Benefits
                </span>
                <h2 class="section-title">Trade Smarter with<br /><span class="ben-accent">Dextrade</span></h2>
                <div class="divider"></div>
                <p class="section-subtitle">
                    Discover the features that separate Dextrade from every other platform in the market.
                </p>
            </div>

            <div class="ben-row reveal-left">
                <div class="ben-img-col">
                    <div class="ben-img-wrap">
                        <img src="{{ asset('web/assets/images/mobileview.png') }}" alt="Mobile Trading" />
                        <div class="ben-img-glow"></div>
                    </div>
                </div>
                <div class="ben-content-col">
                    <h3 class="ben-heading">Trade Anytime,<br />Anywhere</h3>
                    <p class="ben-desc">Dextrade is fully optimized for mobile and desktop. Access live markets, manage
                        your portfolio, and execute trades from any device — 24 hours a day, 7 days a week, across all
                        time zones.</p>
                    <div class="ben-points">
                        <div class="ben-point">
                            <div class="ben-point-icon ben-point-icon--amber">
                                <img src="{{ asset('web/assets/icons/lucide/globe.svg') }}" alt="" />
                            </div>
                            <div>
                                <span class="ben-point-title">24/7 Market Access</span>
                                <span class="ben-point-sub">Global markets open around the clock, every day</span>
                            </div>
                        </div>
                        <div class="ben-point">
                            <div class="ben-point-icon ben-point-icon--teal">
                                <img src="{{ asset('web/assets/icons/lucide/zap.svg') }}" alt="" />
                            </div>
                            <div>
                                <span class="ben-point-title">Instant Execution</span>
                                <span class="ben-point-sub">Orders filled in milliseconds with zero slippage</span>
                            </div>
                        </div>
                        <div class="ben-point">
                            <div class="ben-point-icon ben-point-icon--amber">
                                <img src="{{ asset('web/assets/icons/lucide/dollar-sign.svg') }}" alt="" />
                            </div>
                            <div>
                                <span class="ben-point-title">Zero Hidden Fees</span>
                                <span class="ben-point-sub">Transparent pricing — what you see is what you pay</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        </section>

    <section class="benefits-section vs12" id="benefits2">
        <div class="ben-glow-line"></div>
        <div class="ben-bg-overlay"></div>
        <div class="ben-container">
            <div class="ben-row ben-row--reverse reveal-right">
                <div class="ben-img-col">
                    <div class="ben-img-wrap">
                        <img src="{{ asset('web/assets/images/image101.png') }}" alt="Security" />
                        <div class="ben-img-glow ben-img-glow--amber"></div>
                    </div>
                </div>
                <div class="ben-content-col">
                    <h3 class="ben-heading">Your Assets,<br />Always Protected</h3>
                    <p class="ben-desc">Security is the foundation of Dextrade. Every account, transaction, and wallet
                        is shielded by military-grade encryption and multi-factor authentication — giving you complete
                        peace of mind.</p>
                    <div class="ben-points">
                        <div class="ben-point">
                            <div class="ben-point-icon ben-point-icon--teal">
                                <img src="{{ asset('web/assets/icons/lucide/lock.svg') }}" alt="" />
                            </div>
                            <div>
                                <span class="ben-point-title">Multi-Layer Security</span>
                                <span class="ben-point-sub">256-bit encryption + 2FA + cold storage protection</span>
                            </div>
                        </div>
                        <div class="ben-point">
                            <div class="ben-point-icon ben-point-icon--amber">
                                <img src="{{ asset('web/assets/icons/lucide/eye.svg') }}" alt="" />
                            </div>
                            <div>
                                <span class="ben-point-title">Full Transparency</span>
                                <span class="ben-point-sub">Every transaction recorded and verifiable on-chain</span>
                            </div>
                        </div>
                        <div class="ben-point">
                            <div class="ben-point-icon ben-point-icon--teal">
                                <img src="{{ asset('web/assets/icons/lucide/link-2.svg') }}" alt="" />
                            </div>
                            <div>
                                <span class="ben-point-title">Immutable Ledger</span>
                                <span class="ben-point-sub">Blockchain-backed records that cannot be altered</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>

    <section class="benefits-section vs13" id="benefits3">
        <div class="ben-glow-line"></div>
        <div class="ben-bg-overlay"></div>
        <div class="ben-container">
            <div class="ben-row reveal-left">
                <div class="ben-img-col">
                    <div class="ben-img-wrap">
                        <img src="{{ asset('web/assets/images/image102.png') }}" alt="Passive Income" />
                        <div class="ben-img-glow"></div>
                    </div>
                </div>
                <div class="ben-content-col">
                    <h3 class="ben-heading">Build Wealth,<br />Multiply Income</h3>
                    <p class="ben-desc">Dextrade isn't just a trading platform — it's a wealth ecosystem. Through our
                        performance-based rewards, referral network, and structured investment plans, your money works
                        harder every day.</p>
                    <div class="ben-points">
                        <div class="ben-point">
                            <div class="ben-point-icon ben-point-icon--amber">
                                <img src="{{ asset('web/assets/icons/lucide/trending-up.svg') }}" alt="" />
                            </div>
                            <div>
                                <span class="ben-point-title">Structured Earning Plans</span>
                                <span class="ben-point-sub">Multiple income tiers designed to scale with your
                                    growth</span>
                            </div>
                        </div>
                        <div class="ben-point">
                            <div class="ben-point-icon ben-point-icon--teal">
                                <img src="{{ asset('web/assets/icons/lucide/users.svg') }}" alt="" />
                            </div>
                            <div>
                                <span class="ben-point-title">Referral Rewards</span>
                                <span class="ben-point-sub">Earn passive commissions as your network expands</span>
                            </div>
                        </div>
                        <div class="ben-point">
                            <div class="ben-point-icon ben-point-icon--amber">
                                <img src="{{ asset('web/assets/icons/lucide/award.svg') }}" alt="" />
                            </div>
                            <div>
                                <span class="ben-point-title">Performance Ranks</span>
                                <span class="ben-point-sub">Unlock elite benefits and bonuses as you level up</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
     PAGE 07 — FAQs
     ============================================================ -->
    <section class="faq-section" id="faq">
        <div class="faq-bg-overlay"></div>
        <div class="faq-container">

            <div class="text-center reveal">
                <span class="section-tag">
                    <img src="{{ asset('web/assets/icons/lucide/help-circle.svg') }}" alt="" class="tag-icon" />
                    Got Questions?
                </span>
                <h2 class="section-title">Frequently Asked<br /><span class="faq-accent">Questions</span></h2>
                <div class="divider"></div>
                <p class="section-subtitle">
                    Everything you need to know about Dextrade — answered clearly and honestly.
                </p>
            </div>

            <div class="faq-grid">

                <!-- Left Column -->
                <div class="faq-col">

                    <div class="faq-item reveal" style="--delay:0s">
                        <button class="faq-question" aria-expanded="false">
                            <span class="faq-q-icon"><img src="{{ asset('web/assets/icons/lucide/circle-help.svg') }}" alt="" /></span>
                            <span>What is Dextrade?</span>
                            <span class="faq-chevron"><img src="{{ asset('web/assets/icons/lucide/chevron-down.svg') }}" alt="" /></span>
                        </button>
                        <div class="faq-answer">
                            <p>Dextrade is a next-generation digital trading and investment platform that combines
                                Forex, cryptocurrency, DeFi, and structured income opportunities into one powerful
                                ecosystem. Our mission is simple: Trade. Invest. Grow.</p>
                        </div>
                    </div>

                    <div class="faq-item reveal" style="--delay:0.05s">
                        <button class="faq-question" aria-expanded="false">
                            <span class="faq-q-icon"><img src="{{ asset('web/assets/icons/lucide/circle-help.svg') }}" alt="" /></span>
                            <span>Who can join Dextrade?</span>
                            <span class="faq-chevron"><img src="{{ asset('web/assets/icons/lucide/chevron-down.svg') }}" alt="" /></span>
                        </button>
                        <div class="faq-answer">
                            <p>Dextrade is open to anyone — beginners, experienced traders, or passive investors.
                                Whether you're looking to trade Forex, invest in crypto, or build a passive income
                                stream through our referral network, there's a place for you on Dextrade.</p>
                        </div>
                    </div>

                    <div class="faq-item reveal" style="--delay:0.1s">
                        <button class="faq-question" aria-expanded="false">
                            <span class="faq-q-icon"><img src="{{ asset('web/assets/icons/lucide/circle-help.svg') }}" alt="" /></span>
                            <span>How do I create an account?</span>
                            <span class="faq-chevron"><img src="{{ asset('web/assets/icons/lucide/chevron-down.svg') }}" alt="" /></span>
                        </button>
                        <div class="faq-answer">
                            <p>Creating a Dextrade account takes just a few minutes. Click "Join Now," complete your
                                registration form, verify your email, and you'll have instant access to the full
                                platform — including live market data, trading tools, and your personal dashboard.</p>
                        </div>
                    </div>

                    <div class="faq-item reveal" style="--delay:0.15s">
                        <button class="faq-question" aria-expanded="false">
                            <span class="faq-q-icon"><img src="{{ asset('web/assets/icons/lucide/circle-help.svg') }}" alt="" /></span>
                            <span>What earning opportunities does Dextrade offer?</span>
                            <span class="faq-chevron"><img src="{{ asset('web/assets/icons/lucide/chevron-down.svg') }}" alt="" /></span>
                        </button>
                        <div class="faq-answer">
                            <p>Dextrade offers multiple income streams: direct Forex and crypto trading profits,
                                structured investment plans, referral commissions from your network, and
                                performance-based rank rewards that unlock as you grow on the platform.</p>
                        </div>
                    </div>

                    <div class="faq-item reveal" style="--delay:0.2s">
                        <button class="faq-question" aria-expanded="false">
                            <span class="faq-q-icon"><img src="{{ asset('web/assets/icons/lucide/circle-help.svg') }}" alt="" /></span>
                            <span>Is Dextrade available worldwide?</span>
                            <span class="faq-chevron"><img src="{{ asset('web/assets/icons/lucide/chevron-down.svg') }}" alt="" /></span>
                        </button>
                        <div class="faq-answer">
                            <p>Yes. Dextrade is a globally accessible platform. Members from countries across Asia,
                                Europe, Africa, and the Americas are actively trading and earning on Dextrade every day.
                                No geographical restrictions apply to joining or using the platform.</p>
                        </div>
                    </div>

                </div>

                <!-- Right Column -->
                <div class="faq-col">

                    <div class="faq-item reveal" style="--delay:0.05s">
                        <button class="faq-question" aria-expanded="false">
                            <span class="faq-q-icon"><img src="{{ asset('web/assets/icons/lucide/circle-help.svg') }}" alt="" /></span>
                            <span>How secure is my money on Dextrade?</span>
                            <span class="faq-chevron"><img src="{{ asset('web/assets/icons/lucide/chevron-down.svg') }}" alt="" /></span>
                        </button>
                        <div class="faq-answer">
                            <p>Your security is our top priority. Dextrade uses 256-bit encryption, two-factor
                                authentication, and multi-layer cold storage for digital assets. All transactions are
                                recorded on an immutable blockchain ledger — fully transparent and tamper-proof.</p>
                        </div>
                    </div>

                    <div class="faq-item reveal" style="--delay:0.1s">
                        <button class="faq-question" aria-expanded="false">
                            <span class="faq-q-icon"><img src="{{ asset('web/assets/icons/lucide/circle-help.svg') }}" alt="" /></span>
                            <span>What cryptocurrencies does Dextrade support?</span>
                            <span class="faq-chevron"><img src="{{ asset('web/assets/icons/lucide/chevron-down.svg') }}" alt="" /></span>
                        </button>
                        <div class="faq-answer">
                            <p>Dextrade supports a wide range of leading cryptocurrencies including Bitcoin (BTC),
                                Ethereum (ETH), BNB, Solana (SOL), XRP, USDT, USDC, Cardano (ADA), Dogecoin (DOGE), and
                                Avalanche (AVAX) — with more assets being added regularly.</p>
                        </div>
                    </div>

                    <div class="faq-item reveal" style="--delay:0.15s">
                        <button class="faq-question" aria-expanded="false">
                            <span class="faq-q-icon"><img src="{{ asset('web/assets/icons/lucide/circle-help.svg') }}" alt="" /></span>
                            <span>How does the referral and rank system work?</span>
                            <span class="faq-chevron"><img src="{{ asset('web/assets/icons/lucide/chevron-down.svg') }}" alt="" /></span>
                        </button>
                        <div class="faq-answer">
                            <p>When you refer new members to Dextrade, you earn commissions based on their activity. As
                                your network and trading volume grow, you progress through performance ranks — each
                                unlocking higher reward tiers, bonuses, and exclusive platform benefits.</p>
                        </div>
                    </div>

                    <div class="faq-item reveal" style="--delay:0.2s">
                        <button class="faq-question" aria-expanded="false">
                            <span class="faq-q-icon"><img src="{{ asset('web/assets/icons/lucide/circle-help.svg') }}" alt="" /></span>
                            <span>Do I need trading experience to start?</span>
                            <span class="faq-chevron"><img src="{{ asset('web/assets/icons/lucide/chevron-down.svg') }}" alt="" /></span>
                        </button>
                        <div class="faq-answer">
                            <p>Not at all. Dextrade is designed for all skill levels. Our Trading Academy provides
                                structured learning paths from beginner to advanced — so whether you've never placed a
                                trade before or you're a seasoned investor, Dextrade has everything you need to succeed.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item reveal" style="--delay:0.25s">
                        <button class="faq-question" aria-expanded="false">
                            <span class="faq-q-icon"><img src="{{ asset('web/assets/icons/lucide/circle-help.svg') }}" alt="" /></span>
                            <span>How do I withdraw my earnings?</span>
                            <span class="faq-chevron"><img src="{{ asset('web/assets/icons/lucide/chevron-down.svg') }}" alt="" /></span>
                        </button>
                        <div class="faq-answer">
                            <p>Withdrawals on Dextrade are fast and straightforward. Head to your wallet dashboard,
                                select the withdrawal option, choose your preferred method (crypto transfer or supported
                                payment channel), and your funds will be processed within the defined timeframe.</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
     FOOTER
     ============================================================ -->
    <footer class="footer" id="footer">
        <div class="footer-glow-line"></div>

        <!-- CTA Banner -->
        <div class="footer-cta-banner">
            <div class="footer-cta-inner">
                <div class="footer-cta-text">
                    <h3>Ready to <span>Trade. Invest. Grow?</span></h3>
                    <p>Join thousands of traders and investors already building their financial future with Dextrade.
                    </p>
                </div>
                <a href="#home" class="footer-cta-btn">
                    <img src="{{ asset('web/assets/icons/lucide/rocket.svg') }}" alt="" />
                    Get Started Free
                </a>
            </div>
        </div>
        
        <!-- Main Grid -->
        <div class="footer-main">

            <div class="footer-brand">
                <div class="logo-wrap">
                    <img src="{{ asset('web/assets/images/logo.png') }}" alt="Dextrade Logo" />
                </div>
                <p>Dextrade is a next-generation trading and investment platform — combining Forex, crypto, DeFi, and
                    structured income into one powerful, transparent ecosystem.</p>
                <div class="footer-tagline">Trade. Invest. Grow.</div>
                <div class="footer-socials">
                    <a href="#" class="fsocial-btn" aria-label="Telegram">
                        <img src="{{ asset('web/assets/icons/lucide/send.svg') }}" alt="Telegram" />
                    </a>
                    <a href="#" class="fsocial-btn" aria-label="Twitter">
                        <img src="{{ asset('web/assets/icons/lucide/twitter.svg') }}" alt="Twitter" />
                    </a>
                    <a href="#" class="fsocial-btn" aria-label="Instagram">
                        <img src="{{ asset('web/assets/icons/lucide/instagram.svg') }}" alt="Instagram" />
                    </a>
                    <a href="#" class="fsocial-btn" aria-label="Youtube">
                        <img src="{{ asset('web/assets/icons/lucide/youtube.svg') }}" alt="Youtube" />
                    </a>
                    <a href="#" class="fsocial-btn" aria-label="Globe">
                        <img src="{{ asset('web/assets/icons/lucide/globe.svg') }}" alt="Website" />
                    </a>
                </div>
            </div>

            <div class="footer-links">
                <h4>Navigation</h4>
                <a href="#home">Home</a>
                <a href="#about">About Us</a>
                <a href="#services">Our Services</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#why-us">Why Dextrade</a>
                <a href="#faq">FAQs</a>
            </div>

            <div class="footer-links">
                <h4>Services</h4>
                <a href="#forex">Forex Trading</a>
                <a href="#crypto">Crypto Investment</a>
                <a href="#analytics">Smart Analytics</a>
                <a href="#defi">DeFi &amp; Web3</a>
                <a href="#education">Trading Academy</a>
                <a href="#passive">Passive Income</a>
            </div>

            <div class="footer-links">
                <h4>Company</h4>
                <a href="#">Terms of Use</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Risk Disclosure</a>
                <a href="#">Referral Program</a>
                <a href="#">Contact Us</a>
            </div>

        </div>

        <div class="footer-bottom">
            <span>&copy; 2026 Dextrade. All rights reserved.</span>
            <span class="footer-sep">|</span>
            <span>Trade. Invest. Grow.</span>
        </div>
    </footer>

    <script src="{{ asset('web/assets/js/main.js') }}"></script>
</body>

</html>