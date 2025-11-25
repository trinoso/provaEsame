<?php
require_once __DIR__ . '/../config.php';

$pdo = getPDO();
$message = '';
$error = '';

function sanitize(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Handle CRUD actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'add') {
            $stmt = $pdo->prepare('INSERT INTO books (title, author, genre, published_year, notes) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([
                trim($_POST['title'] ?? ''),
                trim($_POST['author'] ?? ''),
                trim($_POST['genre'] ?? ''),
                (int)($_POST['published_year'] ?? 0),
                trim($_POST['notes'] ?? ''),
            ]);
            $message = 'Libro inserito correttamente.';
        }

        if ($action === 'update') {
            $stmt = $pdo->prepare('UPDATE books SET title = ?, author = ?, genre = ?, published_year = ?, notes = ? WHERE id = ?');
            $stmt->execute([
                trim($_POST['title'] ?? ''),
                trim($_POST['author'] ?? ''),
                trim($_POST['genre'] ?? ''),
                (int)($_POST['published_year'] ?? 0),
                trim($_POST['notes'] ?? ''),
                (int)($_POST['id'] ?? 0),
            ]);
            $message = 'Libro aggiornato.';
        }

        if ($action === 'delete') {
            $stmt = $pdo->prepare('DELETE FROM books WHERE id = ?');
            $stmt->execute([(int)($_POST['id'] ?? 0)]);
            $message = 'Libro cancellato.';
        }
    } catch (PDOException $e) {
        $error = 'Errore: ' . $e->getMessage();
    }
}

// Queries for display
$search = trim($_GET['search'] ?? '');
$booksStmt = $pdo->prepare('SELECT * FROM books WHERE author LIKE ? OR genre LIKE ? ORDER BY title');
$likeTerm = '%' . $search . '%';
$booksStmt->execute([$likeTerm, $likeTerm]);
$books = $booksStmt->fetchAll();

$genreStats = $pdo->query('SELECT genre, COUNT(*) AS total FROM books GROUP BY genre ORDER BY total DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca tecnica - PHP/MySQL</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 1.5rem; background: #f5f5f5; }
        header { margin-bottom: 1rem; }
        section { background: #fff; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        h1, h2, h3 { margin-top: 0; }
        label { display: block; margin: 0.3rem 0 0.1rem; font-weight: bold; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 0.4rem; }
        textarea { min-height: 70px; }
        .row { display: flex; gap: 1rem; }
        .row > div { flex: 1; }
        .message { padding: 0.6rem; border-radius: 4px; }
        .message.success { background: #e8f5e9; color: #2e7d32; }
        .message.error { background: #ffebee; color: #c62828; }
        table { width: 100%; border-collapse: collapse; margin-top: 0.5rem; }
        th, td { border: 1px solid #ccc; padding: 0.4rem; text-align: left; }
        th { background: #eee; }
        .small { font-size: 0.9rem; color: #555; }
    </style>
</head>
<body>
<header>
    <h1>Biblioteca tecnica</h1>
    <p class="small">Esempio di prova pratica: progettazione di una base di dati, interrogazioni SQL e interfaccia Web minimale.</p>
</header>

<?php if ($message): ?>
    <div class="message success"><?php echo sanitize($message); ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="message error"><?php echo sanitize($error); ?></div>
<?php endif; ?>

<section>
    <h2>Aggiungi libro</h2>
    <p class="small">Esegue un <strong>INSERT</strong> nella tabella <code>books</code> tramite prepared statement.</p>
    <form method="post">
        <input type="hidden" name="action" value="add">
        <label for="title">Titolo</label>
        <input type="text" id="title" name="title" required>

        <label for="author">Autore</label>
        <input type="text" id="author" name="author" required>

        <label for="genre">Genere</label>
        <input type="text" id="genre" name="genre" required>

        <label for="published_year">Anno di pubblicazione</label>
        <input type="number" id="published_year" name="published_year" min="0" max="2100" value="2024">

        <label for="notes">Note</label>
        <textarea id="notes" name="notes"></textarea>

        <button type="submit">Inserisci</button>
    </form>
</section>

<section>
    <h2>Aggiorna o elimina</h2>
    <p class="small">Esegue <strong>UPDATE</strong> o <strong>DELETE</strong> sul record identificato da <code>id</code>.</p>
    <div class="row">
        <form method="post">
            <input type="hidden" name="action" value="update">
            <label for="id">ID libro</label>
            <input type="number" id="id" name="id" min="1" required>

            <label for="title_u">Titolo</label>
            <input type="text" id="title_u" name="title" required>

            <label for="author_u">Autore</label>
            <input type="text" id="author_u" name="author" required>

            <label for="genre_u">Genere</label>
            <input type="text" id="genre_u" name="genre" required>

            <label for="published_year_u">Anno di pubblicazione</label>
            <input type="number" id="published_year_u" name="published_year" min="0" max="2100">

            <label for="notes_u">Note</label>
            <textarea id="notes_u" name="notes"></textarea>

            <button type="submit">Aggiorna libro</button>
        </form>
        <form method="post">
            <input type="hidden" name="action" value="delete">
            <label for="id_delete">ID da cancellare</label>
            <input type="number" id="id_delete" name="id" min="1" required>
            <p class="small">Elimina in modo permanente il libro selezionato.</p>
            <button type="submit">Cancella libro</button>
        </form>
    </div>
</section>

<section>
    <h2>Ricerca e elenco completo</h2>
    <p class="small">Esegue <strong>SELECT</strong> con filtro su autore o genere. Lascia il campo vuoto per vedere tutto l’elenco.</p>
    <form method="get">
        <label for="search">Filtro (autore o genere)</label>
        <input type="text" id="search" name="search" value="<?php echo sanitize($search); ?>" placeholder="es. Software Engineering">
        <button type="submit">Cerca</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titolo</th>
                <th>Autore</th>
                <th>Genere</th>
                <th>Anno</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?php echo (int)$book['id']; ?></td>
                <td><?php echo sanitize($book['title']); ?></td>
                <td><?php echo sanitize($book['author']); ?></td>
                <td><?php echo sanitize($book['genre']); ?></td>
                <td><?php echo sanitize((string)$book['published_year']); ?></td>
                <td><?php echo sanitize($book['notes']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

<section>
    <h2>Statistiche per genere</h2>
    <p class="small">Esegue <strong>GROUP BY</strong> per contare il numero di libri per genere.</p>
    <table>
        <thead>
            <tr><th>Genere</th><th>Totale libri</th></tr>
        </thead>
        <tbody>
        <?php foreach ($genreStats as $row): ?>
            <tr>
                <td><?php echo sanitize($row['genre']); ?></td>
                <td><?php echo (int)$row['total']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
</body>
</html>
