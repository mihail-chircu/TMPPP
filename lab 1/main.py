from toy import BoardGame, ElectronicToy, Plush
from order import Order, StandardPriceCalculator, BulkDiscountCalculator, SeasonalDiscountCalculator
from toy_store import ToyStore


def pause():
    input("\n  Apasa ENTER pentru a continua...")


def citeste_int(prompt, error_msg="Valoare invalida. Introduceti un numar intreg."):
    while True:
        try:
            return int(input(prompt).strip())
        except ValueError:
            print(f"  {error_msg}")
            retry = input("  Incercati din nou? (d/n): ").strip().lower()
            if retry != "d":
                return None


def citeste_float(prompt, error_msg="Valoare invalida. Introduceti un numar."):
    while True:
        try:
            return float(input(prompt).strip())
        except ValueError:
            print(f"  {error_msg}")
            retry = input("  Incercati din nou? (d/n): ").strip().lower()
            if retry != "d":
                return None


def alege_optiune(prompt, optiuni_valide, error_msg="Optiune invalida."):
    while True:
        choice = input(prompt).strip()
        if choice in optiuni_valide:
            return choice
        print(f"  {error_msg} Optiuni valide: {', '.join(optiuni_valide)}")
        retry = input("  Incercati din nou? (d/n): ").strip().lower()
        if retry != "d":
            return None


def header(title):
    print(f"\n{'='*60}")
    print(f"  {title}")
    print(f"{'='*60}")


id_counter = [0]


def next_id(prefix):
    id_counter[0] += 1
    return f"{prefix}{id_counter[0]:03d}"


def init_store():
    store = ToyStore("KINDER")

    bg1 = BoardGame("BG001", "Colonistii din Catan", 149.99, 10, 3, 4)
    bg2 = BoardGame("BG002", "Monopoly Classic", 89.99, 8, 2, 6)
    bg3 = BoardGame("BG003", "Dixit", 119.99, 8, 3, 6)

    et1 = ElectronicToy("ET001", "Robot Dansator", 199.99, 5, "AA", True)
    et2 = ElectronicToy("ET002", "Masina RC 4x4", 249.99, 6, "Li-Ion", True)
    et3 = ElectronicToy("ET003", "Mini Drona LED", 179.99, 12, "Li-Po", False)

    pl1 = Plush("PL001", "Ursulet Teddy", 59.99, 3, "bumbac", "M")
    pl2 = Plush("PL002", "Unicorn Magic", 79.99, 3, "plus", "L")
    pl3 = Plush("PL003", "Panda Gigant", 129.99, 3, "bumbac", "XL")

    for toy, qty in [(bg1, 15), (bg2, 20), (bg3, 12),
                     (et1, 8), (et2, 10), (et3, 5),
                     (pl1, 25), (pl2, 18), (pl3, 10)]:
        store.add_to_inventory(toy, qty)

    id_counter[0] = 3

    return store


def vizualizeaza_inventar(store):
    header("INVENTAR")
    items = store.inventory.get_all_toys()
    if not items:
        print("  Inventarul este gol.")
    else:
        print(f"\n  {'Nr':<5} {'ID':<8} {'Nume':<25} {'Pret':>10} {'Stoc':>6}")
        print(f"  {'-'*55}")
        for i, (toy, qty) in enumerate(items.items(), 1):
            print(f"  {i:<5} {toy.toy_id:<8} {toy.name:<25} {toy.price:>8.2f} LEI {qty:>5}")
        print(f"\n  Valoare totala stoc: {store.inventory.get_total_value():.2f} LEI")
    pause()


def adauga_jucarie(store):
    header("ADAUGA JUCARIE")
    print("\n  Tipuri disponibile:")
    print("    [1] Joc de societate (BoardGame)")
    print("    [2] Jucarie electronica (ElectronicToy)")
    print("    [3] Jucarie de plus (Plush)")

    tip = alege_optiune("\n  Alege tipul [1/2/3]: ", ["1", "2", "3"])
    if tip is None:
        return

    name = input("  Nume: ").strip()
    if not name:
        print("  Numele nu poate fi gol.")
        pause()
        return

    price = citeste_float("  Pret (LEI): ")
    if price is None:
        return

    age = citeste_int("  Varsta recomandata (numar, ex: 6): ")
    if age is None:
        return

    if tip == "1":
        min_p = citeste_int("  Numar minim jucatori: ")
        if min_p is None:
            return
        max_p = citeste_int("  Numar maxim jucatori: ")
        if max_p is None:
            return
        toy_id = next_id("BG")
        toy = BoardGame(toy_id, name, price, age, min_p, max_p)

    elif tip == "2":
        battery = input("  Tip baterie (AA/Li-Ion/Li-Po): ").strip()
        print("  Are sunete?")
        print("    [1] Da")
        print("    [2] Nu")
        sounds_choice = alege_optiune("  Alegeti [1/2]: ", ["1", "2"])
        if sounds_choice is None:
            return
        has_sounds = sounds_choice == "1"
        toy_id = next_id("ET")
        toy = ElectronicToy(toy_id, name, price, age, battery, has_sounds)

    elif tip == "3":
        material = input("  Material (bumbac/plus/etc.): ").strip()
        print("  Marime:")
        print("    [1] S   [2] M   [3] L   [4] XL")
        size_map = {"1": "S", "2": "M", "3": "L", "4": "XL"}
        size_choice = alege_optiune("  Alegeti [1/2/3/4]: ", ["1", "2", "3", "4"])
        if size_choice is None:
            return
        size = size_map[size_choice]
        toy_id = next_id("PL")
        toy = Plush(toy_id, name, price, age, material, size)

    qty = citeste_int("  Cantitate in stoc: ")
    if qty is None:
        return

    store.add_to_inventory(toy, qty)
    print(f"\n  Jucaria '{name}' [{toy_id}] a fost adaugata cu succes! (stoc: {qty})")
    pause()


def cauta_jucarii(store):
    header("CAUTA JUCARII")
    keyword = input("  Introduceti termen de cautare: ").strip()
    if not keyword:
        print("  Termenul de cautare nu poate fi gol.")
        pause()
        return

    results = store.search_toys(keyword)
    print(f"\n  Rezultate ({len(results)} gasite):\n")
    if not results:
        print("  Nicio jucarie gasita.")
    else:
        for i, toy in enumerate(results, 1):
            stock = store.inventory.get_stock(toy)
            print(f"    [{i}] {toy}")
            print(f"         Stoc: {stock}")
    pause()


def pick_toys_for_order(store):
    items = store.inventory.get_all_toys()
    if not items:
        print("  Inventarul este gol.")
        return None

    toy_list = list(items.keys())
    print("\n  Jucarii disponibile:\n")
    for i, toy in enumerate(toy_list, 1):
        stock = items[toy]
        print(f"    [{i:>2}] {toy.name:<25} {toy.price:>8.2f} LEI  (stoc: {stock})")

    selected = []
    print("\n  Adaugati jucarii in comanda. Tastati 'gata' pentru a finaliza.\n")

    while True:
        user_input = input("  Numar jucarie (sau 'gata'): ").strip().lower()
        if user_input == "gata":
            break

        try:
            idx = int(user_input) - 1
            if idx < 0 or idx >= len(toy_list):
                raise IndexError
        except (ValueError, IndexError):
            print(f"  Numar invalid. Alegeti intre 1 si {len(toy_list)}.")
            retry = input("  Incercati din nou? (d/n): ").strip().lower()
            if retry != "d":
                break
            continue

        toy = toy_list[idx]
        stock = items[toy]
        qty = citeste_int(f"  Cantitate pentru '{toy.name}' (stoc: {stock}): ")
        if qty is None:
            continue

        if qty < 1 or qty > stock:
            print(f"  Cantitate invalida. Disponibil: {stock}.")
            retry = input("  Incercati din nou? (d/n): ").strip().lower()
            if retry != "d":
                continue
            continue

        selected.append((toy, qty))
        print(f"  + {toy.name} x{qty}")

    return selected if selected else None


def comanda(store, calculator, label):
    header(f"COMANDA - {label}")
    items = pick_toys_for_order(store)
    if items is None:
        print("\n  Comanda anulata (nicio jucarie selectata).")
        pause()
        return

    order = Order(calculator)
    for toy, qty in items:
        for _ in range(qty):
            order.add_item(toy)

    print(f"\n  Rezumat comanda ({label}):\n")
    for toy, qty in items:
        subtotal = toy.calculate_price() * qty
        print(f"    {toy.name:<25} x{qty:>3}  {subtotal:>10.2f} LEI "
              f"(reducere: {toy.get_discount()*100:.0f}%)")
    print(f"\n  {'TOTAL:':>35} {order.get_total():>10.2f} LEI")

    print("\n  Confirmati comanda?")
    print("    [1] Da")
    print("    [2] Nu")
    confirm = alege_optiune("  Alegeti [1/2]: ", ["1", "2"])
    if confirm != "1":
        print("  Comanda anulata.")
        pause()
        return

    success = store.place_order(order)
    if success:
        print(f"\n  Comanda {order.order_id} inregistrata cu succes!")
    else:
        print("\n  Comanda nu a putut fi plasata (stoc insuficient).")
    pause()


def istoric_comenzi(store):
    header("ISTORIC COMENZI")
    orders = store.orders
    if not orders:
        print("  Nu exista comenzi inregistrate.")
    else:
        for i, order in enumerate(orders, 1):
            print(f"\n  Comanda #{i}:")
            print(f"  {order}")
    pause()


def raport_complet(store):
    header("RAPORT COMPLET")
    print(store.get_report())
    pause()


def show_menu():
    print()
    print("=" * 60)
    print("  KINDER - Lab 1: OOP + SOLID")
    print("=" * 60)
    print("    [1] Vizualizeaza inventar")
    print("    [2] Adauga jucarie noua")
    print("    [3] Cauta jucarii")
    print("    [4] Comanda - Pret Standard")
    print("    [5] Comanda - Bulk Discount (5+ articole = -10%)")
    print("    [6] Comanda - Reducere Sezoniera (-20%)")
    print("    [7] Istoric comenzi")
    print("    [8] Raport complet")
    print("    [0] Iesire")
    print("-" * 60)


def main():
    store = init_store()

    while True:
        show_menu()
        choice = input("  Optiunea: ").strip()

        if choice == "1":
            vizualizeaza_inventar(store)
        elif choice == "2":
            adauga_jucarie(store)
        elif choice == "3":
            cauta_jucarii(store)
        elif choice == "4":
            comanda(store, StandardPriceCalculator(), "Pret Standard")
        elif choice == "5":
            comanda(store, BulkDiscountCalculator(), "Bulk Discount")
        elif choice == "6":
            comanda(store, SeasonalDiscountCalculator(), "Reducere Sezoniera")
        elif choice == "7":
            istoric_comenzi(store)
        elif choice == "8":
            raport_complet(store)
        elif choice == "0":
            print("\n  La revedere!\n")
            break
        else:
            print("  Optiune invalida.")
            retry = input("  Incercati din nou? (d/n): ").strip().lower()
            if retry != "d":
                print("\n  La revedere!\n")
                break


if __name__ == "__main__":
    main()
