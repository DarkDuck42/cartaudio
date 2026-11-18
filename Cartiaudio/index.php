<?php
// Simulare bază de date cu cărți audio
$carti_audio = [
    [
        'id' => 1,
        'titlu' => 'Ion',
        'autor' => 'Liviu Rebreanu',
        'categorie' => 'Literatură Clasică',
        'durata' => '11h 45m',
        'descriere' => 'O capodoperă a literaturii române despre lupta pentru pământ și iubire în satul transilvănean.',
        'coperta' => 'https://placehold.co/300x420/2c3e50/ffffff?text=Ion+-+L.+Rebreanu',
        // Folosim un fișier audio de test gratuit
        'audio_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3'
    ],
    [
        'id' => 2,
        'titlu' => 'Inteligența Emoțională',
        'autor' => 'Daniel Goleman',
        'categorie' => 'Dezvoltare Personală',
        'durata' => '8h 20m',
        'descriere' => 'Descoperă de ce coeficientul emoțional (EQ) poate conta mai mult decât IQ-ul în viață.',
        'coperta' => 'https://placehold.co/300x420/e67e22/ffffff?text=Inteligenta+Emotionala',
        'audio_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3'
    ],
    [
        'id' => 3,
        'titlu' => 'Dune',
        'autor' => 'Frank Herbert',
        'categorie' => 'SF & Fantasy',
        'durata' => '21h 02m',
        'descriere' => 'O aventură epică pe planeta deșertică Arrakis, unde mirodenia este cea mai valoroasă resursă.',
        'coperta' => 'https://placehold.co/300x420/c0392b/ffffff?text=Dune+-+F.+Herbert',
        'audio_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3'
    ],
    [
        'id' => 4,
        'titlu' => 'Atomic Habits',
        'autor' => 'James Clear',
        'categorie' => 'Dezvoltare Personală',
        'durata' => '5h 35m',
        'descriere' => 'O metodă ușoară și dovedită de a-ți forma obiceiuri bune și de a scăpa de cele proaste.',
        'coperta' => 'https://placehold.co/300x420/27ae60/ffffff?text=Atomic+Habits',
        'audio_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-4.mp3'
    ]
];

// Logica de căutare și filtrare
$cautare = isset($_GET['cauta']) ? trim($_GET['cauta']) : '';
$categorie_selectata = isset($_GET['categorie']) ? $_GET['categorie'] : '';

$carti_filtrate = array_filter($carti_audio, function($carte) use ($cautare, $categorie_selectata) {
    $potrivire_text = true;
    $potrivire_categorie = true;

    if ($cautare !== '') {
        $potrivire_text = stripos($carte['titlu'], $cautare) !== false || 
                          stripos($carte['autor'], $cautare) !== false;
    }

    if ($categorie_selectata !== '' && $categorie_selectata !== 'Toate') {
        $potrivire_categorie = ($carte['categorie'] === $categorie_selectata);
    }

    return $potrivire_text && $potrivire_categorie;
});

// Extragem categoriile unice pentru meniul de filtrare
$categorii = array_unique(array_column($carti_audio, 'categorie'));
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cărți Audio - Ascultă poveștile preferate</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text-dark);
            padding-bottom: 100px; /* Spațiu pentru player-ul fix de jos */
        }

        /* Header */
        header {
            background-color: var(--card-bg);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        nav a {
            margin-left: 20px;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
        }

        nav a:hover {
            color: var(--primary);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            text-align: center;
            padding: 4rem 1rem;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        /* Bara de căutare */
        .search-bar {
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            gap: 10px;
        }

        .search-bar input, .search-bar select {
            padding: 12px 15px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            outline: none;
        }

        .search-bar input {
            flex: 1;
        }

        .search-bar button {
            padding: 12px 25px;
            background-color: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }

        .search-bar button:hover {
            background-color: #059669;
        }

        /* Container Cărți */
        .container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 20px;
        }

        .section-title {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 25px;
        }

        /* Card Carte */
        .card {
            background: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
            display: flex;
            flex-direction: column;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .card-body {
            padding: 1.2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .badge {
            display: inline-block;
            background: #e0e7ff;
            color: var(--primary);
            font-size: 0.75rem;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            margin-bottom: 8px;
            width: fit-content;
        }

        .card-title {
            font-size: 1.2rem;
            margin-bottom: 4px;
        }

        .card-author {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .card-desc {
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 15px;
            flex: 1;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 12px;
        }

        .duration {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .btn-play {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s;
        }

        .btn-play:hover {
            background-color: var(--primary-hover);
        }

        /* Player Audio Fix (Jos) */
        .audio-player-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #1e293b;
            color: white;
            padding: 15px 5%;
            display: none; /* Ascuns până se apasă Play */
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 -4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
        }

        .now-playing {
            display: flex;
            flex-direction: column;
        }

        .now-playing span {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        audio {
            width: 50%;
            max-width: 500px;
            height: 40px;
        }

        @media (max-width: 768px) {
            .search-bar { flex-direction: column; }
            .audio-player-bar { flex-direction: column; gap: 10px; text-align: center; }
            audio { width: 100%; }
        }
    </style>
</head>
<body>

    <!-- Meniu Navigare -->
    <header>
        <a href="index.php" class="logo">🎧 Cărți Audio</a>
        <nav>
            <a href="index.php">Acasă</a>
            <a href="#catalog">Catalog</a>
            <a href="#despre">Despre Noi</a>
        </nav>
    </header>

    <!-- Secțiunea Principală (Hero) -->
    <section class="hero">
        <h1>Bine ai venit pe Cărți Audio</h1>
        <p>Ascultă mii de cărți oriunde te-ai afla, în mașină, la sală sau înainte de culcare.</p>
        
        <!-- Formular Căutare PHP -->
        <form method="GET" action="index.php" class="search-bar">
            <input type="text" name="cauta" placeholder="Caută după titlu sau autor..." value="<?= htmlspecialchars($cautare) ?>">
            <select name="categorie">
                <option value="Toate">Toate categoriile</option>
                <?php foreach ($categorii as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= ($categorie_selectata === $cat) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Caută</button>
        </form>
    </section>

    <!-- Catalog Cărți -->
    <main class="container" id="catalog">
        <h2 class="section-title">
            <?php if ($cautare || ($categorie_selectata && $categorie_selectata !== 'Toate')): ?>
                Rezultate căutare (<?= count($carti_filtrate) ?>)
            <?php else: ?>
                Recomandările noastre
            <?php endif; ?>
        </h2>

        <?php if (empty($carti_filtrate)): ?>
            <p>Nu am găsit nicio carte audio care să corespundă criteriilor tale. <a href="index.php">Resetează filtrele</a>.</p>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($carti_filtrate as $carte): ?>
                    <article class="card">
                        <img src="<?= htmlspecialchars($carte['coperta']) ?>" alt="Coperta <?= htmlspecialchars($carte['titlu']) ?>">
                        <div class="card-body">
                            <span class="badge"><?= htmlspecialchars($carte['categorie']) ?></span>
                            <h3 class="card-title"><?= htmlspecialchars($carte['titlu']) ?></h3>
                            <div class="card-author">de <?= htmlspecialchars($carte['autor']) ?></div>
                            <p class="card-desc"><?= htmlspecialchars($carte['descriere']) ?></p>
                            <div class="card-footer">
                                <span class="duration">⏱ <?= htmlspecialchars($carte['durata']) ?></span>
                                <button class="btn-play" onclick="ascultaCarte('<?= htmlspecialchars(addslashes($carte['titlu'])) ?>', '<?= htmlspecialchars(addslashes($carte['autor'])) ?>', '<?= htmlspecialchars($carte['audio_url']) ?>')">
                                    ▶ Ascultă
                                </button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Player Audio Fix -->
    <div class="audio-player-bar" id="playerBar">
        <div class="now-playing">
            <span>Acum asculți:</span>
            <strong id="playerTitle">Titlu Carte</strong>
        </div>
        <audio id="audioElement" controls>
            Browserul tău nu suportă elementul audio.
        </audio>
    </div>

    <!-- Script pentru controlul player-ului -->
    <script>
        function ascultaCarte(titlu, autor, url) {
            const playerBar = document.getElementById('playerBar');
            const playerTitle = document.getElementById('playerTitle');
            const audioElement = document.getElementById('audioElement');

            // Afișăm bara player-ului
            playerBar.style.display = 'flex';
            
            // Setăm titlul și sursa audio
            playerTitle.textContent = titlu + ' - ' + autor;
            audioElement.src = url;
            
            // Pornim redarea automat
            audioElement.play();
        }
    </script>

</body>
</html>