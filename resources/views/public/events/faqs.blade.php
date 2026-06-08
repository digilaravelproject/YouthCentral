@extends('layouts.app-public')

@section('title', 'YC SPARK 2026 - FAQs')

@section('content')
    <!-- Hero Section -->
    <div class="event-hero-section" style="margin-top: 61px;">
        <div class="container">
            <h1>YC SPARK 2026 - Frequently Asked Questions</h1>
        </div>
    </div>

    <!-- English FAQ Section -->
    <div class="container">
        <div class="faq-card">
            <h2>FAQs (English)</h2>
            <div class="faq-list">
                <!-- FAQ 1 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>1. What is YC SPARK and how is it useful for students?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer active">
                        It isn’t just another exam — It’s a national-level online exam where young minds rise, race, and shine! Designed for Grade 6 and Grade 9 students across India, YC SPARK brings together the thrill of competition, the joy of learning, and the power of recognition — all from the comfort of your home or school. This is where classroom knowledge meets real-world curiosity. Where Science, Maths, GK, and Social Studies are tested not for marks — but for mastery, logic, and understanding. Whether you're from a metro city or a small town, your brilliance deserves a stage — and YC SPARK 2026 is that stage. NCERT curriculum-based examination that helps students assess their strengths and weaknesses in Mathematics, Science, Social Studies, and General Knowledge. By encouraging analytical thinking and healthy competition, YC SPARK nurtures the competitive spirit of young minds.<br>
                        • Recognize real talent early<br>
                        • Boost confidence & critical thinking<br>
                        • Give exposure beyond classroom marks<br>
                        • Open doors to exciting opportunities at a young age
                    </div>
                </div>
                <!-- FAQ 2 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>2. Who designs the YC SPARK question papers?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        The question papers are designed by highly experienced educational specialists. Performance analysis is carried out at multiple levels – individual student, class, school, and national levels.
                    </div>
                </div>
                <!-- FAQ 3 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>3. Can only schools participate in YC SPARK 2026?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        No. While schools can participate, parents can also register their children directly through our website: <a href="https://www.youthcentral.co" target="_blank">www.youthcentral.co</a>.
                    </div>
                </div>
                <!-- FAQ 4 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>4. Can I get sample questions before the exam?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        Yes. After registration, students will be added to a dedicated WhatsApp channel where:<br>
                        • Weekly sample questions with answers will be shared.<br>
                        • Monthly sample papers will be provided, and solutions will be shared within two days.
                    </div>
                </div>
                <!-- FAQ 5 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>5. How and when will the exam be conducted?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • The exam will be conducted online, with only one attempt per student.<br>
                        • A demo exam link will be shared 15 days before the actual exam to help students familiarize themselves with the portal.
                    </div>
                </div>
                <!-- FAQ 6 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>6. What is the exam pattern of YC SPARK 2026?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • Eligibility: Class 6 and Class 9 students<br>
                        • No. of Questions: 60<br>
                        • Total Marks: 120<br>
                        • Duration: 1 hour<br>
                        • Mode: Online (Laptop, Desktop, Tablet, iPad, Android/iOS Phones)<br>
                        • Marking: No negative marking
                    </div>
                </div>
                <!-- FAQ 7 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>7. What is the last date for registration?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        Registrations close on 27th November 2025.
                    </div>
                </div>
                <!-- FAQ 8 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>8. Can a student give the exam on a mobile phone or tablet?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        Yes. Students may use Laptop, Desktop, Tablet, iPad, or Mobile Phone with:<br>
                        • A stable internet connection<br>
                        • A working camera
                    </div>
                </div>
                <!-- FAQ 9 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>9. What is the syllabus for Class 6 and Class 9?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • Class 6: NCERT syllabus from Class 4 to Class 6<br>
                        • Class 9: NCERT syllabus from Class 7 to Class 9
                    </div>
                </div>
                <!-- FAQ 10 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>10. Will study material be provided?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        Yes. A YC SPARK Exam Guide PDF (syllabus, study material, and preparation tips) will be shared between 30th October – 15th November 2025.
                    </div>
                </div>
                <!-- FAQ 11 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>11. Will there be a sample paper or demo test?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • A demo test link will be provided 15 days before the exam.<br>
                        • No printed sample papers will be given.
                    </div>
                </div>
                <!-- FAQ 12 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>12. How can students practice beyond course books?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        While based on NCERT, the exam will test reasoning ability, logical thinking, and problem-solving skills rather than direct textbook recall.
                    </div>
                </div>
                <!-- FAQ 13 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>13. What scholarships and rewards are offered?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        YC SPARK identifies talented students and awards them as National and State Champions with cash prizes to support school fees and encourage extracurricular passions.
                    </div>
                </div>
                <!-- FAQ 14 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>14. What is the Grand Finale and who can participate?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • The 1st and 2nd winners from each state will be invited and sponsored for the Grand Finale.<br>
                        • Winners of the Grand Finale will be declared as National Champions.
                    </div>
                </div>
                <!-- FAQ 15 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>15. What is the selection criteria for State and National Winners?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • State Winners: Selected based on performance in the online exam. (The exam will be AI-proctored with strict rules.)<br>
                        • National Champions: Selected based on combined performance in the Online Exam + Grand Finale.<br>
                        <strong>Important Exam Rules:</strong><br>
                        • Do not look away from the screen.<br>
                        • Do not switch screens.<br>
                        • Only the student may appear on the camera (no assistance allowed).
                    </div>
                </div>
                <!-- FAQ 16 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>16. What is the format of the Grand Finale?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        The Grand Finale includes:<br>
                        • Group Discussion<br>
                        • 30-minute Personal Interview<br>
                        Final evaluation will consider performance in the Online Exam, GD, and Interview.
                    </div>
                </div>
                <!-- FAQ 17 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>17. When will the results be announced?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        Results will be announced on 10th January 2026.
                    </div>
                </div>
                <!-- FAQ 18 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>18. How will students receive updates?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        All updates will be shared through Email, SMS, and WhatsApp. Students must provide an active mobile number and email ID during registration.
                    </div>
                </div>
                <!-- FAQ 19 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>19. Can exam details be changed after registration?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        Yes. A student can change exam details twice, at least 45 days before the exam date.
                    </div>
                </div>
                <!-- FAQ 20 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>20. What if payment is deducted but registration fails?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • Wait up to 3 working days.<br>
                        • If not refunded, contact us on WhatsApp: +91-9137398807.<br>
                        • Keep transaction details and screenshots ready.
                    </div>
                </div>
                <!-- FAQ 21 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>21. What is Youth Central?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        Youth Central is an all-in-one platform that empowers children and families to discover the best opportunities in academics, sports, arts, wellness, and events. We connect families with verified service providers, offering trusted access to high-quality programs, workshops, and coaching. Through thoughtfully curated competitions and talent showcases, we aim to nurture confidence, curiosity, and creativity in every child. Our mission is to bridge the gap between students and opportunities, especially in smaller towns and rural areas across India.
                    </div>
                </div>
                <!-- FAQ 22 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>22. Where to contact for queries?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • Call: +91-9137398807<br>
                        • Email: info@youthcentral.co
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Marathi FAQ Section -->
    <div class="container">
        <div class="faq-card">
            <h2>FAQs (मराठी)</h2>
            <div class="faq-list">
                <!-- FAQ 1 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>1. YC SPARK म्हणजे काय आणि हे विद्यार्थ्यांसाठी कसे उपयुक्त आहे?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        YC SPARK ही एक राष्ट्रीय स्तरावरील ऑनलाइन परीक्षा आहे जी विद्यार्थ्यांच्या ज्ञान, तर्कशक्ती आणि आत्मविश्वासाला चालना देते. इयत्ता 6 आणि 9 मधील विद्यार्थ्यांसाठी खास डिझाइन केलेली ही परीक्षा शालेय अभ्यासक्रमाच्या पलीकडे जाऊन विचार करण्याची संधी देते. विज्ञान, गणित, सामान्य ज्ञान आणि सामाजिक शास्त्र या विषयांवर आधारित ही परीक्षा गुणांसाठी नाही, तर समज, लॉजिक आणि अ‍ॅनालिटिकल विचारासाठी घेतली जाते. शहरातील किंवा ग्रामीण भागातील कोणताही विद्यार्थी या व्यासपीठावर आपली प्रतिभा दाखवू शकतो.<br>
                        • लवकर वयात खरी प्रतिभा ओळखली जाते<br>
                        • आत्मविश्वास आणि विचारशक्ती वाढते<br>
                        • शालेय गुणांपलीकडे अनुभव मिळतो<br>
                        • विद्यार्थ्यांना राज्य व राष्ट्रीय स्तरावर संधी मिळते
                    </div>
                </div>
                <!-- FAQ 2 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>2. YC SPARK चे प्रश्नपत्र तयार कोण करतो?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        प्रश्नपत्रे अनुभवी शिक्षणतज्ज्ञ तयार करतात. विद्यार्थ्यांचे विश्लेषण वैयक्तिक, वर्ग, शाळा आणि राष्ट्रीय स्तरावर केले जाते.
                    </div>
                </div>
                <!-- FAQ 3 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>3. YC SPARK मध्ये फक्त शाळाच सहभागी होऊ शकतात का?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        नाही. शाळा सहभागी होऊ शकतात, पण पालकही आपल्या मुलांची नोंदणी थेट वेबसाइटवरून करू शकतात: <a href="https://www.youthcentral.co" target="_blank">www.youthcentral.co</a>
                    </div>
                </div>
                <!-- FAQ 4 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>4. परीक्षेपूर्वी नमुना प्रश्न मिळतील का?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        होय. नोंदणी केल्यानंतर विद्यार्थ्यांना WhatsApp चॅनेलमध्ये जोडले जाईल जिथे:<br>
                        • साप्ताहिक नमुना प्रश्न आणि उत्तरे दिली जातील<br>
                        • मासिक नमुना पेपर आणि दोन दिवसांत त्याचे उत्तरपत्रिका मिळेल
                    </div>
                </div>
                <!-- FAQ 5 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>5. परीक्षा कशी आणि कधी घेतली जाईल?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • परीक्षा ऑनलाइन असेल आणि प्रत्येक विद्यार्थ्याला एकच संधी मिळेल<br>
                        • परीक्षेपूर्वी 15 दिवसांनी डेमो लिंक दिली जाईल
                    </div>
                </div>
                <!-- FAQ 6 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>6. YC SPARK 2026 ची परीक्षा पद्धत काय आहे?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • पात्रता: इयत्ता 6 आणि 9<br>
                        • प्रश्नांची संख्या: 60<br>
                        • एकूण गुण: 120<br>
                        • कालावधी: 1 तास<br>
                        • पद्धत: ऑनलाइन (लॅपटॉप, डेस्कटॉप, टॅबलेट, मोबाईल)<br>
                        • गुणांकन: निगेटिव्ह मार्किंग नाही
                    </div>
                </div>
                <!-- FAQ 7 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>7. नोंदणीची अंतिम तारीख काय आहे?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        नोंदणी 27 नोव्हेंबर 2025 रोजी बंद होईल.
                    </div>
                </div>
                <!-- FAQ 8 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>8. विद्यार्थी मोबाईल किंवा टॅबलेटवर परीक्षा देऊ शकतो का?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        होय. विद्यार्थ्यांना खालील गोष्टी आवश्यक आहेत:<br>
                        • स्थिर इंटरनेट कनेक्शन<br>
                        • काम करणारा कॅमेरा
                    </div>
                </div>
                <!-- FAQ 9 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>9. इयत्ता 6 आणि 9 साठी अभ्यासक्रम काय आहे?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • इयत्ता 6: इयत्ता 4 ते 6 पर्यंतचा NCERT अभ्यासक्रम<br>
                        • इयत्ता 9: इयत्ता 7 ते 9 पर्यंतचा NCERT अभ्यासक्रम
                    </div>
                </div>
                <!-- FAQ 10 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>10. अभ्यास साहित्य दिले जाईल का?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        होय. YC SPARK मार्गदर्शक PDF (अभ्यासक्रम, तयारी टिप्स) 30 ऑक्टोबर ते 15 नोव्हेंबर दरम्यान दिला जाईल.
                    </div>
                </div>
                <!-- FAQ 11 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>11. नमुना पेपर किंवा डेमो टेस्ट मिळेल का?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • परीक्षेपूर्वी 15 दिवसांनी डेमो टेस्ट लिंक दिली जाईल<br>
                        • प्रिंटेड नमुना पेपर दिले जाणार नाहीत
                    </div>
                </div>
                <!-- FAQ 12 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>12. अभ्यासपुस्तकांपलीकडे सराव कसा करता येईल?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        ही परीक्षा तर्कशक्ती, लॉजिकल विचार आणि समस्यांचे निराकरण यावर आधारित असेल — केवळ पाठांतरावर नाही.
                    </div>
                </div>
                <!-- FAQ 13 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>13. शिष्यवृत्ती आणि बक्षिसे कोणती आहेत?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        राज्य आणि राष्ट्रीय स्तरावर विजेते घोषित केले जातात आणि त्यांना शिष्यवृत्ती व रोख बक्षिसे दिली जातात.
                    </div>
                </div>
                <!-- FAQ 14 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>14. ग्रँड फिनाले म्हणजे काय आणि कोण सहभागी होऊ शकतो?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • प्रत्येक राज्यातील पहिल्या दोन विजेत्यांना ग्रँड फिनालेसाठी आमंत्रित केले जाईल<br>
                        • ग्रँड फिनालेचे विजेते राष्ट्रीय विजेते म्हणून घोषित केले जातील
                    </div>
                </div>
                <!-- FAQ 15 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>15. राज्य आणि राष्ट्रीय विजेते कसे निवडले जातात?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • राज्य विजेते: ऑनलाइन परीक्षेच्या कामगिरीवर आधारित<br>
                        • राष्ट्रीय विजेते: ऑनलाइन परीक्षा + ग्रँड फिनालेच्या एकत्रित कामगिरीवर आधारित<br>
                        <strong>महत्त्वाचे परीक्षा नियम:</strong><br>
                        • स्क्रीनकडे सतत पाहा<br>
                        • स्क्रीन बदलू नका<br>
                        • फक्त विद्यार्थीच कॅमेरावर दिसला पाहिजे
                    </div>
                </div>
                <!-- FAQ 16 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>16. ग्रँड फिनालेचे स्वरूप काय आहे?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        ग्रँड फिनालेमध्ये खालील गोष्टींचा समावेश आहे:<br>
                        • समूह चर्चा<br>
                        • 30 मिनिटांची वैयक्तिक मुलाखत<br>
                        अंतिम मूल्यांकन ऑनलाइन परीक्षा, GD आणि मुलाखतीवर आधारित असेल.
                    </div>
                </div>
                <!-- FAQ 17 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>17. निकाल कधी जाहीर केला जाईल?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        10 जानेवारी 2026 रोजी निकाल जाहीर केला जाईल.
                    </div>
                </div>
                <!-- FAQ 18 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>18. विद्यार्थ्यांना अपडेट्स कसे मिळतील?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        ईमेल, SMS आणि WhatsApp वरून सर्व अपडेट्स दिल्या जातील. नोंदणी करताना सक्रिय मोबाईल नंबर आणि ईमेल ID द्यावी.
                    </div>
                </div>
                <!-- FAQ 19 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>19. नोंदणी केल्यानंतर परीक्षा तपशील बदलता येतील का?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        होय. परीक्षा तारखेपूर्वी किमान 45 दिवस आधी दोन वेळा तपशील बदलता येतील.
                    </div>
                </div>
                <!-- FAQ 20 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>20. पेमेंट झाले पण नोंदणी अयशस्वी झाली तर?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • 3 कार्यदिवसांपर्यंत प्रतीक्षा करा<br>
                        • परतावा न झाल्यास WhatsApp वर संपर्क करा: +91-9137398807<br>
                        • लेन-देन तपशील आणि स्क्रीनशॉट तयार ठेवा
                    </div>
                </div>
                <!-- FAQ 21 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>21. Youth Central म्हणजे काय?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        Youth Central ही एक अशी व्यासपीठ आहे जी मुलांना आणि कुटुंबांना शिक्षण, खेळ, कला, आरोग्य आणि इव्हेंट्समधील सर्वोत्तम संधी उपलब्ध करून देते. आम्ही विश्वासार्ह सेवा प्रदात्यांशी जोडतो आणि मुलांमध्ये आत्मविश्वास, जिज्ञासा आणि सर्जनशीलता वाढवण्याचे उद्दिष्ट ठेवतो.
                    </div>
                </div>
                <!-- FAQ 22 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>22. संपर्क कसा करावा?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • कॉल: +91-9137398807<br>
                        • ईमेल: info@youthcentral.co
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hindi FAQ Section -->
    <div class="container">
        <div class="faq-card">
            <h2>FAQs (हिंदी)</h2>
            <div class="faq-list">
                <!-- FAQ 1 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>1. YC SPARK क्या है और यह बच्चों के लिए कैसे उपयोगी है?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        YC SPARK एक राष्ट्रीय स्तर की ऑनलाइन परीक्षा है जो बच्चों की प्रतिभा, तर्कशक्ति और आत्मविश्वास को निखारने का अवसर देती है. यह परीक्षा कक्षा 6 और 9 के छात्रों के लिए डिज़ाइन की गई है, जो उन्हें स्कूल की पढ़ाई से आगे सोचने और सीखने का मंच देती है. विज्ञान, गणित, सामान्य ज्ञान और सामाजिक अध्ययन जैसे विषयों पर आधारित यह परीक्षा अंकों के लिए नहीं, बल्कि समझ, लॉजिक और विश्लेषणात्मक सोच के लिए होती है. चाहे बच्चा किसी महानगर से हो या छोटे शहर से — YC SPARK 2026 हर बच्चे को अपनी प्रतिभा दिखाने का समान अवसर देता है.<br>
                        • बच्चों की असली प्रतिभा को कम उम्र में पहचानें<br>
                        • आत्मविश्वास और सोचने की क्षमता बढ़ाएं<br>
                        • स्कूल की पढ़ाई से आगे बढ़ने का अनुभव दें<br>
                        • बच्चों को प्रांतीय और राष्ट्रीय स्तर के अवसर प्रदान करें
                    </div>
                </div>
                <!-- FAQ 2 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>2. YC SPARK के प्रश्नपत्र कौन तैयार करता है?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        प्रश्नपत्र अनुभवी शिक्षा विशेषज्ञों द्वारा तैयार किए जाते हैं. मूल्यांकन व्यक्तिगत, कक्षा, स्कूल और राष्ट्रीय स्तर पर किया जाता है.
                    </div>
                </div>
                <!-- FAQ 3 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>3. क्या केवल स्कूल ही YC SPARK में भाग ले सकते हैं?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        नहीं. स्कूलों के साथ-साथ माता-पिता भी अपने बच्चों की रजिस्ट्रेशन सीधे वेबसाइट से कर सकते हैं: <a href="https://www.youthcentral.co" target="_blank">www.youthcentral.co</a>
                    </div>
                </div>
                <!-- FAQ 4 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>4. क्या परीक्षा से पहले सैंपल प्रश्न मिलेंगे?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        हाँ. रजिस्ट्रेशन के बाद छात्रों को एक विशेष WhatsApp चैनल में जोड़ा जाएगा जहाँ:<br>
                        • हर सप्ताह सैंपल प्रश्न और उत्तर साझा किए जाएंगे<br>
                        • हर महीने सैंपल पेपर और दो दिन में समाधान दिए जाएंगे
                    </div>
                </div>
                <!-- FAQ 5 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>5. परीक्षा कैसे और कब आयोजित होगी?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • परीक्षा ऑनलाइन होगी और हर छात्र को एक ही प्रयास मिलेगा<br>
                        • परीक्षा से 15 दिन पहले डेमो लिंक साझा किया जाएगा
                    </div>
                </div>
                <!-- FAQ 6 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>6. YC SPARK 2026 की परीक्षा संरचना क्या है?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • पात्रता: कक्षा 6 और 9<br>
                        • प्रश्नों की संख्या: 60<br>
                        • कुल अंक: 120<br>
                        • समय: 1 घंटा<br>
                        • माध्यम: ऑनलाइन (लैपटॉप, डेस्कटॉप, टैबलेट, मोबाइल)<br>
                        • मार्किंग: कोई नेगेटिव मार्किंग नहीं
                    </div>
                </div>
                <!-- FAQ 7 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>7. रजिस्ट्रेशन की अंतिम तिथि क्या है?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        रजिस्ट्रेशन 27 नवंबर 2025 को बंद हो जाएगा.
                    </div>
                </div>
                <!-- FAQ 8 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>8. क्या छात्र मोबाइल या टैबलेट से परीक्षा दे सकते हैं?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        हाँ. छात्रों को चाहिए:<br>
                        • स्थिर इंटरनेट कनेक्शन<br>
                        • सक्रिय कैमरा
                    </div>
                </div>
                <!-- FAQ 9 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>9. कक्षा 6 और 9 के लिए पाठ्यक्रम क्या है?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • कक्षा 6: कक्षा 4 से 6 तक का NCERT पाठ्यक्रम<br>
                        • कक्षा 9: कक्षा 7 से 9 तक का NCERT पाठ्यक्रम
                    </div>
                </div>
                <!-- FAQ 10 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>10. क्या अध्ययन सामग्री दी जाएगी?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        हाँ. YC SPARK परीक्षा गाइड PDF (पाठ्यक्रम, तैयारी टिप्स) 30 अक्टूबर से 15 नवंबर के बीच साझा की जाएगी.
                    </div>
                </div>
                <!-- FAQ 11 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>11. क्या सैंपल पेपर या डेमो टेस्ट मिलेगा?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • परीक्षा से 15 दिन पहले डेमो टेस्ट लिंक दिया जाएगा<br>
                        • प्रिंटेड सैंपल पेपर नहीं दिए जाएंगे
                    </div>
                </div>
                <!-- FAQ 12 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>12. पाठ्यपुस्तकों से आगे कैसे अभ्यास करें?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        यह परीक्षा रटने पर नहीं, बल्कि तर्कशक्ति, लॉजिकल सोच और समस्या समाधान पर आधारित होगी.
                    </div>
                </div>
                <!-- FAQ 13 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>13. क्या छात्रवृत्ति और पुरस्कार मिलते हैं?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        राज्य और राष्ट्रीय स्तर पर विजेताओं को नकद पुरस्कार और छात्रवृत्ति दी जाती है ताकि वे स्कूल फीस और अपनी रुचियों को आगे बढ़ा सकें.
                    </div>
                </div>
                <!-- FAQ 14 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>14. ग्रैंड फिनाले क्या है और कौन भाग ले सकता है?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • हर राज्य के पहले और दूसरे स्थान के विजेताओं को आमंत्रित किया जाएगा<br>
                        • ग्रैंड फिनाले के विजेताओं को राष्ट्रीय चैंपियन घोषित किया जाएगा
                    </div>
                </div>
                <!-- FAQ 15 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>15. राज्य और राष्ट्रीय विजेताओं का चयन कैसे होता है?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • राज्य विजेता: ऑनलाइन परीक्षा के प्रदर्शन के आधार पर<br>
                        • राष्ट्रीय विजेता: ऑनलाइन परीक्षा + ग्रैंड फिनाले के संयुक्त प्रदर्शन पर<br>
                        <strong>महत्वपूर्ण परीक्षा नियम:</strong><br>
                        • स्क्रीन से नजर न हटाएं<br>
                        • स्क्रीन न बदलें<br>
                        • केवल छात्र ही कैमरे पर होना चाहिए (कोई सहायता नहीं)
                    </div>
                </div>
                <!-- FAQ 16 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>16. ग्रैंड फिनाले का प्रारूप क्या है?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        ग्रैंड फिनाले में शामिल हैं:<br>
                        • समूह चर्चा<br>
                        • 30 मिनट की व्यक्तिगत साक्षात्कार<br>
                        अंतिम मूल्यांकन ऑनलाइन परीक्षा, GD और इंटरव्यू के आधार पर होगा.
                    </div>
                </div>
                <!-- FAQ 17 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>17. परिणाम कब घोषित होंगे?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        10 जनवरी 2026 को परिणाम घोषित किए जाएंगे.
                    </div>
                </div>
                <!-- FAQ 18 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>18. छात्रों को अपडेट्स कैसे मिलेंगे?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        ईमेल, SMS और WhatsApp के माध्यम से सभी अपडेट साझा किए जाएंगे. रजिस्ट्रेशन के समय सक्रिय मोबाइल नंबर और ईमेल ID देना अनिवार्य है.
                    </div>
                </div>
                <!-- FAQ 19 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>19. क्या रजिस्ट्रेशन के बाद परीक्षा विवरण बदला जा सकता है?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        हाँ. परीक्षा तिथि से कम से कम 45 दिन पहले दो बार विवरण बदला जा सकता है.
                    </div>
                </div>
                <!-- FAQ 20 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>20. अगर पेमेंट कट जाए लेकिन रजिस्ट्रेशन न हो?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • 3 कार्यदिवस तक प्रतीक्षा करें<br>
                        • अगर रिफंड न मिले तो WhatsApp पर संपर्क करें: +91-9137398807<br>
                        • लेन-देन का विवरण और स्क्रीनशॉट तैयार रखें
                    </div>
                </div>
                <!-- FAQ 21 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>21. Youth Central क्या है?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        Youth Central एक ऐसा मंच है जो बच्चों और परिवारों को शिक्षा, खेल, कला, स्वास्थ्य और प्रतियोगिताओं में सर्वोत्तम अवसरों से जोड़ता है. हम विश्वसनीय सेवा प्रदाताओं से जुड़ने का मौका देते हैं और बच्चों की जिज्ञासा, आत्मविश्वास और रचनात्मकता को बढ़ावा देते हैं.
                    </div>
                </div>
                <!-- FAQ 22 -->
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>22. संपर्क कैसे करें?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        • कॉल: +91-9137398807<br>
                        • ईमेल: info@youthcentral.co
                    </div>
                </div>
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
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Hero Section */
        .event-hero-section {
            background: #f8f9fa;
            padding: 40px 0;
            text-align: center;
        }

        .event-hero-section h1 {
            font-size: 2rem;
            font-weight: bold;
        }

        /* FAQ Section */
        .faq-card {
            margin: 40px 0;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .faq-card h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .faq-item {
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .faq-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            cursor: pointer;
            font-weight: 500;
        }

        .faq-question:hover {
            background: #e9ecef;
        }

        .faq-toggle {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .faq-toggle.active {
            transform: rotate(45deg);
        }

        .faq-answer {
            display: none;
            padding: 15px;
            background: #fff;
            color: #555;
            line-height: 1.6;
        }

        .faq-answer.active {
            display: block;
        }
    </style>

    <script>
        function toggleFAQ(element) {
            // Get the answer element (next sibling)
            const answer = element.nextElementSibling;
            // Get the toggle icon
            const toggle = element.querySelector('.faq-toggle');
            // Check if the answer is currently active
            const isActive = answer.classList.contains('active');

            // Close all other FAQs
            document.querySelectorAll('.faq-answer').forEach(ans => {
                ans.classList.remove('active');
            });
            document.querySelectorAll('.faq-toggle').forEach(tog => {
                tog.classList.remove('active');
                tog.textContent = '+';
            });

            // Toggle the clicked FAQ
            if (!isActive) {
                answer.classList.add('active');
                toggle.classList.add('active');
                toggle.textContent = '−';
            }
        }
    </script>
@endsection