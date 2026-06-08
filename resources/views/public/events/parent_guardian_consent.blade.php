@extends('layouts.app-public')

@section('title', 'YC SPARK 2025 – Terms & Conditions')

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
                    <h3 class="mb-0">YC SPARK 2025 – Terms & Conditions</h3>
                </div>
                <div class="card-body">
                    <p>Please read the following terms carefully before registering for YC SPARK 2025, an online knowledge-based event organized by Youthcentuary Academy Private Limited (CIN: U85500MH2025PTC446620), operating under the brand name Youth Central.</p>

                    <h5>1. Nature of the Event</h5>
                    <ul>
                        <li>Youth Central is not an educational institute and does not impart any formal education.</li>
                        <li>YC SPARK 2025 is an online competition aimed at encouraging curiosity, awareness, and knowledge among students.</li>
                    </ul>
                    
                    <h5>2. Cash Prizes</h5>
                    <ul>
                        <li>All cash prizes for YC SPARK 2025 winners will be issued only through account payee cheques in the name of the student or their parent/guardian who holds a valid KYC-approved bank account.</li>
                        <li>No cash payments will be made under any circumstances to ensure compliance with financial and anti-money laundering regulations.</li>
                        <li>Youth Central reserves the right to verify the bank account details and KYC documents of the recipient before releasing the prize money.</li>
                        <li>Youth Central will track and ensure that the prize money is used solely for the student's education and development.</li>
                        <li>The prize money must not be misused or diverted by parents/guardians or any other individual for purposes unrelated to the student's welfare.</li>
                    </ul>

                    <h5>3. Event Language & Format</h5>
                    <ul>
                        <li>The online exam can be attempted in all major regional languages.</li>
                        <li>Exam Format: 100% online, recommended on a laptop/desktop, but also accessible via tablet, iPad, or smartphone.</li>
                        <li>A demo exam link will be shared 1 month before the event to help students familiarize themselves with the platform and exam guidelines.</li>
                    </ul>

                    <h5>4. Study Material</h5>
                    <ul>
                        <li>Supporting PDF study material will be provided to all registered students by October 15th, 2025.</li>
                    </ul>

                    <h5>5. State Winners & Grand Finale</h5>
                    <ul>
                        <li>State winners will be declared only if a minimum number of registrations per grade are received from that state.</li>
                        <li>In case of low participation, multiple states may be merged, and a common winner will be declared.</li>
                        <li>1st and 2nd place winners from each state, along with one parent/guardian, will be invited to the Grand Finale in Mumbai.</li>
                    </ul>

                    <h5>6. Travel & Accommodation for Grand Finale</h5>
                    <ul>
                        <li>Winners (1st & 2nd place) + 1 parent/guardian will receive:</li>
                        <li>Return 2nd AC train tickets from their home state to Mumbai.</li>
                        <li>Hotel accommodation in Mumbai during the Grand Finale.</li>
                        <li>If parents prefer to arrange their own travel, an equivalent amount equal to 2nd AC train ticket can be reimbursed.</li>
                    </ul>

                    <h5>7. General Conditions</h5>
                    <ul>
                        <li>Parents/Students can cancel their registration and will be eligible for a 90% refund of the registration fee on or before October 20th, 2025, OR within 7 days of completing their registration – whichever is later.</li>
                        <li>Registrations cannot be cancelled or refunded after the above period.</li>
                        <li>Registrations are non-transferable.</li>
                        <li>Youth Central reserves the right to modify event details, timelines, or rules if required.</li>
                        <li>By registering, parents/guardians confirm they understand the nature of the event and consent to their child's participation.</li>
                        <li>Youth Central is not liable for technical failures, network issues, or device problems during the online exam.</li>
                    </ul>

                    <h5>8. Data Privacy</h5>
                    <ul>
                        <li>Youth Central will collect only the necessary student details for event registration.</li>
                        <li>Personal data will be handled securely and not shared with any unauthorized third parties.</li>
                    </ul>

                    <a href="{{ url()->previous() }}" class="btn btn-primary back-btn mt-3">Back to Registration</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 