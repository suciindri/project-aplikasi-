<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Laundry</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
    }

    header {
        background-color: #4CAF50;
        color: white;
        padding: 15px;
        text-align: center;
    }

    .menu-container {
        display: flex;
        overflow-x: auto;
        padding: 10px;
        background: #eee;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        cursor: grab;
    }

    .menu-item {
        flex: 0 0 auto;
        background: white;
        padding: 10px 20px;
        margin-right: 10px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        scroll-snap-align: start;
        white-space: nowrap;
        transition: transform 0.2s ease;
    }

    .menu-item:hover {
        transform: scale(1.05);
        background-color: #f9f9f9;
    }

    main {
        padding: 20px;
    }
</style>
</head>
<body>

<header>
    <h1>Dashboard Laundry</h1>
</header>

<div class="menu-container">
    <div class="menu-item">Pesanan Baru</div>
    <div class="menu-item">Proses Cuci</div>
    <div class="menu-item">Selesai</div>
    <div class="menu-item">Pengantaran</div>
    <div class="menu-item">Riwayat</div>
    <div class="menu-item">Pengaturan</div>
</div>

<main>
    <h2>Selamat datang di dashboard!</h2>
    <p>Pilih menu di atas untuk melihat detail.</p>
</main>

<script>
    const menu = document.querySelector('.menu-container');
    let isDown = false;
    let startX;
    let scrollLeft;

    menu.addEventListener('mousedown', (e) => {
        isDown = true;
        menu.style.cursor = 'grabbing';
        startX = e.pageX - menu.offsetLeft;
        scrollLeft = menu.scrollLeft;
    });

    menu.addEventListener('mouseleave', () => {
        isDown = false;
        menu.style.cursor = 'grab';
    });

    menu.addEventListener('mouseup', () => {
        isDown = false;
        menu.style.cursor = 'grab';
    });

    menu.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - menu.offsetLeft;
        const walk = (x - startX) * 2; // geser lebih cepat
        menu.scrollLeft = scrollLeft - walk;
    });
</script>

</body>
</html>
