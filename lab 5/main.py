from flyweight import ToyTypeFactory, ToyOnShelf
from decorator import BasicNotification, EmailDecorator, SMSDecorator, PushDecorator, GiftWrapDecorator
from bridge import PhoneDisplay, TabletDisplay, WebDisplay, ListCatalogView, GridCatalogView, DetailCatalogView
from proxy import RealToyService, CachingProxy, AccessControlProxy, LoggingProxy


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


ToyTypeFactory.clear()
shelf = []
toys_data = [
    ("Ursulet Teddy", 59.99, "Plus", "bumbac", "3+"),
    ("Unicorn Magic", 79.99, "Plus", "bumbac", "3+"),
    ("Panda Gigant", 129.99, "Plus", "bumbac", "3+"),
    ("Robot Dansator", 199.99, "Electronic", "plastic", "5+"),
    ("Masina RC", 249.99, "Electronic", "plastic", "5+"),
    ("Mini Drona", 179.99, "Electronic", "plastic", "12+"),
    ("Catan", 149.99, "Board Game", "carton", "10+"),
    ("Monopoly", 89.99, "Board Game", "carton", "8+"),
]
for name, price, cat, mat, age in toys_data:
    toy_type = ToyTypeFactory.get_toy_type(cat, mat, age)
    shelf.append(ToyOnShelf(name, price, toy_type))

real_service = RealToyService()
caching_proxy = CachingProxy(RealToyService())
logging_proxy = LoggingProxy(RealToyService())


def flyweight_display():
    header("FLYWEIGHT - Raft jucarii")
    if not shelf:
        print("  Raftul este gol.")
    else:
        for toy in shelf:
            print(f"  {toy.display()}")
        print(f"\n  Total jucarii: {len(shelf)}")
        print(f"  Tipuri unice (flyweight): {ToyTypeFactory.get_count()}")
    pause()


def flyweight_add():
    header("FLYWEIGHT - Adauga jucarie")
    name = input("  Nume jucarie: ").strip()
    if not name:
        print("  Numele nu poate fi gol.")
        pause()
        return

    price = citeste_float("  Pret (LEI): ")
    if price is None:
        return

    print("  Categorie:")
    print("    [1] Plus")
    print("    [2] Electronic")
    print("    [3] Board Game")
    cat_choice = alege_optiune("  Alegeti [1/2/3]: ", ["1", "2", "3"])
    if cat_choice is None:
        return
    cat_map = {"1": "Plus", "2": "Electronic", "3": "Board Game"}
    category = cat_map[cat_choice]

    material = input("  Material: ").strip()
    age = input("  Grupa varsta (ex: 3+): ").strip()

    toy_type = ToyTypeFactory.get_toy_type(category, material, age)
    shelf.append(ToyOnShelf(name, price, toy_type))
    print(f"\n  Adaugat: {name} - {price:.2f} LEI {toy_type}")
    print(f"  Tipuri unice acum: {ToyTypeFactory.get_count()}")
    pause()


def flyweight_stats():
    header("FLYWEIGHT - Statistici")
    print(f"  Total jucarii pe raft: {len(shelf)}")
    print(f"  Tipuri unice partajate: {ToyTypeFactory.get_count()}")
    print(f"  Memorie salvata: {len(shelf) - ToyTypeFactory.get_count()} obiecte tip reutilizate")
    pause()


def decorator_notify():
    header("DECORATOR - Notificare")
    recipient = input("  Destinatar (email/telefon): ").strip()
    if not recipient:
        print("  Destinatarul nu poate fi gol.")
        pause()
        return

    message = input("  Mesaj: ").strip()

    print("\n  Selectati canalele (mai multe separate prin virgula):")
    print("    [1] Email")
    print("    [2] SMS")
    print("    [3] Push")
    print("    [4] Gift Wrap")
    choices_str = input("  Alegeti: ").strip()
    choices = [c.strip() for c in choices_str.split(",")]

    notification = BasicNotification(recipient)
    for c in choices:
        if c == "1":
            notification = EmailDecorator(notification)
        elif c == "2":
            notification = SMSDecorator(notification)
        elif c == "3":
            notification = PushDecorator(notification)
        elif c == "4":
            notification = GiftWrapDecorator(notification)
        else:
            print(f"  Canal '{c}' ignorat (invalid).")

    print(f"\n  {notification.send(message)}")
    pause()


def bridge_display():
    header("BRIDGE - Catalog pe dispozitiv")
    print("\n  Alege dispozitivul:")
    print("    [1] Telefon")
    print("    [2] Tableta")
    print("    [3] Web")
    dev_choice = alege_optiune("  Alegeti [1/2/3]: ", ["1", "2", "3"])
    if dev_choice is None:
        return

    print("\n  Alege tipul vizualizare:")
    print("    [1] Lista")
    print("    [2] Grid")
    print("    [3] Detaliu")
    view_choice = alege_optiune("  Alegeti [1/2/3]: ", ["1", "2", "3"])
    if view_choice is None:
        return

    devices = {"1": PhoneDisplay(), "2": TabletDisplay(), "3": WebDisplay()}
    device = devices[dev_choice]

    toy_names = [t.name for t in shelf[:6]]

    if view_choice == "1":
        view = ListCatalogView(device, toy_names)
    elif view_choice == "2":
        view = GridCatalogView(device, toy_names)
    elif view_choice == "3":
        name = input("  Nume jucarie pentru detaliu: ").strip()
        price_val = 0.0
        for t in shelf:
            if t.name.lower() == name.lower():
                price_val = t.price
                name = t.name
                break
        view = DetailCatalogView(device, name, price_val)

    print(f"\n  {view.show()}")
    pause()


def proxy_caching():
    header("PROXY - Caching")
    print("\n  ID-uri disponibile: BG001, ET001, PL001")
    toy_id = input("  Introduceti toy ID: ").strip()
    if not toy_id:
        print("  ID-ul nu poate fi gol.")
        pause()
        return

    print(f"\n  Prima cerere: {caching_proxy.get_toy_info(toy_id)}")
    print(f"  A doua cerere: {caching_proxy.get_toy_info(toy_id)}")
    pause()


def proxy_access():
    header("PROXY - Access Control")
    print("\n  Alege rolul:")
    print("    [1] Guest")
    print("    [2] Admin")
    role_choice = alege_optiune("  Alegeti [1/2]: ", ["1", "2"])
    if role_choice is None:
        return
    role = "admin" if role_choice == "2" else "guest"

    proxy = AccessControlProxy(RealToyService(), role)

    print(f"\n  Rol: {role}")
    print(f"  Citire BG001: {proxy.get_toy_info('BG001')}")

    new_price = citeste_float("  Pret nou pentru BG001 (LEI): ")
    if new_price is None:
        return
    print(f"  Modificare pret: {proxy.update_price('BG001', new_price)}")
    pause()


def proxy_logging():
    header("PROXY - Logging")
    print("\n  ID-uri disponibile: BG001, ET001, PL001")
    toy_id = input("  Introduceti toy ID: ").strip()
    if not toy_id:
        print("  ID-ul nu poate fi gol.")
        pause()
        return

    result = logging_proxy.get_toy_info(toy_id)
    print(f"  Rezultat: {result}")
    print(f"  Loguri acumulate: {logging_proxy.logs}")
    pause()


def show_menu():
    print()
    print("=" * 60)
    print("  KINDER - Lab 5: Flyweight, Decorator, Bridge, Proxy")
    print("=" * 60)
    print("    [1] Flyweight - Afiseaza raftul de jucarii")
    print("    [2] Flyweight - Adauga jucarie pe raft")
    print("    [3] Flyweight - Statistici flyweight")
    print("    [4] Decorator - Trimite notificare")
    print("    [5] Bridge - Afiseaza catalog pe dispozitiv")
    print("    [6] Proxy - Caching Proxy")
    print("    [7] Proxy - Access Control")
    print("    [8] Proxy - Logging Proxy")
    print("    [0] Iesire")
    print("-" * 60)


def main():
    while True:
        show_menu()
        choice = input("  Optiunea: ").strip()

        if choice == "1":
            flyweight_display()
        elif choice == "2":
            flyweight_add()
        elif choice == "3":
            flyweight_stats()
        elif choice == "4":
            decorator_notify()
        elif choice == "5":
            bridge_display()
        elif choice == "6":
            proxy_caching()
        elif choice == "7":
            proxy_access()
        elif choice == "8":
            proxy_logging()
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
