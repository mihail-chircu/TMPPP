# Laborator 2 - Factory Method & Abstract Factory

Magazin de jucarii KINDER.

## Structura

```
lab 2/
├── factory_method.py   - Factory Method pattern
├── abstract_factory.py - Abstract Factory pattern
├── test_patterns.py    - Teste unitare
├── main.py             - Demo
└── README.md
```

## Rulare

```bash
python3 main.py
python3 -m unittest test_patterns.py
```

## Factory Method

Fiecare tip de jucarie are propria fabrica:
- `BoardGameFactory` -> creeaza `BoardGame`
- `ElectronicToyFactory` -> creeaza `ElectronicToy`
- `PlushFactory` -> creeaza `Plush`

Clasa abstracta `ToyFactory` defineste metoda `create_toy()` pe care fiecare fabrica concreta o implementeaza. Metoda `order_toy()` foloseste factory method-ul fara sa stie ce tip concret se creeaza.

## Abstract Factory

Creeaza pachete cadou complete (jucarie + cutie + felicitare) adaptate pe grupe de varsta:
- `ToddlerPackageFactory` - pentru copii 0-5 ani
- `KidPackageFactory` - pentru copii 6-12 ani
- `TeenPackageFactory` - pentru adolescenti 13+

Fiecare fabrica produce o familie de produse inrudite care sunt coerente intre ele (jucaria, cutia si felicitarea sunt toate potrivite grupei de varsta).
