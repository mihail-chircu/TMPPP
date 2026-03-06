# Sistem de Management pentru Magazin de Jucarii

## Structura proiect

```
toy-store/
├── interfaces.py    - Interfete abstracte (Discountable, Searchable, PriceCalculator)
├── toy.py           - Clasa abstracta Toy + subclase (BoardGame, ElectronicToy, Plush)
├── inventory.py     - Gestionare stoc
├── order.py         - Comenzi + strategii de calcul pret
├── toy_store.py     - Clasa principala de coordonare
├── main.py          - Demo complet
└── README.md
```

## Rulare

```bash
python3 main.py
```

Nu necesita dependente externe, doar Python 3.

## Diagrama UML

```
     ┌──────────────┐      ┌──────────────┐
     │ Discountable │      │  Searchable  │
     │  (abstract)  │      │  (abstract)  │
     └──────┬───────┘      └──────┬───────┘
            │    implements       │
            └────────┬───────────┘
                     │
              ┌──────┴──────┐
              │  Toy (ABC)  │
              └──┬───┬───┬──┘
                 │   │   │  inherits
     ┌───────────┘   │   └───────────┐
     │               │               │
┌────┴─────┐  ┌──────┴──────┐  ┌─────┴───┐
│BoardGame │  │ElectronicToy│  │  Plush  │
└──────────┘  └─────────────┘  └─────────┘

┌─────────────────┐         ┌─────────────────┐
│ PriceCalculator │         │    ToyStore      │
│    (abstract)   │         │─────────────────│
└───┬────┬────┬───┘         │ - inventory      │
    │    │    │              │ - orders         │
    │    │    │              └─────────────────┘
    │    │    │                      │ uses
    │    │    │              ┌───────┴────────┐
    │    │    │              │   Inventory    │
    │    │    │              └────────────────┘
    │    │    │
┌───┴──┐ │ ┌─┴────────┐
│Stand.│ │ │Seasonal   │
│Calc. │ │ │Disc.Calc. │
└──────┘ │ └───────────┘
   ┌─────┴──────┐
   │BulkDiscount│
   │  Calc.     │
   └────────────┘

┌────────────────┐
│     Order      │
│────────────────│
│ - items: [Toy] │
│ - calculator   │──── uses PriceCalculator
└────────────────┘
```

## Principii SOLID

### S - Single Responsibility (SRP)
Fiecare clasa are o singura responsabilitate:
- `Toy` - defineste o jucarie
- `Inventory` - gestioneaza stocul
- `Order` - gestioneaza o comanda
- `ToyStore` - coordoneaza magazinul
- `PriceCalculator` - calculeaza pretul

### O - Open/Closed (OCP)
Se pot adauga noi tipuri de jucarii (ex: `PuzzleToy`) sau noi strategii de pret (ex: `LoyaltyDiscountCalculator`) fara a modifica codul existent.

### L - Liskov Substitution (LSP)
`BoardGame`, `ElectronicToy`, `Plush` pot inlocui `Toy` oriunde in program. La fel, orice `PriceCalculator` poate fi folosit interschimbabil.

### I - Interface Segregation (ISP)
3 interfete mici, separate:
- `Discountable` - doar comportament de reducere
- `Searchable` - doar comportament de cautare
- `PriceCalculator` - doar calcul de pret

Clientii nu sunt fortati sa depinda de metode pe care nu le folosesc.

### D - Dependency Inversion (DIP)
- `Order` depinde de abstractia `PriceCalculator`, nu de o implementare concreta
- `ToyStore` depinde de abstractia `Toy`, nu de `BoardGame`/`ElectronicToy`
- Injectare de dependente in constructorul `Order`

## Design Patterns

- **Strategy Pattern** - `PriceCalculator` cu 3 implementari (Standard, Bulk, Seasonal)
- **Composition** - `ToyStore` contine `Inventory` si `List[Order]`
- **Template Method** - `Toy.calculate_price()` foloseste `get_discount()` implementat de subclase
