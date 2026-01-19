@extends('main')

@push('head')
    <title>Little Farmers Academy - Online Farming Courses for Kids</title>
@endpush

@section('content')
    <style>
        /* General styling */
        main {
            padding: 20px;
            max-width: 1000px;
            margin: 0 auto;
            /* Center the content */
            color: #333;
            /* Ensure consistent text color */
        }

        h1 {
            font-size: 36px;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 24px;
            margin-top: 30px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        h3 {
            margin-bottom: 20px !important;
            font-size: 18px !important;
        }

        p>a {
            color: #ffa03f !important;
        }

        ul {
            margin-left: 20px;
            margin-bottom: 20px;
        }

        ul li {
            margin-bottom: 10px;
            font-size: 16px;
        }

        .page-title-area {
            margin-top: 50px;
            /* Adds space above the image */
        }

        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            main {
                padding: 10px;
            }

            h1 {
                font-size: 28px;
            }

            h2 {
                font-size: 20px;
            }

            h3 {
                font-size: 18px !important;
            }

            p,
            ul li {
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            main {
                padding: 5px;
                /* Reduce padding for smaller screens */
            }

            h1 {
                font-size: 24px;
                /* Reduce heading size for extra small devices */
            }

            h2 {
                font-size: 18px;
            }

            h3 {
                font-size: 16px !important;
            }

            p,
            ul li {
                font-size: 12px;
                /* Make text smaller for better readability on small screens */
            }
        }
    </style>

    <!--slider-area start-->
    <section class="slider-area pt-xs-10 pt-80 pb-xs-35">
    </section>

    <main class="container mt-70">
        <h1>Privacy Policy</h1>
        <p>Last updated: October 30, 2025</p>
        <p>
            Welcome to Little Farmers Academy (“we,” “our,” or “us”). We are committed to protecting your privacy and
            ensuring that your personal information is handled responsibly and transparently. This Privacy Policy explains
            how we collect, use, and safeguard your data when you interact with our website <a
                href="https://welittlefarmers.com"> welittlefarmers.com</a>, mobile app, or related services.
        </p>

        <h2>1. Information We Collect </h2>
        <p>
            We collect information to provide you with the best possible learning experience. The types of information we
            may collect include:
        </p>
        <ul>
            <h3>a. Personal Information (Provided by You) </h3>
            <li><strong>Contact Information:</strong> Your name, email address, phone number, and postal address.</li>
            <li><strong>Account Data:</strong> Login credentials, course enrollments, and communication preferences.</li>
            <li><strong>Payment Details:</strong> Billing address, transaction ID, and limited card/payment gateway details
                (processed securely by third-party providers).</li>
        </ul>

        <ul>
            <h3>b. Automatically Collected Information </h3>
            <li><strong>Usage Data: </strong> Your IP address, browser type, device model, operating system, pages visited,
                and session duration.</li>
            <li><strong>Cookies and Similar Technologies: </strong> We use cookies to improve website functionality,
                remember your preferences, and measure engagement. </li>
        </ul>

        <ul>
            <h3>c. Child Information </h3>
            <li>
                Little Farmers Academy is designed for children <strong>aged 5 to 15</strong>, and we collect limited
                information only with <strong>verified parental consent. </strong>
            </li>
        </ul>


        <h2>2. How We Use Your Information</h2>
        <p>
            We use your information to:
        </p>
        <ul>
            <li>Provide access to online courses, lessons, and community features. </li>
            <li>Process payments and deliver physical materials (kits or packages, where applicable). </li>
            <li>Personalize learning recommendations and track progress.</li>
            <li>Communicate updates, new courses, and special offers. </li>
            <li>Improve our products, website performance, and customer experience. </li>
            <li>Maintain data security and prevent fraudulent activity. </li>
        </ul>
        <p>We will not sell or rent your personal data to third parties.</p>

        <h2>3. Data Sharing and Disclosure </h2>
        <p>
            We may share limited information with:
        </p>
        <ul>
            <li>
                <strong>Service Providers:</strong>
                Trusted partners who help us host courses, process payments, manage analytics, and deliver products.
            </li>
            <li><strong>Legal and Compliance Authorities: </strong>
                Only when required by applicable law or regulatory obligation. </li>
            <li><strong>International Transfers:</strong> If data is transferred outside your country (e.g., between India,
                Sweden, or the UAE), we ensure it is protected under recognized international safeguards (e.g., Standard
                Contractual Clauses). </li>
        </ul>

        <h2>4. Data Security </h2>
        <p>
            We implement administrative, technical, and physical safeguards to protect your data, including:
        </p>
        <ul>
            <li>SSL encryption on our website and payment gateways. </li>
            <li>Regular security reviews and access controls. </li>
            <li>Restricted access to personal data only to authorized personnel.</li>
        </ul>
        <p>However, please note that no online transmission is 100% secure; you share information at your own risk. </p>

        <h2>5. Data Retention </h2>
        <p>
            We retain personal data only as long as necessary to:
        </p>

        <h2>Children's Privacy</h2>
        <p>
            We do not knowingly collect personal information from children under 16 years of age without parental consent.
            If we learn that we have collected such information, we will take steps to delete it as soon as possible.
        </p>
        <ul>
            <li>Fulfill course access, certification, and after-sales support. </li>
            <li>Meet legal, accounting, or tax requirements.
                Afterward, your data will be securely deleted or anonymized. </li>
        </ul>

        <h2>6. Your Rights </h2>
        <p>
            Depending on your region, you have the following rights:
        </p>
        <ul>
            <li><strong>Access and Correction: </strong> Request copies or updates of your personal information.</li>
            <li><strong>Erasure (“Right to Be Forgotten”):</strong> Ask for deletion of your account and data. </li>
            <li><strong>Objection:</strong> Opt out of marketing communications anytime. </li>
            <li><strong>Data Portability:</strong> Request export of your information in a machine-readable format.</li>
        </ul>
        <p>To exercise any of these rights, email us at <strong>contact@welittlefarmers.com</strong>. </p>

        <h2>7. Children’s Privacy </h2>
        <p>
            We do not knowingly collect data from children under 16 without verifiable parental consent. If a parent or
            guardian believes their child has shared personal data without permission, they should contact us immediately,
            and we will delete the data promptly.
        </p>

        <h2>8. Cookies and Tracking </h2>
        <p>
            You can manage cookies through your browser settings. Some cookies are essential for our site to function
            properly (for example, to keep you logged in during a learning session).
        </p>

        <h2>9. International Users </h2>
        <p>
            If you are located in the European Union, United Arab Emirates, or any other region with specific data laws,
            your information may be processed in India or other jurisdictions where our servers are hosted. We ensure
            compliance with <strong>GDPR (EU), DPDPA (India 2023), and UAE Federal Decree-Law No. 45 of 2021</strong> .
        </p>
        <h2>10. Updates to This Policy </h2>
        <p>
            We may update this Privacy Policy periodically to reflect changes in law, technology, or our operations. Updates
            will be posted on this page with a new “Last Updated” date.
        </p>
        <h2>11. Contact Us </h2>
        <p>
            If you have questions or concerns regarding this Privacy Policy, please contact us:
            <strong>contact@welittlefarmers.com </strong>
        </p>

    </main>
@endsection
