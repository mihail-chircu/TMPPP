# Kinder — Magazin online de jucării

Proiect de an, TMPPP (Tehnici și Metodologii de Programare a Produselor Program), UTM, specialitatea TI-235.
Autor: **Chircu Mihail**.

Aplicație e-commerce reală pentru un magazin de jucării din Leova, construită pe Laravel 11, care
ilustrează **15 pattern-uri GoF** într-un context de business plauzibil (catalog, coș, checkout,
plăți, livrare, notificări, panou admin).

## Stack

- PHP ^8.2, Laravel ^11.31
- SQLite (default) — comutabil pe MySQL din `.env`
- Blade + Tailwind CSS, Vite pentru build-uri
- PHPUnit pentru teste

## Quick start

```bash
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Site: `http://127.0.0.1:8000` · admin: `/admin` (credențiale create de seeder).

## Pattern-uri GoF

15 din 23 pattern-uri GoF — selectate pentru relevanța lor în domeniul magazinului online.

**Creaționale (4)**

| # | Pattern | Locație |
|---|---|---|
| 1 | Builder | [Order\OrderBuilder](app/Services/Order/OrderBuilder.php) |
| 2 | Prototype | [Product::clonePrototype](app/Models/Product.php) — duplicare produse |
| 3 | Factory Method | [Shipping\Methods](app/Services/Shipping/Methods) — metode de livrare |
| 4 | Abstract Factory | [Notifications\Channels](app/Notifications/Channels) — Email / SMS / Push |

**Structurale (5)**

| # | Pattern | Locație |
|---|---|---|
| 5 | Facade | [Checkout\CheckoutFacade](app/Services/Checkout/CheckoutFacade.php) |
| 6 | Decorator | [Pricing\\*Decorator](app/Services/Pricing) — discount, tax, gift wrap |
| 7 | Adapter | [Payment\Adapters](app/Services/Payment/Adapters) — gateway-uri externe |
| 8 | Proxy | [Repositories\CachingProductProxy](app/Repositories/CachingProductProxy.php) |
| 9 | Composite | [Models\Category](app/Models/Category.php) + [CategoryComponent](app/Contracts/CategoryComponent.php) |

**Comportamentale (6)**

| # | Pattern | Locație |
|---|---|---|
| 10 | Strategy | [Catalog\Strategies](app/Services/Catalog/Strategies) — sortare catalog |
| 11 | State | [Services\OrderState](app/Services/OrderState) — ciclul de viață al comenzii |
| 12 | Command | [Cart\Commands](app/Services/Cart/Commands) — `CartCommandInvoker` cu undo |
| 13 | Chain of Responsibility | [Validation\Handlers](app/Services/Validation/Handlers) — `OrderValidationPipeline` |
| 14 | Template Method | [Export\OrderExportTemplate](app/Services/Export/OrderExportTemplate.php) — CSV / JSON |
| 15 | Observer | [Events\OrderPlaced](app/Events/OrderPlaced.php) + [Listeners](app/Listeners) |

Restul de 8 (Singleton, Mediator, Flyweight, Memento, Visitor, Interpreter, Bridge, Iterator) au fost
deliberat omise — domeniul ales nu generează natural nevoia lor (vezi secțiunea de limitări din raport).

Detalii vizuale, cu capturi de ecran pentru fiecare pattern, în [design-patterns-ilustrate.pdf](design-patterns-ilustrate.pdf).

## Funcționalități

- Catalog cu filtre (categorii ierarhice, preț, brand), sortare strategică, paginație
- Coș cu Command + undo, wishlist persistent
- Checkout printr-un Facade unic care orchestrează validare → preț → plată → livrare → comandă → notificări
- Calcul preț în lanț de decoratoare (discount, taxă, ambalaj cadou)
- Notificări multi-canal (Email/SMS/Push) prin Abstract Factory
- Panou admin: produse, categorii, comenzi (cu state machine), discount-uri, utilizatori
- Import scraper de pe jucarenia.md (`php artisan import:jucarenia`)

## Comenzi utile

```bash
php artisan test                 # PHPUnit
php artisan migrate:fresh --seed # reset DB cu date demo
php artisan import:jucarenia     # populează catalog din jucarenia.md (necesită internet)
npm run dev                      # Vite în watch
```

## Livrabile proiect de an

- [Raport](Chircu_Mihail_TI-235_Raport_proiect_de_an.pdf) ·
  [.docx](Chircu_Mihail_TI-235_Raport_proiect_de_an.docx)
- [Prezentare](Chircu_Mihail_TI-235_Prezentare_proiect_de_an.pdf) ·
  [.pptx](Chircu_Mihail_TI-235_Prezentare_proiect_de_an.pptx)
- [PDF ilustrativ pattern-uri](../design-patterns-ilustrate.pdf)
