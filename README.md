# provaEsame

Pagina di esempio PHP/MySQL per preparare la prova pratica di informatica.

## Contenuti
- `schema.sql`: crea il database `exam_library` con la tabella `books` e alcuni dati di esempio.
- `config.php`: parametri di connessione e helper `getPDO()`.
- `public/index.php`: unica pagina Web con CRUD, ricerche e statistiche.
- `doc/relazione.md`: relazione sintetica con criteri, mezzi e interrogazioni implementate.

## Come provarla in locale
1. Avvia MySQL e importa lo schema: `mysql -u root -p < schema.sql` (adatta utente/password).
2. Aggiorna le credenziali in `config.php` se necessario.
3. Avvia il server PHP dalla cartella `public`: `php -S localhost:8000`.
4. Apri <http://localhost:8000> nel browser e prova a inserire, aggiornare, cancellare o cercare libri.

## Interrogazioni chiave
- **SELECT con filtro** su autore o genere per ricerche personalizzate.
- **INSERT/UPDATE/DELETE** per popolare e mantenere la tabella.
- **GROUP BY** per conteggiare i libri per genere.
