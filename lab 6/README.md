# Laborator 6 - Strategy, Observer, Command, Memento & Iterator

Magazin de jucarii KINDER.

## Structura

```
lab 6/
├── strategy.py      - Strategy pattern
├── observer.py      - Observer pattern
├── command.py       - Command pattern (cu Undo/Redo)
├── memento.py       - Memento pattern (Save/Load)
├── iterator.py      - Iterator pattern
├── test_patterns.py - Teste unitare
├── main.py          - Demo
└── README.md
```

## Rulare

```bash
python3 main.py
python3 -m unittest test_patterns.py
```

## Strategy

Sortarea catalogului de jucarii cu strategii interschimbabile:
- `SortByPrice` / `SortByPriceDesc` - dupa pret
- `SortByName` - alfabetic
- `SortByAge` - dupa varsta recomandata

`ToyCatalog` schimba strategia dinamic la runtime.

## Observer

Sistem de notificari pentru evenimente din magazin:
- `ToyStoreEvents` (subiect) emite: new_arrival, price_drop, out_of_stock
- `EmailSubscriber`, `SMSSubscriber`, `DashboardLogger` (observatori)

Observatorii se pot atasa/detasa dinamic.

## Command

Gestiunea stocului cu suport Undo/Redo:
- `AddStockCommand`, `RemoveStockCommand`, `UpdatePriceCommand`
- `CommandHistory` tine istoricul si permite undo/redo

## Memento

Cos de cumparaturi cu Save/Load:
- `ShoppingCart` creeaza snapshot-uri (`CartMemento`)
- `CartHistory` pastreaza mai multe versiuni
- Restaurare la orice versiune anterioara

## Iterator

Parcurgerea colectiei de jucarii fara a expune structura interna:
- `ToyIterator` - iterator standard
- `category_iterator()` - filtrat pe categorie
- `price_range_iterator()` - filtrat pe interval de pret
