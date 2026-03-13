# Laborator 7 - Chain of Responsibility, State, Mediator, Template Method & Visitor

Magazin de jucarii KINDER.

## Structura

```
lab 7/
├── chain_of_responsibility.py - Chain of Responsibility pattern
├── state.py                   - State pattern
├── mediator.py                - Mediator pattern
├── template_method.py         - Template Method pattern
├── visitor.py                 - Visitor pattern
├── test_patterns.py           - Teste unitare
├── main.py                    - Demo
└── README.md
```

## Rulare

```bash
python3 main.py
python3 -m unittest test_patterns.py
```

## Chain of Responsibility

Sistem de suport tehnic KINDER cu nivele de escaladare:
- `FAQHandler` (severitate 1) - intrebari frecvente
- `CustomerServiceHandler` (severitate 2) - operator
- `ManagerHandler` (severitate 3) - management
- `DirectorHandler` (severitate 4+) - director

Cererea trece prin lant pana cand un handler o proceseaza.

## State

Comanda online trece prin stari: Draft -> Confirmata -> Ambalata -> Expediata -> Livrata.
- Fiecare stare defineste tranzitiile valide (next/prev)
- Comanda nu poate reveni din starea "Expediata" sau "Livrata"
- Istoricul tranzitiilor este pastrat

## Mediator

Departamentele KINDER comunica printr-un mediator central (`KinderStoreMediator`):
- `SalesDepartment` - inregistreaza vanzari
- `WarehouseDepartment` - gestioneaza stocul
- `AccountingDepartment` - inregistreaza veniturile

Cand se face o vanzare, mediatorul coordoneaza: scadere stoc, inregistrare venit, alerta stoc scazut.

## Template Method

Generare rapoarte cu format comun (header/body/footer) si continut specific:
- `SalesReport` - raport vanzari
- `InventoryReport` - raport inventar cu alerte stoc
- `ReturnsReport` - raport retururi

`header()` si `footer()` sunt comune, `body()` e specific fiecarui tip de raport.

## Visitor

Operatii noi pe jucarii fara a modifica clasele existente:
- `CSVExportVisitor` - export in format CSV
- `XMLExportVisitor` - export in format XML
- `TaxCalculatorVisitor` - calcul TVA diferentiat pe categorie (9% board games, 19% electronice, 5% plus)
