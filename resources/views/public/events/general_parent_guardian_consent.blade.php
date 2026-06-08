@extends('layouts.app-public')

@section('title', 'General Event – Terms & Conditions')

@push('styles')
<style>
    /* Responsive margin adjustments */
    .terms-container {
        margin-top: 5%;
    }
    
    @media (max-width: 767px) {
        .terms-container {
            margin-top: 16%;
        }
    }
    
    .terms-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(51, 153, 204, 0.1);
        margin-bottom: 2rem;
        background-color: #fdfdfd;
    }
    
    .terms-card .card-header {
        background: linear-gradient(45deg, #bb9706, var(--primary-color));
        color: white;
        font-weight: 600;
        border-radius: 15px 15px 0 0;
        border: none;
        padding: 18px 25px;
    }
    
    .terms-card .card-header h3 {
        color: white;
        font-weight: 600;
    }
    
    .terms-card .card-body {
        padding: 25px;
    }
    
    .terms-card h5 {
        text-align: left;
        color: var(--primary-color);
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .terms-card ul {
        padding-left: 1.5rem;
    }
    
    .terms-card li {
        margin-bottom: 0.5rem;
    }
    
    .back-btn {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
    
    .back-btn:hover {
        background-color: #bb9706;
        border-color: #bb9706;
    }
</style>
@endpush

@section('content')
<div class="container terms-container">
    <div class="row">
        <div class="col-12">
            <div class="card terms-card">
                <div class="card-header">
                    <h3 class="mb-0">General Event – Terms & Conditions</h3>
                </div>
                <div class="card-body">
                    <p>
                    These Terms & Conditions (“Terms”) govern the creation, listing, and hosting of parent-hosted events on the YouthCentral platform (“Platform”).
                    </p>

                    <p>
                    By creating and publishing an event on YouthCentral, the parent or legal guardian (“Host”) confirms that they have read, understood, and agreed to these Terms in full.
                    </p>

                    <h5>1. Eligibility of Host</h5>
                    <ul>
                        <li>Only a parent or legal guardian aged 18 years or above may host an event on YouthCentral.</li>
                        <li>The Host confirms that they are legally competent to enter into this agreement under Indian law.</li>
                    </ul>

                    <h5>2. Permitted Event Categories</h5>
                    <ul>
                        <li>YouthCentral currently permits hosting of only the following event types:</li>
                        <li>Birthday Party</li>
                        <li>Indoor Games</li>
                        <li>Book Reading (at Home or Public Park)</li>
                        <li>Events outside these categories are strictly prohibited and may be removed without notice.</li>
                    </ul>

                    <h5>3. Role of YouthCentral (Platform-Only Role)</h5>
                    <ul>
                        <li>YouthCentral is a technology and listing platform only that enables parents to publish and discover parent-hosted events.</li>
                        <li>YouthCentral does not organize, co-organize, manage, supervise, sponsor, endorse, control, or conduct any event listed on the Platform.</li>
                        <li>Any interaction, agreement, communication, or arrangement between the Host and event participants is solely between the Host and the participants.</li>
                    </ul>

                    <h5>4. Verification Does Not Create Responsibility</h5>
                    <ul>
                        <li>The Host acknowledges that YouthCentral may:</li>
                        <li>Call the Host to verify basic identity or event details</li>
                        <li>Request the Host to sign an Event Hosting Form agreeing to these Terms</li>
                        <li>Such verification or documentation is conducted only for platform safety and compliance purposes.</li>
                        <li>Verification, calls, form signing, or communication by YouthCentral shall not be construed as:</li>
                        <li>Approval or endorsement of the event</li>
                        <li>Supervision or monitoring of the event</li>
                        <li>Assumption of responsibility or duty of care</li>
                        <li>YouthCentral remains not liable in any way or manner whatsoever, irrespective of any verification conducted.</li>
                    </ul>

                    <h5>5. Host Responsibilities</h5>
                    <ul>
                        <li>The Host is solely and fully responsible for:</li>
                        <li>Planning, organizing, and conducting the event</li>
                        <li>Venue selection and permissions</li>
                        <li>Child safety, supervision, and welfare</li>
                        <li>Communication with attendees and parents</li>
                        <li>The Host confirms that the event will be conducted in a safe, lawful, age-appropriate, and responsible manner.</li>
                    </ul>

                    <h5>6. Child Safety & Supervision</h5>
                    <ul>
                        <li>The Host shall ensure:</li>
                        <li>Continuous and adequate adult supervision</li>
                        <li>Safe play conditions and activities</li>
                        <li>Basic first-aid readiness and emergency access</li>
                        <li>YouthCentral shall not be responsible or liable for:</li>
                        <li>Injury, accident, illness, or death</li>
                        <li>Emotional distress or trauma</li>
                        <li>Conflicts, disputes, or misconduct</li>
                    </ul>

                    <h5>7. Venue & Public Place Compliance</h5>
                    <ul>
                        <li>For events held at:</li>
                        <li>Residential societies</li>
                        <li>Homes</li>
                        <li>Public parks</li>
                        <li>Common areas</li>
                        <li>The Host confirms compliance with:</li>
                        <li>Society / RWA rules</li>
                        <li>Municipal and local authority regulations</li>
                        <li>Noise and public conduct norms</li>
                        <li>Any fines, penalties, or legal consequences are solely the Host’s responsibility.</li>
                    </ul>

                    <h5>8. Legal Compliance (India)</h5>
                    <ul>
                        <li>The Host agrees to comply with all applicable Indian laws, including but not limited to:</li>
                        <li>Local municipal regulations</li>
                        <li>Public safety and noise laws</li>
                        <li>Child protection laws</li>
                        <li>The Host shall not violate the Juvenile Justice (Care and Protection of Children) Act or any other applicable statute.</li>
                    </ul>

                    <h5>9. Prohibited Activities</h5>
                    <ul>
                        <li>Events must NOT include:</li>
                        <li>Commercial activity, paid services, or ticket sales</li>
                        <li>Alcohol, tobacco, or illegal substances</li>
                        <li>Hazardous or high-risk activities</li>
                        <li>Religious, political, or discriminatory content</li>
                        <li>Any activity unsafe or inappropriate for children</li>
                        <li>YouthCentral reserves the right to remove such events immediately.</li>
                    </ul>

                    <h5>10. Accuracy of Information</h5>
                    <ul>
                        <li>The Host must ensure that all event information is true, accurate, and complete.</li>
                        <li>Misrepresentation or misleading information may result in:</li>
                        <li>Event removal</li>
                        <li>Account suspension</li>
                        <li>Permanent restriction from hosting</li>
                    </ul>

                    <h5>11. Media & Privacy</h5>
                    <ul>
                        <li>The Host is responsible for obtaining explicit parental consent before taking or sharing photos or videos of children.</li>
                        <li>YouthCentral bears no responsibility or liability for any media captured or shared during events.</li>
                    </ul>

                    <h5>12. Absolute Disclaimer of Liability</h5>
                    <ul>
                        <li>YouthCentral shall not be liable in any way or manner whatsoever, whether directly or indirectly, for any event hosted by parents.</li>
                        <li>This includes, without limitation:</li>
                        <li>Personal injury or death</li>
                        <li>Property damage or loss</li>
                        <li>Child safety incidents</li>
                        <li>Negligence or misconduct</li>
                        <li>Emotional distress or dissatisfaction</li>
                        <li>The Host expressly agrees that:</li>
                        <li>Hosting is an independent personal activity</li>
                        <li>YouthCentral’s role is limited strictly to platform facilitation</li>
                        <li>No duty of care arises on YouthCentral under any circumstance</li>
                    </ul>

                    <h5>13. Indemnity</h5>
                    <ul>
                        <li>The Host agrees to fully indemnify, defend, and hold harmless YouthCentral, its founders, directors, employees, partners, and affiliates against any claims, losses, damages, or legal proceedings arising out of or related to the hosted event.</li>
                    </ul>

                    <h5>14. Event Cancellation</h5>
                    <ul>
                        <li>The Host is solely responsible for:</li>
                        <li>Cancelling events</li>
                        <li>Informing participants</li>
                        <li>Managing communication</li>
                        <li>YouthCentral bears no responsibility for cancellations or communication failures.</li>
                    </ul>

                    <h5>15. Governing Law & Jurisdiction</h5>
                    <ul>
                        <li>These Terms shall be governed by the laws of India, and courts in India shall have exclusive jurisdiction.</li>
                    </ul>

                    <h5>16. Acceptance</h5>
                    <ul>
                        <li>By clicking “I Agree” and publishing the event, the Host confirms that they:</li>
                        <li>Are a parent or legal guardian</li>
                        <li>Accept full responsibility for the event</li>
                        <li>Acknowledge that YouthCentral holds no responsibility or liability whatsoever</li>
                    </ul>


                    <a href="{{ url()->previous() }}" class="btn btn-primary back-btn mt-3">Back to Registration</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 