# Relazione sulla prova pratica

Questa mini–applicazione PHP/MySQL dimostra come progettare e realizzare una base di dati minimale, alcune interrogazioni SQL significative e una semplice interfaccia Web per interagirvi. Il dominio scelto è una piccola **biblioteca tecnica**.

## Criteri seguiti
- **Semplicità**: una sola tabella `books` con i campi essenziali (titolo, autore, genere, anno e note) per concentrare la prova su CRUD e interrogazioni.
- **Trasparenza**: l’unico file Web (`public/index.php`) contiene sia l’interfaccia sia la logica di gestione delle richieste; la configurazione del database è centralizzata in `config.php`.
- **Sicurezza di base**: tutte le operazioni SQL usano *prepared statement* per evitare injection.

## Mezzi impiegati
- **Linguaggi**: PHP 8+ con estensione PDO per MySQL, SQL standard per definire schema e query.
- **Script SQL**: `schema.sql` crea il database `exam_library`, la tabella `books` e inserisce alcuni dati di esempio.
- **Ambiente**: qualunque server PHP + MySQL locale (es. XAMPP, Docker LAMP).

## Interrogazioni significative
Nel file `public/index.php` sono implementate e descritte le seguenti query:
1. **Elenco completo**: `SELECT * FROM books ORDER BY title` – restituisce tutti i libri ordinati alfabeticamente.
2. **Ricerca per autore/genre**: `SELECT * FROM books WHERE author LIKE ? OR genre LIKE ?` – filtra i libri con corrispondenza parziale su autore o genere.
3. **Conteggio per genere**: `SELECT genre, COUNT(*) FROM books GROUP BY genre ORDER BY COUNT(*) DESC` – fornisce una statistica rapida delle categorie più presenti.
4. **Inserimento**: `INSERT INTO books (...) VALUES (...)` – popola nuovi record dal form "Aggiungi libro".
5. **Aggiornamento**: `UPDATE books SET ... WHERE id = ?` – modifica titolo, autore, genere, anno e note di un libro esistente.
6. **Cancellazione**: `DELETE FROM books WHERE id = ?` – rimuove un libro tramite ID.

Per ogni query presente nell’interfaccia è riportata una descrizione testuale vicino al relativo form o pannello di output.

## Risultati ottenuti
- Pagina unica pronta per essere eseguita su un server PHP; consente di **inserire**, **aggiornare**, **cancellare** e **ricercare** libri.
- **Statistiche** pronte all’uso per verificare il corretto popolamento del database.
- Documentazione rapida in `README.md` con istruzioni per configurare l’ambiente e provare la pagina.

## Possibili estensioni
- Gestione utenti e autenticazione per operazioni protette.
- Validazioni lato client e stili più curati con un framework CSS.
- Ulteriori interrogazioni (es. filtro per intervallo di anni, export CSV).
