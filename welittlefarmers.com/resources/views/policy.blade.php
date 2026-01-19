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
        <h1>Refund & Cancellation Policy</h1>
        <p>Last updated: October 30, 2025</p>
        <p>
            At <strong>Little Farmers Academy</strong>, we value your trust and aim to deliver meaningful, high-quality
            learning experiences for every child. This Refund & Cancellation Policy outlines the terms under which refunds,
            replacements, or cancellations are accepted for purchases made on <a
                href="https://welittlefarmers.com/">welittlefarmers.com</a> or via our official payment channels.
        </p>

        <h2>1. Payment Methods</h2>
        <p>
            We currently accept:
        </p>
        <ul>
            <li><strong>For India: </strong> UPI, Debit/Credit Cards, Net Banking, and Wallets (via our secure Indian
                payment partners).</li>
            <li><strong>For International Customers:</strong>PayPal (USD transactions only).</li>
        </ul>
        <p>All transactions are processed securely through PCI-compliant gateways. We do not store your full payment
            details. </p>

        <h2>2. Course Purchases (Digital Content) </h2>
        <ul>
            <li><strong>Instant Access Courses / Digital Programs:</strong><br>
                Once access credentials, learning materials, or downloadable content have been delivered, <strong>refunds
                    are not applicable</strong> , as these are digital products. </li>

            <li><strong>Live or Scheduled Sessions: </strong><br>
                Cancellations made at least <strong>72 hours before</strong> the start of a live class or workshop are
                eligible for a <strong>100% refund</strong>, minus payment gateway fees. </li>

            <li><strong>Missed Sessions:</strong><br>
                If you miss a live session, a recording or alternate batch (if available) will be provided. Refunds are not
                issued for non-attendance. </li>
        </ul>

        <h2>3. Physical Kits and Materials </h2>
        <p>
            For courses that include a starter kit or farm/<strong>PCB box</strong>:
        </p>
        <ul>
            <li>If your kit arrives damaged or incomplete, please contact us within <strong>7 days of delivery</strong> at
                <strong>contact@welittlefarmers.com</strong> with photos of the package.
            </li>
            <li>After verification, we will arrange a <strong>free replacement</strong> or <strong>full refund</strong>
                (excluding delivery charges, if applicable). </li>
            <li>Returns are not accepted after the kit has been used or partially assembled. </li>
        </ul>

        <h2>4. Refund Timelines </h2>
        <p>
            Once your refund request is approved:
        </p>
        <ul>
            <li><strong>UPI / Indian Bank Accounts:</strong> Within <strong>7–10 business days.</strong> </li>
            <li><strong>PayPal Transactions (International):</strong> Within <strong>5–7 business days</strong> after
                processing through PayPal’s refund system. </li>
            <li>Refunds will always be made to the original payment method used at the time of purchase.</li>
        </ul>

        <h2>5. Order Cancellation </h2>
        <ul>
            <li>Orders for physical kits can be cancelled <strong> before dispatch</strong> by emailing
                <strong>support@welittlefarmers.com</strong> or messaging via our website contact form.
            </li>
            <li>Once dispatched, the order cannot be cancelled, but you may request a return if eligible (see Section 3).
            </li>
        </ul>

        <h2>6. Non-Refundable Situations</h2>
        <p>
            Refunds will not be granted for:
        </p>
        <ul>
            <li>Completed or accessed digital courses. </li>
            <li>Gift cards or promotional credits.</li>
            <li>Orders where incorrect shipping details were provided by the customer. </li>
            <li>Course access suspended due to policy violations or misuse. </li>
        </ul>

        <h2>7. Technical Issues or Duplicate Payments </h2>
        <p>If you were charged twice or faced a failed transaction, please share the payment reference (Transaction ID or
            UTR) at <strong>contact@welittlefarmers.com.</strong>
            We will verify and issue a <strong>full refund</strong> or adjustment within <strong>5 business days</strong>.
        </p>

        <h2>8. Dispute Resolution </h2>
        <p>For any disputes: </p>
        <ul>
            <li>Indian users are covered under the <strong>Consumer Protection (E-Commerce) Rules, 2020.</strong> </li>
            <li>International users paying via PayPal may also raise a claim under <strong>PayPal Buyer Protection</strong>
                if applicable. <br>
                We strive to resolve all refund or return requests amicably and within <strong>10 business days</strong> of
                receiving the complaint.</li>
        </ul>

        <h2>9. Contact Us </h2>
        <p>If you have any questions about this Refund Policy, reach us at: <br>📧 contact@welittlefarmers.com </p>

    </main>
@endsection
