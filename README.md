# URLs
- [Slim](http://localhost:8080)
- [swagger](http://localhost:8081/)

# Produzione

Per avviare il server
```bash
docker-compose up -d
```
Per avviare il server aggiornando l'immagine 
```bash
docker-compose up -d --build
```

Per fermare il server 
```bash
docker-compose kill
```

# Sviluppo

Per avviare il server di sviluppo
```bash
docker-compose -f docker-compose.dev.yml up -d
```
Per fermare il server di sviluppo
```bash
docker-compose -f docker-compose.dev.yml kill
```
Ricordarsi di installare le dipendenze 
```bash
docker-compose -f docker-compose.dev.yml exec slim-server composer update
```
