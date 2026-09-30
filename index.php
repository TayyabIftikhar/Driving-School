<?php 
session_start(); 
include 'includes/header.php'; 
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        background: #F9FAFB;
        color: #0F172A;
    }
    .hero {
        background: linear-gradient(rgba(15,23,42,0.85), rgba(15,23,42,0.85)), 
                    url('images/driving-bg.jpg') no-repeat center center/cover;
        color: white;
        text-align: center;
        padding: 90px 20px;
    }
    .hero h2 {
        font-size: 3em;
        margin-bottom: 12px;
        text-shadow: 2px 2px 10px rgba(0,0,0,0.6);
    }
    .hero p {
        font-size: 1.2em;
        max-width: 650px;
        margin: 0 auto;
        color: #E2E8F0;
    }
    .info-section {
        padding: 60px 20px;
        text-align: center;
        background: #FFFFFF;
    }
    .info-section h3 {
        font-size: 2.2em;
        margin-bottom: 20px;
        color: #0D9488;
    }
    .features {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 25px;
        margin-top: 40px;
    }
    .feature-card {
        background: #F1F5F9;
        padding: 30px;
        border-radius: 14px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        width: 280px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 18px rgba(0,0,0,0.15);
    }
    .feature-card h4 {
        margin-bottom: 12px;
        color: #0D9488;
    }
    .feature-card p {
        color: #334155;
        font-size: 0.95em;
        line-height: 1.5em;
    }
    .cta {
        margin-top: 45px;
    }
    .cta a {
        background: #0D9488;
        color: white;
        padding: 14px 28px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s ease;
    }
    .cta a:hover {
        background: #0F766E;
    }
</style>

<div class="hero">
    <h2>Learn, Drive, Succeed</h2>
    <p>Building safe, confident, and skilled drivers for a lifetime of safe journeys.</p>
    <div class="cta">
        
    </div>
</div>

<div class="info-section">
    <h3>What Makes Us Different?</h3>
    <div class="features">
        <div class="feature-card">
            <h4>Expert Mentorship</h4>
            <p>Our instructors go beyond lessons — they mentor you in becoming a confident driver on every road.</p>
        </div>
        <div class="feature-card">
            <h4>Personalized Training</h4>
            <p>From nervous beginners to advanced learners, our lessons are tailored to your needs.</p>
        </div>
        <div class="feature-card">
            <h4>Budget-Friendly Options</h4>
            <p>Choose from flexible packages designed to give you the best value without compromise.</p>
        </div>
        <div class="feature-card">
            <h4>State-of-the-Art Vehicles</h4>
            <p>Drive modern, dual-control cars equipped with the latest safety features for worry-free practice.</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
