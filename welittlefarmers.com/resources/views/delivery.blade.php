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

        /* Apply object-fit cover to the background image */
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
        <h1>Delivery Policy</h1>
        <p>Last updated: October 30, 2025 </p>
        <p>
            Welcome to Little Farmers Academy! We’re excited to deliver your learning kits and give you access to our fun,
            educational courses. This Delivery Policy explains how we process, ship, and deliver physical kits and digital
            course materials when you order from <a href="https://welittlefarmers.com/">welittlefarmers.com.</a>
        </p>

        <h2>1. Scope of Delivery</h2>
        <p>
            We currently provide:
        </p>
        <ul>
            <li><strong>Physical Deliveries:</strong> Starter kits, course boxes, or printed materials shipped across India
                and selected international destinations.</li>
            <li><strong>Digital Deliveries: </strong> Online course access, login credentials, and downloadable content
                delivered electronically to your registered email or dashboard.</li>
        </ul>

        <h2>2. Order Processing Time </h2>
        <ul>
            <li>Orders are usually <strong>processed within 1–3 business days</strong> after successful payment
                confirmation. </li>
            <li>During peak seasons, workshops, or new launches, processing may take up to <strong>5 business days</strong>.
            </li>
            <li>You’ll receive an <strong>email or WhatsApp confirmation</strong> once your order is packed and ready for
                dispatch. </li>
        </ul>

        <h2>3. Delivery of Physical Kits </h2>

        <ul>
            <h3>a. Domestic Deliveries (India) </h3>
            <li>Shipped through trusted logistics partners such as <strong>DTDC, India Post, BlueDart, or
                    Delhivery</strong>. </li>
            <li>Estimated delivery time: <strong>3–7 business days</strong> depending on location. </li>
            <li>Tracking details will be shared via <strong>email or SMS</strong> once the shipment is dispatched</li>
        </ul>

        <ul>
            <h3>b. International Deliveries </h3>
            <li>Shipped via <strong>registered international courier partners</strong> (DHL, Aramex, or similar). </li>
            <li>Estimated delivery time: <strong>10–15 business days</strong> depending on destination and customs
                clearance. </li>
            <li>International buyers are responsible for <strong>any customs duties, import taxes, or clearance
                    fees</strong> applicable in their country. </li>
        </ul>

        <ul>
            <h3>c. Packaging </h3>
            <li>All kits are <strong> carefully packed using eco-friendly, recyclable materials</strong> to ensure your
                items reach you in perfect condition. </li>
        </ul>

        <h2>4. Delivery of Digital Courses </h2>
        <ul>
            <li>Once payment is confirmed, <strong>course access is automatically activated</strong> within 24 hours (or
                immediately for PayPal/UPI instant payments). </li>
            <li>You’ll receive an <strong>email with login details</strong> or a <strong>unique course access link</strong>.
            </li>
            <li>For live classes or scheduled batches, the class start date and joining link will be shared in advance by
                email or WhatsApp. </li>
        </ul>

        <h2>5. Delivery Delays </h2>
        <p>While we strive to deliver on time, occasional delays may occur due to: </p>
        <ul>
            <li>Public holidays, strikes, or courier disruptions. </li>
            <li>Remote delivery locations or adverse weather conditions.</li>
            <li>Customs inspections (for international orders). </li>
        </ul>
        <p>In case of a delay exceeding <strong>10 days beyond the estimated window</strong>, please contact us at
            contact@welittlefarmers.com — our support team will investigate and provide a status update within 48 hours.
        </p>

        <h2>6. Damaged or Missing Packages </h2>
        <p>If your kit arrives damaged, incomplete, or tampered with: </p>
        <ul>
            <li>Please take <strong>clear photos of the package</strong> and send them to
                <strong>contact@welittlefarmers.com</strong> within <strong>48 hours of delivery</strong>.
            </li>
            <li>We will verify and, if confirmed, arrange a <strong>free replacement or refund as per our Refund
                    Policy</strong>.
            </li>
        </ul>

        <h2>7. Undelivered Orders </h2>
        <p>If the courier marks your order as “undelivered” due to an incorrect address, failed contact attempts, or refusal
            to
            accept the package: </p>
        <ul>
            <li>We will attempt redelivery once after confirming the address with you. </li>
            <li>Additional shipping charges may apply for repeated delivery attempts. </li>
        </ul>

        <h2>8. Order Tracking </h2>
        <ul>
            <li>Once your order is dispatched, a tracking ID and courier name will be sent to your registered email or
                WhatsApp.
            </li>
            <li>You can track your package on the courier’s website using this tracking number. </li>
        </ul>

        <h2>9. Shipping Charges </h2>
        <ul>
            <li><strong>India:</strong> Standard shipping is free for most courses and kits unless specified. </li>
            <li><strong>International:</strong> Shipping charges vary based on destination, weight, and customs. Exact costs
                will be shown at checkout before payment. </li>
        </ul>
        <h2>10. Contact Us </h2>
        <p>For questions about your order, delivery status, or tracking updates, please reach out to: <br>📧
            contact@welittlefarmers.com </p>
    </main>
@endsection
