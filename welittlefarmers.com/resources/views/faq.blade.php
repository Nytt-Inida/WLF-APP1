@extends('main')

@php
    $faqs = [
        [
            'question' => 'What is Little Farmers Academy?',
            'answer' => 'We are an online learning platform that delivers engaging, interactive courses in farming, food science and sustainability designed especially for children (ages 5–15). Our mission is to reconnect kids with nature, teach them where food comes from, and equip them with lifelong green skills.'
        ],
        [
            'question' => 'Who are the courses designed for?',
            'answer' => 'Our courses are primarily designed for children aged 5 to 15. However, we also offer “Any Age” live sessions (for example: Future Agriculturists AI or Robotics)—so younger siblings, teens, or even parents can join in.'
        ],
        [
            'question' => 'What kinds of courses do you offer?',
            'answer' => 'We offer a range of courses including: “Little Farmers Full Course” (for 5-15 years) with multiple classes, “Future Agriculturists – AI Live Session” (Any Age), and “Future Agriculturists – Robotics Live Session” (Any Age). Each course covers topics like growing food, understanding food science, sustainability, and fostering a connection with nature.'
        ],
        [
            'question' => 'How long are the courses and what happens when I complete one?',
            'answer' => 'For example, the Little Farmers full course includes 19 classes for age 5-15. At the end, participants receive a certificate which they can download. Beyond the certificate, the aim is to instill practical skills, awareness of healthy food, and a love of nature.'
        ],
        [
            'question' => 'Is the learning safe and suitable for kids?',
            'answer' => 'Yes. The platform is designed with children in mind: friendly language, age-appropriate content, and courses focused on positive values like caring for nature, sustainability, and health.'
        ],
        [
            'question' => 'Will my child get to build a robot?',
            'answer' => 'In some courses, yes! They will learn how to build and program robots for farming tasks.'
        ],
        [
            'question' => 'What are the benefits of joining Little Farmers Academy?',
            'answer' => 'Benefits include: Fun, interactive, and educational sessions for kids; Builds awareness about nature and sustainability; Encourages creativity, curiosity, and responsibility; Teaches real-life skills related to food and the environment; Certificates of completion for motivation and achievement.'
        ],
        [
            'question' => 'How are classes made engaging for kids?',
            'answer' => 'We use storytelling, videos, virtual farm tours, simple science experiments, and quizzes. Every class is built to be visually rich, interactive, and full of imagination — so learning feels like play.'
        ],
        [
            'question' => 'Who designs and teaches the courses?',
            'answer' => 'Our courses are developed by experts in food science, agriculture, child education, and sustainability. Lessons are simplified and tested with real students before being launched.'
        ],
        [
            'question' => 'How is Little Farmers different from other online classes?',
            'answer' => 'Unlike generic learning platforms, we focus exclusively on nature-based education. Our curriculum blends STEM + sustainability, helping children understand how technology and environment can coexist.'
        ],
        [
            'question' => 'What do I need to join a class?',
            'answer' => 'All you need is: A laptop, tablet, or smartphone; A stable internet connection; A curiosity to explore! After enrollment, you’ll receive a login link with all class details.'
        ],
        [
            'question' => 'Can we access lessons anytime?',
            'answer' => 'Yes! Recorded lessons are available 24×7 after enrollment. You can watch and replay them whenever convenient.'
        ],
        [
            'question' => 'What if we face technical issues during class?',
            'answer' => 'Our support team is always ready to assist via email and chat. We’ll make sure your child’s learning experience remains uninterrupted.'
        ],
        [
            'question' => 'How do I enroll my child?',
            'answer' => 'Visit welittlefarmers.com → choose a course → click Start Your Free Trial → make payment → receive confirmation instantly.'
        ],
        [
            'question' => 'What are the course fees?',
            'answer' => 'Fees vary by program. For instance, our Little Farmers Course is available at a special introductory rate.'
        ],
        [
            'question' => 'What is your refund policy?',
            'answer' => 'If you’re unsatisfied or face issues, contact us within 7 days of purchase. We’ll offer a refund or course credit — no questions asked.'
        ],
        [
            'question' => 'Do you offer family-oriented classes?',
            'answer' => 'Yes, we plan to host special events and workshops where families can learn and grow together.'
        ],
        [
            'question' => 'How is technology used in the classes?',
            'answer' => 'We use everything from simple tools like soil sensors to more advanced robotics and AI to show how technology is revolutionizing farming.'
        ],
        [
            'question' => 'Will this help with school learning or future careers?',
            'answer' => 'Definitely. Our courses build real-world awareness and curiosity in STEM, environment, and agriculture technology — skills that align with global sustainability careers of tomorrow.'
        ],
        [
            'question' => 'Are the courses the same all year round?',
            'answer' => 'No, our curriculum is seasonal, so what we plant and learn about changes with the time of year.'
        ],
        [
            'question' => 'Is there a certificate at the end of the course?',
            'answer' => 'Students who complete our adult courses receive a certificate of completion, highlighting the skills they have learned.'
        ]
    ];
@endphp

@push('head')
    <style>
        .faq-wrapper {
            max-width: 70rem;
            margin: 40px auto;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 15px;
        }

        .accordion-button::after {
            content: '\203A' !important;
            /* Unicode for single right-pointing angle quotation mark (›) */
            font-size: 1.4rem;
            color: black;
            transition: transform 0.2s;
            margin-left: auto;
            transform: rotate(0deg);
            /* points right */
        }

        .accordion-button:not(.collapsed)::after {
            transform: rotate(90deg) !important;
            /* points downward when expanded */
        }

        .accordion-body {
            font-size: 17px !important;
            padding-left: 2.7rem !important;
        }

        @media (max-width: 768px) {
            .accordion-body {
                padding-left: 0.5px !important;
            }

        }
    </style>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "{{ route('home') }}"
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "FAQ",
        "item": "{{ route('faq') }}"
      }]
    }
    </script>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        @foreach ($faqs as $index => $faq)
        {
          "@type": "Question",
          "name": "{{ $faq['question'] }}",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "{{ $faq['answer'] }}"
          }
        }@if (!$loop->last),@endif
        @endforeach
      ]
    }
    </script>
@endpush

@section('content')
    <main>

        <!-- FAQ Section -->

        <section class="faq-area pt-150 pb-30 pt-md-100 pb-md-70 pt-xs-100 pb-xs-70">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title text-center mb-60">
                            <h5 class="bottom-line mb-25">Frequently Asked Questions</h5>
                            <h2 class="mb-20">Your Questions Answered</h2>
                            <p>Here are some of the most common questions we receive about our courses and programs. If you
                                have any other questions, feel free to contact us!</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="faq-wrapper">
                            <div class="accordion" id="faqAccordion">

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            1. What is Little Farmers Academy?
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            We are an online learning platform that delivers engaging, interactive courses
                                            in farming, food science and sustainability designed especially for children
                                            (ages 5–15). Our mission is to reconnect kids with nature, teach them where food
                                            comes from, and equip them with lifelong green skills.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            2. Who are the courses designed for?
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Our courses are primarily designed for children aged 5 to 15. However, we also
                                            offer “Any Age” live sessions (for example: Future Agriculturists AI or
                                            Robotics)—so younger siblings, teens, or even parents can join in.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            3. What kinds of courses do you offer?
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>We offer a range of courses including:</p>
                                            <ul class="ps-4">
                                                <li>“Little Farmers Full Course” (for 5-15 years) with multiple classes.
                                                </li>
                                                <li>“Future Agriculturists – AI Live Session” (Any Age) </li>
                                                <li>“Future Agriculturists – Robotics Live Session” (Any Age) </li>
                                            </ul>
                                            <p>Each course covers topics like growing food, understanding food science,
                                                sustainability, and fostering a connection with nature.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFour">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFour" aria-expanded="false"
                                            aria-controls="collapseFour">
                                            4. How long are the courses and what happens when I complete one?
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            For example, the Little Farmers full course includes 19 classes for age 5-15. At
                                            the end, participants receive a certificate which they can download. <a
                                                href="https://welittlefarmers.com/?utm_source=chatgpt.com">welittlefarmers.com</a>
                                            <br>
                                            Beyond the certificate, the aim is to instill practical skills, awareness of
                                            healthy food, and a love of nature.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFive">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFive" aria-expanded="false"
                                            aria-controls="collapseFive">
                                            5. Is the learning safe and suitable for kids?
                                        </button>
                                    </h2>
                                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Yes. The platform is designed with children in mind: friendly language,
                                            age-appropriate content, and courses focused on positive values like caring for
                                            nature, sustainability, and health. <br>
                                            Little Farmers Academy - Online Farming Courses for Kids <br>
                                            Little Farmers Academy offers online farming courses for kids, providing
                                            essential skills in agriculture, food science, and sustainable practices.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingSix">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                            6.Will my child get to build a robot?
                                        </button>
                                    </h2>
                                    <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            In some courses, yes! They will learn how to build and program robots for
                                            farming tasks.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingSeven">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseSeven"
                                            aria-expanded="false" aria-controls="collapseSeven">
                                            7. What are the benefits of joining Little Farmers Academy?
                                        </button>
                                    </h2>
                                    <div id="collapseSeven" class="accordion-collapse collapse"
                                        aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <ul class="ps-4">
                                                <li>Fun, interactive, and educational sessions for kids</li>
                                                <li>Builds awareness about nature and sustainability</li>
                                                <li>Encourages creativity, curiosity, and responsibility</li>
                                                <li>Teaches real-life skills related to food and the environment</li>
                                                <li>Certificates of completion for motivation and achievement</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingEight">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseEight"
                                            aria-expanded="false" aria-controls="collapseEight">
                                            8. How are classes made engaging for kids?
                                        </button>
                                    </h2>
                                    <div id="collapseEight" class="accordion-collapse collapse"
                                        aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            We use storytelling, videos, virtual farm tours, simple science experiments, and
                                            quizzes. Every class is built to be visually rich, interactive, and full of
                                            imagination — so learning feels like play.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingNine">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseNine"
                                            aria-expanded="false" aria-controls="collapseNine">
                                            9. Who designs and teaches the courses?
                                        </button>
                                    </h2>
                                    <div id="collapseNine" class="accordion-collapse collapse"
                                        aria-labelledby="headingNine" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Our courses are developed by <strong>experts in food science, agriculture, child
                                                education, and sustainability</strong>. Lessons are simplified and tested
                                            with real students before being launched.
                                        </div>
                                    </div>
                                </div>


                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTen">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false"
                                            aria-controls="collapseTen">
                                            10. How is Little Farmers different from other online classes?
                                        </button>
                                    </h2>
                                    <div id="collapseTen" class="accordion-collapse collapse"
                                        aria-labelledby="headingTen" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Unlike generic learning platforms, we focus exclusively on <strong>nature-based
                                                education</strong>. <br>
                                            Our curriculum blends <strong>STEM + sustainability</strong>, helping children
                                            understand how technology and environment can coexist.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingLeven">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseLeven"
                                            aria-expanded="false" aria-controls="collapseLeven">
                                            11. What do I need to join a class?
                                        </button>
                                    </h2>
                                    <div id="collapseLeven" class="accordion-collapse collapse"
                                        aria-labelledby="headingLeven" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            All you need is:
                                            <ul class="ps-4">
                                                <li>A laptop, tablet, or smartphone</li>
                                                <li>A stable internet connection</li>
                                                <li>A curiosity to explore!</li>
                                            </ul>
                                            After enrollment, you’ll receive a login link with all class details.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwelve">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwelve"
                                            aria-expanded="false" aria-controls="collapseTwelve">
                                            12. Can we access lessons anytime?
                                        </button>
                                    </h2>
                                    <div id="collapseTwelve" class="accordion-collapse collapse"
                                        aria-labelledby="headingTwelve" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Yes! Recorded lessons are available <strong>24×7</strong> after enrollment. You
                                            can watch and replay them whenever convenient.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThriteen">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseThriteen"
                                            aria-expanded="false" aria-controls="collapseThriteen">
                                            13. What if we face technical issues during class?
                                        </button>
                                    </h2>
                                    <div id="collapseThriteen" class="accordion-collapse collapse"
                                        aria-labelledby="headingThriteen" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Our support team is always ready to assist via <strong>email and chat</strong>.
                                            We’ll make sure your child’s learning experience remains uninterrupted.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFiveteen">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFiveteen"
                                            aria-expanded="false" aria-controls="collapseFiveteen">
                                            14. How do I enroll my child?
                                        </button>
                                    </h2>
                                    <div id="collapseFiveteen" class="accordion-collapse collapse"
                                        aria-labelledby="headingFiveteen" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Visit <a
                                                href="https://welittlefarmers.com?utm_source=chatgpt.com">welittlefarmers.com</a>
                                            → choose a course → click <strong>Start Your Free Trial</strong> → make payment → receive
                                            confirmation instantly.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingSixteen">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseSixteen"
                                            aria-expanded="false" aria-controls="collapseSixteen">
                                            15. What are the course fees?
                                        </button>
                                    </h2>
                                    <div id="collapseSixteen" class="accordion-collapse collapse"
                                        aria-labelledby="headingSixteen" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Fees vary by program. For instance, our Little Farmers Course is available at a
                                            <strong>special introductory rate</strong>.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingSeventeen">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseSeventeen"
                                            aria-expanded="false" aria-controls="collapseSeventeen">
                                            16. What is your refund policy?
                                        </button>
                                    </h2>
                                    <div id="collapseSeventeen" class="accordion-collapse collapse"
                                        aria-labelledby="headingSeventeen" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            If you’re unsatisfied or face issues, contact us within <strong>7 days of
                                                purchase</strong>. We’ll offer a refund or course credit — no questions
                                            asked. <br>
                                            Little Farmers Academy - Online Farming Courses for Kids <br>
                                            Little Farmers Academy offers online farming courses for kids, providing
                                            essential skills in agriculture, food science, and sustainable practices.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingEighteen">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseEighteen"
                                            aria-expanded="false" aria-controls="collapseEighteen">
                                            17.Do you offer family-oriented classes?
                                        </button>
                                    </h2>
                                    <div id="collapseEighteen" class="accordion-collapse collapse"
                                        aria-labelledby="headingEighteen" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Yes, we plan to host special events and workshops where families can learn and
                                            grow together.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingNineteen">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseNineteen"
                                            aria-expanded="false" aria-controls="collapseNineteen">
                                            18.How is technology used in the classes?
                                        </button>
                                    </h2>
                                    <div id="collapseNineteen" class="accordion-collapse collapse"
                                        aria-labelledby="headingNineteen" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            We use everything from simple tools like soil sensors to more advanced robotics
                                            and AI to show how technology is revolutionizing farming.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwenty">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwenty"
                                            aria-expanded="false" aria-controls="collapseTwenty">
                                            19. Will this help with school learning or future careers?
                                        </button>
                                    </h2>
                                    <div id="collapseTwenty" class="accordion-collapse collapse"
                                        aria-labelledby="headingTwenty" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Definitely. Our courses build real-world awareness and curiosity in
                                            <strong>STEM, environment, and agriculture technology</strong> — skills that
                                            align with global sustainability careers of tomorrow.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwentyOne">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwentyOne"
                                            aria-expanded="false" aria-controls="collapseTwentyOne">
                                            20.Are the courses the same all year round?
                                        </button>
                                    </h2>
                                    <div id="collapseTwentyOne" class="accordion-collapse collapse"
                                        aria-labelledby="headingTwentyOne" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            No, our curriculum is seasonal, so what we plant and learn about changes with
                                            the time of year.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwentyTwo">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwentyTwo"
                                            aria-expanded="false" aria-controls="collapseTwentyTwo">
                                            21.Is there a certificate at the end of the course?
                                        </button>
                                    </h2>
                                    <div id="collapseTwentyTwo" class="accordion-collapse collapse"
                                        aria-labelledby="headingTwentyTwo" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Students who complete our adult courses receive a certificate of completion,
                                            highlighting the skills they have learned.
                                        </div>
                                    </div>
                                </div>






                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
