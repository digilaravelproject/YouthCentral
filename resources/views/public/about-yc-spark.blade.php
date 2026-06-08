@extends('layouts.app')

@section('title', 'About YC SPARK')

@section('content')
  <div class="container" style="display: flex; flex-wrap: wrap; gap: 40px; align-items: flex-start;">
    <div class="image-box" style="flex: 1 1 300px; text-align: center;">
      <img src="{{ asset('mdevi.png') }}"
           alt="Portrait of Ms. Manorama Devi Pathak, inspiration behind YC SPARK scholarship"
           loading="lazy"
           style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    </div>
    <div class="text-box" style="flex: 2 1 500px;">
      <h2 style="color: #007BFF; margin-bottom: 20px;">The inspiring story behind YC SPARK scholarship</h2>
      <p>The idea of YC SPARK was born from the inspiring life of Ms. Manorama Devi, a lifelong learner of science and retired principal from a junior school in Hardoi, Uttar Pradesh.</p>
      <p>Coming from a very humble background, she pursued her passion for science at a time when girls were often discouraged from higher education. With courage and determination, she became the first female science teacher in her district after completing higher secondary studies in science and BTC training.</p>
      <p>Despite the challenges of marriage and raising six children—often living apart from her husband due to professional postings in different cities—she never gave up on her own learning, completing her Bachelor of Arts later in life.</p>
      <p>Her journey stands as a testament to resilience, merit, and the belief that talent must never be confined by circumstances.</p>
      <p><strong>YC SPARK</strong> carries forward this legacy by providing today’s young students a fair platform to showcase their knowledge and be recognized purely on the basis of merit.</p>
    </div>
  </div>
@endsection