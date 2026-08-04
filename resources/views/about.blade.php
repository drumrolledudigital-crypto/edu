@extends('layouts.app')

@section('title', 'About Us | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- 1. Hero Section -->
<section class="relative pt-20 pb-32 px-4 md:px-12 bg-white overflow-hidden">
    <div class="absolute top-0 right-0 w-1/2 h-full bg-primary/5 rounded-bl-[100px] -z-10"></div>
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="fade-up z-10">
            <span class="inline-block py-1.5 px-4 rounded-full bg-accent/20 text-secondary font-bold text-sm mb-6 tracking-wide uppercase">
                More Than Tutoring
            </span>
            <h1 class="text-5xl lg:text-7xl font-black text-secondary leading-tight mb-6">
                Sparking the <br/><span class="text-primary">Joy of Learning</span>
            </h1>
            <p class="text-xl text-gray-600 mb-10 max-w-lg leading-relaxed">
                We started Drumroll because we believe every child has a unique rhythm. We're here to help them find it, practice it, and perform with confidence.
            </p>
            <a href="{{ route('student.booking.create') }}" class="inline-flex items-center justify-center px-8 py-4 text-white bg-primary hover:bg-secondary rounded-full font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                Book a Free Session
            </a>
        </div>
        <div class="relative fade-up" style="transition-delay: 0.2s;">
            <div class="relative w-full max-w-lg mx-auto">
                <div class="absolute inset-0 bg-accent rounded-full opacity-20 blur-3xl"></div>
                <img src="https://illustrations.popsy.co/pink/student-going-to-school.svg" alt="Happy student" class="relative z-10 w-full animate-float">
                
                <!-- Floating Elements -->
                <div class="absolute -left-8 top-1/4 w-16 h-16 bg-white rounded-2xl shadow-soft flex items-center justify-center text-primary text-2xl animate-float" style="animation-delay: 1s;">
                    <i class="fas fa-star"></i>
                </div>
                <div class="absolute -right-4 bottom-1/4 w-14 h-14 bg-white rounded-xl shadow-soft flex items-center justify-center text-primary-500 text-xl animate-float" style="animation-delay: 0.5s;">
                    <i class="fas fa-lightbulb"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 1b. Our Mission -->
<section class="py-20 px-4 md:px-12 bg-white">
    <div class="max-w-4xl mx-auto text-center fade-up">
        <span class="inline-block py-1.5 px-4 rounded-full bg-primary/10 text-primary font-bold text-sm mb-6 tracking-wide uppercase">Our Mission</span>
        <p class="text-xl md:text-2xl text-secondary font-bold leading-relaxed mb-6">
            At Drumroll Edu, we believe that every child has the potential to learn, grow, and thrive when provided with the right guidance and resources. Our mission is to make learning engaging, accessible, and enjoyable while building confidence, curiosity, and a lifelong love for education.
        </p>
        <p class="text-lg text-gray-600 leading-relaxed">
            We are an innovative e-learning platform dedicated to supporting children in their academic journey through personalized online tutoring and thoughtfully designed educational resources. By combining expert teaching with high-quality practice materials, we help students develop strong foundational skills and achieve their learning goals at their own pace.
        </p>
    </div>
</section>

<!-- 2. Our Story (Timeline Style) -->
<section class="py-24 px-4 md:px-12 bg-light relative">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl md:text-5xl font-black text-secondary mb-6">How It All Started</h2>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto">A journey born from a simple realization: standard classrooms don't fit every child.</p>
        </div>

        <div class="space-y-12 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-1 before:bg-gradient-to-b before:from-transparent before:via-primary/20 before:to-transparent">
            
            <!-- Story Item 1 -->
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active fade-up">
                <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-primary text-white font-bold shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-soft z-10">
                    1
                </div>
                <div class="w-[calc(100%-4rem)] md:w-[calc(50%-3rem)] bg-white p-6 rounded-2xl shadow-soft hover:shadow-hover transition-shadow border border-gray-50">
                    <h3 class="text-xl font-bold text-secondary mb-2">The Struggle</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">We saw brilliant kids losing confidence simply because a concept wasn't explained in a way that clicked for them. Homework became a battleground instead of a bridge to knowledge.</p>
                </div>
            </div>

            <!-- Story Item 2 -->
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active fade-up">
                <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-accent text-secondary font-bold shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-soft z-10">
                    2
                </div>
                <div class="w-[calc(100%-4rem)] md:w-[calc(50%-3rem)] bg-white p-6 rounded-2xl shadow-soft hover:shadow-hover transition-shadow border border-gray-50">
                    <h3 class="text-xl font-bold text-secondary mb-2">The Purpose</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">We decided to change the narrative. What if learning felt like an exciting game? What if every child had a mentor who truly understood their learning style?</p>
                </div>
            </div>

            <!-- Story Item 3 -->
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active fade-up">
                <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-secondary text-white font-bold shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-soft z-10">
                    3
                </div>
                <div class="w-[calc(100%-4rem)] md:w-[calc(50%-3rem)] bg-white p-6 rounded-2xl shadow-soft hover:shadow-hover transition-shadow border border-gray-50">
                    <h3 class="text-xl font-bold text-secondary mb-2">The Growth</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">Drumroll was born. From a small group of passionate educators, we've grown into a thriving community helping hundreds of students turn their 'I can't' into 'I did it!'.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 2b. Online Learning Programs & Practice Books -->
<section class="py-24 px-4 md:px-12 bg-light">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12">
        <div class="bg-white rounded-[2.5rem] p-10 shadow-soft border border-gray-50 fade-up">
            <div class="w-14 h-14 rounded-2xl bg-primary-50 flex items-center justify-center text-primary-500 text-2xl mb-6">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <h3 class="text-2xl md:text-3xl font-black text-secondary mb-4">Our Online Learning Programs</h3>
            <p class="text-gray-600 leading-relaxed mb-6">
                At Drumroll Edu, we offer interactive online tutoring for students up to Year/Grade 8, tailored to meet each child's unique learning needs. Our experienced educators create a supportive and encouraging environment where students feel comfortable asking questions, exploring new concepts, and building confidence in their abilities.
            </p>
            <p class="text-secondary font-bold mb-4">Our online sessions focus on:</p>
            <ul class="space-y-3 text-gray-600 text-sm">
                <li class="flex items-start gap-3"><i class="fas fa-check-circle text-primary mt-1"></i> Personalized learning plans</li>
                <li class="flex items-start gap-3"><i class="fas fa-check-circle text-primary mt-1"></i> English and Mathematics support</li>
                <li class="flex items-start gap-3"><i class="fas fa-check-circle text-primary mt-1"></i> Grammar, vocabulary, reading comprehension, and creative writing</li>
                <li class="flex items-start gap-3"><i class="fas fa-check-circle text-primary mt-1"></i> Numeracy skills, problem-solving, and mathematical reasoning</li>
                <li class="flex items-start gap-3"><i class="fas fa-check-circle text-primary mt-1"></i> Homework assistance and exam preparation</li>
                <li class="flex items-start gap-3"><i class="fas fa-check-circle text-primary mt-1"></i> Skill-building activities that encourage critical thinking and creativity</li>
            </ul>
        </div>

        <div class="bg-white rounded-[2.5rem] p-10 shadow-soft border border-gray-50 fade-up" style="transition-delay: 0.1s;">
            <div class="w-14 h-14 rounded-2xl bg-yellow-50 flex items-center justify-center text-yellow-600 text-2xl mb-6">
                <i class="fas fa-book-open"></i>
            </div>
            <h3 class="text-2xl md:text-3xl font-black text-secondary mb-4">Engaging Practice Books for Young Learners</h3>
            <p class="text-gray-600 leading-relaxed mb-6">
                We understand that consistent practice plays a vital role in academic success. That's why we create carefully designed practice books that transform learning into an enjoyable experience.
            </p>
            <p class="text-secondary font-bold mb-4">Our practice books are:</p>
            <ul class="space-y-3 text-gray-600 text-sm">
                <li class="flex items-start gap-3"><i class="fas fa-check-circle text-accent mt-1"></i> Age-appropriate and curriculum-aligned</li>
                <li class="flex items-start gap-3"><i class="fas fa-check-circle text-accent mt-1"></i> Visually engaging and easy to follow</li>
                <li class="flex items-start gap-3"><i class="fas fa-check-circle text-accent mt-1"></i> Designed to reinforce classroom learning</li>
                <li class="flex items-start gap-3"><i class="fas fa-check-circle text-accent mt-1"></i> Filled with interactive exercises and skill-building activities</li>
                <li class="flex items-start gap-3"><i class="fas fa-check-circle text-accent mt-1"></i> Created to strengthen understanding, retention, and confidence</li>
            </ul>
            <a href="{{ route('books.index') }}" class="inline-flex items-center gap-2 mt-6 font-bold text-primary hover:text-secondary transition text-sm">
                Browse Practice Books <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>
</section>

<!-- 3. Learning Philosophy (Split Layout) -->
<section class="py-24 px-4 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-16 items-center">
        <div class="lg:w-1/2 fade-up">
            <h2 class="text-4xl md:text-5xl font-black text-secondary mb-8">Our Learning Philosophy</h2>
            <div class="space-y-8">
                <div class="flex gap-4 group">
                    <div class="w-14 h-14 rounded-2xl bg-primary-50 flex items-center justify-center text-primary-500 text-2xl shrink-0 group-hover:scale-110 group-hover:bg-primary-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-puzzle-piece"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-secondary mb-2">Learning by Doing</h4>
                        <p class="text-gray-600">Active participation over passive listening. We use interactive tools, puzzles, and real-world examples to make concepts stick.</p>
                    </div>
                </div>
                <div class="flex gap-4 group">
                    <div class="w-14 h-14 rounded-2xl bg-pink-50 flex items-center justify-center text-primary text-2xl shrink-0 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-secondary mb-2">Confidence First</h4>
                        <p class="text-gray-600">Before a child can master math or English, they must believe they can. We celebrate small wins to build unstoppable confidence.</p>
                    </div>
                </div>
                <div class="flex gap-4 group">
                    <div class="w-14 h-14 rounded-2xl bg-yellow-50 flex items-center justify-center text-yellow-600 text-2xl shrink-0 group-hover:scale-110 group-hover:bg-accent group-hover:text-secondary transition-all duration-300">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-secondary mb-2">Personal Connection</h4>
                        <p class="text-gray-600">A strong mentor-student bond is the catalyst for growth. Our educators are patient, friendly, and deeply invested in your child's success.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:w-1/2 fade-up" style="transition-delay: 0.2s;">
            <div class="relative">
                <div class="absolute inset-0 bg-primary translate-x-4 translate-y-4 rounded-[2.5rem] opacity-10"></div>
                <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&q=80&w=800&h=600" alt="Happy student learning" class="relative z-10 w-full h-[500px] object-cover rounded-[2.5rem] shadow-soft">
                
                <!-- Floating badge -->
                <div class="absolute -bottom-6 -left-6 bg-white p-5 rounded-2xl shadow-hover z-20 animate-float">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-100 text-green-500 rounded-full flex items-center justify-center text-xl">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-bold">Average Growth</p>
                            <p class="text-xl font-black text-secondary">+40% Scores</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. What Makes Us Different -->
<section class="py-24 px-4 md:px-12 bg-secondary text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent/20 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-4xl md:text-5xl font-black mb-4">Why Choose <span class="text-primary">Drumroll Edu?</span></h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">We don't just teach; we transform the way children perceive education.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $features = [
                    ['icon' => 'user-graduate', 'title' => 'Student-Centred Learning', 'desc' => 'Every session is built around your child\'s pace, interests, and needs.'],
                    ['icon' => 'chalkboard-user', 'title' => 'Experienced Educators', 'desc' => 'Passionate, qualified mentors who genuinely care about every learner.'],
                    ['icon' => 'calendar-check', 'title' => 'Flexible Online Learning', 'desc' => 'Learn from anywhere, on a schedule that fits your family.'],
                    ['icon' => 'book-open', 'title' => 'Thoughtfully Designed Books', 'desc' => 'Engaging, curriculum-aligned practice materials for every subject.'],
                    ['icon' => 'chart-line', 'title' => 'Progress Monitoring', 'desc' => 'Regular feedback so you always know how your child is growing.'],
                    ['icon' => 'heart', 'title' => 'Confidence & Creativity', 'desc' => 'A focus on academic growth alongside emotional well-being.'],
                ];
            @endphp
            @foreach($features as $index => $feature)
            <div class="bg-white/10 backdrop-blur-sm border border-white/10 p-8 rounded-card hover:bg-white/20 transition-all duration-300 hover:-translate-y-2 group fade-up" style="transition-delay: {{ 0.1 * $index }}s;">
                <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center text-accent text-2xl mb-6 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all">
                    <i class="fas fa-{{ $feature['icon'] }}"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">{{ $feature['title'] }}</h3>
                <p class="text-gray-300 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 5. Meet Our Founder -->
<section class="py-24 px-4 md:px-12 bg-light">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-4xl md:text-5xl font-black text-secondary mb-4">About <span class="text-primary">Preeti M</span> ✨</h2>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto">The passionate mentor behind Drumroll Edu.</p>
        </div>

        <div class="bg-primary text-white rounded-[2.5rem] p-10 md:p-14 relative overflow-hidden shadow-soft fade-up">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative z-10">
                <h3 class="text-2xl md:text-3xl font-black mb-1">Preeti Matharu</h3>
                <p class="text-white/80 font-bold text-sm uppercase tracking-wider mb-8">Founder, Drumroll Edu</p>
                <p class="text-white/90 leading-relaxed mb-4">
                    With over 10 years of experience in education, Preeti has worked extensively with both national and international students. She began her journey with a specialization in Early Childhood Care and Education (ECCE), and further expanded her expertise by studying Child Psychology.
                </p>
                <p class="text-white/90 leading-relaxed">
                    As a certified Play Therapy Practitioner, Preeti brings creativity and compassion into her practice, helping children express and heal through play. She holds a Master's in Counseling Psychology and is trained in evidence-based approaches such as REBT (Rational Emotive Behavior Therapy) and CBT (Cognitive Behavioral Therapy). Her passion lies in supporting emotional well-being, fostering resilience, and empowering individuals to thrive academically, socially, and personally.
                </p>
                <div class="flex flex-wrap gap-4 mt-8 text-sm font-bold">
                    <a href="tel:+919619759234" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 transition rounded-full px-5 py-3"><i class="fas fa-phone"></i> +91 96197 59234</a>
                    <a href="mailto:info@drumrolledu.com" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 transition rounded-full px-5 py-3"><i class="fas fa-envelope"></i> info@drumrolledu.com</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. Learning Journey (Horizontal Timeline) -->
<section class="py-24 px-4 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-4xl md:text-5xl font-black text-secondary mb-4">The Drumroll <span class="text-primary">Journey</span></h2>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto">A structured path from the first hello to academic confidence.</p>
        </div>

        <div class="relative">
            <!-- Connecting Line -->
            <div class="hidden lg:block absolute top-1/2 left-0 w-full h-1 bg-gray-100 -translate-y-1/2"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-8 relative z-10">
                @php
                    $steps = [
                        ['icon' => 'clipboard-check', 'color' => 'blue', 'title' => 'Assessment', 'desc' => 'Understanding current level'],
                        ['icon' => 'map-marked-alt', 'color' => 'pink', 'title' => 'Custom Plan', 'desc' => 'Mapping the route'],
                        ['icon' => 'video', 'color' => 'yellow', 'title' => 'Live Classes', 'desc' => 'Interactive 1-on-1'],
                        ['icon' => 'pencil-alt', 'color' => 'green', 'title' => 'Practice', 'desc' => 'Reinforcing concepts'],
                        ['icon' => 'chart-bar', 'color' => 'purple', 'title' => 'Review', 'desc' => 'Tracking progress'],
                        ['icon' => 'trophy', 'color' => 'red', 'title' => 'Confidence', 'desc' => 'Achieving goals'],
                    ];
                @endphp

                @foreach($steps as $index => $step)
                <div class="text-center fade-up" style="transition-delay: {{ 0.1 * $index }}s;">
                    <div class="w-16 h-16 mx-auto bg-white border-4 border-gray-100 rounded-full flex items-center justify-center text-{{ $step['color'] == 'pink' ? 'primary' : ($step['color'] == 'yellow' ? 'accent' : $step['color'].'-500') }} text-xl mb-4 shadow-sm relative group hover:border-{{ $step['color'] == 'pink' ? 'primary' : ($step['color'] == 'yellow' ? 'accent' : $step['color'].'-400') }} transition-colors">
                        <i class="fas fa-{{ $step['icon'] }} group-hover:scale-110 transition-transform"></i>
                    </div>
                    <h4 class="font-bold text-secondary mb-2">{{ $step['title'] }}</h4>
                    <p class="text-xs text-gray-500">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- 7. Parent Trust & 8. Why Parents Choose Us (Combined Grid) -->
<section class="py-24 px-4 md:px-12 bg-light">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        
        <!-- Checklist -->
        <div class="fade-up">
            <h2 class="text-3xl md:text-5xl font-black text-secondary mb-6">A Platform Parents <span class="text-primary">Trust</span></h2>
            <p class="text-gray-600 mb-10 text-lg">Your child's safety, privacy, and academic growth are our top priorities.</p>
            
            <div class="space-y-6">
                <div class="flex items-start gap-4 p-4 bg-white rounded-2xl shadow-sm border border-gray-50 hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-green-100 text-green-500 rounded-full flex items-center justify-center shrink-0">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-secondary">Verified Educators</h4>
                        <p class="text-sm text-gray-500">Strict background checks and vetting.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-white rounded-2xl shadow-sm border border-gray-50 hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-primary-100 text-primary-500 rounded-full flex items-center justify-center shrink-0">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-secondary">Secure Environment</h4>
                        <p class="text-sm text-gray-500">Safe video infrastructure and data privacy.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-white rounded-2xl shadow-sm border border-gray-50 hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center shrink-0">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-secondary">Transparent Progress</h4>
                        <p class="text-sm text-gray-500">Regular feedback loops and performance tracking.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static Testimonial Grid -->
        <div class="grid gap-6 fade-up" style="transition-delay: 0.2s;">
            <div class="bg-white p-8 rounded-card shadow-soft relative">
                <i class="fas fa-quote-right absolute top-8 right-8 text-4xl text-gray-100"></i>
                <div class="flex text-accent text-sm mb-4">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="text-gray-600 italic mb-6">"Finally, a tutoring platform that understands that my daughter gets anxious about math. The patience of her tutor is incredible. Her grades went from C to A-."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center text-primary font-bold">SM</div>
                    <div>
                        <h5 class="font-bold text-secondary text-sm">Sarah Mitchell</h5>
                        <p class="text-xs text-gray-400">Parent</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-secondary text-white p-8 rounded-card shadow-soft relative ml-0 md:ml-12">
                <i class="fas fa-quote-right absolute top-8 right-8 text-4xl text-white/10"></i>
                <div class="flex text-accent text-sm mb-4">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="text-gray-300 italic mb-6">"The 1-on-1 attention is unmatched. My son looks forward to his science sessions every week. Highly recommended for busy parents!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white font-bold">JD</div>
                    <div>
                        <h5 class="font-bold text-white text-sm">James Peterson</h5>
                        <p class="text-xs text-gray-400">Parent</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9. Fun Learning Environment (Gallery Layout) -->
<section class="py-24 px-4 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-4xl md:text-5xl font-black text-secondary mb-4">A Fun Learning <span class="text-primary">Environment</span></h2>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto">Where curiosity meets creativity.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 auto-rows-[200px]">
            <div class="col-span-2 row-span-2 bg-light rounded-2xl overflow-hidden relative group fade-up">
                <img src="https://illustrations.popsy.co/pink/remote-work.svg" alt="Online Learning" class="w-full h-full object-cover p-10 group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-secondary/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                    <h3 class="text-white font-bold text-xl">Interactive Tech</h3>
                </div>
            </div>
            <div class="bg-primary-50 rounded-2xl overflow-hidden relative group fade-up" style="transition-delay: 0.1s;">
                <img src="https://illustrations.popsy.co/blue/freelancer.svg" alt="Study" class="w-full h-full object-cover p-4 group-hover:scale-105 transition-transform duration-500">
            </div>
            <div class="bg-yellow-50 rounded-2xl overflow-hidden relative group fade-up" style="transition-delay: 0.2s;">
                <img src="https://illustrations.popsy.co/yellow/student-graduating.svg" alt="Success" class="w-full h-full object-cover p-4 group-hover:scale-105 transition-transform duration-500">
            </div>
            <div class="col-span-2 bg-green-50 rounded-2xl overflow-hidden relative group fade-up" style="transition-delay: 0.3s;">
                <img src="https://illustrations.popsy.co/green/man-reading-a-book.svg" alt="Reading" class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-500">
            </div>
        </div>
    </div>
</section>

<!-- 9b. Our Vision -->
<section class="py-24 px-4 md:px-12 bg-white">
    <div class="max-w-4xl mx-auto text-center fade-up">
        <span class="inline-block py-1.5 px-4 rounded-full bg-accent/20 text-secondary font-bold text-sm mb-6 tracking-wide uppercase">Our Vision</span>
        <p class="text-xl md:text-2xl text-secondary font-bold leading-relaxed mb-8">
            At Drumroll Edu, we envision a world where every child has access to quality education that inspires curiosity, nurtures potential, and celebrates individual growth. We are committed to empowering young learners with the knowledge, skills, and confidence they need to succeed both inside and outside the classroom.
        </p>
        <p class="text-lg text-primary font-black">Drumroll Edu — from practice to progress.<br>Big dreams begin with a Drumroll! 🌟</p>
    </div>
</section>

<!-- 10. Large Premium CTA -->
<section class="py-24 px-4 md:px-12 bg-light">
    <div class="max-w-6xl mx-auto">
        <div class="bg-secondary rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl fade-up">
            <div class="absolute -top-32 -left-32 w-64 h-64 border-[40px] border-primary/20 rounded-full"></div>
            <div class="absolute -bottom-32 -right-32 w-80 h-80 border-[50px] border-accent/20 rounded-full"></div>
            
            <div class="relative z-10">
                <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center text-3xl text-accent mx-auto mb-8">
                    <i class="fas fa-rocket"></i>
                </div>
                <h2 class="text-4xl md:text-6xl font-black text-white mb-6">Ready to help your child grow?</h2>
                <p class="text-white/80 text-lg md:text-xl mb-12 max-w-2xl mx-auto font-medium">Join the Drumroll family today. Experience premium, personalized education that children actually love.</p>
                
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="{{ route('student.booking.create') }}" class="w-full sm:w-auto bg-primary hover:bg-white hover:text-primary text-white font-black py-4 px-10 rounded-full shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        Book a Free Session
                    </a>
                    <a href="{{ route('contact') }}" class="w-full sm:w-auto bg-transparent border-2 border-white/30 text-white hover:bg-white/10 font-bold py-4 px-10 rounded-full transition-all duration-300">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection