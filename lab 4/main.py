from adapter import PayPalAdapter, StripeAdapter, CashAdapter
from composite import ToyItem, ToyCategory
from facade import OrderFacade


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


catalog = ToyCategory("Catalog KINDER")

jocuri = ToyCategory("Jocuri de societate")
jocuri.add(ToyItem("Colonistii din Catan", 149.99))
jocuri.add(ToyItem("Monopoly Classic", 89.99))
jocuri.add(ToyItem("Dixit", 119.99))

electronice = ToyCategory("Jucarii electronice")
electronice.add(ToyItem("Robot Dansator", 199.99))
electronice.add(ToyItem("Masina RC 4x4", 249.99))
drone = ToyCategory("Drone")
drone.add(ToyItem("Mini Drona LED", 179.99))
drone.add(ToyItem("Drona FPV Racing", 349.99))
electronice.add(drone)

plus = ToyCategory("Jucarii de plus")
plus.add(ToyItem("Ursulet Teddy", 59.99))
plus.add(ToyItem("Unicorn Magic", 79.99))
plus.add(ToyItem("Panda Gigant", 129.99))

catalog.add(jocuri)
catalog.add(electronice)
catalog.add(plus)


def adapter_paypal():
    header("ADAPTER - Plata PayPal")
    email = input("  Email PayPal: ").strip()
    if not email:
        print("  Email-ul nu poate fi gol.")
        pause()
        return

    amount = citeste_float("  Suma de plata (LEI): ")
    if amount is None:
        return

    adapter = PayPalAdapter(email)
    print(f"\n  {adapter.pay(amount)}")
    pause()


def adapter_stripe():
    header("ADAPTER - Plata Stripe")
    token = input("  Token Stripe (ex: tok_visa_4242): ").strip()
    if not token:
        print("  Token-ul nu poate fi gol.")
        pause()
        return

    amount = citeste_float("  Suma de plata (LEI): ")
    if amount is None:
        return

    adapter = StripeAdapter(token)
    print(f"\n  {adapter.pay(amount)}")
    pause()


def adapter_cash():
    header("ADAPTER - Plata Cash")
    amount = citeste_float("  Suma de plata (LEI): ")
    if amount is None:
        return

    received = citeste_float("  Suma primita (LEI): ")
    if received is None:
        return

    adapter = CashAdapter(received)
    print(f"\n  {adapter.pay(amount)}")
    pause()


def composite_display():
    header("COMPOSITE - Catalog complet")
    print(catalog.display())
    pause()


def composite_add():
    header("COMPOSITE - Adauga produs")
    categories = []
    for child in catalog.get_children():
        if isinstance(child, ToyCategory):
            categories.append(child)

    if not categories:
        print("  Nu exista categorii.")
        pause()
        return

    print("\n  Categorii disponibile:")
    for i, cat in enumerate(categories, 1):
        print(f"    [{i}] {cat.name}")

    idx = citeste_int("\n  Alege categoria (numar): ")
    if idx is None:
        return

    while idx < 1 or idx > len(categories):
        print(f"  Index invalid. Alegeti intre 1 si {len(categories)}.")
        retry = input("  Incercati din nou? (d/n): ").strip().lower()
        if retry != "d":
            return
        idx = citeste_int("\n  Alege categoria (numar): ")
        if idx is None:
            return

    name = input("  Nume produs: ").strip()
    if not name:
        print("  Numele nu poate fi gol.")
        pause()
        return

    price = citeste_float("  Pret (LEI): ")
    if price is None:
        return

    categories[idx - 1].add(ToyItem(name, price))
    print(f"\n  Adaugat '{name}' in '{categories[idx - 1].name}'")
    pause()


def composite_total():
    header("COMPOSITE - Pret total catalog")
    print(f"\n  Pret total catalog: {catalog.get_price():.2f} LEI\n")
    for child in catalog.get_children():
        if isinstance(child, ToyCategory):
            print(f"    {child.name}: {child.get_price():.2f} LEI")
    pause()


def facade_order():
    header("FACADE - Comanda rapida")
    facade = OrderFacade()
    products = ["Ursulet Teddy", "Robot Dansator", "Colonistii din Catan"]

    print("\n  Produse disponibile:")
    for i, p in enumerate(products, 1):
        print(f"    [{i}] {p}")

    print("\n  Alegeti produsul (numar sau scrieti numele):")
    custom = input("  Alegeti: ").strip()
    try:
        idx = int(custom) - 1
        if 0 <= idx < len(products):
            toy_name = products[idx]
        else:
            print(f"  Index invalid.")
            retry = input("  Incercati din nou? (d/n): ").strip().lower()
            if retry != "d":
                return
            toy_name = input("  Scrieti numele produsului: ").strip()
    except ValueError:
        toy_name = custom

    disc = citeste_float("  Reducere % (0 pentru fara): ")
    if disc is None:
        return
    disc = disc / 100

    print("  Ambalaj cadou?")
    print("    [1] Da")
    print("    [2] Nu")
    gift_choice = alege_optiune("  Alegeti [1/2]: ", ["1", "2"])
    if gift_choice is None:
        return
    gift = gift_choice == "1"

    result = facade.place_order(toy_name, discount=disc, gift_wrap=gift)
    print(f"\n  {result}")
    pause()


def show_menu():
    print()
    print("=" * 60)
    print("  KINDER - Lab 4: Adapter, Composite, Facade")
    print("=" * 60)
    print("    [1] Adapter - Plata cu PayPal")
    print("    [2] Adapter - Plata cu Stripe")
    print("    [3] Adapter - Plata Cash")
    print("    [4] Composite - Afiseaza catalog complet")
    print("    [5] Composite - Adauga produs in catalog")
    print("    [6] Composite - Pret total catalog")
    print("    [7] Facade - Plaseaza comanda rapida")
    print("    [0] Iesire")
    print("-" * 60)


def main():
    while True:
        show_menu()
        choice = input("  Optiunea: ").strip()

        if choice == "1":
            adapter_paypal()
        elif choice == "2":
            adapter_stripe()
        elif choice == "3":
            adapter_cash()
        elif choice == "4":
            composite_display()
        elif choice == "5":
            composite_add()
        elif choice == "6":
            composite_total()
        elif choice == "7":
            facade_order()
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
