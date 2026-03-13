from strategy import ToyCatalog, SortByPrice, SortByPriceDesc, SortByName, SortByAge
from observer import ToyStoreEvents, EmailSubscriber, SMSSubscriber, DashboardLogger
from command import Inventory, AddStockCommand, RemoveStockCommand, UpdatePriceCommand, CommandHistory
from memento import ShoppingCart, CartHistory
from iterator import Toy, ToyCollection


def pause():
    input("\n  Apasa ENTER pentru a continua...")


def citeste_float(prompt, error_msg="Valoare invalida. Introduceti un numar."):
    while True:
        try:
            return float(input(prompt).strip())
        except ValueError:
            print(f"  {error_msg}")
            retry = input("  Incercati din nou? (d/n): ").strip().lower()
            if retry != "d":
                return None


def citeste_int(prompt, error_msg="Valoare invalida. Introduceti un numar intreg."):
    while True:
        try:
            return int(input(prompt).strip())
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


catalog = ToyCatalog()
catalog.add_toy("Robot Dansator", 199.99, 5)
catalog.add_toy("Ursulet Teddy", 59.99, 3)
catalog.add_toy("Colonistii din Catan", 149.99, 10)
catalog.add_toy("Mini Drona LED", 179.99, 12)
catalog.add_toy("Panda Gigant", 129.99, 3)

store_events = ToyStoreEvents()
email_sub = EmailSubscriber("ana@email.com")
sms_sub = SMSSubscriber("+37369123456")
dashboard = DashboardLogger()
store_events.attach(email_sub)
store_events.attach(sms_sub)
store_events.attach(dashboard)

inv = Inventory()
inv.add("Robot Dansator", 10)
inv.add("Ursulet Teddy", 20)
inv.add("Catan", 8)
prices = {"Robot Dansator": 199.99, "Ursulet Teddy": 59.99, "Catan": 149.99}
cmd_history = CommandHistory()

cart = ShoppingCart()
cart_history = CartHistory()

collection = ToyCollection()
collection.add(Toy("Robot Dansator", 199.99, "Electronic"))
collection.add(Toy("Masina RC", 249.99, "Electronic"))
collection.add(Toy("Ursulet Teddy", 59.99, "Plus"))
collection.add(Toy("Panda Gigant", 129.99, "Plus"))
collection.add(Toy("Catan", 149.99, "Board Game"))
collection.add(Toy("Monopoly", 89.99, "Board Game"))


def strategy_sort():
    header("STRATEGY - Sortare catalog")
    print("\n  Strategie de sortare:")
    print("    [1] Dupa pret (crescator)")
    print("    [2] Dupa pret (descrescator)")
    print("    [3] Dupa nume (A-Z)")
    print("    [4] Dupa varsta recomandata")
    choice = alege_optiune("  Alegeti [1/2/3/4]: ", ["1", "2", "3", "4"])
    if choice is None:
        return

    strategies = {
        "1": SortByPrice(),
        "2": SortByPriceDesc(),
        "3": SortByName(),
        "4": SortByAge(),
    }
    catalog.set_sort_strategy(strategies[choice])
    print(catalog.display())
    pause()


def strategy_add():
    header("STRATEGY - Adauga jucarie")
    name = input("  Nume jucarie: ").strip()
    if not name:
        print("  Numele nu poate fi gol.")
        pause()
        return

    price = citeste_float("  Pret (LEI): ")
    if price is None:
        return

    age = citeste_int("  Varsta recomandata: ")
    if age is None:
        return

    catalog.add_toy(name, price, age)
    print(f"\n  Adaugat: {name} - {price:.2f} LEI (varsta: {age}+)")
    pause()


def observer_new():
    header("OBSERVER - Produs nou")
    name = input("  Nume produs nou: ").strip()
    if not name:
        print("  Numele nu poate fi gol.")
        pause()
        return

    price = citeste_float("  Pret (LEI): ")
    if price is None:
        return

    store_events.new_arrival(name, price)
    print("  Notificare trimisa catre toti abonati.")
    pause()


def observer_price_drop():
    header("OBSERVER - Reducere pret")
    name = input("  Nume produs: ").strip()
    if not name:
        print("  Numele nu poate fi gol.")
        pause()
        return

    old = citeste_float("  Pret vechi (LEI): ")
    if old is None:
        return

    new = citeste_float("  Pret nou (LEI): ")
    if new is None:
        return

    store_events.price_drop(name, old, new)
    print("  Notificare trimisa catre toti abonati.")
    pause()


def observer_out_of_stock():
    header("OBSERVER - Produs epuizat")
    name = input("  Nume produs epuizat: ").strip()
    if not name:
        print("  Numele nu poate fi gol.")
        pause()
        return

    store_events.out_of_stock(name)
    print("  Notificare trimisa catre toti abonati.")
    pause()


def observer_view():
    header("OBSERVER - Notificari")
    print("\n  Mesaje Email (ana@email.com):")
    for msg in email_sub.messages:
        print(f"    {msg}")

    print(f"\n  Mesaje SMS ({sms_sub.phone}):")
    for msg in sms_sub.messages:
        print(f"    {msg}")

    print(f"\n  Dashboard: {len(dashboard.log)} evenimente inregistrate")
    pause()


def command_add():
    header("COMMAND - Adauga stoc")
    toy = input("  Nume jucarie: ").strip()
    if not toy:
        print("  Numele nu poate fi gol.")
        pause()
        return

    qty = citeste_int("  Cantitate: ")
    if qty is None:
        return

    cmd = AddStockCommand(inv, toy, qty)
    result = cmd_history.execute(cmd)
    print(f"  {result}")
    print(f"  Stoc: {inv}")
    pause()


def command_remove():
    header("COMMAND - Scoate stoc")
    toy = input("  Nume jucarie: ").strip()
    if not toy:
        print("  Numele nu poate fi gol.")
        pause()
        return

    qty = citeste_int("  Cantitate: ")
    if qty is None:
        return

    cmd = RemoveStockCommand(inv, toy, qty)
    result = cmd_history.execute(cmd)
    print(f"  {result}")
    print(f"  Stoc: {inv}")
    pause()


def command_price():
    header("COMMAND - Actualizeaza pret")
    toy = input("  Nume jucarie: ").strip()
    if not toy:
        print("  Numele nu poate fi gol.")
        pause()
        return

    new_price = citeste_float("  Pret nou (LEI): ")
    if new_price is None:
        return

    cmd = UpdatePriceCommand(prices, toy, new_price)
    result = cmd_history.execute(cmd)
    print(f"  {result}")
    print(f"  Preturi: {prices}")
    pause()


def command_undo_redo():
    header("COMMAND - Undo/Redo")
    print("    [1] Undo")
    print("    [2] Redo")
    choice = alege_optiune("  Alegeti [1/2]: ", ["1", "2"])
    if choice is None:
        return

    if choice == "1":
        print(f"  {cmd_history.undo()}")
    elif choice == "2":
        print(f"  {cmd_history.redo()}")

    print(f"  Stoc: {inv}")
    print(f"  Preturi: {prices}")
    pause()


def memento_add():
    header("MEMENTO - Adauga in cos")
    name = input("  Nume produs: ").strip()
    if not name:
        print("  Numele nu poate fi gol.")
        pause()
        return

    price = citeste_float("  Pret (LEI): ")
    if price is None:
        return

    cart.add_item(name, price)
    print(f"  Adaugat in cos: {name} - {price:.2f} LEI")
    pause()


def memento_save():
    header("MEMENTO - Salveaza cos")
    cart_history.save(cart.save())
    snapshots = cart_history.list_snapshots()
    print(f"  Cos salvat! Total snapshots: {len(snapshots)}")
    pause()


def memento_restore():
    header("MEMENTO - Restaureaza cos")
    snapshots = cart_history.list_snapshots()
    if not snapshots:
        print("  Nu exista snapshots salvate.")
        pause()
        return

    print("\n  Snapshots disponibile:")
    for s in snapshots:
        print(f"    {s}")

    idx = citeste_int("  Index snapshot: ")
    if idx is None:
        return

    try:
        cart.restore(cart_history.get_snapshot(idx))
        print(f"  Cos restaurat din snapshot #{idx}")
        print(cart)
    except (ValueError, IndexError):
        print("  Index invalid.")
        retry = input("  Incercati din nou? (d/n): ").strip().lower()
        if retry == "d":
            memento_restore()
    pause()


def memento_view():
    header("MEMENTO - Vizualizeaza cos")
    print(cart)
    pause()


def iterator_all():
    header("ITERATOR - Toate jucariile")
    for toy in collection.iterator():
        print(f"    {toy}")
    pause()


def iterator_category():
    header("ITERATOR - Filtru categorie")
    print("  Categorii: Electronic, Plus, Board Game")
    category = input("  Categorie: ").strip()
    if not category:
        print("  Categoria nu poate fi goala.")
        pause()
        return

    print(f"\n  Jucarii din categoria '{category}':")
    found = False
    for toy in collection.category_iterator(category):
        print(f"    {toy}")
        found = True
    if not found:
        print("    Niciun rezultat.")
    pause()


def iterator_price():
    header("ITERATOR - Filtru pret")
    min_p = citeste_float("  Pret minim (LEI): ")
    if min_p is None:
        return

    max_p = citeste_float("  Pret maxim (LEI): ")
    if max_p is None:
        return

    print(f"\n  Jucarii intre {min_p:.2f} - {max_p:.2f} LEI:")
    found = False
    for toy in collection.price_range_iterator(min_p, max_p):
        print(f"    {toy}")
        found = True
    if not found:
        print("    Niciun rezultat.")
    pause()


def show_menu():
    print()
    print("=" * 60)
    print("  KINDER - Lab 6: Strategy, Observer, Command,")
    print("                   Memento, Iterator")
    print("=" * 60)
    print("    [1]  Strategy - Sorteaza catalog")
    print("    [2]  Strategy - Adauga jucarie in catalog")
    print("    [3]  Observer - Produs nou")
    print("    [4]  Observer - Reducere pret")
    print("    [5]  Observer - Produs epuizat")
    print("    [6]  Observer - Vizualizeaza notificari")
    print("    [7]  Command - Adauga stoc")
    print("    [8]  Command - Scoate din stoc")
    print("    [9]  Command - Actualizeaza pret")
    print("    [10] Command - Undo / Redo")
    print("    [11] Memento - Adauga in cos")
    print("    [12] Memento - Salveaza cos")
    print("    [13] Memento - Restaureaza cos")
    print("    [14] Memento - Vizualizeaza cos")
    print("    [15] Iterator - Toate jucariile")
    print("    [16] Iterator - Filtru categorie")
    print("    [17] Iterator - Filtru pret")
    print("    [0]  Iesire")
    print("-" * 60)


def main():
    while True:
        show_menu()
        choice = input("  Optiunea: ").strip()

        if choice == "1":
            strategy_sort()
        elif choice == "2":
            strategy_add()
        elif choice == "3":
            observer_new()
        elif choice == "4":
            observer_price_drop()
        elif choice == "5":
            observer_out_of_stock()
        elif choice == "6":
            observer_view()
        elif choice == "7":
            command_add()
        elif choice == "8":
            command_remove()
        elif choice == "9":
            command_price()
        elif choice == "10":
            command_undo_redo()
        elif choice == "11":
            memento_add()
        elif choice == "12":
            memento_save()
        elif choice == "13":
            memento_restore()
        elif choice == "14":
            memento_view()
        elif choice == "15":
            iterator_all()
        elif choice == "16":
            iterator_category()
        elif choice == "17":
            iterator_price()
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
