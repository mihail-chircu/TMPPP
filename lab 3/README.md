# Laborator 3 - Builder, Prototype & Singleton

Magazin de jucarii KINDER.

## Structura

```
lab 3/
├── builder.py       - Builder pattern
├── prototype.py     - Prototype pattern
├── singleton.py     - Singleton pattern (thread-safe)
├── test_patterns.py - Teste unitare
├── main.py          - Demo
└── README.md
```

## Rulare

```bash
python3 main.py
python3 -m unittest test_patterns.py
```

## Builder

Construieste pachete cadou pas cu pas prin `GiftDirector` + builderi specifici:
- `BirthdayPackageBuilder` - pachet de ziua de nastere
- `ChristmasPackageBuilder` - pachet de Craciun
- `EconomyPackageBuilder` - pachet economic

Directorul poate construi pachete complete sau minimale.

## Prototype

Cloneaza jucarii existente pentru a crea variante noi rapid:
- `ToyPrototype` - suporta shallow copy si deep copy
- `ToyRegistry` - registru central de prototipuri

Exemplu: din prototipul "Ursulet Teddy" se creeaza rapid variantele S, M, L, XL.

## Singleton

`StoreConfig` - configuratia unica a magazinului KINDER:
- Thread-safe cu double-checked locking
- O singura instanta globala partajata de toate modulele
- Stocheaza setari precum: moneda, taxa, discount maxim
