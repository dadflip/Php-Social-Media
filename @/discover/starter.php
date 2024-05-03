<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutoriel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .tutorial-container {
        position: relative;
        width: 100%;
        height: 100vh;
        overflow: hidden;
    }

    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: none;
        background-color: #f0f0f0;
        text-align: center;
        padding-top: 50px;
        transition: opacity 0.5s ease-in-out;
    }

    .slide.active {
        display: block;
        opacity: 1;
    }

    .prev-btn, .next-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        padding: 10px 20px;
        border: none;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
    }

    .prev-btn {
        left: 20px;
    }

    .next-btn {
        right: 20px;
    }
</style>
<body>
    <div class="tutorial-container">
        <div class="slide">Slide 1</div>
        <div class="slide">Slide 2</div>
        <div class="slide">Slide 3</div>
        <button class="prev-btn">Previous</button>
        <button class="next-btn">Next</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide');
            const prevBtn = document.querySelector('.prev-btn');
            const nextBtn = document.querySelector('.next-btn');
            let currentSlide = 0;

            function showSlide(index) {
                slides.forEach((slide, idx) => {
                    if (idx === index) {
                        slide.classList.add('active');
                    } else {
                        slide.classList.remove('active');
                    }
                });
            }

            showSlide(currentSlide);

            prevBtn.addEventListener('click', function() {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(currentSlide);
            });

            nextBtn.addEventListener('click', function() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            });
        });
    </script>
</body>
</html>
