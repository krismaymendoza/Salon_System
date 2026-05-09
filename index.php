<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glow & Style Salon | Professional Beauty Services</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header>
        <div class="logo">
            Glow & Style <span>Salon</span>
        </div>

        <nav>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </div>
            <div class="nav-btns">
                <a href="login.php" class="btn-login">Login</a>
                <a href="register.php" class="btn-book">Book Now</a>
            </div>
        </nav>
    </header>

    <section class="hero-container">
        <div class="carousel">
            <div class="slides fade">
                <img src="images/salon1.jpg" alt="Salon Interior">
            </div>
            <div class="slides fade">
                <img src="images/salon2.jpg" alt="Hair Styling">
            </div>
            <div class="slides fade">
                <img src="images/salon3.jpg" alt="Manicure Service">
            </div>
        </div>

        <div class="hero-overlay">
            <div class="hero-content">
                <h1>Feel Beautiful. <br><span>Feel Confident.</span></h1>
                <p>Experience the finest beauty treatments in Batangas. Your transformation begins with a single click.</p>
                <a href="register.php" class="hero-btn">Explore Services</a>
            </div>
        </div>
    </section>

    <main class="container">
        
        <section id="about" class="about-card">
            <div class="section-header">
                <h2>About Us</h2>
                <div class="underline"></div>
            </div>
            <p>
                Glow & Style Salon offers professional beauty services including hair styling, 
                advanced facial treatments, and meticulous nail care. We aim to give every 
                customer a relaxing, premium, and satisfying salon experience.
            </p>
        </section>

        <section id="contact" class="contact-grid">
            <div class="contact-info">
                <div class="section-header">
                    <h2>Contact Us</h2>
                    <div class="underline"></div>
                </div>
                <div class="info-item">
                    <strong>Email:</strong> <span>glowandstyle@gmail.com</span>
                </div>
                <div class="info-item">
                    <strong>Phone:</strong> <span>0912 345 6789</span>
                </div>
                <div class="info-item">
                    <strong>Address:</strong> <span>Batangas, Philippines</span>
                </div>
            </div>
        </section>

    </main>

    <footer>
        <p>&copy; 2026 Glow & Style Salon. Designed for Elegance.</p>
    </footer>

    <script>
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let slides = document.getElementsByClassName("slides");
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) { slideIndex = 1; }
            slides[slideIndex - 1].style.display = "block";
            setTimeout(showSlides, 4000); 
        }
    </script>

</body>
</html>