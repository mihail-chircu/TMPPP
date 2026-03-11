# Laborator 5 - Flyweight, Decorator, Bridge & Proxy

Magazin de jucarii KINDER.

## Structura

```
lab 5/
├── flyweight.py     - Flyweight pattern
├── decorator.py     - Decorator pattern
├── bridge.py        - Bridge pattern
├── proxy.py         - Proxy pattern
├── test_patterns.py - Teste unitare
├── main.py          - Demo
└── README.md
```

## Rulare

```bash
python3 main.py
python3 -m unittest test_patterns.py
```

## Flyweight

Partajeaza tipurile de jucarii (`ToyType`) intre obiectele de pe raft (`ToyOnShelf`):
- 8 jucarii pe raft, dar doar 4 tipuri unice in memorie
- `ToyTypeFactory` gestioneaza cache-ul de tipuri partajate

## Decorator

Adauga canale de notificare dinamic peste `BasicNotification`:
- `EmailDecorator` - adauga trimitere email
- `SMSDecorator` - adauga trimitere SMS
- `PushDecorator` - adauga push notification
- `GiftWrapDecorator` - adauga ambalaj cadou

Se pot combina liber (ex: Email + SMS + Push).

## Bridge

Separa modul de afisare (List/Grid/Detail) de dispozitiv (Phone/Tablet/Web):
- 3 view-uri x 3 dispozitive = 9 combinatii fara explozie de clase
- View-ul deleaga randarea catre dispozitiv prin bridge

## Proxy

3 tipuri de proxy peste `RealToyService`:
- `CachingProxy` - cache local, evita interogari repetate
- `AccessControlProxy` - restrictii pe rol (guest vs admin)
- `LoggingProxy` - inregistreaza toate operatiile
