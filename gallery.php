<?php include('includes/db-config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery | Amari Staycation Alabang</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --amari-tan: #b0885a;
            --amari-navy: #042e47;
            --text-dark: #333333;
            --bg-light: #f9f9f9;
        }

        body { margin: 0; font-family: 'Lato', sans-serif; color: var(--text-dark); background-color: white; }
        h1, h2, h3, h4 { font-family: 'Playfair Display', serif; font-weight: 400; }

        /* --- SYNCED NAVBAR STYLES --- */
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 50px; background: white; border-bottom: 1px solid #eee; position: sticky; top: 0; z-index: 100; }
        .nav-links a { margin: 0 15px; text-decoration: none; color: #555; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
        .nav-links a:hover { color: var(--amari-tan); }
        .btn-book-nav { background-color: var(--amari-tan); color: white; padding: 12px 25px; text-decoration: none; font-weight: bold; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; transition: 0.3s; }
        .btn-book-nav:hover { background-color: var(--amari-navy); }

        /* Header */
        .gallery-header { text-align: center; padding: 60px 10% 30px; background-color: var(--bg-light); }
        .gallery-header h1 { font-size: 3rem; color: var(--amari-navy); text-transform: uppercase; letter-spacing: 5px; }

        /* Gallery Grid */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px; padding: 20px 10%; max-width: 1400px; margin: 0 auto;
        }

        .gallery-item {
            position: relative; height: 400px; overflow: hidden; cursor: pointer;
        }

        .gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s ease; }
        .gallery-item:hover img { transform: scale(1.08); }

        .item-label {
            position: absolute; bottom: 0; left: 0; right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: white; padding: 25px; font-size: 0.9rem; text-transform: uppercase;
        }

        /* LIGHTBOX MODAL */
        #lightbox {
            display: none; position: fixed; z-index: 1000; top: 0; left: 0; 
            width: 100%; height: 100%; background: rgba(4, 46, 71, 0.98);
            justify-content: center; align-items: center; padding: 20px;
        }

        .modal-content-wrapper {
            background: white; padding: 30px; border-radius: 5px;
            max-width: 850px; width: 100%; position: relative;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5); text-align: center;
        }

        .close-btn { position: absolute; top: 10px; right: 20px; color: #333; font-size: 2rem; cursor: pointer; z-index: 10; }

        /* CAROUSEL CONTROLS */
        .carousel-container { position: relative; width: 100%; height: 450px; overflow: hidden; border-radius: 3px; }
        #lightbox-img { width: 100%; height: 100%; object-fit: cover; transition: opacity 0.4s ease-in-out; }

        .nav-arrow {
            position: absolute; top: 50%; transform: translateY(-50%);
            background: rgba(255,255,255,0.8); color: var(--amari-navy);
            width: 45px; height: 45px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 1.2rem; transition: 0.3s;
        }
        .nav-arrow:hover { background: var(--amari-tan); color: white; }
        .prev-arrow { left: 15px; }
        .next-arrow { right: 15px; }

        /* Amenities Section */
        .amenities-row {
            display: flex; gap: 25px; margin-top: 20px; padding: 20px;
            border-top: 1px solid #eee; width: 100%; justify-content: center; flex-wrap: wrap;
        }
        .amenity-item { text-align: center; font-size: 0.7rem; text-transform: uppercase; color: var(--amari-navy); font-weight: bold; }
        .amenity-item i { display: block; font-size: 1.5rem; color: var(--amari-tan); margin-bottom: 5px; }

        .modal-book-btn {
            display: inline-block; background-color: var(--amari-navy); color: white;
            padding: 12px 30px; text-decoration: none; font-weight: bold;
            text-transform: uppercase; letter-spacing: 2px; margin-top: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div>
        <h2 style="color: var(--amari-navy); margin: 0; font-family: 'Playfair Display', serif;">AMARI ALABANG</h2>
    </div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="index.php#rooms">Rooms</a>
        <a href="experience.php">Experience</a>
        <a href="gallery.php" style="color: var(--amari-tan);">Gallery</a>
    </div>
    <a href="index.php#rooms" class="btn-book-nav">Book Now</a>
</nav>

<header class="gallery-header">
    <h1>The Gallery</h1>
</header>

<main class="gallery-grid">
    <div class="gallery-item" onclick="openUnitModal('The Grand Suite', ['assets/images/unit1-1.jpg', 'assets/images/unit1-2.jpg', 'assets/images/unit1-3.jpg'], 'Pool, WiFi, Smart TV, Kitchenette')">
        <img src="assets/images/unit1-1.jpg" alt="Unit 1">
        <div class="item-label">Amari</div>
    </div>

    <div class="gallery-item" onclick="openUnitModal('The Executive Loft', ['assets/images/unit2-1.jpg', 'assets/images/unit2-2.jpg'], 'WiFi, Gym, Workspace, Coffee')">
        <img src="assets/images/unit2-1.jpg" alt="Unit 2">
        <div class="item-label">Mariah</div>
    </div>

    <div class="gallery-item" onclick="openUnitModal('The Garden Studio', ['assets/images/unit3-1.jpg', 'assets/images/unit3-2.jpg'], 'WiFi, Garden View, Coffee')">
        <img src="assets/images/unit3-1.jpg" alt="Unit 3">
        <div class="item-label">Ara</div>
    </div>
</main>

<div id="lightbox" onclick="closeLightbox()">
    <div class="modal-content-wrapper" onclick="event.stopPropagation()">
        <span class="close-btn" onclick="closeLightbox()">&times;</span>
        
        <h2 id="modal-title" style="color: var(--amari-navy); margin-bottom: 15px;"></h2>
        
        <div class="carousel-container">
            <div class="nav-arrow prev-arrow" onclick="changeImage(-1)"><i class="fa-solid fa-chevron-left"></i></div>
            <img id="lightbox-img" src="">
            <div class="nav-arrow next-arrow" onclick="changeImage(1)"><i class="fa-solid fa-chevron-right"></i></div>
        </div>
        
        <div class="amenities-row" id="amenities-list"></div>

        <a href="index.php#rooms" class="modal-book-btn">Book This Unit</a>
    </div>
</div>

<footer class="site-footer">
    <center><p>&copy; 2024 Amari Staycation Alabang.</p></center>
</footer>

<script>
    let currentImages = [];
    let currentIndex = 0;

    function openUnitModal(title, imageArray, amenitiesString) {
        document.getElementById('modal-title').innerText = title;
        currentImages = imageArray;
        currentIndex = 0;
        
        updateModalImage();
        
        // Handle Amenities
        const list = document.getElementById('amenities-list');
        list.innerHTML = '';
        amenitiesString.split(', ').forEach(item => {
            let icon = 'fa-star';
            if(item.includes('WiFi')) icon = 'fa-wifi';
            if(item.includes('Pool')) icon = 'fa-person-swimming';
            if(item.includes('TV')) icon = 'fa-tv';
            if(item.includes('Kitchenette')) icon = 'fa-sink';
            if(item.includes('Gym')) icon = 'fa-dumbbell';
            if(item.includes('Coffee')) icon = 'fa-coffee';

            const div = document.createElement('div');
            div.className = 'amenity-item';
            div.innerHTML = `<i class="fa-solid ${icon}"></i>${item}`;
            list.appendChild(div);
        });

        document.getElementById('lightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function changeImage(step) {
        currentIndex += step;
        if (currentIndex >= currentImages.length) currentIndex = 0;
        if (currentIndex < 0) currentIndex = currentImages.length - 1;
        
        // Add a quick fade effect
        const img = document.getElementById('lightbox-img');
        img.style.opacity = '0.5';
        setTimeout(() => {
            updateModalImage();
            img.style.opacity = '1';
        }, 150);
    }

    function updateModalImage() {
        document.getElementById('lightbox-img').src = currentImages[currentIndex];
    }

    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
</script>

</body>
</html>