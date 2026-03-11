# Laborator 4 - Adapter, Composite & Facade

Magazin de jucarii KINDER.

## Structura

```
lab 4/
├── adapter.py       - Adapter pattern
├── composite.py     - Composite pattern
├── facade.py        - Facade pattern
├── test_patterns.py - Teste unitare
├── main.py          - Demo
└── README.md
```

## Rulare

```bash
python3 main.py
python3 -m unittest test_patterns.py
```

## Adapter

Integreaza gateway-uri de plata cu API-uri diferite printr-o interfata comuna `PaymentProcessor`:
- `PayPalAdapter` - adapteaza PayPalAPI (lucreaza cu email + total)
- `StripeAdapter` - adapteaza StripeAPI (lucreaza cu token + bani in centi)
- `CashAdapter` - adapteaza CashRegister (lucreaza cu numerar primit + pret)

## Composite

Catalog ierarhic de jucarii unde categorii si produse sunt tratate uniform:
- `ToyItem` - produs individual (frunza)
- `ToyCategory` - categorie care contine alte produse sau subcategorii (nod compus)
- `get_price()` calculeaza pretul total recursiv pe toata ierarhia

## Facade

`OrderFacade` ofera o singura metoda `place_order()` care orchestreaza 5 subsisteme:
- `InventoryService` - verificare si rezervare stoc
- `PricingService` - calcul pret si reduceri
- `PaymentService` - procesare plata
- `WrappingService` - ambalare
- `NotificationService` - confirmare email
