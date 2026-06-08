@extends('layouts.app-public')

@section('title', 'About YC SPARK 2026')

@section('content')

    <style>
        /* Alternate layout for second section */
        /* .about-container.alternate {
            flex-direction: row-reverse;
        } */

        /* Mobile responsive keeps stacking */
        @media (max-width: 768px) {
            .about-container.alternate {
                flex-direction: column;
            }
        }
    </style>
    <!-- Hero Section -->
    <div class="event-hero-section">
        <div class="container">
            <h1>About YC SPARK</h1>
        </div>
    </div>

    <!-- About Content Section -->
    <div class="container">
        <div class="about-container">
            <div class="image-box">
                <img src="{{ asset('/uploads/images/monorama.jpeg') }}" alt="Ms. Manorama Devi">
            </div>
            <div class="text-box">
                <h2>The inspiring story behind YC SPARK scholarship</h2>
                <p>The idea of YC SPARK was born from the inspiring life of Ms. Manorama Devi, a lifelong learner of science and retired principal from a junior school in Hardoi, Uttar Pradesh.</p>
                <p>Coming from a very humble background, she pursued her passion for science at a time when girls were often discouraged from higher education. With courage and determination, she became the first female science teacher in her district after completing higher secondary studies in science and BTC training.</p>
                <p>Despite the challenges of marriage and raising six children—often living apart from her husband due to professional postings in different cities—she never gave up on her own learning, completing her Bachelor of Arts later in life.</p>
                <p>Her journey stands as a testament to resilience, merit, and the belief that talent must never be confined by circumstances.</p>
                <p><strong>YC SPARK</strong> carries forward this legacy by providing today’s young students a fair platform to showcase their knowledge and be recognized purely on the basis of merit.</p>
            </div>
        </div>

        <!-- Second Section (Alternate Layout: Text Left, Image Right) -->
        <div class="about-container alternate">
            <div class="text-box">
                <h2>Pratima Shukla – Director, YC SPARK</h2>
                <p>Pratima Shukla is a seasoned academician and education leader with over 15 years of experience in teaching, research, and academic administration in the field of commerce and accountancy. With a strong foundation in financial education and a deep commitment to student development, she brings both expertise and vision to YC SPARK.</p>
                <p>She has served as an Assistant Professor at reputed institutions such as Don Bosco College of Commerce and Arts, Pune, and has also been associated with Ness Wadia College of Commerce and Karmaveer Bhaurao Patil College, Navi Mumbai. Throughout her career, she has been actively involved in delivering high-quality education, designing industry-relevant curricula, and mentoring students to achieve academic and professional excellence.</p>
                <p>Pratima has played key administrative roles, including Chief Exam Officer and as a University-appointed Moderator, reinforcing fair assessment practices.</p>
                <p>An accomplished scholar, she holds an M.Phil. in Commerce, a Master’s degree in Financial Accountancy, and has qualified the UGC NET for Lectureship. Her academic journey is complemented by achievements in innovation, including developing an online examination system early in her career.</p>
                <p>At YC SPARK, Pratima Shukla drives academic excellence, assessment frameworks, and student engagement strategies. Her passion lies in nurturing young minds, building structured learning pathways, and creating opportunities that empower students to realize their full potential.</p>
            </div>
            <div class="image-box">
                <img src="{{ asset('/uploads/images/darshana.jpeg') }}" alt="Innovation Image">
            </div>
        </div>

        <!-- Third Section (Alternate Layout: Image Left, Text Right) -->
        <div class="about-container">
            <div class="image-box manage-img">
                <img src="{{ asset('/uploads/images/pratima.jpeg') }}" alt="Education Image">
            </div>
            <div class="text-box">
                <h2>Darshana Kanojia – Director, YC SPARK</h2>
                <p>Darshana Kanojia is a passionate educator and child development advocate with over 15 years of experience across education, wellness, and strategic communication. With a unique blend of expertise in advertising, yoga, counselling, and early childhood education, she brings a holistic and purpose-driven approach to nurturing young minds.</p>
                <p>An MBA graduate in Marketing, she also holds diplomas in Yoga Teacher’s Training, Yoga Therapy & Naturopathy, and Early Childhood Education, along with a Post Graduate Diploma in Counselling from the Tata Institute of Social Sciences (TISS). Her journey spans impactful work in advertising, deep engagement in yoga and wellness education, and hands-on teaching experience at the primary and pre-primary levels.</p>
                <p>At YC Spark, Darshana leads marketing and communication while also driving initiatives focused on children’s overall well-being and development. She plays a key role in shaping the organization’s vision, building meaningful connections with students, parents, and educators, and expanding its reach as a platform for holistic growth.</p>
                <p>Deeply committed to social impact, she works towards empowering children with the right mindset, emotional strength, and life skills needed to thrive in an ever-evolving world. Her work reflects a strong belief that every child deserves the opportunity to grow into a confident, aware, and capable individual</p>
            </div>
        </div>

    </div>

    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Hero Section */
        .event-hero-section {
            background: #f8f9fa;
            padding: 40px 0;
            text-align: center;
            margin-top: 61px;
        }

        .event-hero-section h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #007BFF;
        }

        /* About Section */
        .about-container {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            align-items: flex-start;
            margin: 40px 0;
        }

        .image-box {
            flex: 1 1 300px;
            text-align: center;
        }

        .image-box img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .text-box {
            flex: 2 1 500px;
        }

        .text-box h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007BFF;
            margin-bottom: 20px;
        }

        .text-box p {
            margin-bottom: 15px;
        }

        .manage-img {
            margin-top: 21% !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .about-container {
                flex-direction: column;
            }

            .image-box,
            .text-box {
                flex: 1 1 100%;
            }
        }
    </style>
@endsection